<<<<<<< HEAD
# Struttura Themes e Folio in <nome progetto>corrente
# Struttura Themes e Folio in <nome progetto>corrente
=======
<<<<<<< HEAD
# Struttura Themes e Folio in <nome progetto>corrente
# Struttura Themes e Folio in <nome progetto>corrente
=======
<<<<<<< HEAD
# Struttura Themes e Folio in SaluteOra
=======
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
# Struttura Themes e Folio in <nome progetto>corrente
=======
=======
=======
<<<<<<< HEAD
<<<<<<< HEAD
# Struttura Themes e Folio in <nome progetto>corrente
=======
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
# Struttura Themes e Folio in
# Struttura Themes e Folio in <nome progetto>
# Struttura Themes e Folio in
# Struttura Themes e Folio in <nome progetto>
# Struttura Themes e Folio in
# Struttura Themes e Folio in <nome progetto>
<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
=======
# Struttura Themes e Folio in SaluteOra
>>>>>>> f6fcbb6f (Fix merge conflict in .gitattributes by removing redundant lines and ensuring proper exclusion of image formats from text processing.)
<<<<<<< HEAD
=======
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> 92912795 (.)
>>>>>>> laraxot/dev

## ⚠️ ATTENZIONE CRITICA

**NON CREARE MAI FILE IN**: `/laravel/resources/views/pages/`

**CREARE SEMPRE FILE IN**: `/laravel/Themes/One/resources/views/pages/`

## Struttura del Progetto

<<<<<<< HEAD
Il progetto <nome progetto>corrente utilizza un sistema di **Themes** che modifica la struttura standard di Laravel Folio:
Il progetto <nome progetto>corrente utilizza un sistema di **Themes** che modifica la struttura standard di Laravel Folio:
=======
<<<<<<< HEAD
Il progetto <nome progetto>corrente utilizza un sistema di **Themes** che modifica la struttura standard di Laravel Folio:
Il progetto <nome progetto>corrente utilizza un sistema di **Themes** che modifica la struttura standard di Laravel Folio:
=======
<<<<<<< HEAD
Il progetto SaluteOra utilizza un sistema di **Themes** che modifica la struttura standard di Laravel Folio:
=======
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
Il progetto <nome progetto>corrente utilizza un sistema di **Themes** che modifica la struttura standard di Laravel Folio:
=======
=======
=======
<<<<<<< HEAD
<<<<<<< HEAD
Il progetto <nome progetto>corrente utilizza un sistema di **Themes** che modifica la struttura standard di Laravel Folio:
=======
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
Il progetto  utilizza un sistema di **Themes** che modifica la struttura standard di Laravel Folio:
Il progetto <nome progetto> utilizza un sistema di **Themes** che modifica la struttura standard di Laravel Folio:
Il progetto  utilizza un sistema di **Themes** che modifica la struttura standard di Laravel Folio:
Il progetto <nome progetto> utilizza un sistema di **Themes** che modifica la struttura standard di Laravel Folio:
Il progetto  utilizza un sistema di **Themes** che modifica la struttura standard di Laravel Folio:
Il progetto <nome progetto> utilizza un sistema di **Themes** che modifica la struttura standard di Laravel Folio:
<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
=======
Il progetto SaluteOra utilizza un sistema di **Themes** che modifica la struttura standard di Laravel Folio:
>>>>>>> f6fcbb6f (Fix merge conflict in .gitattributes by removing redundant lines and ensuring proper exclusion of image formats from text processing.)
<<<<<<< HEAD
=======
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> 92912795 (.)
>>>>>>> laraxot/dev

```
/laravel/
├── resources/views/pages/          # ❌ NON USARE MAI
└── Themes/
    ├── One/                        # Theme principale
    │   └── resources/
    │       └── views/
    │           └── pages/          # ✅ USARE SEMPRE QUESTO
    │               ├── index.blade.php
    │               ├── patient/
    │               │   └── book.blade.php
    │               └── ...
    └── Two/                        # Theme secondario
```

## Configurazione Theme

Il theme attivo è configurato in `/laravel/config/theme.php`:

```php
return [
    'name' => 'One',                              # Theme attivo
    'views_path' => 'Themes/One/resources/views', # Path delle views
    'assets_path' => 'Themes/One/assets',         # Path degli assets
];
```

