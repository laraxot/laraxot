# Employee Module Dashboard Widgets

## Overview

The Employee module provides a comprehensive dashboard with interactive widgets for time tracking, attendance management, and employee oversight. All widgets follow Laraxot conventions and extend XotBase classes.

## Widget Architecture from Image Analysis

Based on the provided interface, the Employee dashboard should include these key widgets:

1. **TimeClock Widget** - Current time display with active session tracking
2. **LeaveBalance Widget** - Monthly leave balance (Ferie, ROL, Permessi, etc.)
3. **AttendanceOverview Widget** - Next 7 days attendance overview
4. **PendingRequests Widget** - Pending approval requests display
5. **TeamPresence Widget** - Current team presence status

## Widget Esistenti

### 1. WorkHourStatsWidget

**Posizione**: `Modules\Employee\Filament\Resources\WorkHourResource\Widgets\WorkHourStatsWidget`
**Estende**: `Modules\Xot\Filament\Widgets\XotBaseStatsOverviewWidget`

#### Funzionalità
- Statistiche giornaliere delle timbrature
- Conteggio settimanale delle presenze
- Monitoraggio clock-in/clock-out
- Tracking approvazioni pendenti

#### Metriche Visualizzate
1. **Today's Entries** - Timbrature totali di oggi
2. **This Week** - Timbrature totali della settimana
3. **Clock In/Out** - Rapporto entrate/uscite giornaliere
4. **Pending Approval** - Timbrature in attesa di approvazione

#### Caratteristiche Tecniche
- Utilizzo di Carbon per gestione date
- Query ottimizzate con filtri temporali
- Colori dinamici basati sui valori (warning se approvazioni pendenti)
- Icone Heroicon per descrizioni

### 2. TimeTrackingWidget ✨ NUOVO

**Posizione**: `Modules\Employee\Filament\Widgets\TimeTrackingWidget`
**Estende**: `Modules\Xot\Filament\Widgets\XotBaseWidget`

#### Funzionalità
- Visualizzazione ora corrente in tempo reale
- Stato sessione lavorativa (attiva, completata, non iniziata)
- Clock-in/Clock-out rapido con un click
- Gestione pause lavorative
- Timer durata sessione corrente
- Statistiche giornaliere rapide

#### Caratteristiche Tecniche
- **Polling automatico**: Aggiornamento ogni 30 secondi
- **Real-time clock**: Ora aggiornata ogni secondo
- **State management**: Tracking stato sessione intelligente
- **Interactive buttons**: Azioni immediate clock-in/out
- **Notifications**: Feedback utente per ogni azione
- **Responsive design**: Ottimizzato per mobile e desktop

#### Interfaccia Utente
- **Header**: Titolo con icona e ora corrente
- **Status panel**: Indicatore visivo stato sessione
- **Action buttons**: Pulsanti colorati per azioni
- **Quick stats**: Statistiche giornaliere in footer

### 3. EmployeeOverviewWidget ✨ NUOVO

**Posizione**: `Modules\Employee\Filament\Widgets\EmployeeOverviewWidget`
**Estende**: `Modules\Xot\Filament\Widgets\XotBaseStatsOverviewWidget`

#### Funzionalità
- Panoramica generale dei dipendenti
- Statistiche di presenza giornaliera
- Monitoraggio stato dipendenti
- Tracking nuove assunzioni

#### Metriche Visualizzate
1. **Total Employees** - Totale dipendenti registrati (con trend chart)
2. **Active Today** - Dipendenti attivi oggi (con activity chart)
3. **On Leave** - Dipendenti attualmente in ferie
4. **New This Month** - Nuove assunzioni del mese (con hiring chart)

#### Caratteristiche Tecniche
- **Caching**: Cache di 5 minuti per performance ottimizzate
- **Chart Integration**: Grafici trend per ogni statistica
- **Smart Colors**: Colori dinamici basati sui valori (gray se zero)
- **Performance**: Query ottimizzate con distinct per conteggi unici
- **Accessibility**: Icone Heroicon con descrizioni semantiche

## Struttura Widget Standard

### Estensione Base Obbligatoria
```php
use Modules\Xot\Filament\Widgets\XotBaseStatsOverviewWidget;

class MyWidget extends XotBaseStatsOverviewWidget
{
    // Implementazione
}
```

