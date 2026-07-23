# ⚡ VOLT CLASS-BASED COMPONENTS - TwentyOne Theme

**Data**: 2026-03-20  
**Stato**: ✅ OBBLIGATORIO  
**Theme**: TwentyOne  
**Riferimento**: `docs/project/VOLT_CLASS_BASED_COMPONENTS.md`

---

## 🎯 FILOSOFIA TWENTYONE

### Ruolo del Theme con Volt

> **"Il Theme contiene SOLO Volt components per routing e UI. La logica è nei Moduli."**

**Architettura**:
```
Themes/TwentyOne/
├── resources/views/
│   ├── layouts/
│   │   └── app.blade.php          # Layout principale
│   ├── pages/                      # Folio routing (VOLT HERE)
│   │   ├── index.blade.php         # Homepage
│   │   ├── [container0]/
│   │   │   ├── index.blade.php     # Generic list
│   │   │   └── [slug0]/
│   │   │       └── index.blade.php # Generic detail
│   │   └── auth/
│   │       ├── login.blade.php     # Login page
│   │       └── register.blade.php  # Register page
│   ├── livewire/                   # Reusable Volt components
│   │   ├── predict-card.blade.php
│   │   ├── market-stats.blade.php
│   │   └── order-book.blade.php
│   └── filament/
│       └── widgets/                # Filament widget views
│           ├── predict-table.blade.php
│           └── trading-form.blade.php
└── docs/
    └── VOLT_INTEGRATION.md         # ← Questo file
```

**Regola d'Oro**:
- ✅ **Theme** = Volt components per routing (pages/) + UI (livewire/)
- ✅ **Moduli** = Filament Widgets + Actions + Models
- ❌ **Theme** = NO logica di business, NO Models diretti

---

## 📋 VOLT COMPONENTS NEL THEME

### 1. Homepage (Folio + Volt)

**File**: `resources/views/pages/index.blade.php`

```php
<?php

declare(strict_types=1);

use Livewire\Volt\Component;
use Modules\Cms\Actions\GetHomepageDataAction;

use function Laravel\Folio\name;

name('home');

new class extends Component {
    public array $blocks = [];
    
    public function mount(GetHomepageDataAction $action): void
    {
        $this->blocks = $action->execute();
    }
};
?>

<x-layouts.app>
    @volt('home')
    <div class="min-h-screen">
        @foreach($blocks as $block)
            <x-section :slug="$block['slug']" :data="$block['data']" />
        @endforeach
    </div>
    @endvolt
</x-layouts.app>
```

**Best Practices**:
- ✅ Action class per dati homepage
- ✅ CMS-driven (JSON config)
- ✅ Section components per rendering
- ✅ NO query dirette nel componente

---

### 2. Generic List Page (Folio + Volt)

**File**: `resources/views/pages/[container0]/index.blade.php`

```php
<?php

declare(strict_types=1);

use Livewire\Volt\Component;
use Modules\Cms\Actions\ResolvePageAction;

use function Laravel\Folio\name;

name('container0.index');

new class extends Component {
    public string $container0 = '';
    public array $data = [];
    
    public function mount(
        ResolvePageAction $action,
        string $container0
    ): void {
        $this->container0 = $container0;
        
        $resolved = $action->execute($container0, '');
        $this->data = $resolved->data;
    }
};
?>

<x-layouts.app>
    @volt('container0.index')
    <div class="min-h-screen">
        <!-- Content -->
        <x-page side="content" :slug="$container0" :data="$data" />
        
        <!-- Sidebar -->
        <x-page side="sidebar" :slug="$container0" :data="$data" />
    </div>
    @endvolt
</x-layouts.app>
```

**Best Practices**:
- ✅ Agnostic (works for predicts, blog, events, etc.)
- ✅ ResolvePageAction per tutti i dati
- ✅ NO if ($container0 === 'predicts')
- ✅ NO Predict::query() nel componente

---

### 3. Generic Detail Page (Folio + Volt)

**File**: `resources/views/pages/[container0]/[slug0]/index.blade.php`

