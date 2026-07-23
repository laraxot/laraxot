# Implementazione Tecnica - Pagina Prediction Market

## 🏗️ Architettura del Sistema

### 1. Struttura Modulare
```
laravel/Modules/Predict/
├── Http/
│   ├── Controllers/
│   │   ├── PredictController.php
│   │   ├── TradingController.php
│   │   └── ApiController.php
│   ├── Livewire/
│   │   ├── TradingWidget.php
│   │   ├── PriceChartWidget.php
│   │   ├── OrderBookWidget.php
│   │   └── NotificationsWidget.php
│   └── Requests/
│       ├── PlaceBetRequest.php
│       └── UpdateMarketRequest.php
├── Models/
│   ├── Predict.php
│   ├── Bet.php
│   ├── Outcome.php
│   └── MarketEvent.php
├── Services/
│   ├── MarketMakerService.php
│   ├── TradingService.php
│   ├── NotificationService.php
│   └── AnalyticsService.php
├── Events/
│   ├── BetPlacedEvent.php
│   ├── PriceUpdatedEvent.php
│   └── MarketClosedEvent.php
└── resources/views/
    ├── pages/predicts/
    │   ├── [slug].blade.php
    │   └── components/
    │       ├── trading-widget.blade.php
    │       ├── price-chart.blade.php
    │       ├── order-book.blade.php
    │       └── notifications.blade.php
    └── livewire/
        ├── trading-widget.blade.php
        ├── price-chart-widget.blade.php
        ├── order-book-widget.blade.php
        └── notifications-widget.blade.php
```

## 🔧 Implementazione Componenti

### 1. Trading Widget

#### Livewire Component
```php
<?php

namespace Modules\Predict\Http\Livewire;

use Livewire\Component;
use Modules\Predict\Models\Predict;
use Modules\Predict\Models\Outcome;
use Modules\Predict\Services\TradingService;

class TradingWidget extends Component
{
    public Predict $market;
    public string $selectedOutcome = '';
    public float $amount = 0;
    public bool $isLoading = false;
    public array $prices = [];
    public array $notifications = [];
    
    protected $listeners = [
        'priceUpdated' => 'updatePrices',
        'betPlaced' => 'handleBetPlaced'
    ];
    
    public function mount(Predict $market)
    {
        $this->market = $market;
        $this->loadPrices();
    }
    
    public function loadPrices()
    {
        $this->prices = $this->market->outcomes->mapWithKeys(function ($outcome) {
            return [$outcome->id => [
                'price' => $outcome->current_price,
                'probability' => $outcome->probability,
                'volume_24h' => $outcome->volume_24h
            ]];
        })->toArray();
    }
    
    public function placeBet()
    {
        $this->validate([
            'selectedOutcome' => 'required|exists:outcomes,id',
            'amount' => 'required|numeric|min:0.01|max:10000',
        ]);
        
        $this->isLoading = true;
        
        try {
            $tradingService = app(TradingService::class);
            $result = $tradingService->placeBet(
                $this->market->id,
                $this->selectedOutcome,
                $this->amount,
                auth()->id()
            );
            
            $this->addNotification('Scommessa piazzata con successo!', 'success');
            $this->reset(['selectedOutcome', 'amount']);
            
            // Emetti evento per aggiornare altri componenti
            $this->emit('betPlaced', $result);
            
        } catch (\Exception $e) {
            $this->addNotification('Errore nel piazzare la scommessa: ' . $e->getMessage(), 'error');
        } finally {
            $this->isLoading = false;
        }
    }
    
    public function updatePrices($newPrices)
    {
        $this->prices = array_merge($this->prices, $newPrices);
    }
    
    public function addNotification($message, $type = 'info')
    {
        $this->notifications[] = [
            'id' => uniqid(),
            'message' => $message,
            'type' => $type,
            'timestamp' => now()
        ];
        
        // Rimuovi notifiche vecchie dopo 5 secondi
        $this->dispatchBrowserEvent('removeNotification', [
            'id' => end($this->notifications)['id']
        ]);
    }
    
    public function render()
    {
        return view('predict::livewire.trading-widget');
    }
}
```

