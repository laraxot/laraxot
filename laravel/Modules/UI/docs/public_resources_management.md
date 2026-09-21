<<<<<<< HEAD
# Gestione delle Risorse Pubbliche
=======
<<<<<<< HEAD
# Gestione delle Risorse Pubbliche
=======
<<<<<<< HEAD
# Gestione delle Risorse Pubbliche 
=======
<<<<<<< HEAD
<<<<<<< HEAD
=======
# Gestione delle Risorse Pubbliche 
=======
<<<<<<< HEAD
>>>>>>> laraxot/dev
# Gestione delle Risorse Pubbliche
=======
# Gestione delle Risorse Pubbliche 
>>>>>>> f6fcbb6f (Fix merge conflict in .gitattributes by removing redundant lines and ensuring proper exclusion of image formats from text processing.)
<<<<<<< HEAD
=======
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> 92912795 (.)
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev

## Indice
- [Panoramica](#panoramica)
- [Struttura delle Cartelle](#struttura-delle-cartelle)
- [Tipi di Risorse](#tipi-di-risorse)
- [Best Practices](#best-practices)
- [Esempi di Utilizzo](#esempi-di-utilizzo)

## Panoramica

Questo documento descrive la corretta gestione delle risorse pubbliche (immagini, CSS, JavaScript, ecc.) , con particolare attenzione alla struttura delle cartelle e alle best practices da seguire.

## Struttura delle Cartelle

La struttura corretta per le risorse pubbliche  è la seguente:

```
<<<<<<< HEAD
/var/www/html/saluteora/
[project-root]/
=======
<<<<<<< HEAD
/var/www/html/saluteora/
<<<<<<< HEAD
[project-root]/
=======
=======
<<<<<<< HEAD
<<<<<<< HEAD
=======
/var/www/html/saluteora/
=======
<<<<<<< HEAD
>>>>>>> laraxot/dev
[project-root]/
=======
/var/www/html/saluteora/
>>>>>>> f6fcbb6f (Fix merge conflict in .gitattributes by removing redundant lines and ensuring proper exclusion of image formats from text processing.)
<<<<<<< HEAD
=======
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> 92912795 (.)
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
├── public_html/           # Directory pubblica principale
│   ├── images/            # Immagini pubbliche
│   ├── css/               # File CSS
│   ├── js/                # File JavaScript
│   ├── fonts/             # Font
│   └── assets/            # Altre risorse statiche
└── laravel/               # Applicazione Laravel (NON contiene file pubblici)
```

<<<<<<< HEAD
> **IMPORTANTE**: MAI utilizzare `/var/www/html/saluteora/laravel/public/` per i file pubblici. Questa cartella non è accessibile via web nel setup di SaluteOra.
> **IMPORTANTE**: MAI utilizzare `[project-root]/laravel/public/` per i file pubblici. Questa cartella non è accessibile via web nel setup di <nome progetto>corrente.
=======
<<<<<<< HEAD
> **IMPORTANTE**: MAI utilizzare `/var/www/html/saluteora/laravel/public/` per i file pubblici. Questa cartella non è accessibile via web nel setup di SaluteOra.
<<<<<<< HEAD
> **IMPORTANTE**: MAI utilizzare `[project-root]/laravel/public/` per i file pubblici. Questa cartella non è accessibile via web nel setup di <nome progetto>corrente.
=======
=======
<<<<<<< HEAD
<<<<<<< HEAD
=======
> **IMPORTANTE**: MAI utilizzare `/var/www/html/saluteora/laravel/public/` per i file pubblici. Questa cartella non è accessibile via web nel setup di SaluteOra.
=======
<<<<<<< HEAD
>>>>>>> laraxot/dev
> **IMPORTANTE**: MAI utilizzare `[project-root]/laravel/public/` per i file pubblici. Questa cartella non è accessibile via web nel setup di <nome progetto>corrente.
=======
> **IMPORTANTE**: MAI utilizzare `/var/www/html/saluteora/laravel/public/` per i file pubblici. Questa cartella non è accessibile via web nel setup di SaluteOra.
>>>>>>> f6fcbb6f (Fix merge conflict in .gitattributes by removing redundant lines and ensuring proper exclusion of image formats from text processing.)
<<<<<<< HEAD
=======
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> 92912795 (.)
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev

## Tipi di Risorse

### Immagini

<<<<<<< HEAD
Le immagini devono essere posizionate in `/var/www/html/saluteora/public_html/images/` e organizzate in sottocartelle per tipologia:
Le immagini devono essere posizionate in `[project-root]/public_html/images/` e organizzate in sottocartelle per tipologia:
=======
<<<<<<< HEAD
Le immagini devono essere posizionate in `/var/www/html/saluteora/public_html/images/` e organizzate in sottocartelle per tipologia:
<<<<<<< HEAD
Le immagini devono essere posizionate in `[project-root]/public_html/images/` e organizzate in sottocartelle per tipologia:
=======
=======
<<<<<<< HEAD
<<<<<<< HEAD
=======
Le immagini devono essere posizionate in `/var/www/html/saluteora/public_html/images/` e organizzate in sottocartelle per tipologia:
=======
<<<<<<< HEAD
>>>>>>> laraxot/dev
Le immagini devono essere posizionate in `[project-root]/public_html/images/` e organizzate in sottocartelle per tipologia:
=======
Le immagini devono essere posizionate in `/var/www/html/saluteora/public_html/images/` e organizzate in sottocartelle per tipologia:
>>>>>>> f6fcbb6f (Fix merge conflict in .gitattributes by removing redundant lines and ensuring proper exclusion of image formats from text processing.)
<<<<<<< HEAD
=======
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> 92912795 (.)
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev

- `/images/avatars/` - Avatar utenti
- `/images/logos/` - Loghi
- `/images/icons/` - Icone
- `/images/backgrounds/` - Sfondi

### CSS e JavaScript

I file CSS e JavaScript compilati devono essere posizionati in:

- `/public_html/css/` - File CSS
- `/public_html/js/` - File JavaScript

### Font

I font devono essere posizionati in `/public_html/fonts/` e organizzati per famiglia.

## Best Practices

1. **Utilizzo nei Template Blade**

   ```blade
   <img src="{{ asset('images/default-avatar.svg') }}" alt="Avatar utente">
   ```

   > **Nota**: La funzione `asset()` punta automaticamente alla directory pubblica corretta.

2. **Generazione di URL per Risorse Pubbliche**

   ```php
   $avatarUrl = asset('images/default-avatar.svg');
   ```

3. **Risorse Localizzate**

   Per risorse che variano in base alla lingua, utilizzare la struttura:

   ```
   /public_html/images/localized/{locale}/image.svg
   ```

   E accedervi con:

   ```php
   $localizedImage = asset('images/localized/' . LaravelLocalization::getCurrentLocale() . '/image.svg');
   ```

4. **Versionamento delle Risorse**

   Per gestire la cache del browser, aggiungere un parametro di versione:

   ```php
   $cssWithVersion = asset('css/app.css') . '?v=' . config('app.version');
   ```

5. **SVG vs Raster**

   - Preferire SVG per icone, loghi e illustrazioni vettoriali
   - Utilizzare WebP o JPEG ottimizzati per fotografie
   - Fornire fallback per browser più vecchi

## Esempi di Utilizzo

### Avatar Utente

```blade
<<<<<<< HEAD
<img
    src="{{ $user->avatar ? asset('images/avatars/' . $user->avatar) : asset('images/default-avatar.svg') }}"
    alt="{{ $user->name }}"
    src="{{ $user->avatar ? asset('images/avatars/' . $user->avatar) : asset('images/default-avatar.svg') }}" 
    alt="{{ $user->name }}" 
=======
<<<<<<< HEAD
<img
    src="{{ $user->avatar ? asset('images/avatars/' . $user->avatar) : asset('images/default-avatar.svg') }}"
    alt="{{ $user->name }}"
=======
<<<<<<< HEAD
<img 
>>>>>>> laraxot/dev
    src="{{ $user->avatar ? asset('images/avatars/' . $user->avatar) : asset('images/default-avatar.svg') }}" 
    alt="{{ $user->name }}" 
=======
<<<<<<< HEAD
<<<<<<< HEAD
=======
<img 
    src="{{ $user->avatar ? asset('images/avatars/' . $user->avatar) : asset('images/default-avatar.svg') }}" 
    alt="{{ $user->name }}" 
=======
<<<<<<< HEAD
>>>>>>> laraxot/dev
<img
    src="{{ $user->avatar ? asset('images/avatars/' . $user->avatar) : asset('images/default-avatar.svg') }}"
    alt="{{ $user->name }}"
=======
<img 
    src="{{ $user->avatar ? asset('images/avatars/' . $user->avatar) : asset('images/default-avatar.svg') }}" 
    alt="{{ $user->name }}" 
>>>>>>> f6fcbb6f (Fix merge conflict in .gitattributes by removing redundant lines and ensuring proper exclusion of image formats from text processing.)
<<<<<<< HEAD
=======
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> 92912795 (.)
>>>>>>> laraxot/dev
    class="h-10 w-10 rounded-full"
>
```

### Logo nell'Header

```blade
<a href="{{ LaravelLocalization::getLocalizedURL(LaravelLocalization::getCurrentLocale(), route('home')) }}">
<<<<<<< HEAD
    <img 
        src="{{ asset('images/logos/saluteora-logo.svg') }}" 
        alt="SaluteOra" 
=======
<<<<<<< HEAD
    <img 
        src="{{ asset('images/logos/saluteora-logo.svg') }}" 
        alt="SaluteOra" 
=======
<<<<<<< HEAD
<<<<<<< HEAD
=======
    <img 
        src="{{ asset('images/logos/saluteora-logo.svg') }}" 
        alt="SaluteOra" 
=======
<<<<<<< HEAD
>>>>>>> laraxot/dev
    <img
        src="{{ asset('images/logos/<nome progetto>ogo.svg') }}"
        alt="<nome progetto>corrente"
=======
    <img 
        src="{{ asset('images/logos/saluteora-logo.svg') }}" 
        alt="SaluteOra" 
>>>>>>> f6fcbb6f (Fix merge conflict in .gitattributes by removing redundant lines and ensuring proper exclusion of image formats from text processing.)
<<<<<<< HEAD
=======
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> 92912795 (.)
>>>>>>> laraxot/dev
        class="h-8"
    >
</a>
```

### CSS e JavaScript

```blade
<link rel="stylesheet" href="{{ asset('css/app.css') }}">
<script src="{{ asset('js/app.js') }}" defer></script>
```

## Conclusione

<<<<<<< HEAD
Seguendo queste linee guida per la gestione delle risorse pubbliche, si garantisce che tutte le risorse siano correttamente accessibili via web e organizzate in modo coerente, facilitando la manutenzione e l'evoluzione del progetto SaluteOra.
Seguendo queste linee guida per la gestione delle risorse pubbliche, si garantisce che tutte le risorse siano correttamente accessibili via web e organizzate in modo coerente, facilitando la manutenzione e l'evoluzione del progetto <nome progetto>corrente.
=======
<<<<<<< HEAD
Seguendo queste linee guida per la gestione delle risorse pubbliche, si garantisce che tutte le risorse siano correttamente accessibili via web e organizzate in modo coerente, facilitando la manutenzione e l'evoluzione del progetto SaluteOra.
<<<<<<< HEAD
Seguendo queste linee guida per la gestione delle risorse pubbliche, si garantisce che tutte le risorse siano correttamente accessibili via web e organizzate in modo coerente, facilitando la manutenzione e l'evoluzione del progetto <nome progetto>corrente.
=======
=======
<<<<<<< HEAD
<<<<<<< HEAD
=======
Seguendo queste linee guida per la gestione delle risorse pubbliche, si garantisce che tutte le risorse siano correttamente accessibili via web e organizzate in modo coerente, facilitando la manutenzione e l'evoluzione del progetto SaluteOra.
=======
<<<<<<< HEAD
>>>>>>> laraxot/dev
Seguendo queste linee guida per la gestione delle risorse pubbliche, si garantisce che tutte le risorse siano correttamente accessibili via web e organizzate in modo coerente, facilitando la manutenzione e l'evoluzione del progetto <nome progetto>corrente.
=======
Seguendo queste linee guida per la gestione delle risorse pubbliche, si garantisce che tutte le risorse siano correttamente accessibili via web e organizzate in modo coerente, facilitando la manutenzione e l'evoluzione del progetto SaluteOra.
>>>>>>> f6fcbb6f (Fix merge conflict in .gitattributes by removing redundant lines and ensuring proper exclusion of image formats from text processing.)
<<<<<<< HEAD
=======
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> 92912795 (.)
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
