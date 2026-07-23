# Specifiche Tecniche - Miglioramenti Prediction Market

## 🏗️ Architettura del Sistema

### 1. **Componenti Livewire**

#### TradingFormWidget
```php
<?php

namespace Modules\Predict\Filament\Widgets;

use Livewire\Component;
use Livewire\Attributes\Validate;
use Modules\Predict\Models\Prediction;
use Modules\Predict\Services\TradingService;

class TradingFormWidget extends Component
{
    public string $slug;
    public string $orderType = 'buy';
    public int $quantity = 1;
    public float $currentPrice = 0;
    public float $totalCost = 0;
    public ?float $potentialProfit = null;
    
    #[Validate('required|in:buy,sell')]
    public string $type;
    
    #[Validate('required|integer|min:1|max:10000')]
    public int $orderQuantity;
    
    public function mount(string $slug)
    {
        $this->slug = $slug;
        $this->updatePrices();
    }
    
    public function setOrderType(string $type)
    {
        $this->orderType = $type;
        $this->updatePrices();
    }
    
    public function updatedQuantity()
    {
        $this->updatePrices();
    }
    
    private function updatePrices()
    {
        $prediction = Prediction::where('slug', $this->slug)->first();
        $this->currentPrice = $prediction->current_price;
        $this->totalCost = $this->quantity * $this->currentPrice;
        
        // Calcolo profitto potenziale
        if (auth()->check()) {
            $userPosition = auth()->user()->positions()
                ->where('prediction_id', $prediction->id)
                ->first();
                
            if ($userPosition) {
                $this->potentialProfit = $this->calculatePotentialProfit($userPosition);
            }
        }
    }
    
    public function placeOrder()
    {
        $this->validate();
        
        $tradingService = app(TradingService::class);
        
        try {
            $order = $tradingService->placeOrder([
                'prediction_slug' => $this->slug,
                'type' => $this->orderType,
                'quantity' => $this->quantity,
                'user_id' => auth()->id(),
            ]);
            
            $this->dispatch('order-placed', $order->id);
            $this->reset(['quantity']);
            
        } catch (\Exception $e) {
            $this->addError('order', $e->getMessage());
        }
    }
    
    private function calculatePotentialProfit($position): float
    {
        // Logica per calcolare profitto potenziale
        $currentValue = $this->quantity * $this->currentPrice;
        $positionValue = $position->quantity * $position->average_price;
        
        return $currentValue - $positionValue;
    }
    
    public function render()
    {
        return view('predict::widgets.trading-form-widget');
    }
}
```

#### MarketDepthWidget
```php
<?php

namespace Modules\Predict\Filament\Widgets;

use Livewire\Component;
use Modules\Predict\Models\Prediction;
use Modules\Predict\Services\OrderBookService;

class MarketDepthWidget extends Component
{
    public string $slug;
    public array $buyOrders = [];
    public array $sellOrders = [];
    public float $maxQuantity = 0;
    
    protected $listeners = ['orderBookUpdated' => 'refreshOrderBook'];
    
    public function mount(string $slug)
    {
        $this->slug = $slug;
        $this->refreshOrderBook();
    }
    
    public function refreshOrderBook()
    {
        $orderBookService = app(OrderBookService::class);
        $orderBook = $orderBookService->getOrderBook($this->slug);
        
        $this->buyOrders = $orderBook['buy'] ?? [];
        $this->sellOrders = $orderBook['sell'] ?? [];
        
        // Calcola quantità massima per normalizzazione
        $allQuantities = array_merge(
            array_column($this->buyOrders, 'quantity'),
            array_column($this->sellOrders, 'quantity')
        );
        
        $this->maxQuantity = max($allQuantities) ?: 1;
    }
    
    public function selectPrice(float $price, string $type)
    {
        $this->dispatch('price-selected', [
            'price' => $price,
            'type' => $type
        ]);
    }
    
    public function render()
    {
        return view('predict::widgets.market-depth-widget');
    }
}
```