```php
<?php

declare(strict_types=1);

use Livewire\Volt\Component;
use Modules\Cms\Actions\ResolvePageAction;

use function Laravel\Folio\name;

name('container0.view');

new class extends Component {
    public string $container0 = '';
    public string $slug0 = '';
    public string $pageSlug = '';
    public array $data = [];
    
    public function mount(
        ResolvePageAction $action,
        string $container0,
        string $slug0
    ): void {
        $this->container0 = $container0;
        $this->slug0 = $slug0;
        
        $resolved = $action->execute($container0, $slug0);
        $this->pageSlug = $resolved->pageSlug;
        $this->data = $resolved->data;
    }
};
?>

<x-layouts.app>
    @volt('container0.view')
    <div class="mx-auto grid max-w-7xl grid-cols-1 gap-6 lg:grid-cols-12">
        <!-- Main Content (8 cols) -->
        <div class="lg:col-span-8">
            <x-page side="content" :slug="$pageSlug" :data="$data" />
        </div>
        
        <!-- Sidebar (4 cols) -->
        <aside class="lg:col-span-4">
            <div class="lg:sticky lg:top-6">
                <x-page side="sidebar" :slug="$pageSlug" :data="$data" />
            </div>
        </aside>
    </div>
    @endvolt
</x-layouts.app>
```

**Best Practices**:
- ✅ Grid layout 8+4 (responsive)
- ✅ Sidebar sticky su desktop
- ✅ ResolvePageAction per dati
- ✅ NO logica specifica per tipo

---

### 4. Auth Pages (Folio + Volt)

**Login**: `resources/views/pages/auth/login.blade.php`

```php
<?php

declare(strict_types=1);

use Livewire\Volt\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;

use function Laravel\Folio\name;

name('login');

new class extends Component {
    #[Validate('required|email')]
    public string $email = '';
    
    #[Validate('required|min:8')]
    public string $password = '';
    
    public bool $remember = false;
    
    public function login(): void
    {
        $this->validate();
        
        if (!Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            $this->addError('email', 'Credenziali non valide');
            return;
        }
        
        redirect()->intended('/');
    }
};
?>

<x-layouts.app>
    @volt('login')
    <div class="flex min-h-screen items-center justify-center">
        <div class="w-full max-w-md rounded-lg bg-white p-6 shadow">
            <h1 class="mb-6 text-2xl font-bold">Accedi</h1>
            
            <form wire:submit="login">
                <div class="mb-4">
                    <label class="block text-sm font-medium">Email</label>
                    <input 
                        type="email" 
                        wire:model="email"
                        class="mt-1 w-full rounded-md border-gray-300"
                    >
                    @error('email') 
                        <span class="text-sm text-red-500">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium">Password</label>
                    <input 
                        type="password" 
                        wire:model="password"
                        class="mt-1 w-full rounded-md border-gray-300"
                    >
                    @error('password') 
                        <span class="text-sm text-red-500">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="mb-4 flex items-center">
                    <input 
                        type="checkbox" 
                        wire:model="remember"
                        class="rounded border-gray-300"
                    >
                    <label class="ml-2 text-sm">Ricordami</label>
                </div>
                
                <button 
                    type="submit"
                    class="w-full rounded-lg bg-primary px-4 py-2 font-bold text-white"
                >
                    Accedi
                </button>
            </form>
        </div>
    </div>
    @endvolt
</x-layouts.app>
```

---

### 5. Reusable Livewire Components

**Predict Card**: `resources/views/livewire/predict-card.blade.php`

```php
<?php

declare(strict_types=1);

use Livewire\Volt\Component;
use Modules\Predict\Models\Predict;

new class extends Component {
    public Predict $predict;
    public bool $showStats = true;
    
    public function mount(Predict $predict): void
    {
        $this->predict = $predict;
    }
    
    public function getProbabilityAttribute(): float
    {
        return $this->predict->current_probability;
    }
    
    public function getVolumeAttribute(): int
    {
        return $this->predict->transactions()->sum('amount');
    }
};
?>

<div class="card rounded-lg bg-white p-4 shadow">
    <h3 class="mb-2 text-lg font-bold">{{ $predict->title }}</h3>
    
    @if($showStats)
        <div class="mb-4 space-y-2">
            <div class="flex justify-between">
                <span class="text-sm text-gray-500">Probabilità</span>
                <span class="font-bold">{{ $probability }}%</span>
            </div>
            <div class="flex justify-between">
                <span class="text-sm text-gray-500">Volume</span>
                <span class="font-bold">{{ $volume }} Credits</span>
            </div>
        </div>
    @endif
    
    <!-- Progress Bar -->
    <div class="mb-4 h-2 w-full overflow-hidden rounded-full bg-gray-200">
        <div 
            class="h-full bg-primary transition-all duration-300"
            style="width: {{ $probability }}%"
        ></div>
    </div>
    
    <!-- CTA -->
    <a 
        href="{{ url(app()->getLocale().'/predicts/'.$predict->slug) }}"
        class="block rounded-lg bg-primary px-4 py-2 text-center font-bold text-white hover:bg-primary-dark"
    >
        Trade Now
    </a>
</div>
```

