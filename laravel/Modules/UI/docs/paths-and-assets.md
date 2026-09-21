# AVVISO IMPORTANTE (2025-05-13)

> **ATTENZIONE:** Tutti i componenti UI condivisi (come `logo.blade.php`) devono essere SEMPRE posizionati in `Modules/UI/resources/views/components/ui/` e MAI in `resources/views/components/`. Qualsiasi violazione di questa regola causa errori di rendering, override errati, problemi di modularità e manutenzione.
>
> **Errore riscontrato:** Il componente `logo.blade.php` era stato posizionato erroneamente in `resources/views/components/ui/` invece che in `Modules/UI/resources/views/components/ui/`.
>
> **Causa:** Dimenticanza della regola di modularità Laraxot: tutti i componenti Blade UI condivisi devono essere sempre nel modulo UI, mai nella root Laravel.
>
> **Soluzione:** Seguire SEMPRE la regola documentata qui sotto e aggiornata anche in README.md e nella root docs/links.md.

<<<<<<< HEAD
=======
<<<<<<< HEAD
>>>>>>> laraxot/dev
# Gestione dei Percorsi e degli Asset
## Collegamenti correlati
- [README modulo UI](/laravel/Modules/UI/docs/README.md)
- [Architettura Modulare](/laravel/Modules/UI/docs/ARCHITECTURE.md)
<<<<<<< HEAD
=======
=======
<<<<<<< HEAD
# Gestione dei Percorsi e degli Asset 

## Collegamenti correlati
- [README modulo UI](/laravel/Modules/UI/docs/README.md)
- [Architettura Modulare](/laravel/Modules/UI/docs/ARCHITECTURE.md)
=======
<<<<<<< HEAD
<<<<<<< HEAD
=======
# Gestione dei Percorsi e degli Asset
=======
<<<<<<< HEAD
>>>>>>> laraxot/dev
<<<<<<< HEAD
# Gestione dei Percorsi e degli Asset 
=======
# Gestione dei Percorsi e degli Asset
>>>>>>> laraxot/dev
=======
# Gestione dei Percorsi e degli Asset 
>>>>>>> f6fcbb6f (Fix merge conflict in .gitattributes by removing redundant lines and ensuring proper exclusion of image formats from text processing.)
<<<<<<< HEAD
=======
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev

## Collegamenti correlati
- [README modulo UI](/laravel/Modules/UI/docs/README.md)
- [Architettura Modulare](/laravel/Modules/UI/docs/architecture.md)
<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
>>>>>>> 92912795 (.)
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
- [Collegamenti Documentazione](/docs/collegamenti-documentazione.md)

## Percorsi Corretti per gli Asset

### Struttura delle Directory

, è fondamentale rispettare la struttura corretta delle directory per gli asset pubblici:

```
<<<<<<< HEAD
[project-root]/
[project-root]/
=======
<<<<<<< HEAD
[project-root]/
[project-root]/
=======
<<<<<<< HEAD
/var/www/html/saluteora/
=======
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
[project-root]/
=======
=======
=======
<<<<<<< HEAD
<<<<<<< HEAD
[project-root]/
=======
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev






<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
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
├── laravel/                 # Applicazione Laravel (codice sorgente)
│   ├── Modules/             # Moduli dell'applicazione
│   ├── resources/           # Risorse non compilate
│   └── ...
└── public_html/             # Directory pubblica (web root)
    ├── images/              # Immagini pubbliche
    │   ├── avatars/         # Avatar utenti
    │   └── ...
    ├── css/                 # File CSS compilati
    ├── js/                  # File JavaScript compilati
    └── ...
```

### Percorsi Corretti vs Percorsi Errati

