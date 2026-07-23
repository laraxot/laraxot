# XotBase Extension Rules - Employee Module

## 🚨 REGOLA ARCHITETTURALE CRITICA

**MAI estendere classi Filament direttamente. SEMPRE estendere classi astratte XotBase.**

## ✅ Pattern di Estensione Corretti (OBBLIGATORI)

### Mapping Completo delle Estensioni
```php
// Resources Pages
Filament\Resources\Pages\CreateRecord → Modules\Xot\Filament\Resources\Pages\XotBaseCreateRecord
Filament\Resources\Pages\EditRecord → Modules\Xot\Filament\Resources\Pages\XotBaseEditRecord  
Filament\Resources\Pages\ListRecords → Modules\Xot\Filament\Resources\Pages\XotBaseListRecords

// Regular Pages  
Filament\Pages\Page → Modules\Xot\Filament\Pages\XotBasePage

// Widgets
Filament\Widgets\Widget → Modules\Xot\Filament\Widgets\XotBaseWidget
Filament\Widgets\StatsOverviewWidget → Modules\Xot\Filament\Widgets\XotBaseStatsOverviewWidget

// Resources
Filament\Resources\Resource → Modules\Xot\Filament\Resources\XotBaseResource
```

## 🚫 PROPRIETÀ PROIBITE PER CLASSI XOTBASE

### Estensioni XotBasePage NON DEVONO Avere:
```php
class WorkHoursPage extends XotBasePage
{
    // ❌ PROIBITO - Queste proprietà sono gestite da XotBase
    // protected static ?string $navigationIcon = 'heroicon-o-clock';
    // protected static ?string $title = 'Timbrature';
    // protected static ?string $navigationLabel = 'Timbrature';
    
    // ✅ CONSENTITO - Solo definizioni widget
    protected function getHeaderWidgets(): array { return [...]; }
    protected function getFooterWidgets(): array { return [...]; }
}
```

### Estensioni XotBaseResource NON DEVONO Avere:
```php
class EmployeeResource extends XotBaseResource
{
    // ❌ PROIBITO - Metodo getTableColumns
    // public static function getTableColumns(): array { return [...]; }
    
    // ✅ CONSENTITO - Altri metodi resource
    public static function table(Table $table): Table { return $table->columns([...]); }
}
```

## 🎯 Esempio Corretto: WorkHoursPage

```php
<?php

declare(strict_types=1);

namespace Modules\Employee\Filament\Pages;

use Modules\Employee\Filament\Widgets\TimeClockWidget;
use Modules\Employee\Filament\Widgets\WorkHoursBoardWidget;
use Modules\Xot\Filament\Pages\XotBasePage;

class WorkHoursPage extends XotBasePage
{
    protected static string $view = 'employee::filament.pages.work-hours';

    protected function getHeaderWidgets(): array
    {
        return [TimeClockWidget::class];
    }

    protected function getFooterWidgets(): array
    {
        return [WorkHoursBoardWidget::class];
    }
}
```

---

**RICORDA: MAI ESTENDERE FILAMENT DIRETTAMENTE. SEMPRE USARE CLASSI ASTRATTE XOTBASE.**