## Laravel Folio con Themes

### Come Funziona

1. **FolioServiceProvider** standard punta a `resources/views/pages`
2. Ma il sistema di **Themes** sovrascrive questo comportamento
3. Le route vengono risolte da `Themes/One/resources/views/pages/`

### Esempio di Route

URL: `/it/patient/book`

File corretto: `/laravel/Themes/One/resources/views/pages/patient/book.blade.php`

### Struttura File Blade con Folio

```php
<?php
declare(strict_types=1);

use Livewire\Volt\Component;
use function Laravel\Folio\{middleware, name};

name('patient.book'); // Nome della route

new class extends Component
{
    // Logica del componente Volt
};
?>

<x-layouts.app>
    @volt('patient.book')
        <!-- Contenuto della pagina -->
    @endvolt
</x-layouts.app>
```

## Localizzazione con Themes

Il parametro `[locale]` NON è gestito come directory dinamica ma attraverso il middleware di localizzazione:

- URL: `/it/patient/book`
- File: `/Themes/One/resources/views/pages/patient/book.blade.php`
- La locale è gestita da `mcamara/laravel-localization`

## Errori Comuni da Evitare

### ❌ ERRORE 1: Creare file nella struttura standard
```bash

# SBAGLIATO
/laravel/resources/views/pages/[locale]/patient/book.blade.php
```

### ❌ ERRORE 2: Dimenticare il sistema di Themes
```php
// SBAGLIATO - cerca nel posto sbagliato
Folio::path(resource_path('views/pages'));
```

### ❌ ERRORE 3: Creare directory [locale]
```bash

# SBAGLIATO - [locale] non è una directory fisica
mkdir -p resources/views/pages/[locale]/patient
```

## Come Creare Nuove Pagine

### 1. Identifica il Theme Attivo
```bash
cat /laravel/config/theme.php | grep "'name'"
```

### 2. Crea il File nel Theme Corretto
```bash

# Esempio per pagina /it/services/cardiology
touch /laravel/Themes/One/resources/views/pages/services/cardiology.blade.php
```

### 3. Struttura Base del File
```php
<?php
declare(strict_types=1);

use function Laravel\Folio\name;

name('services.cardiology');
?>

<x-layouts.app>
    <!-- Contenuto pagina -->
</x-layouts.app>
```

## Debugging

### Verificare il Theme Attivo
```php
config('theme.name') // Output: "One"
```

### Verificare il Path delle Views
```php
config('theme.views_path') // Output: "Themes/One/resources/views"
```

### Errori Comuni e Soluzioni

1. **404 Not Found**: Verifica che il file sia in `Themes/One/` e non in `resources/views/`
2. **View not found**: Controlla il theme attivo in `config/theme.php`
3. **Route non funzionante**: Assicurati che Folio sia configurato per il theme

## Checklist Pre-Creazione File

- [ ] Ho verificato il theme attivo?
- [ ] Sto creando il file in `Themes/One/resources/views/pages/`?
- [ ] NON sto creando in `resources/views/pages/`?
- [ ] Ho capito che `[locale]` non è una directory fisica?
- [ ] Ho incluso `declare(strict_types=1)`?

## Link Utili