### 2. **Servizi di Business Logic**

#### TradingService
```php
<?php

namespace Modules\Predict\Services;

use Modules\Predict\Models\Prediction;
use Modules\Predict\Models\Order;
use Modules\Predict\Models\Position;
use Modules\Predict\Events\OrderPlaced;
use Modules\Predict\Events\OrderMatched;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class TradingService
{
    public function placeOrder(array $data): Order
    {
        return DB::transaction(function () use ($data) {
            // Validazione ordine
            $this->validateOrder($data);
            
            // Creazione ordine
            $order = Order::create([
                'prediction_id' => Prediction::where('slug', $data['prediction_slug'])->first()->id,
                'user_id' => $data['user_id'],
                'type' => $data['type'],
                'quantity' => $data['quantity'],
                'price' => $this->calculateOrderPrice($data),
                'status' => 'open',
            ]);
            
            // Tentativo di matching immediato
            $this->attemptMatching($order);
            
            // Broadcast evento
            OrderPlaced::dispatch($order);
            
            // Invalida cache
            $this->invalidateCache($data['prediction_slug']);
            
            return $order;
        });
    }
    
    private function validateOrder(array $data): void
    {
        $prediction = Prediction::where('slug', $data['prediction_slug'])->first();
        
        if (!$prediction) {
            throw new \Exception('Prediction non trovata');
        }
        
        if ($prediction->status !== 'active') {
            throw new \Exception('Mercato non attivo');
        }
        
        // Verifica fondi utente
        $user = auth()->user();
        $requiredFunds = $data['quantity'] * $prediction->current_price;
        
        if ($data['type'] === 'buy' && $user->balance < $requiredFunds) {
            throw new \Exception('Fondi insufficienti');
        }
        
        // Verifica quote disponibili per vendita
        if ($data['type'] === 'sell') {
            $userPosition = $user->positions()
                ->where('prediction_id', $prediction->id)
                ->first();
                
            if (!$userPosition || $userPosition->quantity < $data['quantity']) {
                throw new \Exception('Quote insufficienti per la vendita');
            }
        }
    }
    
    private function calculateOrderPrice(array $data): float
    {
        $prediction = Prediction::where('slug', $data['prediction_slug'])->first();
        
        // Implementazione LMSR (Logarithmic Market Scoring Rule)
        return $this->calculateLMSRPrice($prediction, $data['type'], $data['quantity']);
    }
    
    private function calculateLMSRPrice(Prediction $prediction, string $type, int $quantity): float
    {
        $liquidityParameter = 100; // Parametro di liquidità
        $currentShares = $prediction->outcome_shares;
        
        if ($type === 'buy') {
            $newShares = $currentShares + $quantity;
        } else {
            $newShares = $currentShares - $quantity;
        }
        
        // Calcolo prezzo usando LMSR
        $exponent = $newShares / $liquidityParameter;
        return exp($exponent) / (1 + exp($exponent));
    }
    
    private function attemptMatching(Order $order): void
    {
        $matchingOrders = Order::where('prediction_id', $order->prediction_id)
            ->where('type', $order->type === 'buy' ? 'sell' : 'buy')
            ->where('status', 'open')
            ->where('price', '<=', $order->price)
            ->orderBy('created_at', 'asc')
            ->get();
            
        foreach ($matchingOrders as $matchingOrder) {
            if ($order->status !== 'open') break;
            
            $this->executeTrade($order, $matchingOrder);
        }
    }
    
    private function executeTrade(Order $buyOrder, Order $sellOrder): void
    {
        $quantity = min($buyOrder->quantity, $sellOrder->quantity);
        $price = ($buyOrder->price + $sellOrder->price) / 2;
        
        // Aggiorna ordini
        $buyOrder->quantity -= $quantity;
        $sellOrder->quantity -= $quantity;
        
        if ($buyOrder->quantity <= 0) {
            $buyOrder->status = 'filled';
        }
        
        if ($sellOrder->quantity <= 0) {
            $sellOrder->status = 'filled';
        }
        
        $buyOrder->save();
        $sellOrder->save();
        
        // Aggiorna posizioni
        $this->updatePositions($buyOrder, $sellOrder, $quantity, $price);
        
        // Broadcast evento
        OrderMatched::dispatch($buyOrder, $sellOrder, $quantity, $price);
    }
    
    private function updatePositions(Order $buyOrder, Order $sellOrder, int $quantity, float $price): void
    {
        // Aggiorna posizione acquirente
        $buyerPosition = Position::firstOrCreate([
            'user_id' => $buyOrder->user_id,
            'prediction_id' => $buyOrder->prediction_id,
        ]);
        
        $buyerPosition->quantity += $quantity;
        $buyerPosition->total_cost += $quantity * $price;
        $buyerPosition->average_price = $buyerPosition->total_cost / $buyerPosition->quantity;
        $buyerPosition->save();
        
        // Aggiorna posizione venditore
        $sellerPosition = Position::firstOrCreate([
            'user_id' => $sellOrder->user_id,
            'prediction_id' => $sellOrder->prediction_id,
        ]);
        
        $sellerPosition->quantity -= $quantity;
        $sellerPosition->total_cost -= $quantity * $price;
        
        if ($sellerPosition->quantity > 0) {
            $sellerPosition->average_price = $sellerPosition->total_cost / $sellerPosition->quantity;
        }
        
        $sellerPosition->save();
    }
    
    private function invalidateCache(string $slug): void
    {
        Cache::forget("market-data-{$slug}");
        Cache::forget("order-book-{$slug}");
        Cache::forget("market-stats-{$slug}");
    }
}
```