#### Blade Template
```blade
{{-- resources/views/livewire/trading-widget.blade.php --}}
<div class="trading-widget bg-white rounded-xl shadow-sm border border-gray-200 p-6">
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-lg font-semibold text-gray-900">Trading</h3>
        <div class="text-sm text-gray-500">
            Ultimo aggiornamento: {{ now()->format('H:i:s') }}
        </div>
    </div>
    
    {{-- Prezzi correnti --}}
    <div class="mb-6">
        <h4 class="text-sm font-medium text-gray-700 mb-3">Prezzi Correnti</h4>
        <div class="space-y-2">
            @foreach($market->outcomes as $outcome)
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                <div class="flex-1">
                    <div class="font-medium text-gray-900">{{ $outcome->name }}</div>
                    <div class="text-sm text-gray-500">
                        Volume 24h: €{{ number_format($prices[$outcome->id]['volume_24h'] ?? 0) }}
                    </div>
                </div>
                <div class="text-right">
                    <div class="text-lg font-bold text-gray-900">
                        €{{ number_format($prices[$outcome->id]['price'] ?? 0, 2) }}
                    </div>
                    <div class="text-sm text-gray-500">
                        {{ number_format($prices[$outcome->id]['probability'] ?? 0, 1) }}%
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    
    {{-- Form di trading --}}
    <form wire:submit.prevent="placeBet" class="space-y-4">
        <div>
            <label for="outcome" class="block text-sm font-medium text-gray-700 mb-2">
                Seleziona Outcome
            </label>
            <select 
                id="outcome"
                wire:model="selectedOutcome"
                class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                required>
                <option value="">Scegli un outcome...</option>
                @foreach($market->outcomes as $outcome)
                <option value="{{ $outcome->id }}">
                    {{ $outcome->name }} (€{{ number_format($prices[$outcome->id]['price'] ?? 0, 2) }})
                </option>
                @endforeach
            </select>
        </div>
        
        <div>
            <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">
                Importo (€)
            </label>
            <div class="relative">
                <input 
                    type="number"
                    id="amount"
                    wire:model="amount"
                    step="0.01"
                    min="0.01"
                    max="10000"
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 pr-8"
                    placeholder="0.00"
                    required>
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                    <span class="text-gray-500 sm:text-sm">€</span>
                </div>
            </div>
        </div>
        
        {{-- Preview costi --}}
        @if($selectedOutcome && $amount > 0)
        <div class="bg-blue-50 rounded-lg p-4">
            <div class="flex justify-between items-center mb-2">
                <span class="text-sm text-gray-600">Costo totale:</span>
                <span class="font-semibold">
                    €{{ number_format($amount * ($prices[$selectedOutcome]['price'] ?? 0), 2) }}
                </span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-sm text-gray-600">Commissioni:</span>
                <span class="font-semibold">€{{ number_format($amount * 0.02, 2) }}</span>
            </div>
            <div class="border-t pt-2 mt-2">
                <div class="flex justify-between items-center">
                    <span class="text-sm font-medium">Totale da pagare:</span>
                    <span class="font-bold text-lg text-blue-600">
                        €{{ number_format($amount * ($prices[$selectedOutcome]['price'] ?? 0) * 1.02, 2) }}
                    </span>
                </div>
            </div>
        </div>
        @endif
        
        <button 
            type="submit"
            wire:loading.attr="disabled"
            class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg font-semibold hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
            <span wire:loading.remove>Piazza Scommessa</span>
            <span wire:loading>
                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Elaborazione...
            </span>
        </button>
    </form>
    
    {{-- Notifiche --}}
    @if(count($notifications) > 0)
    <div class="mt-4 space-y-2">
        @foreach($notifications as $notification)
        <div 
            x-data="{ show: true }"
            x-show="show"
            x-init="setTimeout(() => show = false, 5000)"
            class="p-3 rounded-lg text-sm {{ $notification['type'] === 'success' ? 'bg-green-50 text-green-800' : ($notification['type'] === 'error' ? 'bg-red-50 text-red-800' : 'bg-blue-50 text-blue-800') }}">
            {{ $notification['message'] }}
        </div>
        @endforeach
    </div>
    @endif
</div>
```

### 2. Price Chart Widget

