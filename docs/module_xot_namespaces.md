# Namespace nel Modulo Xot

## Struttura Base
Il namespace base per il modulo Xot è `Modules\Xot`. Questo è il punto di partenza per tutti i componenti del modulo.

## Directory e Namespace
La struttura delle directory e i relativi namespace sono organizzati come segue:

```
laravel/Modules/Xot/
├── app/
│   ├── Actions/              -> Modules\Xot\Actions
│   ├── Console/             -> Modules\Xot\Console
│   ├── Contracts/           -> Modules\Xot\Contracts
│   ├── Filament/
│   │   ├── Pages/          -> Modules\Xot\Filament\Pages
│   │   ├── Resources/      -> Modules\Xot\Filament\Resources
│   │   └── Widgets/        -> Modules\Xot\Filament\Widgets
│   ├── Http/
│   │   ├── Controllers/    -> Modules\Xot\Http\Controllers
│   │   ├── Middleware/     -> Modules\Xot\Http\Middleware
│   │   └── Requests/       -> Modules\Xot\Http\Requests
│   ├── Models/             -> Modules\Xot\Models
│   ├── Providers/          -> Modules\Xot\Providers
│   └── Services/           -> Modules\Xot\Services
```

## Importante
- NON utilizzare `Modules\Xot\App` come namespace
- La directory `app/` è solo un contenitore fisico, non fa parte del namespace
- Tutti i namespace iniziano con `Modules\Xot\`

## Esempi di Namespace Corretti
```php
namespace Modules\Xot\Actions;           // Per le Actions
namespace Modules\Xot\Filament\Pages;    // Per le pagine Filament
namespace Modules\Xot\Models;            // Per i Models
namespace Modules\Xot\Services;          // Per i Services
```

## Esempi di Namespace Errati da Evitare
```php
namespace Modules\Xot\App\Actions;       // ERRATO: non usare App
namespace App\Modules\Xot\Actions;       // ERRATO: non usare App
namespace Xot\Actions;                   // ERRATO: manca Modules
```

## Best Practices
1. Mantenere la coerenza con la struttura dei namespace in tutto il modulo
2. Non aggiungere livelli di namespace non necessari
3. Seguire le convenzioni di Laravel per i nomi delle directory
4. Rispettare il case-sensitive dei path e dei namespace
5. Utilizzare sempre il namespace completo nelle dichiarazioni use 