#### OrderBookService
```php
<?php

namespace Modules\Predict\Services;

use Modules\Predict\Models\Order;
use Modules\Predict\Models\Prediction;
use Illuminate\Support\Facades\Cache;

class OrderBookService
{
    public function getOrderBook(string $slug): array
    {
        return Cache::remember("order-book-{$slug}", 10, function () use ($slug) {
            $prediction = Prediction::where('slug', $slug)->first();
            
            if (!$prediction) {
                return ['buy' => [], 'sell' => []];
            }
            
            $buyOrders = Order::where('prediction_id', $prediction->id)
                ->where('type', 'buy')
                ->where('status', 'open')
                ->orderBy('price', 'desc')
                ->limit(10)
                ->get()
                ->groupBy('price')
                ->map(function ($orders) {
                    return [
                        'price' => $orders->first()->price,
                        'quantity' => $orders->sum('quantity'),
                        'orders_count' => $orders->count(),
                    ];
                })
                ->values()
                ->toArray();
                
            $sellOrders = Order::where('prediction_id', $prediction->id)
                ->where('type', 'sell')
                ->where('status', 'open')
                ->orderBy('price', 'asc')
                ->limit(10)
                ->get()
                ->groupBy('price')
                ->map(function ($orders) {
                    return [
                        'price' => $orders->first()->price,
                        'quantity' => $orders->sum('quantity'),
                        'orders_count' => $orders->count(),
                    ];
                })
                ->values()
                ->toArray();
                
            return [
                'buy' => $buyOrders,
                'sell' => $sellOrders,
            ];
        });
    }
    
    public function getMarketDepth(string $slug): array
    {
        $orderBook = $this->getOrderBook($slug);
        
        $buyDepth = [];
        $sellDepth = [];
        
        // Calcola profondità cumulativa per acquisti
        $cumulativeBuy = 0;
        foreach ($orderBook['buy'] as $order) {
            $cumulativeBuy += $order['quantity'];
            $buyDepth[] = [
                'price' => $order['price'],
                'quantity' => $order['quantity'],
                'cumulative' => $cumulativeBuy,
            ];
        }
        
        // Calcola profondità cumulativa per vendite
        $cumulativeSell = 0;
        foreach ($orderBook['sell'] as $order) {
            $cumulativeSell += $order['quantity'];
            $sellDepth[] = [
                'price' => $order['price'],
                'quantity' => $order['quantity'],
                'cumulative' => $cumulativeSell,
            ];
        }
        
        return [
            'buy' => $buyDepth,
            'sell' => $sellDepth,
        ];
    }
}
```