---

## 🆚 VOLT VS FILAMENT NEL THEME

### Volt Components (Theme)

```
✅ VOLT IN THEME:
- Pages routing (Folio)
- Reusable UI components
- Live search, filters
- Real-time updates

File:
- resources/views/pages/*.blade.php
- resources/views/livewire/*.blade.php

Caratteristiche:
- Single-file (PHP + Blade)
- Type-safe properties
- Lifecycle hooks
- Event listeners
```

### Filament Widgets (Moduli)

```
✅ FILAMENT IN MODULES:
- Table lists
- Stats dashboard
- Charts
- Complex forms

File:
- Modules/*/app/Filament/Widgets/*.php
- resources/views/filament/widgets/*.blade.php (views)

Caratteristiche:
- Class-based
- InteractsWithForms
- TableWidget
- StatsWidget
```

---

## 🔧 VOLT INTEGRATION PATTERNS

### Pattern 1: CMS-Driven Homepage

```php
// resources/views/pages/index.blade.php
<?php

use Livewire\Volt\Component;
use Modules\Cms\Actions\GetHomepageDataAction;

new class extends Component {
    public array $blocks = [];
    
    public function mount(GetHomepageDataAction $action): void
    {
        $this->blocks = $action->execute();
    }
};
?>

@foreach($blocks as $block)
    <x-section :slug="$block['slug']" :data="$block['data']" />
@endforeach
```

**JSON Config**: `config/local/predict/database/content/pages/home.json`

```json
{
  "blocks": [
    {
      "slug": "hero",
      "data": {
        "title": "Prediction Market Platform",
        "cta_primary": "Inizia a Tradare",
        "cta_secondary": "Scopri di Più"
      }
    },
    {
      "slug": "stats",
      "data": {
        "show_real_time": true
      }
    }
  ]
}
```

---

### Pattern 2: Real-Time Updates

```php
// resources/views/livewire/market-stats.blade.php
<?php

use Livewire\Volt\Component;
use Livewire\Attributes\On;

new class extends Component {
    public int $predictId;
    public array $stats = [];
    
    public function mount(int $predictId): void
    {
        $this->predictId = $predictId;
        $this->loadStats();
    }
    
    #[On('trade-placed')]
    public function loadStats(): void
    {
        $predict = Predict::find($this->predictId);
        
        $this->stats = [
            'volume' => $predict->transactions()->sum('amount'),
            'participants' => $predict->transactions()
                ->distinct('user_id')
                ->count(),
        ];
    }
};
?>

<div wire:poll.5s="loadStats">
    <div>Volume: {{ $stats['volume'] }} Credits</div>
    <div>Partecipanti: {{ $stats['participants'] }}</div>
</div>
```

---

### Pattern 3: Live Search with Debounce

```php
// resources/views/pages/[container0]/index.blade.php
<?php

use Livewire\WithPagination;
use Livewire\Volt\Component;

new class extends Component {
    use WithPagination;
    
    public string $search = '';
    
    public function updatingSearch(): void
    {
        $this->resetPage();
    }
    
    public function with(): array
    {
        return [
            'items' => $this->getItems(),
        ];
    }
    
    protected function getItems()
    {
        // Agnostic query (works for any container0)
        return ResolvePageAction::search($this->container0, $this->search)
            ->paginate(12);
    }
};
?>

<input 
    type="text" 
    wire:model.live.debounce.300ms="search"
    placeholder="Cerca..."
/>

@foreach($items as $item)
    <x-item.card :item="$item" />
@endforeach

{{ $items->links() }}
```

---

## ✅ BEST PRACTICES TWENTYONE

### 1. Usa Action Classes

```php
// ✅ GOOD
public function mount(GetHomepageDataAction $action): void
{
    $this->blocks = $action->execute();
}

// ❌ AVOID
public function mount(): void
{
    $this->blocks = json_decode(
        file_get_contents(config_path('local/predict/database/content/pages/home.json'))
    );
}
```

