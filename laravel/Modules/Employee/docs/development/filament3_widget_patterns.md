# Filament 3 Widget Patterns - Employee Module

## Studio Filament 3 per Widget Custom

### Componenti Filament Nativi da Usare

#### ✅ Componenti Obbligatori
```blade
{{-- Pulsanti --}}
<x-filament::button wire:click="action">Testo</x-filament::button>

{{-- Icone --}}
<x-filament::icon name="heroicon-m-clock" class="w-5 h-5" />

{{-- Avatar --}}
<x-filament::avatar :src="$user->avatar" />

{{-- Dropdown --}}
<x-filament::dropdown>
    {{-- Contenuto dropdown --}}
</x-filament::dropdown>
```

#### ❌ Da Evitare
```blade
{{-- Non usare button HTML standard --}}
<button>...</button>

{{-- Non usare componenti custom non necessari --}}
<x-custom-button>...</x-custom-button>
```

### Pattern Widget Custom

#### Struttura Base
```php
<?php

declare(strict_types=1);

namespace Modules\Employee\Filament\Widgets;

use Modules\Xot\Filament\Widgets\XotBaseWidget;

class MyCustomWidget extends XotBaseWidget
{
    protected static string $view = 'employee::filament.widgets.my-custom-widget';
    
    // Proprietà pubbliche per la vista
    public string $currentTime = '';
    public array $data = [];
    
    public function mount(): void
    {
        $this->updateData();
    }
    
    public function getFormSchema(): array
    {
        return []; // Array vuoto se non serve form
    }
    
    private function updateData(): void
    {
        // Logica di aggiornamento dati
    }
}
```

#### Vista Blade Pattern
```blade
<x-filament-widgets::widget>
    <div class="grid grid-cols-3 gap-4 items-center h-20">
        {{-- Colonna 1 --}}
        <div class="text-center">
            <!-- Contenuto colonna 1 -->
        </div>
        
        {{-- Colonna 2 --}}
        <div class="text-center">
            <!-- Contenuto colonna 2 -->
        </div>
        
        {{-- Colonna 3 --}}
        <div class="text-center">
            <x-filament::button wire:click="myAction" color="primary">
                Azione
            </x-filament::button>
        </div>
    </div>
</x-filament-widgets::widget>
```

## Specifiche TimeTrackingWidget V2

### Layout Richiesto dall'Immagine

#### Struttura 3 Colonne in Una Riga
```
[   ORA   ] [  TIMBRATURE  ] [  PULSANTE  ]
[  DATA   ] [   SESSIONE   ] [   AZIONE   ]
```

#### Colonna Sinistra - Ora e Data
- Ora corrente: `09:21` (font mono, grande)
- Data: `lunedì 1 settembre 2025` (formato italiano)
- Aggiornamento real-time

#### Colonna Centro - Timbrature
- Stato: "Sessione attiva" 
- Lista: "● 08:02" (timbrature cronologiche)
- Logica reale dal database

#### Colonna Destra - Pulsante Filament
- `<x-filament::button>` nativo
- Colore dinamico (success/danger)
- Testo: "Timbra entrata" / "Timbra uscita"

### Implementazione Corretta

#### Widget Class
```php
class TimeClockWidget extends XotBaseWidget
{
    protected static string $view = 'employee::filament.widgets.time-clock-widget';
    
    public string $currentTime = '';
    public string $todayDate = '';
    public array $todayEntries = [];
    public bool $isClockedIn = false;
    
    public function mount(): void
    {
        $this->updateData();
    }
    
    public function getFormSchema(): array
    {
        return []; // Widget senza form
    }
    
    #[LivewireMethod]
    public function clockIn(): void
    {
        // Logica clock-in reale
    }
    
    #[LivewireMethod] 
    public function clockOut(): void
    {
        // Logica clock-out reale
    }
    
    public function updateData(): void
    {
        $this->currentTime = Carbon::now()->format('H:i');
        $this->todayDate = Carbon::now()->locale('it')->isoFormat('dddd D MMMM YYYY');
        
        // Query timbrature reali
        $user = Auth::user();
        $employee = Employee::where('user_id', $user->id)->first();
        
        if ($employee) {
            $entries = WorkHour::where('employee_id', $employee->id)
                ->whereDate('timestamp', today())
                ->orderBy('timestamp', 'asc')
                ->get();
                
            $this->todayEntries = $entries->map(fn($entry) => [
                'time' => $entry->timestamp->format('H:i'),
                'type' => $entry->type
            ])->toArray();
            
            $lastEntry = $entries->last();
            $this->isClockedIn = $lastEntry && $lastEntry->type === 'clock_in';
        }
    }
}
```