### 3. **Modelli Database**

#### Order Model
```php
<?php

namespace Modules\Predict\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'prediction_id',
        'user_id',
        'type',
        'quantity',
        'price',
        'status',
        'filled_quantity',
        'average_fill_price',
    ];
    
    protected $casts = [
        'price' => 'decimal:4',
        'filled_quantity' => 'integer',
        'average_fill_price' => 'decimal:4',
    ];
    
    public function prediction(): BelongsTo
    {
        return $this->belongsTo(Prediction::class);
    }
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    public function getRemainingQuantityAttribute(): int
    {
        return $this->quantity - $this->filled_quantity;
    }
    
    public function getIsFilledAttribute(): bool
    {
        return $this->remaining_quantity <= 0;
    }
    
    public function getIsPartiallyFilledAttribute(): bool
    {
        return $this->filled_quantity > 0 && !$this->is_filled;
    }
}
```

#### Position Model
```php
<?php

namespace Modules\Predict\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Position extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'prediction_id',
        'quantity',
        'total_cost',
        'average_price',
        'realized_pnl',
        'unrealized_pnl',
    ];
    
    protected $casts = [
        'total_cost' => 'decimal:4',
        'average_price' => 'decimal:4',
        'realized_pnl' => 'decimal:4',
        'unrealized_pnl' => 'decimal:4',
    ];
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    public function prediction(): BelongsTo
    {
        return $this->belongsTo(Prediction::class);
    }
    
    public function getCurrentValueAttribute(): float
    {
        return $this->quantity * $this->prediction->current_price;
    }
    
    public function getTotalPnlAttribute(): float
    {
        return $this->realized_pnl + $this->unrealized_pnl;
    }
    
    public function getPnlPercentageAttribute(): float
    {
        if ($this->total_cost <= 0) return 0;
        
        return ($this->total_pnl / $this->total_cost) * 100;
    }
}
```

### 4. **Eventi e Broadcasting**

#### OrderPlaced Event
```php
<?php

namespace Modules\Predict\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Predict\Models\Order;

class OrderPlaced implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    
    public Order $order;
    
    public function __construct(Order $order)
    {
        $this->order = $order;
    }
    
    public function broadcastOn(): array
    {
        return [
            new Channel("prediction.{$this->order->prediction->slug}"),
        ];
    }
    
    public function broadcastAs(): string
    {
        return 'order.placed';
    }
    
    public function broadcastWith(): array
    {
        return [
            'order_id' => $this->order->id,
            'type' => $this->order->type,
            'quantity' => $this->order->quantity,
            'price' => $this->order->price,
            'user_id' => $this->order->user_id,
            'timestamp' => $this->order->created_at->toISOString(),
        ];
    }
}
```

#### OrderMatched Event
```php
<?php

namespace Modules\Predict\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Predict\Models\Order;

class OrderMatched implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    
    public Order $buyOrder;
    public Order $sellOrder;
    public int $quantity;
    public float $price;
    
    public function __construct(Order $buyOrder, Order $sellOrder, int $quantity, float $price)
    {
        $this->buyOrder = $buyOrder;
        $this->sellOrder = $sellOrder;
        $this->quantity = $quantity;
        $this->price = $price;
    }
    
    public function broadcastOn(): array
    {
        return [
            new Channel("prediction.{$this->buyOrder->prediction->slug}"),
        ];
    }
    
    public function broadcastAs(): string
    {
        return 'order.matched';
    }
    
    public function broadcastWith(): array
    {
        return [
            'trade_id' => uniqid(),
            'quantity' => $this->quantity,
            'price' => $this->price,
            'buy_order_id' => $this->buyOrder->id,
            'sell_order_id' => $this->sellOrder->id,
            'timestamp' => now()->toISOString(),
        ];
    }
}
```