#### Livewire Component
```php
<?php

namespace Modules\Predict\Http\Livewire;

use Livewire\Component;
use Modules\Predict\Models\Predict;
use Modules\Predict\Services\AnalyticsService;

class PriceChartWidget extends Component
{
    public Predict $market;
    public string $timeframe = '24h';
    public array $chartData = [];
    public bool $isLoading = false;
    
    protected $listeners = [
        'priceUpdated' => 'updateChartData',
        'timeframeChanged' => 'changeTimeframe'
    ];
    
    public function mount(Predict $market)
    {
        $this->market = $market;
        $this->loadChartData();
    }
    
    public function loadChartData()
    {
        $this->isLoading = true;
        
        try {
            $analyticsService = app(AnalyticsService::class);
            $this->chartData = $analyticsService->getPriceHistory(
                $this->market->id,
                $this->timeframe
            );
        } catch (\Exception $e) {
            $this->chartData = [];
        } finally {
            $this->isLoading = false;
        }
    }
    
    public function changeTimeframe($timeframe)
    {
        $this->timeframe = $timeframe;
        $this->loadChartData();
    }
    
    public function updateChartData($newData)
    {
        // Aggiorna i dati del grafico con i nuovi prezzi
        if (isset($newData['prices'])) {
            $this->chartData['prices'] = array_merge(
                $this->chartData['prices'] ?? [],
                $newData['prices']
            );
        }
    }
    
    public function render()
    {
        return view('predict::livewire.price-chart-widget');
    }
}
```

#### Blade Template
```blade
{{-- resources/views/livewire/price-chart-widget.blade.php --}}
<div class="price-chart-widget bg-white rounded-xl shadow-sm border border-gray-200 p-6">
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-lg font-semibold text-gray-900">Andamento Prezzi</h3>
        
        {{-- Controlli timeframe --}}
        <div class="flex space-x-2">
            <button 
                wire:click="changeTimeframe('1h')"
                class="px-3 py-1 text-sm rounded-md {{ $timeframe === '1h' ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:bg-gray-100' }}">
                1H
            </button>
            <button 
                wire:click="changeTimeframe('24h')"
                class="px-3 py-1 text-sm rounded-md {{ $timeframe === '24h' ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:bg-gray-100' }}">
                24H
            </button>
            <button 
                wire:click="changeTimeframe('7d')"
                class="px-3 py-1 text-sm rounded-md {{ $timeframe === '7d' ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:bg-gray-100' }}">
                7G
            </button>
            <button 
                wire:click="changeTimeframe('30d')"
                class="px-3 py-1 text-sm rounded-md {{ $timeframe === '30d' ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:bg-gray-100' }}">
                30G
            </button>
        </div>
    </div>
    
    {{-- Loading state --}}
    @if($isLoading)
    <div class="flex items-center justify-center h-64">
        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
    </div>
    @else
    {{-- Chart container --}}
    <div class="relative h-64">
        <canvas id="priceChart-{{ $market->id }}" width="400" height="200"></canvas>
    </div>
    
    {{-- Statistiche --}}
    <div class="grid grid-cols-3 gap-4 mt-6">
        <div class="text-center">
            <div class="text-2xl font-bold text-green-600">
                +{{ number_format($chartData['change_24h'] ?? 0, 1) }}%
            </div>
            <div class="text-sm text-gray-500">Variazione 24h</div>
        </div>
        <div class="text-center">
            <div class="text-2xl font-bold text-blue-600">
                €{{ number_format($chartData['volume_24h'] ?? 0) }}
            </div>
            <div class="text-sm text-gray-500">Volume 24h</div>
        </div>
        <div class="text-center">
            <div class="text-2xl font-bold text-purple-600">
                {{ number_format($chartData['trades_24h'] ?? 0) }}
            </div>
            <div class="text-sm text-gray-500">Transazioni</div>
        </div>
    </div>
    @endif
</div>

@push('scripts')
<script>
document.addEventListener('livewire:load', function () {
    const ctx = document.getElementById('priceChart-{{ $market->id }}').getContext('2d');
    
    const chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($chartData['labels'] ?? []),
            datasets: @json($chartData['datasets'] ?? [])
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                intersect: false,
                mode: 'index'
            },
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': €' + context.parsed.y.toFixed(2);
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    ticks: {
                        callback: function(value) {
                            return '€' + value.toFixed(2);
                        }
                    }
                }
            }
        }
    });
    
    // Aggiorna il grafico quando i dati cambiano
    Livewire.on('chartDataUpdated', (data) => {
        chart.data.labels = data.labels;
        chart.data.datasets = data.datasets;
        chart.update();
    });
});
</script>
@endpush
```