| Tipo di Asset | ✅ Percorso Corretto | ❌ Percorso Errato |
|---------------|---------------------|-------------------|
<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
<<<<<<< HEAD
<<<<<<< HEAD
=======
=======
<<<<<<< HEAD
>>>>>>> laraxot/dev
<<<<<<< HEAD
| Immagini | `[project-root]/public_html/images/` | `[project-root]/laravel/public/images/` |
| CSS | `[project-root]/public_html/css/` | `[project-root]/laravel/public/css/` |
| JavaScript | `[project-root]/public_html/js/` | `[project-root]/laravel/public/js/` |
| SVG | `[project-root]/public_html/images/` | `[project-root]/laravel/public/images/` |
=======
<<<<<<< HEAD
=======
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
| Immagini | `public_html/images/` | `public/images/` |
| CSS | `public_html/css/` | `public/css/` |
| JavaScript | `public_html/js/` | `public/js/` |
| SVG | `public_html/images/` | `public/images/` |
| Immagini | `public_html/images/` | `public/images/` |
| CSS | `public_html/css/` | `public/css/` |
| JavaScript | `public_html/js/` | `public/js/` |
| SVG | `public_html/images/` | `public/images/` |
<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
=======
>>>>>>> 92912795 (.)
| Immagini | `/var/www/html/saluteora/public_html/images/` | `/var/www/html/saluteora/laravel/public/images/` |
| CSS | `/var/www/html/saluteora/public_html/css/` | `/var/www/html/saluteora/laravel/public/css/` |
| JavaScript | `/var/www/html/saluteora/public_html/js/` | `/var/www/html/saluteora/laravel/public/js/` |
| SVG | `/var/www/html/saluteora/public_html/images/` | `/var/www/html/saluteora/laravel/public/images/` |
<<<<<<< HEAD
=======
>>>>>>> f6fcbb6f (Fix merge conflict in .gitattributes by removing redundant lines and ensuring proper exclusion of image formats from text processing.)
<<<<<<< HEAD
=======
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> 92912795 (.)
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev

## Utilizzo degli Asset nei Componenti Blade

### Helper `asset()`

Quando si fa riferimento agli asset nei componenti Blade, utilizzare sempre l'helper `asset()` che punta automaticamente alla directory pubblica corretta:

```php
<img src="{{ asset('images/avatars/default-1.svg') }}" alt="Avatar">
```

### Gestione dei Fallback

Per garantire una buona esperienza utente, implementare sempre un fallback per le immagini che potrebbero non essere disponibili:

```php
<<<<<<< HEAD
<img
=======
<<<<<<< HEAD
<img 
=======
<<<<<<< HEAD
<<<<<<< HEAD
=======
<img
=======
<<<<<<< HEAD
>>>>>>> laraxot/dev
<<<<<<< HEAD
<img 
=======
<img
>>>>>>> laraxot/dev
=======
<img 
>>>>>>> f6fcbb6f (Fix merge conflict in .gitattributes by removing redundant lines and ensuring proper exclusion of image formats from text processing.)
<<<<<<< HEAD
=======
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> 92912795 (.)
>>>>>>> laraxot/dev
    src="{{ asset('images/avatars/default-' . $avatarNumber . '.svg') }}"
    alt="{{ $user->name ?? 'User' }}"
    onerror="this.src='{{ asset('images/default-avatar.svg') }}'"
/>
```

## Componenti SVG

### SVG come Componenti Blade

Gli SVG utilizzati come icone o componenti UI dovrebbero essere implementati come componenti Blade in:

```
<<<<<<< HEAD
[project-root]/laravel/Themes/One/resources/views/components/ui/
[project-root]/laravel/Themes/One/resources/views/components/ui/
=======
<<<<<<< HEAD
[project-root]/laravel/Themes/One/resources/views/components/ui/
[project-root]/laravel/Themes/One/resources/views/components/ui/
=======
<<<<<<< HEAD
/var/www/html/saluteora/laravel/Themes/One/resources/views/components/ui/
=======
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
[project-root]/laravel/Themes/One/resources/views/components/ui/
=======
=======
=======
<<<<<<< HEAD
<<<<<<< HEAD
[project-root]/laravel/Themes/One/resources/views/components/ui/
=======
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
Themes/One/resources/views/components/ui/
Themes/One/resources/views/components/ui/
Themes/One/resources/views/components/ui/
Themes/One/resources/views/components/ui/
Themes/One/resources/views/components/ui/
Themes/One/resources/views/components/ui/
<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
=======
/var/www/html/saluteora/laravel/Themes/One/resources/views/components/ui/
>>>>>>> f6fcbb6f (Fix merge conflict in .gitattributes by removing redundant lines and ensuring proper exclusion of image formats from text processing.)
<<<<<<< HEAD
=======
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> 92912795 (.)
>>>>>>> laraxot/dev
```