### 5. **Middleware e Validazione**

#### Rate Limiting Middleware
```php
<?php

namespace Modules\Predict\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Cache\RateLimiter;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class TradingRateLimit
{
    protected RateLimiter $limiter;
    
    public function __construct(RateLimiter $limiter)
    {
        $this->limiter = $limiter;
    }
    
    public function handle(Request $request, Closure $next): Response
    {
        $key = 'trading:' . Auth::id();
        
        if ($this->limiter->tooManyAttempts($key, 10)) { // 10 ordini per minuto
            return response()->json([
                'error' => 'Troppi ordini. Riprova tra un minuto.',
                'retry_after' => $this->limiter->availableIn($key),
            ], 429);
        }
        
        $this->limiter->hit($key, 60); // 1 minuto di finestra
        
        return $next($request);
    }
}
```

#### Order Validation Request
```php
<?php

namespace Modules\Predict\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Predict\Models\Prediction;
use Illuminate\Validation\Rule;

class PlaceOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }
    
    public function rules(): array
    {
        return [
            'prediction_slug' => ['required', 'string', 'exists:predictions,slug'],
            'type' => ['required', 'string', Rule::in(['buy', 'sell'])],
            'quantity' => ['required', 'integer', 'min:1', 'max:10000'],
            'price_limit' => ['nullable', 'numeric', 'min:0', 'max:100'],
        ];
    }
    
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $this->validateOrderLimits($validator);
            $this->validateMarketHours($validator);
            $this->validateUserFunds($validator);
        });
    }
    
    private function validateOrderLimits($validator): void
    {
        $prediction = Prediction::where('slug', $this->prediction_slug)->first();
        
        if (!$prediction || $prediction->status !== 'active') {
            $validator->errors()->add('prediction', 'Mercato non disponibile per il trading');
        }
    }
    
    private function validateMarketHours($validator): void
    {
        $prediction = Prediction::where('slug', $this->prediction_slug)->first();
        
        if ($prediction && $prediction->market_hours) {
            $now = now();
            $marketOpen = $prediction->market_open_time;
            $marketClose = $prediction->market_close_time;
            
            if ($now < $marketOpen || $now > $marketClose) {
                $validator->errors()->add('market_hours', 'Mercato chiuso. Orari: ' . $marketOpen->format('H:i') . ' - ' . $marketClose->format('H:i'));
            }
        }
    }
    
    private function validateUserFunds($validator): void
    {
        if ($this->type === 'buy') {
            $prediction = Prediction::where('slug', $this->prediction_slug)->first();
            $requiredFunds = $this->quantity * $prediction->current_price;
            
            if (auth()->user()->balance < $requiredFunds) {
                $validator->errors()->add('funds', 'Fondi insufficienti. Richiesti: €' . number_format($requiredFunds, 2));
            }
        }
    }
    
    public function messages(): array
    {
        return [
            'prediction_slug.required' => 'Prediction richiesta',
            'prediction_slug.exists' => 'Prediction non trovata',
            'type.required' => 'Tipo ordine richiesto',
            'type.in' => 'Tipo ordine non valido',
            'quantity.required' => 'Quantità richiesta',
            'quantity.integer' => 'Quantità deve essere un numero intero',
            'quantity.min' => 'Quantità minima: 1',
            'quantity.max' => 'Quantità massima: 10,000',
        ];
    }
}
```

### 6. **Configurazione Broadcasting**

#### Broadcasting Configuration
```php
// config/broadcasting.php
'pusher' => [
    'driver' => 'pusher',
    'key' => env('PUSHER_APP_KEY'),
    'secret' => env('PUSHER_APP_SECRET'),
    'app_id' => env('PUSHER_APP_ID'),
    'options' => [
        'cluster' => env('PUSHER_APP_CLUSTER'),
        'encrypted' => true,
        'host' => env('PUSHER_HOST') ?: 'api-'.env('PUSHER_APP_CLUSTER', 'mt1').'.pusherapp.com',
        'port' => env('PUSHER_PORT', 443),
        'scheme' => env('PUSHER_SCHEME', 'https'),
    ],
],
```

