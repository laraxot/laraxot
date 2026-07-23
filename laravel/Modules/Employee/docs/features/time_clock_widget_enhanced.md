# Enhanced TimeClockWidget - Badge-Based Time Entries Visualization

## Panoramica

Il TimeClockWidget è stato migliorato per utilizzare i componenti badge di Filament per visualizzare le timbrature in modo più moderno e user-friendly. Le migliorie includono:

- Utilizzo di badge colorati per differenziare entrata/uscita
- Buttons con badge per contatori di sessioni
- Miglior contrasto e leggibilità
- Design più moderno e professionale

## Analisi dell'Implementazione Attuale

### Stato Corrente
L'implementazione attuale utilizza:
```blade
<span class="inline-block w-2 h-2 {{ $entry['type'] === 'clock_in' ? 'bg-green-500' : 'bg-red-500' }} rounded-full mr-2"></span>
{{ $entry['time'] }}
```

### Limitazioni Identificate
1. **Visibilità limitata**: Piccoli pallini colorati poco visibili
2. **Informazioni insufficienti**: Solo ora senza tipo di azione
3. **Mancanza di contesto**: Non mostra durata o stato della sessione
4. **Design inconsistente**: Non utilizza componenti Filament standard
5. **Accessibilità**: Colori potrebbero non essere sufficienti per daltonici

## Design Migliorato con Badge

### Approccio 1: Badge Standalone per Ogni Timbratura

#### Entrate (Clock In)
```blade
<x-filament::badge
    color="success"
    icon="heroicon-m-arrow-right-on-rectangle"
    size="sm"
>
    Entrata {{ $entry['time'] }}
</x-filament::badge>
```

#### Uscite (Clock Out)
```blade
<x-filament::badge
    color="danger"
    icon="heroicon-m-arrow-left-on-rectangle"
    size="sm"
>
    Uscita {{ $entry['time'] }}
</x-filament::badge>
```

### Approccio 2: Buttons con Badge per Sessioni

#### Pulsante Sessione Attiva
```blade
<x-filament::button
    color="success"
    size="sm"
    badge-color="success"
    icon="heroicon-m-clock"
>
    Sessione in corso
    
    <x-slot name="badge">
        {{ $sessionDuration }}
    </x-slot>
</x-filament::button>
```

#### Pulsante Sessioni Completate
```blade
<x-filament::button
    color="gray"
    size="sm"
    badge-color="info"
    icon="heroicon-m-check-circle"
>
    Sessioni completate
    
    <x-slot name="badge">
        {{ $completedSessions }}
    </x-slot>
</x-filament::button>
```

## Schema Colori Semantico

### Badge per Tipologie
- **Verde (success)**: Entrate, sessioni attive, operazioni positive
- **Rosso (danger)**: Uscite, fine sessione, attenzione
- **Blu (info)**: Informazioni generali, statistiche
- **Grigio (gray)**: Stati neutri, completati
- **Giallo (warning)**: Avvisi, pause, situazioni da verificare

### Icone Significative
- **🏠 heroicon-m-arrow-right-on-rectangle**: Entrata
- **🚪 heroicon-m-arrow-left-on-rectangle**: Uscita
- **⏰ heroicon-m-clock**: Tempo, durata
- **✅ heroicon-m-check-circle**: Completato
- **⚠️ heroicon-m-exclamation-triangle**: Avviso

## Layout Migliorato