### SVG come Asset Pubblici

Gli SVG utilizzati come immagini (avatar, loghi, ecc.) dovrebbero essere posizionati in:

```
<<<<<<< HEAD
[project-root]/public_html/images/
[project-root]/public_html/images/
=======
<<<<<<< HEAD
[project-root]/public_html/images/
[project-root]/public_html/images/
=======
<<<<<<< HEAD
/var/www/html/saluteora/public_html/images/
=======
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
[project-root]/public_html/images/
=======
=======
=======
<<<<<<< HEAD
<<<<<<< HEAD
[project-root]/public_html/images/
=======
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
public_html/images/
public_html/images/
public_html/images/
public_html/images/
public_html/images/
public_html/images/
<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
=======
/var/www/html/saluteora/public_html/images/
>>>>>>> f6fcbb6f (Fix merge conflict in .gitattributes by removing redundant lines and ensuring proper exclusion of image formats from text processing.)
<<<<<<< HEAD
=======
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> 92912795 (.)
>>>>>>> laraxot/dev
```

## Gestione dei Componenti UI

### Componente Avatar

Il componente avatar è implementato in:

```
<<<<<<< HEAD
[project-root]/laravel/Themes/One/resources/views/components/ui/avatar.blade.php
[project-root]/laravel/Themes/One/resources/views/components/ui/avatar.blade.php
=======
<<<<<<< HEAD
[project-root]/laravel/Themes/One/resources/views/components/ui/avatar.blade.php
[project-root]/laravel/Themes/One/resources/views/components/ui/avatar.blade.php
=======
<<<<<<< HEAD
/var/www/html/saluteora/laravel/Themes/One/resources/views/components/ui/avatar.blade.php
=======
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
[project-root]/laravel/Themes/One/resources/views/components/ui/avatar.blade.php
=======
=======
=======
<<<<<<< HEAD
<<<<<<< HEAD
[project-root]/laravel/Themes/One/resources/views/components/ui/avatar.blade.php
=======
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
Themes/One/resources/views/components/ui/avatar.blade.php
Themes/One/resources/views/components/ui/avatar.blade.php
Themes/One/resources/views/components/ui/avatar.blade.php
Themes/One/resources/views/components/ui/avatar.blade.php
Themes/One/resources/views/components/ui/avatar.blade.php
Themes/One/resources/views/components/ui/avatar.blade.php
<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
=======
/var/www/html/saluteora/laravel/Themes/One/resources/views/components/ui/avatar.blade.php
>>>>>>> f6fcbb6f (Fix merge conflict in .gitattributes by removing redundant lines and ensuring proper exclusion of image formats from text processing.)
<<<<<<< HEAD
=======
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> 92912795 (.)
>>>>>>> laraxot/dev
```

E utilizza gli avatar SVG dalla directory pubblica:

```
<<<<<<< HEAD
[project-root]/public_html/images/avatars/
[project-root]/public_html/images/avatars/
=======
<<<<<<< HEAD
[project-root]/public_html/images/avatars/
[project-root]/public_html/images/avatars/
=======
<<<<<<< HEAD
/var/www/html/saluteora/public_html/images/avatars/
=======
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
[project-root]/public_html/images/avatars/
=======
=======
=======
<<<<<<< HEAD
<<<<<<< HEAD
[project-root]/public_html/images/avatars/
=======
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
public_html/images/avatars/
public_html/images/avatars/
public_html/images/avatars/
public_html/images/avatars/
public_html/images/avatars/
public_html/images/avatars/
<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
=======
/var/www/html/saluteora/public_html/images/avatars/
>>>>>>> f6fcbb6f (Fix merge conflict in .gitattributes by removing redundant lines and ensuring proper exclusion of image formats from text processing.)
<<<<<<< HEAD
=======
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> 92912795 (.)
>>>>>>> laraxot/dev
```

### Componente Icon

Il componente icon è implementato in:

```
<<<<<<< HEAD
[project-root]/laravel/Themes/One/resources/views/components/ui/icon.blade.php
[project-root]/laravel/Themes/One/resources/views/components/ui/icon.blade.php
=======
<<<<<<< HEAD
[project-root]/laravel/Themes/One/resources/views/components/ui/icon.blade.php
[project-root]/laravel/Themes/One/resources/views/components/ui/icon.blade.php
=======
<<<<<<< HEAD
/var/www/html/saluteora/laravel/Themes/One/resources/views/components/ui/icon.blade.php
=======
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
[project-root]/laravel/Themes/One/resources/views/components/ui/icon.blade.php
=======
=======
=======
<<<<<<< HEAD
<<<<<<< HEAD
[project-root]/laravel/Themes/One/resources/views/components/ui/icon.blade.php
=======
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
Themes/One/resources/views/components/ui/icon.blade.php
Themes/One/resources/views/components/ui/icon.blade.php
Themes/One/resources/views/components/ui/icon.blade.php
Themes/One/resources/views/components/ui/icon.blade.php
Themes/One/resources/views/components/ui/icon.blade.php
Themes/One/resources/views/components/ui/icon.blade.php
<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
=======
/var/www/html/saluteora/laravel/Themes/One/resources/views/components/ui/icon.blade.php
>>>>>>> f6fcbb6f (Fix merge conflict in .gitattributes by removing redundant lines and ensuring proper exclusion of image formats from text processing.)
<<<<<<< HEAD
=======
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> 92912795 (.)
>>>>>>> laraxot/dev
```

E include le definizioni SVG direttamente nel componente.

## Regola sui Componenti Blade UI

> **IMPORTANTE:** Tutti i componenti Blade UI condivisi (es. logo, button, badge, ecc.) devono essere posizionati esclusivamente in:
>
<<<<<<< HEAD
> `Modules/UI/resources/views/components/ui/`
> `Modules/UI/resources/views/components/ui/`
=======
<<<<<<< HEAD
> `/var/www/html/ptvx/laravel/Modules/UI/resources/views/components/ui/`
=======
<<<<<<< HEAD
<<<<<<< HEAD
=======
> `Modules/UI/resources/views/components/ui/`
<<<<<<< HEAD
> `Modules/UI/resources/views/components/ui/`
=======
=======
<<<<<<< HEAD
>>>>>>> laraxot/dev
<<<<<<< HEAD
> `/var/www/html/ptvx/laravel/Modules/UI/resources/views/components/ui/`
=======
> `Modules/UI/resources/views/components/ui/`
>>>>>>> laraxot/dev
=======
> `/var/www/html/ptvx/laravel/Modules/UI/resources/views/components/ui/`
>>>>>>> f6fcbb6f (Fix merge conflict in .gitattributes by removing redundant lines and ensuring proper exclusion of image formats from text processing.)
<<<<<<< HEAD
=======
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> 92912795 (.)
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>
> **MAI** in `resources/views/components/ui/` della root Laravel.

### Motivazione
- Garantisce la modularità e la possibilità di override a livello di modulo
- Evita conflitti e duplicazioni tra moduli e root
- Permette una gestione centralizzata e documentata dei componenti UI
- Segue la filosofia Laraxot di separazione delle responsabilità

### Esempio di errore e correzione