#### Vista Blade Corretta
```blade
<x-filament-widgets::widget>
    <div class="grid grid-cols-3 gap-6 items-center h-20" wire:poll.1s="updateData">
        {{-- SINISTRA: Ora e Data --}}
        <div class="text-center">
            <div class="text-3xl font-mono font-bold text-gray-900 dark:text-gray-100">
                {{ $currentTime }}
            </div>
            <div class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                {{ $todayDate }}
            </div>
        </div>
        
        {{-- CENTRO: Timbrature --}}
        <div class="text-center">
            @if($isClockedIn)
                <div class="text-sm font-medium text-green-600 mb-2">
                    Sessione attiva
                </div>
            @endif
            
            <div class="space-y-1">
                @foreach($todayEntries as $entry)
                    <div class="text-sm text-gray-700 dark:text-gray-300">
                        <span class="inline-block w-2 h-2 {{ $entry['type'] === 'clock_in' ? 'bg-green-500' : 'bg-red-500' }} rounded-full mr-2"></span>
                        {{ $entry['time'] }}
                    </div>
                @endforeach
            </div>
        </div>
        
        {{-- DESTRA: Pulsante Filament --}}
        <div class="text-center">
            @if($isClockedIn)
                <x-filament::button 
                    wire:click="clockOut" 
                    color="danger"
                    size="lg">
                    🔴 Timbra uscita
                </x-filament::button>
            @else
                <x-filament::button 
                    wire:click="clockIn" 
                    color="success"
                    size="lg">
                    🟢 Timbra entrata
                </x-filament::button>
            @endif
        </div>
    </div>
</x-filament-widgets::widget>
```

## Best Practices Filament 3

### 1. Widget Wrapper
- SEMPRE usare `<x-filament-widgets::widget>`
- Mai wrapper custom per widget

### 2. Componenti Nativi
- SEMPRE `<x-filament::button>` per pulsanti
- SEMPRE `<x-filament::icon>` per icone
- Mai componenti HTML standard

### 3. Colori Filament
- `color="success"` per azioni positive
- `color="danger"` per azioni di stop
- `color="warning"` per azioni di attenzione

### 4. Polling Real-time
- `wire:poll.1s` per aggiornamenti frequenti
- `wire:poll.30s` per aggiornamenti meno frequenti
- Usare sui container principali

### 5. Responsive Grid
```blade
{{-- Desktop: 3 colonne --}}
<div class="grid grid-cols-3 gap-6">

{{-- Mobile: 1 colonna --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
```

## Correzioni da Implementare

### 1. Nuovo Widget: TimeClockWidget
- Nome più specifico
- Layout una riga con 3 colonne
- Componenti Filament nativi
- Altezza fissa (`h-20`)

### 2. Pulsanti Filament
- Sostituire `<button>` con `<x-filament::button>`
- Usare attributi Filament (`color`, `size`)
- Emoji nel testo per visual feedback

### 3. Polling Ottimizzato
- Un solo `wire:poll.1s` sul container principale
- Metodo unico `updateData()` per tutto

### 4. Query Performance
- Spostare query PHP dal Blade al widget
- Caching se necessario
- Eager loading per relazioni

## Documentazione da Aggiornare

### File da Modificare
1. **Widget documentation** - Pattern Filament 3
2. **Component usage** - Regole componenti nativi
3. **Performance guide** - Polling best practices
4. **Layout standards** - Grid e responsive

### Regole da Aggiornare
1. **.cursor/rules** - Componenti Filament obbligatori
2. **.cursor/memories** - Pattern widget corretti
3. **project_docs** - Standard implementazione

---

**Studio completato**: Filament 3 patterns  
**Prossimo step**: Creare TimeClockWidget corretto  
**Focus**: Layout 1 riga, 3 colonne, componenti nativi

## Collegamenti

- [Filament Components Documentation](https://filamentphp.com/docs/3.x/support/blade-components)
- [Widget Documentation](https://filamentphp.com/docs/3.x/widgets/custom)
- [UI Components Usage](.cursor/rules/ui-components-usage.mdc)

*Ultimo aggiornamento: Gennaio 2025*