### Struttura Colonna Centro (Badge)
```blade
<div class="text-center">
    {{-- Stato Sessione con Badge --}}
    @if($isClockedIn)
        <x-filament::badge
            color="success"
            icon="heroicon-m-clock"
            size="lg"
            class="mb-3"
        >
            Sessione attiva da {{ $sessionStartTime }}
        </x-filament::badge>
    @else
        <x-filament::badge
            color="gray"
            icon="heroicon-m-moon"
            size="lg"
            class="mb-3"
        >
            Nessuna sessione attiva
        </x-filament::badge>
    @endif
    
    {{-- Elenco Timbrature con Badge --}}
    <div class="space-y-2">
        @forelse($todayEntries as $entry)
            <div class="flex justify-center">
                <x-filament::badge
                    color="{{ $entry['type'] === 'clock_in' ? 'success' : 'danger' }}"
                    icon="{{ $entry['type'] === 'clock_in' ? 'heroicon-m-arrow-right-on-rectangle' : 'heroicon-m-arrow-left-on-rectangle' }}"
                    size="sm"
                >
                    {{ $entry['type'] === 'clock_in' ? 'Entrata' : 'Uscita' }} {{ $entry['time'] }}
                </x-filament::badge>
            </div>
        @empty
            <x-filament::badge
                color="gray"
                icon="heroicon-m-calendar"
                size="sm"
            >
                Nessuna timbratura per oggi
            </x-filament::badge>
        @endforelse
    </div>
</div>
```

### Struttura Colonna Destra (Buttons con Badge)
```blade
<div class="text-center space-y-2">
    {{-- Pulsante Azione Principale --}}
    @if($isClockedIn)
        <x-filament::button 
            wire:click="clockOut" 
            color="danger"
            size="lg"
            icon="heroicon-o-arrow-left-on-rectangle"
            badge-color="warning"
        >
            Timbra uscita
            
            <x-slot name="badge">
                {{ $workingHours }}h
            </x-slot>
        </x-filament::button>
    @else
        <x-filament::button 
            wire:click="clockIn" 
            color="success"
            size="lg"
            icon="heroicon-o-arrow-right-on-rectangle"
        >
            Timbra entrata
        </x-filament::button>
    @endif
    
    {{-- Statistiche con Buttons Badge --}}
    @if(!empty($todayEntries))
        <div class="grid grid-cols-2 gap-2">
            <x-filament::button
                color="info"
                size="xs"
                badge-color="info"
                icon="heroicon-m-clock"
            >
                Ore lavorate
                
                <x-slot name="badge">
                    {{ $totalWorkingTime }}
                </x-slot>
            </x-filament::button>
            
            <x-filament::button
                color="gray"
                size="xs"
                badge-color="gray"
                icon="heroicon-m-list-bullet"
            >
                Timbrature
                
                <x-slot name="badge">
                    {{ count($todayEntries) }}
                </x-slot>
            </x-filament::button>
        </div>
    @endif
</div>
```

## Nuove Proprietà Widget

### Proprietà Aggiuntive
```php
/**
 * Ora di inizio sessione corrente.
 */
public ?string $sessionStartTime = null;

/**
 * Durata sessione corrente in minuti.
 */
public int $sessionDuration = 0;

/**
 * Ore lavorate totali oggi.
 */
public string $totalWorkingTime = '0:00';

/**
 * Numero di sessioni completate.
 */
public int $completedSessions = 0;
```

### Metodi di Calcolo
```php
/**
 * Calcola la durata della sessione corrente.
 */
private function calculateSessionDuration(): void
{
    if (!$this->isClockedIn || empty($this->todayEntries)) {
        $this->sessionDuration = 0;
        return;
    }
    
    $lastClockIn = collect($this->todayEntries)
        ->where('type', 'clock_in')
        ->last();
    
    if ($lastClockIn) {
        $start = Carbon::createFromFormat('H:i', $lastClockIn['time']);
        $this->sessionDuration = Carbon::now()->diffInMinutes($start);
        $this->sessionStartTime = $lastClockIn['time'];
    }
}

/**
 * Calcola il tempo totale lavorato oggi.
 */
private function calculateTotalWorkingTime(): void
{
    $sessions = $this->buildCompletedSessions();
    $totalMinutes = collect($sessions)->sum(function ($session) {
        if ($session['in'] && $session['out']) {
            $start = Carbon::createFromFormat('H:i', $session['in']);
            $end = Carbon::createFromFormat('H:i', $session['out']);
            return $end->diffInMinutes($start);
        }
        return 0;
    });
    
    // Aggiungi sessione corrente se attiva
    if ($this->isClockedIn) {
        $totalMinutes += $this->sessionDuration;
    }
    
    $hours = floor($totalMinutes / 60);
    $minutes = $totalMinutes % 60;
    $this->totalWorkingTime = sprintf('%d:%02d', $hours, $minutes);
}
```