**❌ Errato:**
```
<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
<<<<<<< HEAD
<<<<<<< HEAD
=======
=======
<<<<<<< HEAD
>>>>>>> laraxot/dev
<<<<<<< HEAD
=======
>>>>>>> f6fcbb6f (Fix merge conflict in .gitattributes by removing redundant lines and ensuring proper exclusion of image formats from text processing.)
>>>>>>> 92912795 (.)
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
/var/www/html/ptvx/laravel/resources/views/components/ui/logo.blade.php
```
**✅ Corretto:**
```
/var/www/html/ptvx/laravel/Modules/UI/resources/views/components/ui/logo.blade.php
<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
resources/views/components/ui/logo.blade.php
```
**✅ Corretto:**
```
Modules/UI/resources/views/components/ui/logo.blade.php
<<<<<<< HEAD
=======
<<<<<<< HEAD
>>>>>>> laraxot/dev
=======
>>>>>>> f6fcbb6f (Fix merge conflict in .gitattributes by removing redundant lines and ensuring proper exclusion of image formats from text processing.)
=======
<<<<<<< HEAD
=======
>>>>>>> laraxot/dev
=======
>>>>>>> f6fcbb6f (Fix merge conflict in .gitattributes by removing redundant lines and ensuring proper exclusion of image formats from text processing.)
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> 92912795 (.)
>>>>>>> laraxot/dev
```

## Best Practices

1. **MAI utilizzare percorsi assoluti hardcoded** nei componenti Blade
2. **SEMPRE utilizzare l'helper `asset()`** per riferirsi agli asset pubblici
3. **Implementare fallback** per le immagini che potrebbero non essere disponibili
4. **Verificare l'esistenza delle directory** prima di salvare nuovi asset
5. **Seguire le convenzioni di naming** per mantenere la coerenza
6. **Documentare i percorsi corretti** per evitare confusione

## Errori Comuni

<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
<<<<<<< HEAD
1. **Utilizzo del percorso Laravel public**: Utilizzare `/var/www/html/saluteora/laravel/public/` invece di `/var/www/html/saluteora/public_html/`
2. **Riferimenti diretti ai file**: Utilizzare percorsi assoluti invece dell'helper `asset()`
3. **Mancanza di fallback**: Non fornire alternative quando un'immagine non è disponibile
4. **Inconsistenza nei nomi dei file**: Utilizzare convenzioni di naming diverse per file simili
=======
<<<<<<< HEAD
<<<<<<< HEAD
=======
=======
<<<<<<< HEAD
>>>>>>> laraxot/dev
<<<<<<< HEAD
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
1. **Utilizzo del percorso Laravel public**: Utilizzare `[project-root]/laravel/public/` invece di `[project-root]/public_html/`
2. **Riferimenti diretti ai file**: Utilizzare percorsi assoluti invece dell'helper `asset()`
3. **Mancanza di fallback**: Non fornire alternative quando un'immagine non è disponibile
4. **Inconsistenza nei nomi dei file**: Utilizzare convenzioni di naming diverse per file simili
<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
=======
<<<<<<< HEAD
=======
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
1. **Utilizzo del percorso Laravel public**: Utilizzare `public/` invece di `public_html/`
1. **Utilizzo del percorso Laravel public**: Utilizzare `public/` invece di `public_html/`
1. **Utilizzo del percorso Laravel public**: Utilizzare `public/` invece di `public_html/`
1. **Utilizzo del percorso Laravel public**: Utilizzare `public/` invece di `public_html/`
1. **Utilizzo del percorso Laravel public**: Utilizzare `public/` invece di `public_html/`
1. **Utilizzo del percorso Laravel public**: Utilizzare `public/` invece di `public_html/`
2. **Riferimenti diretti ai file**: Utilizzare percorsi assoluti invece dell'helper `asset()`
3. **Mancanza di fallback**: Non fornire alternative quando un'immagine non è disponibile
4. **Inconsistenza nei nomi dei file**: Utilizzare convenzioni di naming diverse per file simili
# AVVISO IMPORTANTE (2025-05-13)

