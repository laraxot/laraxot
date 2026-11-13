<<<<<<< HEAD
---
title: "convenzioni per namespace e percorsi dei moduli"
type: note
status: unverified
created: 2026-09-24
updated: 2026-10-20
module: Xot
tags: [xot, docs]
qmd: "convenzioni per namespace e percorsi dei moduli"
---
=======
>>>>>>> 77b1e2748 (first)
# convenzioni per namespace e percorsi dei moduli

## struttura corretta del percorso

uno degli errori più comuni è la confusione tra il namespace nel codice php e il percorso fisico dei file nel filesystem.

### percorso fisico corretto

i file dei moduli devono sempre seguire questa struttura:

```
<<<<<<< HEAD
/var/www/html/base_saluteora/laravel/Modules/{NomeModulo}/app/{Tipo}/...
```

per esempio:
- `/var/www/html/base_saluteora/laravel/Modules/SaluteOra/app/Filament/Resources/...`
- `/var/www/html/base_saluteora/laravel/Modules/SaluteOra/app/Models/...`
- `/var/www/html/base_saluteora/laravel/Modules/SaluteOra/app/Http/Controllers/...`
=======
Modules/{NomeModulo}/app/{Tipo}/...
```

per esempio:
- `Modules/<nome progetto>/app/Filament/Resources/...`
- `Modules/<nome progetto>/app/Models/...`
- `Modules/<nome progetto>/app/Http/Controllers/...`
>>>>>>> 77b1e2748 (first)

### namespace corretto

i namespace nei file php devono seguire questa struttura:

```php
namespace Modules\{NomeModulo}\{Tipo}\...;
```

per esempio:
<<<<<<< HEAD
- `namespace Modules\SaluteOra\Filament\Resources;`
- `namespace Modules\SaluteOra\Models;`
- `namespace Modules\SaluteOra\Http\Controllers;`
=======
- `namespace Modules\<nome progetto>\Filament\Resources;`
- `namespace Modules\<nome progetto>\Models;`
- `namespace Modules\<nome progetto>\Http\Controllers;`
>>>>>>> 77b1e2748 (first)

## errore comune

spesso si confonde il percorso fisico con il namespace, cercando file in:

```
<<<<<<< HEAD
/var/www/html/base_saluteora/laravel/Modules/{NomeModulo}/{Tipo}/...
=======
Modules/{NomeModulo}/{Tipo}/...
>>>>>>> 77b1e2748 (first)
```

questo è **errato** perché omette la directory `app/` nel percorso fisico.

## verifiche rapide

1. percorso fisico: deve contenere `/app/` dopo il nome del modulo
2. namespace: non deve contenere `app` nel namespace

## esempi corretti

| namespace | percorso fisico |
|-----------|----------------|
<<<<<<< HEAD
| `Modules\SaluteOra\Filament\Resources\DoctorResource` | `/var/www/html/base_saluteora/laravel/Modules/SaluteOra/app/Filament/Resources/DoctorResource.php` |
| `Modules\User\Models\User` | `/var/www/html/base_saluteora/laravel/Modules/User/app/Models/User.php` |
=======
| `Modules\<nome progetto>\Filament\Resources\DoctorResource` | `Modules/<nome progetto>/app/Filament/Resources/DoctorResource.php` |
| `Modules\User\Models\User` | `Modules/User/app/Models/User.php` |
>>>>>>> 77b1e2748 (first)

## come evitare l'errore

1. usa sempre strumenti come `find_by_name` per verificare il percorso effettivo
2. controlla sempre la corrispondenza tra il namespace e il percorso fisico
3. considera sempre che il percorso fisico contiene `/app/` mentre il namespace no

## linkback

<<<<<<< HEAD
- [convenzioni di codice](/var/www/html/base_saluteora/laravel/docs/conventions.md)
- [struttura progetto](/var/www/html/base_saluteora/laravel/docs/project-structure.md)
=======
- [convenzioni di codice](docs/conventions.md)
- [struttura progetto](docs/project-structure.md)
>>>>>>> 77b1e2748 (first)