## Accessibilità e UX

### Miglioramenti Accessibilità
1. **Testi descrittivi**: Badge con testo esplicativo invece di solo colori
2. **Icone significative**: Supporto visivo aggiuntivo ai colori
3. **Contrasti**: Utilizzo dei colori standard Filament ottimizzati
4. **Screen readers**: Attributi `aria-label` dove necessario

### Responsive Design
```blade
{{-- Mobile: Stack verticale --}}
<div class="flex flex-col space-y-2 md:hidden">
    @foreach($todayEntries as $entry)
        <x-filament::badge
            color="{{ $entry['type'] === 'clock_in' ? 'success' : 'danger' }}"
            icon="heroicon-m-{{ $entry['type'] === 'clock_in' ? 'arrow-right' : 'arrow-left' }}-on-rectangle"
            class="justify-center"
        >
            {{ $entry['type'] === 'clock_in' ? 'Entrata' : 'Uscita' }} {{ $entry['time'] }}
        </x-filament::badge>
    @endforeach
</div>

{{-- Desktop: Layout compatto --}}
<div class="hidden md:flex md:flex-wrap md:gap-1 md:justify-center">
    @foreach($todayEntries as $entry)
        <x-filament::badge
            color="{{ $entry['type'] === 'clock_in' ? 'success' : 'danger' }}"
            size="xs"
        >
            {{ substr($entry['time'], 0, 5) }}
        </x-filament::badge>
    @endforeach
</div>
```

## Performance e Ottimizzazioni

### Lazy Loading Badge
```php
/**
 * Badge data caricati solo quando necessari.
 */
public function getBadgeDataProperty(): array
{
    return Cache::remember(
        "widget_badge_data_{$this->employee_id}_" . today()->format('Y-m-d'),
        300, // 5 minuti
        fn () => $this->calculateBadgeMetrics()
    );
}
```

### Polling Intelligente
```blade
{{-- Poll diversi per diversi elementi --}}
<div wire:poll.1s="updateCurrentTime">
    {{ $currentTime }}
</div>

<div wire:poll.30s="updateSessionData">
    {{-- Badge sessioni --}}
</div>

<div wire:poll.5m="updateDailyStats">
    {{-- Statistiche giornaliere --}}
</div>
```

## Testing Strategy

### Test Cases Badge
```php
it('displays correct badge colors for entry types', function () {
    // Test badge colors
    $this->assertEquals('success', $widget->getEntryBadgeColor('clock_in'));
    $this->assertEquals('danger', $widget->getEntryBadgeColor('clock_out'));
});

it('shows working hours in button badge', function () {
    // Test button badge content
    $widget->clockIn();
    Carbon::setTestNow(Carbon::now()->addHours(4));
    
    $this->assertStringContains('4h', $widget->getTotalWorkingTime());
});
```

## Migration Path

### Fase 1: Implementazione Badge Base
1. Sostituire pallini colorati con badge semplici
2. Mantenere compatibilità esistente

### Fase 2: Button Enhancement
1. Aggiungere badge ai pulsanti di azione
2. Implementare contatori e statistiche

### Fase 3: Advanced Features
1. Animazioni e transizioni
2. Dark mode support
3. Personalizzazione colori

---

**Status**: Pianificazione completata
**Priorità**: Alta - Miglioramento UX significativo
**Impatto**: Miglior visualizzazione e accessibilità

## Collegamenti

- [Badge Component Documentation](https://filamentphp.com/docs/3.x/support/blade-components/badge)
- [Button Badge Documentation](https://filamentphp.com/docs/3.x/support/blade-components/button#adding-a-badge-to-a-button)
- [TimeClockWidget Original](./time_tracking_widget.md)
- [Color System Guidelines](../implementation/color_system.md)

*Documento creato: Gennaio 2025*