> **ATTENZIONE:** Tutti i componenti UI condivisi (come `logo.blade.php`) devono essere SEMPRE posizionati in `Modules/UI/resources/views/components/ui/` e MAI in `resources/views/components/`. Qualsiasi violazione di questa regola causa errori di rendering, override errati, problemi di modularità e manutenzione.
>
> **Errore riscontrato:** Il componente `logo.blade.php` era stato posizionato erroneamente in `resources/views/components/ui/` invece che in `Modules/UI/resources/views/components/ui/`.
>
> **Causa:** Dimenticanza della regola di modularità Laraxot: tutti i componenti Blade UI condivisi devono essere sempre nel modulo UI, mai nella root Laravel.
>
> **Soluzione:** Seguire SEMPRE la regola documentata qui sotto e aggiornata anche in README.md e nella root docs/links.md.

# Gestione dei Percorsi e degli Asset

## Collegamenti correlati
- [README modulo UI](/laravel/Modules/UI/docs/README.md)
- [Architettura Modulare](/laravel/Modules/UI/docs/architecture.md)
- [Collegamenti Documentazione](/docs/collegamenti-documentazione.md)

## Percorsi Corretti per gli Asset

### Struttura delle Directory

, è fondamentale rispettare la struttura corretta delle directory per gli asset pubblici:

```

├── laravel/                 # Applicazione Laravel (codice sorgente)
│   ├── Modules/             # Moduli dell'applicazione
│   ├── resources/           # Risorse non compilate
│   └── ...
└── public_html/             # Directory pubblica (web root)
    ├── images/              # Immagini pubbliche
    │   ├── avatars/         # Avatar utenti
    │   └── ...
    ├── css/                 # File CSS compilati
    ├── js/                  # File JavaScript compilati
    └── ...
```

### Percorsi Corretti vs Percorsi Errati

| Tipo di Asset | ✅ Percorso Corretto | ❌ Percorso Errato |
|---------------|---------------------|-------------------|
| Immagini | `public_html/images/` | `public/images/` |
| CSS | `public_html/css/` | `public/css/` |
| JavaScript | `public_html/js/` | `public/js/` |
| SVG | `public_html/images/` | `public/images/` |

## Utilizzo degli Asset nei Componenti Blade

### Helper `asset()`

Quando si fa riferimento agli asset nei componenti Blade, utilizzare sempre l'helper `asset()` che punta automaticamente alla directory pubblica corretta:

```php
<img src="{{ asset('images/avatars/default-1.svg') }}" alt="Avatar">
```

### Gestione dei Fallback

Per garantire una buona esperienza utente, implementare sempre un fallback per le immagini che potrebbero non essere disponibili:

```php
<img
    src="{{ asset('images/avatars/default-' . $avatarNumber . '.svg') }}"
    alt="{{ $user->name ?? 'User' }}"
    onerror="this.src='{{ asset('images/default-avatar.svg') }}'"
/>
```

## Componenti SVG

### SVG come Componenti Blade

Gli SVG utilizzati come icone o componenti UI dovrebbero essere implementati come componenti Blade in:

```
Themes/One/resources/views/components/ui/
```

### SVG come Asset Pubblici

Gli SVG utilizzati come immagini (avatar, loghi, ecc.) dovrebbero essere posizionati in:

```
public_html/images/
```

## Gestione dei Componenti UI

### Componente Avatar

Il componente avatar è implementato in:

```
Themes/One/resources/views/components/ui/avatar.blade.php
```

E utilizza gli avatar SVG dalla directory pubblica:

```
public_html/images/avatars/
```

### Componente Icon

Il componente icon è implementato in:

```
Themes/One/resources/views/components/ui/icon.blade.php
```

E include le definizioni SVG direttamente nel componente.

## Regola sui Componenti Blade UI