### 3. Order Book Widget

#### Livewire Component
```php
<?php

namespace Modules\Predict\Http\Livewire;

use Livewire\Component;
use Modules\Predict\Models\Predict;
use Modules\Predict\Services\TradingService;

class OrderBookWidget extends Component
{
    public Predict $market;
    public array $buyOrders = [];
    public array $sellOrders = [];
    public float $spread = 0;
    public bool $isLoading = false;
    
    protected $listeners = [
        'orderBookUpdated' => 'updateOrderBook'
    ];
    
    public function mount(Predict $market)
    {
        $this->market = $market;
        $this->loadOrderBook();
    }
    
    public function loadOrderBook()
    {
        $this->isLoading = true;
        
        try {
            $tradingService = app(TradingService::class);
            $orderBook = $tradingService->getOrderBook($this->market->id);
            
            $this->buyOrders = $orderBook['buy'] ?? [];
            $this->sellOrders = $orderBook['sell'] ?? [];
            $this->spread = $orderBook['spread'] ?? 0;
        } catch (\Exception $e) {
            $this->buyOrders = [];
            $this->sellOrders = [];
            $this->spread = 0;
        } finally {
            $this->isLoading = false;
        }
    }
    
    public function updateOrderBook($orderBook)
    {
        $this->buyOrders = $orderBook['buy'] ?? [];
        $this->sellOrders = $orderBook['sell'] ?? [];
        $this->spread = $orderBook['spread'] ?? 0;
    }
    
    public function render()
    {
        return view('predict::livewire.order-book-widget');
    }
}
```

#### Blade Template
```blade
{{-- resources/views/livewire/order-book-widget.blade.php --}}
<div class="order-book-widget bg-white rounded-xl shadow-sm border border-gray-200 p-6">
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-lg font-semibold text-gray-900">Order Book</h3>
        <div class="text-sm text-gray-500">
            Spread: €{{ number_format($spread, 2) }}
        </div>
    </div>
    
    @if($isLoading)
    <div class="flex items-center justify-center h-32">
        <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600"></div>
    </div>
    @else
    <div class="grid grid-cols-2 gap-6">
        {{-- Buy Orders --}}
        <div>
            <h4 class="text-sm font-medium text-green-600 mb-3">Acquisti</h4>
            <div class="space-y-2">
                @forelse($buyOrders as $order)
                <div class="flex justify-between items-center text-sm">
                    <span class="text-gray-600">€{{ number_format($order['price'], 2) }}</span>
                    <span class="font-medium">{{ number_format($order['quantity']) }}</span>
                </div>
                @empty
                <div class="text-sm text-gray-400 text-center py-4">
                    Nessun ordine di acquisto
                </div>
                @endforelse
            </div>
        </div>
        
        {{-- Sell Orders --}}
        <div>
            <h4 class="text-sm font-medium text-red-600 mb-3">Vendite</h4>
            <div class="space-y-2">
                @forelse($sellOrders as $order)
                <div class="flex justify-between items-center text-sm">
                    <span class="text-gray-600">€{{ number_format($order['price'], 2) }}</span>
                    <span class="font-medium">{{ number_format($order['quantity']) }}</span>
                </div>
                @empty
                <div class="text-sm text-gray-400 text-center py-4">
                    Nessun ordine di vendita
                </div>
                @endforelse
            </div>
        </div>
    </div>
    @endif
</div>
```

## 🔄 Real-time Updates

### 1. WebSocket Events
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

class PriceUpdatedEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public string $marketId,
        public array $prices,
        public array $volumes
    ) {}

    public function broadcastOn(): array
    {
        return [
            new Channel("market.{$this->marketId}")
        ];
    }

    public function broadcastAs(): string
    {
        return 'price.updated';
    }

    public function broadcastWith(): array
    {
        return [
            'market_id' => $this->marketId,
            'prices' => $this->prices,
            'volumes' => $this->volumes,
            'timestamp' => now()->toISOString()
        ];
    }
}
```

### 2. Frontend WebSocket Listener
```javascript
// resources/js/prediction-market.js
class PredictionMarketWebSocket {
    constructor(marketId) {
        this.marketId = marketId;
        this.channel = Echo.channel(`market.${marketId}`);
        this.setupListeners();
    }
    
    setupListeners() {
        // Ascolta aggiornamenti prezzi
        this.channel.listen('.price.updated', (e) => {
            this.handlePriceUpdate(e);
        });
        
        // Ascolta nuove scommesse
        this.channel.listen('.bet.placed', (e) => {
            this.handleBetPlaced(e);
        });
        
        // Ascolta aggiornamenti order book
        this.channel.listen('.orderbook.updated', (e) => {
            this.handleOrderBookUpdate(e);
        });
    }
    
    handlePriceUpdate(data) {
        // Aggiorna i prezzi nei componenti Livewire
        Livewire.emit('priceUpdated', data.prices);
        
        // Aggiorna il grafico
        if (window.priceChart) {
            window.priceChart.data.labels.push(new Date().toLocaleTimeString());
            window.priceChart.data.datasets.forEach((dataset, index) => {
                const outcomeId = dataset.outcomeId;
                if (data.prices[outcomeId]) {
                    dataset.data.push(data.prices[outcomeId]);
                }
            });
            
            // Mantieni solo gli ultimi 100 punti
            if (window.priceChart.data.labels.length > 100) {
                window.priceChart.data.labels.shift();
                window.priceChart.data.datasets.forEach(dataset => {
                    dataset.data.shift();
                });
            }
            
            window.priceChart.update('none');
        }
        
        // Mostra notifica
        this.showNotification('Prezzi aggiornati', 'info');
    }
    
    handleBetPlaced(data) {
        // Aggiorna l'order book
        Livewire.emit('orderBookUpdated', data.orderBook);
        
        // Mostra notifica
        this.showNotification('Nuova scommessa piazzata', 'success');
    }
    
    handleOrderBookUpdate(data) {
        // Aggiorna l'order book
        Livewire.emit('orderBookUpdated', data);
    }
    