### 2. Type Hint Properties

```php
// ✅ GOOD
public ?string $title = null;
public int $count = 0;
public array $items = [];
public bool $isLoading = false;

// ❌ AVOID
public $title;
public $count;
public $items;
public $isLoading;
```

### 3. Protected Helper Methods

```php
// ✅ GOOD
public function getStats(): array
{
    return [
        'volume' => $this->calculateVolume(),
        'participants' => $this->countParticipants(),
    ];
}

protected function calculateVolume(): int
{
    // Complex calculation
}

protected function countParticipants(): int
{
    // Complex calculation
}

// ❌ AVOID
public function getStats(): array
{
    // 20 righe di logica
}
```

### 4. Use with() for Expensive Queries

```php
// ✅ GOOD
public function with(): array
{
    return [
        'predicts' => Predict::with(['outcomes', 'author'])
            ->latest()
            ->paginate(12),
    ];
}

// ❌ AVOID (query in blade)
<div>
    @foreach(Predict::with(['outcomes', 'author'])->paginate(12) as $predict)
        <!-- ... -->
    @endforeach
</div>
```

### 5. Lifecycle Hooks Corretti

```php
// ✅ GOOD
public function mount(): void
{
    // Initialize
}

public function booted(): void
{
    // Authorization
    abort_unless(auth()->user()->can('trade'), 403);
}

public function updatingSearch(): void
{
    // Reset pagination
    $this->resetPage();
}

// ❌ AVOID
public function mount(): void
{
    // Authorization (wrong hook!)
    abort_unless(auth()->user()->can('trade'), 403);
}
```

---

## 🚨 ERRORI COMUNI NEL THEME

### 1. ❌ Logica di Business nel Theme

```php
// WRONG - In theme Volt component
public function getPredictData(): array
{
    return Predict::with('outcomes')->get()->toArray();
}

// CORRECT - Use Action from Module
public function mount(GetPredictDataAction $action): void
{
    $this->data = $action->execute();
}
```

### 2. ❌ Container Pollution

```php
// WRONG - In [container0]/index.blade.php
if ($container0 === 'predicts') {
    $this->data = Predict::all();
} elseif ($container0 === 'blog') {
    $this->data = Post::all();
}

// CORRECT - Agnostic
public function mount(ResolvePageAction $action): void
{
    $this->data = $action->execute($container0, '');
}
```

### 3. ❌ No Type Hints

```php
// WRONG
public $data;
public $items = [];

// CORRECT
public array $data = [];
public array $items = [];
```

### 4. ❌ Logic in Blade

```blade
{{-- WRONG --}}
@php
    $volume = $predict->transactions()->sum('amount');
    $probability = $predict->current_probability;
@endphp

{{-- CORRECT --}}
// In Volt component
public function with(): array
{
    return [
        'volume' => $this->predict->transactions()->sum('amount'),
        'probability' => $this->predict->current_probability,
    ];
}
```

---

## 📊 VOLT MOUNTED PATHS

### VoltServiceProvider

```php
// app/Providers/VoltServiceProvider.php
public function boot(): void
{
    $xot = XotData::make();
    Volt::mount([
        $xot->getPubThemeViewPath('livewire'),
        $xot->getPubThemeViewPath('pages'),
    ]);
}
```

**Paths**:
1. `Themes/TwentyOne/resources/views/livewire/` - Reusable components
2. `Themes/TwentyOne/resources/views/pages/` - Folio routing

---

## 📚 RIFERIMENTI

### Documentazione
- **Volt Class-Based**: `docs/project/VOLT_CLASS_BASED_COMPONENTS.md`
- **Architecture Zen**: `docs/project/ARCHITECTURE_ZEN.md`
- **Folio Routing**: `docs/project/FOLIO_ROUTING.md`
- **JSON Architecture**: `JSON_ARCHITECTURE.md`

### Esterni
- **Livewire Volt**: https://livewire.laravel.com/docs/volt
- **Folio**: https://github.com/laravel/folio
- **Livewire**: https://livewire.laravel.com/docs

---

**Ultimo Aggiornamento**: 2026-03-20  
**Stato**: ✅ OBBLIGATORIO  
**Enforcement**: Code Review + PHPStan