#### JavaScript per Real-time Updates
```javascript
// resources/js/trading.js
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY,
    cluster: process.env.MIX_PUSHER_APP_CLUSTER,
    encrypted: true,
});

// Ascolta eventi per una prediction specifica
function subscribeToPrediction(slug) {
    window.Echo.channel(`prediction.${slug}`)
        .listen('.order.placed', (e) => {
            console.log('Nuovo ordine piazzato:', e);
            // Aggiorna order book
            Livewire.dispatch('orderBookUpdated');
        })
        .listen('.order.matched', (e) => {
            console.log('Ordine eseguito:', e);
            // Aggiorna grafici e statistiche
            Livewire.dispatch('tradeExecuted', e);
        });
}

// Inizializza quando la pagina è caricata
document.addEventListener('livewire:init', () => {
    const slug = document.querySelector('[data-prediction-slug]')?.dataset.predictionSlug;
    if (slug) {
        subscribeToPrediction(slug);
    }
});
```

## 📊 Database Schema

### Migrazioni

#### Orders Table
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prediction_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['buy', 'sell']);
            $table->integer('quantity');
            $table->decimal('price', 8, 4);
            $table->enum('status', ['open', 'filled', 'cancelled', 'partially_filled'])->default('open');
            $table->integer('filled_quantity')->default(0);
            $table->decimal('average_fill_price', 8, 4)->nullable();
            $table->timestamp('filled_at')->nullable();
            $table->timestamps();
            
            $table->index(['prediction_id', 'type', 'status']);
            $table->index(['user_id', 'status']);
            $table->index(['prediction_id', 'price', 'type']);
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
```

#### Positions Table
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('positions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('prediction_id')->constrained()->onDelete('cascade');
            $table->integer('quantity')->default(0);
            $table->decimal('total_cost', 12, 4)->default(0);
            $table->decimal('average_price', 8, 4)->default(0);
            $table->decimal('realized_pnl', 12, 4)->default(0);
            $table->decimal('unrealized_pnl', 12, 4)->default(0);
            $table->timestamp('last_trade_at')->nullable();
            $table->timestamps();
            
            $table->unique(['user_id', 'prediction_id']);
            $table->index(['user_id', 'prediction_id']);
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('positions');
    }
};
```

#### Trades Table (per audit trail)
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prediction_id')->constrained()->onDelete('cascade');
            $table->foreignId('buy_order_id')->constrained('orders')->onDelete('cascade');
            $table->foreignId('sell_order_id')->constrained('orders')->onDelete('cascade');
            $table->foreignId('buyer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('seller_id')->constrained('users')->onDelete('cascade');
            $table->integer('quantity');
            $table->decimal('price', 8, 4);
            $table->decimal('total_value', 12, 4);
            $table->string('trade_hash')->unique(); // Per audit trail
            $table->timestamps();
            
            $table->index(['prediction_id', 'created_at']);
            $table->index(['buyer_id', 'created_at']);
            $table->index(['seller_id', 'created_at']);
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('trades');
    }
};
```

## 🔒 Sicurezza e Compliance

### 1. **Audit Trail**
```php
<?php

namespace Modules\Predict\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class AuditService
{
    public function logTrade(array $tradeData): void
    {
        $hash = $this->generateTradeHash($tradeData);
        
        Log::channel('trading')->info('Trade executed', [
            'trade_id' => $tradeData['id'],
            'hash' => $hash,
            'prediction_id' => $tradeData['prediction_id'],
            'buyer_id' => $tradeData['buyer_id'],
            'seller_id' => $tradeData['seller_id'],
            'quantity' => $tradeData['quantity'],
            'price' => $tradeData['price'],
            'timestamp' => now()->toISOString(),
        ]);
    }
    