### Pattern di Implementazione
```php
protected function getStats(): array
{
    return [
        Stat::make('Label', $value)
            ->description('Descrizione statistica')
            ->descriptionIcon('heroicon-m-icon-name')
            ->color('primary|success|warning|danger|info'),
    ];
}
```

## Tipi di Widget Disponibili

### 1. StatsOverviewWidget
**Uso**: Statistiche numeriche con descrizioni
**Base**: `XotBaseStatsOverviewWidget`
**Esempio**: `WorkHourStatsWidget`

### 2. ChartWidget
**Uso**: Grafici e visualizzazioni dati
**Base**: `XotBaseChartWidget`
**Esempio**: Grafici trend presenze

### 3. TableWidget
**Uso**: Tabelle di dati recenti
**Base**: `XotBaseTableWidget`
**Esempio**: Ultime timbrature

### 4. CalendarWidget
**Uso**: Visualizzazione calendario eventi
**Base**: `XotBaseCalendarWidget`
**Esempio**: Calendario presenze

## Registrazione Widget nel Dashboard

### Implementazione Attuale nel Dashboard
Il file `Modules\Employee\Filament\Pages\Dashboard` è stato aggiornato per includere i widget:

```php
/**
 * @return array<class-string<\Filament\Widgets\Widget>>
 */
protected function getHeaderWidgets(): array
{
    return [
        \Modules\Employee\Filament\Widgets\EmployeeOverviewWidget::class,
        \Modules\Employee\Filament\Resources\WorkHourResource\Widgets\WorkHourStatsWidget::class,
    ];
}
```

### Ordine di Visualizzazione
1. **EmployeeOverviewWidget** - Statistiche generali (sort: 1)
2. **WorkHourStatsWidget** - Statistiche timbrature (sort: default)

### Metodo getHeaderWidgets() (Template)
```php
/**
 * @return array<class-string<\Filament\Widgets\Widget>>
 */
protected function getHeaderWidgets(): array
{
    return [
        \Modules\Employee\Filament\Resources\WorkHourResource\Widgets\WorkHourStatsWidget::class,
        // Altri widget header...
    ];
}
```

### Metodo getFooterWidgets()
```php
/**
 * @return array<class-string<\Filament\Widgets\Widget>>
 */
protected function getFooterWidgets(): array
{
    return [
        \Modules\Employee\Filament\Widgets\EmployeeChartWidget::class,
        // Altri widget footer...
    ];
}
```

## Linee Guida per Nuovi Widget

### 1. Naming Convention
- **Stats Widget**: `{Entity}StatsWidget`
- **Chart Widget**: `{Entity}ChartWidget`
- **Table Widget**: `{Entity}TableWidget`
- **Calendar Widget**: `{Entity}CalendarWidget`

### 2. Namespace Standard
```php
namespace Modules\Employee\Filament\Widgets;
// oppure per widget specifici di risorsa:
namespace Modules\Employee\Filament\Resources\{Resource}\Widgets;
```

### 3. Documentazione PHPDoc
```php
/**
 * Widget per statistiche dipendenti.
 * 
 * Fornisce una panoramica delle metriche chiave
 * relative alla gestione dei dipendenti.
 */
class EmployeeStatsWidget extends XotBaseStatsOverviewWidget
{
    /**
     * Restituisce le statistiche da visualizzare.
     *
     * @return array<\Filament\Widgets\StatsOverviewWidget\Stat>
     */
    protected function getStats(): array
    {
        // Implementazione
    }
}
```

### 4. Performance e Caching
```php
// Utilizzo di caching per query costose
protected function getStats(): array
{
    return cache()->remember('employee.stats.overview', 300, function () {
        // Query pesanti qui
        return [
            // Stats array
        ];
    });
}
```

## Widget Dashboard HR - Analisi Implementazione 2025-09-01

Basato sull'immagine fornita dall'utente, la dashboard Employee richiede 5 widget principali:

### 1. TodoWidget ("COSE DA FARE") 📋 **DA IMPLEMENTARE**
**Scopo**: Gestione task e azioni da completare
**Tipo**: Card widget con lista azioni
**Funzionalità**: 
- "Una busta paga da leggere" (come mostrato nell'immagine)
- Task HR pendenti
- Azioni amministrative
- Link di navigazione per completare task

**Design**: Card blu con icona documento e freccia di navigazione

### 2. UpcomingScheduleWidget ("PROSSIMI 7 GIORNI") 📅 **DA IMPLEMENTARE**
**Scopo**: Timeline eventi e presenze future
**Tipo**: Timeline widget con filtri
**Funzionalità**: 
- Dropdown "SVILUPPO" per selezione team/reparto
- Tab: "Assenze", "Smart Working", "Trasferte"
- Lista dipendenti con orari (es. "Filippo Beltrame - Assenza dalle 14:00 alle 18:00")
- "Vedi presenze" link per dettagli completi
- Navigazione "Per vedere i giustificativi oltre il 07 settembre vai nella pagina Presenze"

**Design**: Card con header filtri e timeline eventi

### 3. TimeOffBalanceWidget ("LE MIE RIMANENZE DI SETTEMBRE") ⏰ **DA IMPLEMENTARE**
**Scopo**: Saldo ferie, permessi e ore accumulate
**Tipo**: Stats widget con progress bars
**Funzionalità**: 
- Toggle "Mensili" / "Annuali"
- Categorie: Ferie (8h 53m), ROL (0), Perm. ex-fs (-2h 32m), Banca ore (0), Permessi (0)
- Progress bars colorate per ogni categoria
- Visualizzazione ore positive/negative

**Design**: Card con toggle e progress bars colorate

### 4. TodayPresenceWidget ("CHI C'È OGGI") 👥 **DA IMPLEMENTARE**
**Scopo**: Presenze giornaliere in tempo reale  
**Tipo**: Card widget con contatori e avatar
**Funzionalità**: 
- Dropdown "SVILUPPO" per selezione team
- Contatori: "13 presenti" (verde), "2 assenti" (rosso)
- Avatar/foto dipendenti presenti
- "Vedi dettaglio" link per vista completa

**Design**: Card con contatori colorati e griglia avatar

### 5. PendingRequestsWidget ("LE MIE RICHIESTE IN ATTESA") 📋 **DA IMPLEMENTARE**
**Scopo**: Stato richieste dipendente
**Tipo**: Status widget con illustrazione
**Funzionalità**: 
- Messaggio stato: "Tutte le tue richieste sono state gestite dall'amministratore"
- Illustrazione personaggio con braccia alzate
- Messaggio motivazionale: "Non devi preoccuparti di nulla."
- Tracking richieste ferie, permessi, rimborsi

**Design**: Card con illustrazione centrale e messaggio di stato

## Widget Legacy (Esistenti)

### EmployeeOverviewWidget ✅ ESISTENTE
**Scopo**: Panoramica generale dipendenti
**Metriche**: Totale dipendenti, attivi, in ferie, nuovi assunti

### WorkHourStatsWidget ✅ ESISTENTE
**Scopo**: Statistiche timbrature
**Metriche**: Timbrature giornaliere, settimanali, approvazioni pendenti

## Implementazione Completa Dashboard HR ✅

### Widget Implementati (2025-01-06)

#### 1. TodoWidget ✅ NUOVO
**File**: `Modules\Employee\Filament\Widgets\TodoWidget`
**Vista**: `employee::filament.widgets.todo-widget`
**Funzionalità**: 
- Lista task HR da completare
- Priorità con colori (alta, media, bassa)
- Icone Heroicon per ogni tipo di task
- Link diretti alle azioni

#### 2. UpcomingScheduleWidget ✅ NUOVO
**File**: `Modules\Employee\Filament\Widgets\UpcomingScheduleWidget`
**Vista**: `employee::filament.widgets.upcoming-schedule-widget`
**Funzionalità**:
- Timeline eventi prossimi 7 giorni
- Filtri per tipo (Assenze, Smart Working, Trasferte)
- Avatar dipendenti con iniziali colorate
- Status approvazione con badge

#### 3. TimeOffBalanceWidget ✅ NUOVO
**File**: `Modules\Employee\Filament\Widgets\TimeOffBalanceWidget`
**Vista**: `employee::filament.widgets.time-off-balance-widget`
**Funzionalità**:
- Saldi ferie, ROL, permessi, banca ore
- Barre di progresso colorate
- Visualizzazione mensile/annuale
- Saldi negativi evidenziati in rosso

#### 4. TodayPresenceWidget ✅ NUOVO
**File**: `Modules\Employee\Filament\Widgets\TodayPresenceWidget`
**Vista**: `employee::filament.widgets.today-presence-widget`
**Funzionalità**:
- Conteggio presenti/assenti in tempo reale
- Avatar dipendenti con iniziali
- Lista dettagliata con orari e location
- Motivi assenza e date di rientro

#### 5. PendingRequestsWidget ✅ NUOVO
**File**: `Modules\Employee\Filament\Widgets\PendingRequestsWidget`
**Vista**: `employee::filament.widgets.pending-requests-widget`
**Funzionalità**:
- Stato richieste dipendente
- Illustrazione SVG animata per stato vuoto
- Lista richieste con icone tipo-specifiche
- Messaggi informativi personalizzati

### Traduzioni Complete ✅
- **Italiano**: `Modules/Employee/lang/it/widgets.php`
- **Inglese**: `Modules/Employee/lang/en/widgets.php`
- **Tedesco**: `Modules/Employee/lang/de/widgets.php`

### Registrazione Dashboard ✅
Tutti i widget sono registrati in `Modules\Employee\Filament\Pages\Dashboard::getHeaderWidgets()`

## Best Practices

### 1. Performance
- Utilizzare caching per query costose
- Limitare il numero di record processati
- Implementare lazy loading quando possibile
- Ottimizzare query con eager loading

### 2. UX/UI
- Utilizzare colori semantici (success, warning, danger)
- Fornire descrizioni chiare e concise
- Implementare stati di loading
- Gestire stati vuoti gracefully

### 3. Accessibilità
- Fornire testi alternativi per icone
- Utilizzare contrasti appropriati
- Supportare navigazione da tastiera
- Implementare ARIA labels quando necessario

### 4. Sicurezza
- Verificare permessi utente per dati sensibili
- Filtrare dati in base al ruolo utente
- Non esporre informazioni riservate
- Implementare rate limiting se necessario

## Esempi di Codice

### Widget Stats Completo
```php
<?php

declare(strict_types=1);

namespace Modules\Employee\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget\Stat;
use Modules\Employee\Models\Employee;
use Modules\Xot\Filament\Widgets\XotBaseStatsOverviewWidget;

/**
 * Widget panoramica dipendenti.
 */
class EmployeeOverviewWidget extends XotBaseStatsOverviewWidget
{
    /**
     * @return array<\Filament\Widgets\StatsOverviewWidget\Stat>
     */
    protected function getStats(): array
    {
        return [
            Stat::make('Total Employees', Employee::count())
                ->description('All registered employees')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'),

            Stat::make('Active Today', Employee::whereHas('workHours', function ($query) {
                    $query->whereDate('timestamp', today());
                })->count())
                ->description('Employees active today')
                ->descriptionIcon('heroicon-m-clock')
                ->color('success'),

            Stat::make('On Leave', Employee::where('status', 'on_leave')->count())
                ->description('Employees currently on leave')
                ->descriptionIcon('heroicon-m-calendar-x')
                ->color('warning'),

            Stat::make('New This Month', Employee::whereMonth('created_at', now()->month)->count())
                ->description('New employees this month')
                ->descriptionIcon('heroicon-m-user-plus')
                ->color('info'),
        ];
    }
}
```

## Testing Widget

### Test di Base
```php
use Livewire\Livewire;
use Modules\Employee\Filament\Widgets\EmployeeOverviewWidget;

it('displays employee statistics correctly', function () {
    // Arrange
    Employee::factory()->count(5)->create();
    
    // Act & Assert
    Livewire::test(EmployeeOverviewWidget::class)
        ->assertSee('Total Employees')
        ->assertSee('5');
});
```

## Collegamenti

- [WorkHour Implementation](./workhour_implementation.md)
- [Model Architecture](./model_architecture.md)
- [Technical Implementation](./technical_implementation.md)
- [XotBase Extension Rules](./xotbase_extension_rules.md)

---

**Creato**: 2025-01-06
**Aggiornato**: 2025-01-06
**Responsabile**: Assistant AI
