# Regole dei Namespace nei Moduli Laravel

## Importanza della Consistenza dei Namespace

### 1. Struttura Base
```
Modules/
└── User/
    └── app/
        ├── Http/
        │   └── Livewire/
        │       └── Auth/
        │           └── Logout.php
        └── Filament/
            └── Widgets/
                └── LoginWidget.php
```

### 2. Regole dei Namespace

#### Regola Fondamentale
```php
// ❌ ERRORE: Capitalizzazione errata di 'App'
namespace Modules\User\App\Http\Livewire\Auth;

// ✅ CORRETTO: 'app' minuscolo
namespace Modules\User\app\Http\Livewire\Auth;
```

#### Motivi
1. Laravel è case-sensitive nei namespace
2. PSR-4 richiede che il namespace corrisponda esattamente alla struttura delle cartelle
3. L'autoloader può generare conflitti con namespace duplicati

### 3. Convenzioni di Naming

#### Directory
```
app/                  → namespace \Modules\User\app
Http/                 → namespace \Modules\User\app\Http
Livewire/            → namespace \Modules\User\app\Http\Livewire
```

#### Namespace Completi
```php
// Components Livewire
namespace Modules\User\app\Http\Livewire\Auth;

// Filament Widgets
namespace Modules\User\app\Filament\Widgets;

// Controllers
namespace Modules\User\app\Http\Controllers;
```

### 4. Errori Comuni

```php
// ❌ ERRORE: Namespace non corrispondente alla struttura delle cartelle
namespace Modules\User\App\Livewire;  // 'App' maiuscolo

// ❌ ERRORE: Namespace incompleto
namespace Modules\User\Livewire;      // manca 'app\Http'

// ❌ ERRORE: Namespace duplicato con capitalizzazione diversa
// In un file:
namespace Modules\User\App\Http\Livewire\Auth;
// In un altro file:
namespace Modules\User\app\Http\Livewire\Auth;

// ✅ CORRETTO: Namespace completo e corretto
namespace Modules\User\app\Http\Livewire\Auth;
```

### 5. Best Practices

1. **Verifica della Struttura**
   - Controllare la corrispondenza tra cartelle e namespace
   - Mantenere la consistenza nella capitalizzazione
   - Seguire le convenzioni PSR-4

2. **Prevenzione Errori**
   - Usare IDE con supporto PHP
   - Configurare correttamente composer.json
   - Implementare controlli automatici

3. **Testing**
   - Verificare l'autoloading delle classi
   - Testare i conflitti di namespace
   - Controllare la case sensitivity

### 6. Namespace Filament

#### Regola Fondamentale
```php
// ❌ ERRORE: Namespace errato con 'App'
namespace Modules\User\App\Filament\Widgets;

// ✅ CORRETTO: Namespace diretto senza 'app'
namespace Modules\User\Filament\Widgets;
```

#### Motivi
1. I componenti Filament devono essere direttamente sotto il namespace del modulo
2. La cartella `app` non deve essere inclusa nel namespace per i componenti Filament
3. Questo permette una migliore integrazione con il sistema di autoloading di Filament

#### Struttura Directory Corretta
```
Modules/
└── User/
    ├── app/                    # Per componenti standard
    │   └── Http/
    │       └── Livewire/
    └── Filament/              # Per componenti Filament
        └── Widgets/
            └── LoginWidget.php
```

#### Namespace Completi per Filament
```php
// Widgets
namespace Modules\User\Filament\Widgets;

// Resources
namespace Modules\User\Filament\Resources;

// Pages
namespace Modules\User\Filament\Pages;
```

## Checklist Implementazione

### 1. Struttura
- [ ] Directory in minuscolo
- [ ] Namespace corrispondenti alle directory
- [ ] Capitalizzazione consistente

### 2. Configurazione
- [ ] PSR-4 in composer.json
- [ ] Autoloader configurato correttamente
- [ ] IDE configurato per il supporto PHP

### 3. Testing
- [ ] Test di autoloading
- [ ] Verifica conflitti
- [ ] Controllo case sensitivity

## Conclusioni

1. Mantenere la consistenza nella capitalizzazione
2. Seguire la struttura delle directory
3. Evitare namespace duplicati
4. Testare l'autoloading
5. Documentare le convenzioni
