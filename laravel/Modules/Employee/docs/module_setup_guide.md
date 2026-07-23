# Guida Configurazione Modulo Employee

## Panoramica
Il modulo Employee gestisce la gestione dei dipendenti nel sistema TechPlanner. Questo modulo segue le convenzioni Laravel Modules e integra Filament per l'amministrazione.

## Struttura Modulo

### Architettura
- **Frontoffice**: Folio + Volt + Filament (componenti)
- **Backoffice**: Filament completo
- **Nessun Controller**: Utilizziamo Filament per l'amministrazione

### Struttura Directory
```
laravel/Modules/Employee/
├── app/
│   ├── Filament/
│   │   ├── Pages/
│   │   │   └── Dashboard.php
│   │   ├── Resources/
│   │   ├── Widgets/
│   │   └── Actions/
│   ├── Providers/
│   │   ├── EmployeeServiceProvider.php
│   │   └── Filament/
│   │       └── AdminPanelProvider.php
│   └── Models/
├── config/
├── database/
├── docs/
├── resources/
├── routes/
├── tests/
├── composer.json
└── module.json
```

## Configurazione Files

### composer.json
```json
{
    "name": "laraxot/module_employee_fila3",
    "description": "employee module for employee management",
    "keywords": [
        "laraxot",
        "laravel",
        "filament",
        "module_employee"
    ],
    "homepage": "https://github.com/laraxot/module_employee_fila3",
    "license": "MIT",
    "authors": [
        {
            "name": "Marco Sottana",
            "email": "marco.sottana@gmail.com"
        }
    ],
    "extra": {
        "laravel": {
            "providers": [
                "Modules\\Employee\\Providers\\EmployeeServiceProvider",
                "Modules\\Employee\\Providers\\Filament\\AdminPanelProvider"
            ],
            "aliases": {}
        }
    },
    "autoload": {
        "psr-4": {
            "Modules\\Employee\\": "app/",
            "Modules\\Employee\\Database\\Factories\\": "database/factories/",
            "Modules\\Employee\\Database\\Seeders\\": "database/seeders/"
        }
    },
    "autoload-dev": {
        "psr-4": {
            "Modules\\Employee\\Tests\\": "tests/"
        }
    },
    "repositories": [
        {
            "type": "path",
            "url": "../Xot"
        },
        {
            "type": "path",
            "url": "../Tenant"
        },
        {
            "type": "path",
            "url": "../UI"
        }
    ],
    "scripts": {
        "post-autoload-dump1": [
            "@php vendor/bin/testbench package:discover --ansi"
        ],
        "post-update-cmd": [
            "Illuminate\\Foundation\\ComposerScripts::postUpdate"
        ],
        "analyse": "vendor/bin/phpstan analyse",
        "test": "./vendor/bin/pest --no-coverage",
        "test-coverage": "vendor/bin/pest --coverage-html coverage",
        "format": "vendor/bin/php-cs-fixer fix --allow-risky=yes"
    },
    "config": {
        "sort-packages": true,
        "allow-plugins": {
            "pestphp/pest-plugin": true,
            "dealerdirect/phpcodesniffer-composer-installer": true,
            "wikimedia/composer-merge-plugin": true
        }
    },
    "minimum-stability": "dev",
    "prefer-stable": true
}
```

### module.json
```json
{
    "name": "Employee",
    "alias": "employee",
    "description": "Gestione dipendenti e risorse umane del sistema",
    "keywords": ["employee", "hr", "human resources", "staff", "personnel"],
    "priority": 0,
    "providers": [
        "Modules\\Employee\\Providers\\EmployeeServiceProvider",
        "Modules\\Employee\\Providers\\Filament\\AdminPanelProvider"
    ],
    "migration": {
        "seeds": [
            "Modules\\Employee\\Database\\Seeders\\EmployeeSeeder"
        ]
    },
    "files": []
}
```

## Providers

### EmployeeServiceProvider.php
```php
<?php

declare(strict_types=1);

namespace Modules\Employee\Providers;

use Modules\Xot\Providers\XotBaseServiceProvider;

/**
 * Service Provider per il modulo Employee.
 * 
 * Questo provider gestisce la registrazione e configurazione
 * del modulo Employee nell'applicazione Laravel.
 * 
 * Estende XotBaseServiceProvider per garantire:
 * - Configurazione automatica del modulo
 * - Registrazione viste e traduzioni
 * - Integrazione con il sistema Laraxot/PTVX
 * - Pattern uniformi con altri moduli
 */
class EmployeeServiceProvider extends XotBaseServiceProvider
{
    /**
     * Nome del modulo.
     */
    public string $name = 'Employee';

    /**
     * Directory del provider.
     */
    protected string $module_dir = __DIR__;

    /**
     * Namespace del provider.
     */
    protected string $module_ns = __NAMESPACE__;
}
```