- [Documentazione Laravel Folio](https://laravel.com/docs/folio)
- [mcamara/laravel-localization](https://github.com/mcamara/laravel-localization)
<<<<<<< HEAD
=======
<<<<<<< HEAD
- Configurazione Theme: `/laravel/config/theme.php`
=======
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
- Configurazione Theme: `/laravel/config/theme.php`
=======
=======
=======
<<<<<<< HEAD
<<<<<<< HEAD
- Configurazione Theme: `/laravel/config/theme.php`
=======
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
- Configurazione Theme: `/laravel/config/theme.php`
# Struttura Themes e Folio in <nome progetto>
## ⚠️ ATTENZIONE CRITICA
**NON CREARE MAI FILE IN**: `/laravel/resources/views/pages/`
**CREARE SEMPRE FILE IN**: `/laravel/Themes/One/resources/views/pages/`
## Struttura del Progetto
Il progetto <nome progetto> utilizza un sistema di **Themes** che modifica la struttura standard di Laravel Folio:
```
/laravel/
├── resources/views/pages/          # ❌ NON USARE MAI
└── Themes/
    ├── One/                        # Theme principale
    │   └── resources/
    │       └── views/
    │           └── pages/          # ✅ USARE SEMPRE QUESTO
    │               ├── index.blade.php
    │               ├── patient/
    │               │   └── book.blade.php
    │               └── ...
    └── Two/                        # Theme secondario
## Configurazione Theme
Il theme attivo è configurato in `/laravel/config/theme.php`:
```

```php
return [
    'name' => 'One',                              # Theme attivo
    'views_path' => 'Themes/One/resources/views', # Path delle views
    'assets_path' => 'Themes/One/assets',         # Path degli assets
];
## Laravel Folio con Themes
### Come Funziona
1. **FolioServiceProvider** standard punta a `resources/views/pages`
2. Ma il sistema di **Themes** sovrascrive questo comportamento
3. Le route vengono risolte da `Themes/One/resources/views/pages/`
### Esempio di Route
URL: `/it/patient/book`
File corretto: `/laravel/Themes/One/resources/views/pages/patient/book.blade.php`
### Struttura File Blade con Folio
<?php
declare(strict_types=1);
use Livewire\Volt\Component;
use function Laravel\Folio\{middleware, name};
name('patient.book'); // Nome della route
new class extends Component
{
    // Logica del componente Volt
};
?>
<x-layouts.app>
    @volt('patient.book')
        <!-- Contenuto della pagina -->
    @endvolt
</x-layouts.app>
## Localizzazione con Themes
Il parametro `[locale]` NON è gestito come directory dinamica ma attraverso il middleware di localizzazione:
- URL: `/it/patient/book`
- File: `/Themes/One/resources/views/pages/patient/book.blade.php`
- La locale è gestita da `mcamara/laravel-localization`
## Errori Comuni da Evitare
### ❌ ERRORE 1: Creare file nella struttura standard
```

```bash
# SBAGLIATO
/laravel/resources/views/pages/[locale]/patient/book.blade.php
### ❌ ERRORE 2: Dimenticare il sistema di Themes
// SBAGLIATO - cerca nel posto sbagliato
Folio::path(resource_path('views/pages'));
### ❌ ERRORE 3: Creare directory [locale]
# SBAGLIATO - [locale] non è una directory fisica
mkdir -p resources/views/pages/[locale]/patient
## Come Creare Nuove Pagine
### 1. Identifica il Theme Attivo
cat /laravel/config/theme.php | grep "'name'"
### 2. Crea il File nel Theme Corretto
# Esempio per pagina /it/services/cardiology
touch /laravel/Themes/One/resources/views/pages/services/cardiology.blade.php
### 3. Struttura Base del File
use function Laravel\Folio\name;
name('services.cardiology');
    <!-- Contenuto pagina -->
## Debugging
### Verificare il Theme Attivo
config('theme.name') // Output: "One"
### Verificare il Path delle Views
config('theme.views_path') // Output: "Themes/One/resources/views"
### Errori Comuni e Soluzioni
1. **404 Not Found**: Verifica che il file sia in `Themes/One/` e non in `resources/views/`
2. **View not found**: Controlla il theme attivo in `config/theme.php`
3. **Route non funzionante**: Assicurati che Folio sia configurato per il theme
## Checklist Pre-Creazione File
- [ ] Ho verificato il theme attivo?
- [ ] Sto creando il file in `Themes/One/resources/views/pages/`?
- [ ] NON sto creando in `resources/views/pages/`?
- [ ] Ho capito che `[locale]` non è una directory fisica?
- [ ] Ho incluso `declare(strict_types=1)`?
## Link Utili
- [Documentazione Laravel Folio](https://laravel.com/docs/folio)
- [mcamara/laravel-localization](https://github.com/mcamara/laravel-localization)
<<<<<<< HEAD

```
=======
<<<<<<< HEAD

```
=======
- Configurazione Theme: `/laravel/config/theme.php`
<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
=======
- Configurazione Theme: `/laravel/config/theme.php`
>>>>>>> f6fcbb6f (Fix merge conflict in .gitattributes by removing redundant lines and ensuring proper exclusion of image formats from text processing.)
<<<<<<< HEAD
=======
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> 92912795 (.)
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