> **IMPORTANTE:** Tutti i componenti Blade UI condivisi (es. logo, button, badge, ecc.) devono essere posizionati esclusivamente in:
>
> `Modules/UI/resources/views/components/ui/`
>
> **MAI** in `resources/views/components/ui/` della root Laravel.

### Motivazione
- Garantisce la modularità e la possibilità di override a livello di modulo
- Evita conflitti e duplicazioni tra moduli e root
- Permette una gestione centralizzata e documentata dei componenti UI
- Segue la filosofia Laraxot di separazione delle responsabilità

### Esempio di errore e correzione

**❌ Errato:**
```
resources/views/components/ui/logo.blade.php
```
**✅ Corretto:**
```
Modules/UI/resources/views/components/ui/logo.blade.php
```

## Best Practices

1. **MAI utilizzare percorsi assoluti hardcoded** nei componenti Blade
2. **SEMPRE utilizzare l'helper `asset()`** per riferirsi agli asset pubblici
3. **Implementare fallback** per le immagini che potrebbero non essere disponibili
4. **Verificare l'esistenza delle directory** prima di salvare nuovi asset
5. **Seguire le convenzioni di naming** per mantenere la coerenza
6. **Documentare i percorsi corretti** per evitare confusione

## Errori Comuni

1. **Utilizzo del percorso Laravel public**: Utilizzare `public/` invece di `public_html/`
2. **Riferimenti diretti ai file**: Utilizzare percorsi assoluti invece dell'helper `asset()`
3. **Mancanza di fallback**: Non fornire alternative quando un'immagine non è disponibile
4. **Inconsistenza nei nomi dei file**: Utilizzare convenzioni di naming diverse per file simili
<<<<<<< HEAD
=======
<<<<<<< HEAD
>>>>>>> laraxot/dev
1. **Utilizzo del percorso Laravel public**: Utilizzare `public/` invece di `public_html/`
2. **Riferimenti diretti ai file**: Utilizzare percorsi assoluti invece dell'helper `asset()`
3. **Mancanza di fallback**: Non fornire alternative quando un'immagine non è disponibile
4. **Inconsistenza nei nomi dei file**: Utilizzare convenzioni di naming diverse per file simili
# AVVISO IMPORTANTE (2025-05-13)
> **ATTENZIONE:** Tutti i componenti UI condivisi (come `logo.blade.php`) devono essere SEMPRE posizionati in `Modules/UI/resources/views/components/ui/` e MAI in `resources/views/components/`. Qualsiasi violazione di questa regola causa errori di rendering, override errati, problemi di modularità e manutenzione.
>
> **Errore riscontrato:** Il componente `logo.blade.php` era stato posizionato erroneamente in `resources/views/components/ui/` invece che in `Modules/UI/resources/views/components/ui/`.
> **Causa:** Dimenticanza della regola di modularità Laraxot: tutti i componenti Blade UI condivisi devono essere sempre nel modulo UI, mai nella root Laravel.
> **Soluzione:** Seguire SEMPRE la regola documentata qui sotto e aggiornata anche in README.md e nella root docs/links.md.
# Gestione dei Percorsi e degli Asset
## Collegamenti correlati
- [README modulo UI](/laravel/Modules/UI/docs/README.md)
- [Architettura Modulare](/laravel/Modules/UI/docs/architecture.md)
- [Collegamenti Documentazione](/docs/collegamenti-documentazione.md)
## Percorsi Corretti per gli Asset
### Struttura delle Directory
, è fondamentale rispettare la struttura corretta delle directory per gli asset pubblici:
```
├── laravel/                 # Applicazione Laravel (codice sorgente)
│   ├── Modules/             # Moduli dell'applicazione
│   ├── resources/           # Risorse non compilate
│   └── ...
└── public_html/             # Directory pubblica (web root)
    ├── images/              # Immagini pubbliche
    │   ├── avatars/         # Avatar utenti
    ├── css/                 # File CSS compilati
    ├── js/                  # File JavaScript compilati
    └── ...
### Percorsi Corretti vs Percorsi Errati
| Tipo di Asset | ✅ Percorso Corretto | ❌ Percorso Errato |
|---------------|---------------------|-------------------|
| Immagini | `public_html/images/` | `public/images/` |
| CSS | `public_html/css/` | `public/css/` |
| JavaScript | `public_html/js/` | `public/js/` |
| SVG | `public_html/images/` | `public/images/` |
## Utilizzo degli Asset nei Componenti Blade
### Helper `asset()`
Quando si fa riferimento agli asset nei componenti Blade, utilizzare sempre l'helper `asset()` che punta automaticamente alla directory pubblica corretta:
```php
<img src="{{ asset('images/avatars/default-1.svg') }}" alt="Avatar">
### Gestione dei Fallback
Per garantire una buona esperienza utente, implementare sempre un fallback per le immagini che potrebbero non essere disponibili:
<img
    src="{{ asset('images/avatars/default-' . $avatarNumber . '.svg') }}"
    alt="{{ $user->name ?? 'User' }}"
    onerror="this.src='{{ asset('images/default-avatar.svg') }}'"