### AdminPanelProvider.php
```php
<?php

declare(strict_types=1);

namespace Modules\Employee\Providers\Filament;

use Filament\Navigation\MenuItem;
use Filament\Panel;
use Modules\Xot\Providers\Filament\XotBasePanelProvider;

class AdminPanelProvider extends XotBasePanelProvider
{
    protected string $module = 'Employee';

    public function panel(Panel $panel): Panel
    {
        $panel = parent::panel($panel);

        // Configurazioni specifiche del modulo Employee
        $panel->pages([
            \Modules\Employee\Filament\Pages\Dashboard::class,
        ]);

        // Menu items specifici
        $panel->userMenuItems([
            MenuItem::make()
                ->label('Gestione Dipendenti')
                ->url(fn (): string => \Modules\Employee\Filament\Pages\Dashboard::getUrl())
                ->icon('heroicon-m-users'),
        ]);

        return $panel;
    }
}
```

## Filament Pages

### Dashboard.php
```php
<?php

declare(strict_types=1);

namespace Modules\Employee\Filament\Pages;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Widgets\Widget;
use Filament\Widgets\WidgetConfiguration;
use Modules\Xot\Filament\Pages\XotBaseDashboard;

class Dashboard extends XotBaseDashboard
{
    use HasFiltersForm;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $title = 'Dashboard Dipendenti';
    protected static ?int $navigationSort = 20;

    /**
     * @return array<class-string<Widget>|WidgetConfiguration>
     */
    public function getWidgets(): array
    {
        return [
            // Widgets specifici per Employee
            // Widgets\EmployeeChartWidget::class,
            // Widgets\RecentEmployeesWidget::class,
        ];
    }

    public function filtersForm(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Filtri')
                    ->schema([
                        DatePicker::make('startDate')
                            ->label('Data Inizio')
                            ->native(false),
                        DatePicker::make('endDate')
                            ->label('Data Fine')
                            ->native(false),
                    ])
                    ->columns(2),
            ]);
    }
}
```

## Best Practices

### 1. Naming Convention
- **Namespace**: `Modules\Employee\`
- **Alias**: `employee`
- **Viste**: `employee::`
- **Traduzioni**: `employee::`

### 2. Struttura Filament
- **Pages**: `app/Filament/Pages/`
- **Resources**: `app/Filament/Resources/`
- **Widgets**: `app/Filament/Widgets/`
- **Actions**: `app/Filament/Actions/`

### 3. Providers
- **ServiceProvider**: Gestisce registrazione e boot del modulo
- **AdminPanelProvider**: Configura Filament per il modulo

### 4. Integrazione
- **Frontoffice**: Folio + Volt + Filament components
- **Backoffice**: Filament completo
- **Nessun Controller**: Tutto gestito da Filament

### 5. ⚠️ REGOLA CRITICA - Estensione Classi
**NON estendere MAI le classi Filament direttamente. Estendere SEMPRE le classi XotBase**

#### Estensioni Corrette:
```php
// ✅ CORRETTO - Estendere XotBase
use Modules\Xot\Filament\Pages\XotBaseDashboard;

class Dashboard extends XotBaseDashboard
{
    // Implementazione
}

// ✅ CORRETTO - Estendere XotBase
use Modules\Xot\Providers\Filament\XotBasePanelProvider;

class AdminPanelProvider extends XotBasePanelProvider
{
    // Implementazione
}
```

#### Estensioni Errate:
```php
// ❌ ERRATO - Estendere Filament direttamente
use Filament\Pages\Dashboard;

class Dashboard extends Dashboard
{
    // Implementazione
}

// ❌ ERRATO - Estendere Filament direttamente
use Filament\Panel;

class AdminPanelProvider extends Panel
{
    // Implementazione
}
```

## Checklist Implementazione

### Files da Creare/Modificare
- [x] `composer.json` - Configurazione completa
- [x] `module.json` - Configurazione modulo
- [x] `app/Providers/EmployeeServiceProvider.php` - Provider principale
- [x] `app/Providers/Filament/AdminPanelProvider.php` - Provider Filament
- [x] `app/Filament/Pages/Dashboard.php` - Dashboard modulo

### Configurazioni
- [x] Autoload PSR-4
- [x] Providers registrati
- [x] Dependencies configurate
- [x] Scripts definiti

### Integrazione
- [x] Registrazione modulo
- [x] Provider attivo
- [x] Dashboard accessibile
- [x] Menu navigation

### Estensioni Classi
- [x] Estendere sempre classi XotBase
- [x] NON estendere mai classi Filament direttamente
- [x] Verificare namespace corretto
- [x] Documentare estensioni

---
*Documentazione per configurazione modulo Employee - Seguire sempre le convenzioni Laravel Modules - Mai estendere classi Filament direttamente* 