    private function generateTradeHash(array $data): string
    {
        $string = json_encode($data) . now()->timestamp;
        return Hash::make($string);
    }
}
```

### 2. **Rate Limiting Avanzato**
```php
<?php

namespace Modules\Predict\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Cache\RateLimiter;
use Illuminate\Support\Facades\Auth;

class AdvancedTradingRateLimit
{
    protected RateLimiter $limiter;
    
    public function __construct(RateLimiter $limiter)
    {
        $this->limiter = $limiter;
    }
    
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        $key = "trading:{$user->id}";
        
        // Limiti diversi per utenti con diversa reputazione
        $limits = $this->getUserLimits($user);
        
        if ($this->limiter->tooManyAttempts($key, $limits['orders_per_minute'])) {
            return response()->json([
                'error' => 'Limite ordini superato',
                'retry_after' => $this->limiter->availableIn($key),
            ], 429);
        }
        
        $this->limiter->hit($key, 60);
        
        return $next($request);
    }
    
    private function getUserLimits($user): array
    {
        $reputation = $user->trading_reputation ?? 0;
        
        if ($reputation >= 1000) {
            return ['orders_per_minute' => 50]; // Trader esperti
        } elseif ($reputation >= 100) {
            return ['orders_per_minute' => 20]; // Trader intermedi
        } else {
            return ['orders_per_minute' => 10]; // Nuovi utenti
        }
    }
}
```

## 📈 Performance Optimizations

### 1. **Caching Strategy**
```php
<?php

namespace Modules\Predict\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

class CacheService
{
    public function getMarketData(string $slug): array
    {
        return Cache::tags(['market-data', "prediction-{$slug}"])
            ->remember("market-data-{$slug}", 30, function () use ($slug) {
                return $this->buildMarketData($slug);
            });
    }
    
    public function getOrderBook(string $slug): array
    {
        return Cache::tags(['order-book', "prediction-{$slug}"])
            ->remember("order-book-{$slug}", 10, function () use ($slug) {
                return $this->buildOrderBook($slug);
            });
    }
    
    public function invalidatePredictionCache(string $slug): void
    {
        Cache::tags(["prediction-{$slug}"])->flush();
    }
    
    public function warmCache(string $slug): void
    {
        // Pre-carica dati popolari
        $this->getMarketData($slug);
        $this->getOrderBook($slug);
        $this->getPriceHistory($slug);
    }
}
```

### 2. **Database Indexing**
```sql
-- Indici per performance ottimali
CREATE INDEX idx_orders_prediction_type_status ON orders(prediction_id, type, status);
CREATE INDEX idx_orders_user_status ON orders(user_id, status);
CREATE INDEX idx_orders_price_type ON orders(prediction_id, price, type);
CREATE INDEX idx_positions_user_prediction ON positions(user_id, prediction_id);
CREATE INDEX idx_trades_prediction_created ON trades(prediction_id, created_at);
CREATE INDEX idx_trades_buyer_created ON trades(buyer_id, created_at);
CREATE INDEX idx_trades_seller_created ON trades(seller_id, created_at);
```

### 3. **Queue Processing**
```php
<?php

namespace Modules\Predict\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Predict\Services\TradingService;

class ProcessOrderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    public $timeout = 30;
    public $tries = 3;
    
    public function __construct(
        protected array $orderData
    ) {}
    
    public function handle(TradingService $tradingService): void
    {
        $tradingService->placeOrder($this->orderData);
    }
    
    public function failed(\Throwable $exception): void
    {
        // Log dell'errore e notifica
        Log::error('Order processing failed', [
            'order_data' => $this->orderData,
            'error' => $exception->getMessage(),
        ]);
    }
}
```

Questa documentazione tecnica fornisce una base solida per implementare tutti i miglioramenti proposti, seguendo le best practices di Laravel e garantendo scalabilità, sicurezza e performance ottimali. 