/>
## Componenti SVG
### SVG come Componenti Blade
Gli SVG utilizzati come icone o componenti UI dovrebbero essere implementati come componenti Blade in:
Themes/One/resources/views/components/ui/
### SVG come Asset Pubblici
Gli SVG utilizzati come immagini (avatar, loghi, ecc.) dovrebbero essere posizionati in:
public_html/images/
## Gestione dei Componenti UI
### Componente Avatar
Il componente avatar è implementato in:
Themes/One/resources/views/components/ui/avatar.blade.php
E utilizza gli avatar SVG dalla directory pubblica:
public_html/images/avatars/
### Componente Icon
Il componente icon è implementato in:
Themes/One/resources/views/components/ui/icon.blade.php
E include le definizioni SVG direttamente nel componente.
## Regola sui Componenti Blade UI
> **IMPORTANTE:** Tutti i componenti Blade UI condivisi (es. logo, button, badge, ecc.) devono essere posizionati esclusivamente in:
> `Modules/UI/resources/views/components/ui/`
> **MAI** in `resources/views/components/ui/` della root Laravel.
### Motivazione
- Garantisce la modularità e la possibilità di override a livello di modulo
- Evita conflitti e duplicazioni tra moduli e root
- Permette una gestione centralizzata e documentata dei componenti UI
- Segue la filosofia Laraxot di separazione delle responsabilità
### Esempio di errore e correzione
**❌ Errato:**
resources/views/components/ui/logo.blade.php
**✅ Corretto:**
Modules/UI/resources/views/components/ui/logo.blade.php
## Best Practices
1. **MAI utilizzare percorsi assoluti hardcoded** nei componenti Blade
2. **SEMPRE utilizzare l'helper `asset()`** per riferirsi agli asset pubblici
3. **Implementare fallback** per le immagini che potrebbero non essere disponibili
4. **Verificare l'esistenza delle directory** prima di salvare nuovi asset
5. **Seguire le convenzioni di naming** per mantenere la coerenza
6. **Documentare i percorsi corretti** per evitare confusione
## Errori Comuni
<<<<<<< HEAD
=======
=======
<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
=======
1. **Utilizzo del percorso Laravel public**: Utilizzare `/var/www/html/saluteora/laravel/public/` invece di `/var/www/html/saluteora/public_html/`
2. **Riferimenti diretti ai file**: Utilizzare percorsi assoluti invece dell'helper `asset()`
3. **Mancanza di fallback**: Non fornire alternative quando un'immagine non è disponibile
4. **Inconsistenza nei nomi dei file**: Utilizzare convenzioni di naming diverse per file simili
>>>>>>> f6fcbb6f (Fix merge conflict in .gitattributes by removing redundant lines and ensuring proper exclusion of image formats from text processing.)
<<<<<<< HEAD
=======
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> 92912795 (.)
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