    showNotification(message, type = 'info') {
        // Implementa notifica toast
        const toast = document.createElement('div');
        toast.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 ${
            type === 'success' ? 'bg-green-500 text-white' :
            type === 'error' ? 'bg-red-500 text-white' :
            'bg-blue-500 text-white'
        }`;
        toast.textContent = message;
        
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.remove();
        }, 3000);
    }
}

// Inizializza WebSocket quando la pagina è caricata
document.addEventListener('DOMContentLoaded', function() {
    const marketId = document.querySelector('[data-market-id]')?.dataset.marketId;
    if (marketId) {
        window.predictionMarketWS = new PredictionMarketWebSocket(marketId);
    }
});
```

## 📊 Database Migrations

### 1. Aggiunta Campi Analytics
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAnalyticsToPredictsTable extends Migration
{
    public function up()
    {
        Schema::table('predicts', function (Blueprint $table) {
            $table->decimal('total_volume', 15, 2)->default(0)->after('description');
            $table->integer('unique_traders')->default(0)->after('total_volume');
            $table->decimal('avg_trade_size', 10, 2)->default(0)->after('unique_traders');
            $table->decimal('volatility', 5, 2)->default(0)->after('avg_trade_size');
            $table->decimal('liquidity_score', 5, 2)->default(0)->after('volatility');
            $table->json('price_history')->nullable()->after('liquidity_score');
            $table->json('volume_history')->nullable()->after('price_history');
            $table->timestamp('last_trade_at')->nullable()->after('volume_history');
        });
    }

    public function down()
    {
        Schema::table('predicts', function (Blueprint $table) {
            $table->dropColumn([
                'total_volume',
                'unique_traders',
                'avg_trade_size',
                'volatility',
                'liquidity_score',
                'price_history',
                'volume_history',
                'last_trade_at'
            ]);
        });
    }
}
```

### 2. Tabella Eventi di Mercato
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMarketEventsTable extends Migration
{
    public function up()
    {
        Schema::create('market_events', function (Blueprint $table) {
            $table->id();
            $table->uuid('event_id')->unique();
            $table->foreignId('market_id')->constrained('predicts')->onDelete('cascade');
            $table->string('event_type');
            $table->json('data');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('hash')->index();
            $table->timestamps();
            
            $table->index(['market_id', 'event_type']);
            $table->index(['created_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('market_events');
    }
}
```

## 🔒 Security Implementation

### 1. Rate Limiting
```php
<?php

namespace Modules\Predict\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class TradingRateLimit
{
    public function handle(Request $request, Closure $next): Response
    {
        $key = 'trading:' . $request->user()->id;
        
        if (RateLimiter::tooManyAttempts($key, 10)) {
            return response()->json([
                'error' => 'Troppe richieste. Riprova tra un minuto.'
            ], 429);
        }
        
        RateLimiter::hit($key, 60);
        
        return $next($request);
    }
}
```

### 2. Validazione Input
```php
<?php

namespace Modules\Predict\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PlaceBetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'market_id' => 'required|exists:predicts,id',
            'outcome_id' => 'required|exists:outcomes,id',
            'amount' => 'required|numeric|min:0.01|max:10000',
        ];
    }

    public function messages(): array
    {
        return [
            'market_id.required' => 'Il mercato è obbligatorio.',
            'market_id.exists' => 'Il mercato selezionato non esiste.',
            'outcome_id.required' => 'L\'outcome è obbligatorio.',
            'outcome_id.exists' => 'L\'outcome selezionato non esiste.',
            'amount.required' => 'L\'importo è obbligatorio.',
            'amount.numeric' => 'L\'importo deve essere un numero.',
            'amount.min' => 'L\'importo minimo è €0.01.',
            'amount.max' => 'L\'importo massimo è €10,000.',
        ];
    }
}
```

## 📱 Mobile Optimization

### 1. CSS Responsive
```css
/* resources/css/prediction-market.css */

/* Mobile First Approach */
.prediction-market-container {
    padding: 1rem;
}

.trading-widget {
    margin-bottom: 1rem;
}

.price-chart-widget {
    margin-bottom: 1rem;
}

/* Tablet */
@media (min-width: 768px) {
    .prediction-market-container {
        padding: 2rem;
    }
    
    .trading-widget {
        position: sticky;
        top: 1rem;
    }
}

/* Desktop */
@media (min-width: 1024px) {
    .prediction-market-container {
        padding: 3rem;
        max-width: 1200px;
        margin: 0 auto;
    }
    
    .grid-layout {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 2rem;
    }
}

/* Touch Optimization */
.trading-button {
    min-height: 44px;
    min-width: 44px;
    padding: 12px 16px;
    border-radius: 8px;
    font-size: 16px;
    -webkit-tap-highlight-color: transparent;
    touch-action: manipulation;
}

.trading-button:active {
    transform: scale(0.98);
}

/* Loading States */
.loading-skeleton {
    background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
    background-size: 200% 100%;
    animation: loading 1.5s infinite;
}

@keyframes loading {
    0% {
        background-position: 200% 0;
    }
    100% {
        background-position: -200% 0;
    }
}

/* Notifications */
.notification-toast {
    position: fixed;
    top: 1rem;
    right: 1rem;
    z-index: 50;
    max-width: 300px;
    animation: slideIn 0.3s ease-out;
}

@keyframes slideIn {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}
```

## 🧪 Testing

### 1. Unit Tests
```php
<?php

namespace Modules\Predict\Tests\Unit;

use Tests\TestCase;
use Modules\Predict\Models\Predict;
use Modules\Predict\Models\Outcome;
use Modules\Predict\Services\TradingService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TradingServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_place_bet()
    {
        $market = Predict::factory()->create();
        $outcome = Outcome::factory()->create(['predict_id' => $market->id]);
        $user = User::factory()->create(['balance' => 1000]);
        
        $tradingService = app(TradingService::class);
        
        $result = $tradingService->placeBet(
            $market->id,
            $outcome->id,
            100,
            $user->id
        );
        
        $this->assertTrue($result['success']);
        $this->assertDatabaseHas('bets', [
            'user_id' => $user->id,
            'predict_id' => $market->id,
            'outcome_id' => $outcome->id,
            'amount' => 100
        ]);
    }
    
    public function test_cannot_place_bet_with_insufficient_balance()
    {
        $market = Predict::factory()->create();
        $outcome = Outcome::factory()->create(['predict_id' => $market->id]);
        $user = User::factory()->create(['balance' => 50]);
        
        $tradingService = app(TradingService::class);
        
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Saldo insufficiente');
        
        $tradingService->placeBet(
            $market->id,
            $outcome->id,
            100,
            $user->id
        );
    }
}
```

### 2. Feature Tests
```php
<?php

namespace Modules\Predict\Tests\Feature;

use Tests\TestCase;
use Modules\Predict\Models\Predict;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PredictionMarketTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_market_page()
    {
        $market = Predict::factory()->create();
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)
            ->get("/predicts/{$market->slug}");
        
        $response->assertStatus(200);
        $response->assertSee($market->title);
    }
    
    public function test_user_can_place_bet()
    {
        $market = Predict::factory()->create();
        $outcome = Outcome::factory()->create(['predict_id' => $market->id]);
        $user = User::factory()->create(['balance' => 1000]);
        
        $response = $this->actingAs($user)
            ->post("/api/bets", [
                'market_id' => $market->id,
                'outcome_id' => $outcome->id,
                'amount' => 100
            ]);
        
        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
    }
}
```

## 📊 Performance Optimization

### 1. Caching Strategy
```php
<?php

namespace Modules\Predict\Services;

use Illuminate\Support\Facades\Cache;
use Modules\Predict\Models\Predict;

class PredictionMarketCache
{
    public function getMarketData(string $marketId): array
    {
        return Cache::remember("market_{$marketId}_data", 300, function () use ($marketId) {
            $market = Predict::with(['outcomes', 'category'])
                ->findOrFail($marketId);
            
            return [
                'market' => $market->toArray(),
                'outcomes' => $market->outcomes->toArray(),
                'analytics' => $this->getMarketAnalytics($marketId)
            ];
        });
    }
    
    public function getPriceHistory(string $marketId, string $timeframe): array
    {
        return Cache::remember("market_{$marketId}_prices_{$timeframe}", 60, function () use ($marketId, $timeframe) {
            return $this->calculatePriceHistory($marketId, $timeframe);
        });
    }
    
    public function invalidateMarketCache(string $marketId): void
    {
        Cache::forget("market_{$marketId}_data");
        Cache::forget("market_{$marketId}_prices_1h");
        Cache::forget("market_{$marketId}_prices_24h");
        Cache::forget("market_{$marketId}_prices_7d");
        Cache::forget("market_{$marketId}_prices_30d");
    }
}
```

### 2. Database Optimization
```php
<?php

namespace Modules\Predict\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Predict extends Model
{
    protected $casts = [
        'price_history' => 'array',
        'volume_history' => 'array',
        'last_trade_at' => 'datetime',
    ];
    
    public function outcomes(): HasMany
    {
        return $this->hasMany(Outcome::class);
    }
    
    public function bets(): HasMany
    {
        return $this->hasMany(Bet::class);
    }
    
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
    
    public function scopeActive($query)
    {
        return $query->where('status', 'active')
                    ->where('end_date', '>', now());
    }
    
    public function scopeWithAnalytics($query)
    {
        return $query->withCount(['bets as total_bets'])
                    ->withSum(['bets as total_volume'], 'amount')
                    ->withAvg(['bets as avg_bet_size'], 'amount');
    }
}
```

## 🚀 Deployment Checklist

### Pre-Deployment
- [ ] Test su ambiente staging
- [ ] Verifica performance con dati reali
- [ ] Controllo sicurezza e validazioni
- [ ] Backup database
- [ ] Test WebSocket connections
- [ ] Verifica mobile responsiveness

### Post-Deployment
- [ ] Monitoraggio errori (Sentry)
- [ ] Verifica metriche performance
- [ ] Test funzionalità critiche
- [ ] Feedback utenti
- [ ] Monitoraggio WebSocket
- [ ] Verifica caching

---

*Documento creato il: {{ date('Y-m-d H:i:s') }}*
*Ultimo aggiornamento: {{ date('Y-m-d H:i:s') }}* 