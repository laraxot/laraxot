# Componenti dei Temi

## Panoramica

Il sistema dei temi utilizza componenti Blade per garantire la coerenza dell'interfaccia utente attraverso diversi temi. Ogni tema deve implementare i componenti base per garantire la compatibilità con il sistema CMS.

## ⚠️ REGOLA CRITICA: Namespace pub_theme::

### Il tema attivo e l'alias dinamico

Il tema attivo è configurato in `laravel/config/local/techplanner/xra.php`:

```php
'pub_theme' => 'Sixteen',
```

**FONDAMENTALE**: Il namespace `pub_theme::` è un **alias dinamico** che punta automaticamente al tema attivo.

### Come Funziona l'Alias

1. **Configurazione**: `'pub_theme' => 'Sixteen'` definisce il tema attivo
2. **Registrazione**: Il `ThemeServiceProvider` di ogni tema registra le sue view con namespace `pub_theme`
3. **Risoluzione**: Laravel risolve automaticamente `pub_theme::` → `Themes/{TemaAttivo}/resources/views/`

### Portabilità dei Temi

**SEMPRE utilizzare `pub_theme::`** per garantire portabilità:

```json
// ✅ CORRETTO - Funziona con qualsiasi tema
"view": "pub_theme::components.blocks.navigation.simple"

// ❌ ERRATO - Funziona solo con tema specifico  
"view": "sixteen::components.blocks.navigation.simple"
```

### Benefici dell'Alias Dinamico

- **Intercambiabilità**: Cambiare tema senza modificare JSON
- **Consistenza**: Un singolo set di configurazioni per tutti i temi
- **Manutenibilità**: Aggiornamenti centralizzati
- **Scalabilità**: Facile aggiungere nuovi temi

## Componenti Obbligatori

### Navigation Components

Tutti i temi devono implementare i seguenti componenti di navigazione:

#### `components/blocks/navigation/simple.blade.php`

Componente di navigazione semplice utilizzato dai blocchi CMS.

**Posizione nei temi:**
- `Themes/Sixteen/resources/views/components/blocks/navigation/simple.blade.php` ✅
- `Themes/Two/resources/views/components/blocks/navigation/simple.blade.php` ✅
- `Themes/Zero/resources/views/components/blocks/navigation/simple.blade.php` ✅

**Interfaccia Standardizzata:**
```php
$data = [
    'title' => 'Titolo della navigazione',     // opzionale
    'brand' => 'Brand Name',                   // opzionale (priorità su title in Sixteen)
    'logo' => '/path/to/logo.png',             // opzionale (solo Sixteen)
    'menu_items' => [                          // opzionale
        [
            'label' => 'Menu Item',            // testo da visualizzare
            'title' => 'Alternative Label',    // fallback per label
            'url' => '/path',                  // link di destinazione
            'external' => false,               // link esterno (target="_blank")
            'active' => false,                 // stato attivo (evidenziato)
        ],
        // ... altri item
    ],
];
```

**Note sull'Interfaccia:**
- Tutti i temi ora usano la stessa struttura `$data`
- `brand` ha priorità su `title` nel tema Sixteen
- `logo` è supportato solo nel tema Sixteen
- Fallback automatici per menu vuoti o mancanti
- Supporto per traduzioni tramite `@lang('pub_theme::navigation.*')`

## Struttura dei Componenti per Tema

### Tema Sixteen (Attivo)
- Design moderno con Tailwind CSS
- Supporto per responsive design
- Menu mobile con hamburger button
- Supporto completo per logo e branding

### Tema Two
- Design pulito e minimalista
- Supporto per dark mode
- Navigazione orizzontale semplice
- Utilizzo di Tailwind CSS

### Tema Zero (Fallback)
- Design base con CSS inline
- Compatibilità massima
- Stili embedded per indipendenza
- Fallback per situazioni di emergenza

## Risoluzione dei Problemi

### Errore "view not found"

Se si riceve l'errore `view not found: pub_theme::components.blocks.navigation.simple`:

1. **Verifica CmsServiceProvider**: Assicurati che `Modules\Cms\Providers\CmsServiceProvider::class` sia registrato in `config/app.php`
2. **Verifica configurazione**: Controlla che `register_pub_theme` sia `true` in `laravel/config/local/techplanner/xra.php`
3. **Verifica tema attivo**: Controlla che `pub_theme` punti al tema corretto (es. 'Sixteen')
4. **Verifica esistenza file**: Assicurati che il componente esista nel tema attivo
5. **Cache**: Pulisci la cache delle view con `php artisan view:clear`

**Nota**: Il namespace `pub_theme::` viene registrato automaticamente dal `CmsServiceProvider` quando `register_pub_theme` è abilitato.

### Aggiunta di Nuovi Componenti

Per aggiungere un nuovo componente:

1. **Crea il componente nel tema attivo**
2. **Replica nei altri temi** per garantire compatibilità
3. **Documenta** il componente in questo file
4. **Aggiorna** i test se necessario

## Best Practices

### Compatibilità tra Temi
- Utilizzare gli stessi nomi di variabili `$data`
- Implementare fallback per dati mancanti
- Testare con tutti i temi disponibili

### Accessibilità
- Utilizzare attributi ARIA appropriati
- Fornire alternative testuali
- Garantire navigazione da tastiera

### Performance
- Minimizzare l'uso di CSS inline (eccetto tema Zero)
- Utilizzare classi CSS riutilizzabili
- Ottimizzare le immagini

## Changelog

### 2024-01-XX - Standardizzazione Componente Navigation Simple
- **Problema**: Errore `view not found: pub_theme::components.blocks.navigation.simple`
- **Causa**: Inconsistenza nell'interfaccia tra i temi (Sixteen usava `$items`, altri usavano `$data['menu_items']`)
- **Soluzione**: 
  - Standardizzato tema Sixteen per usare `$data['menu_items']`
  - Migliorato supporto per branding e logo in Sixteen
  - Aggiunto supporto per traduzioni con `@lang()`
  - Aggiunta documentazione dell'interfaccia standardizzata
  - Implementati fallback consistenti per tutti i temi

### 2024-01-XX - Fix Widget Filament nell'Header
- **Problema**: Errore `Using $this when not in object context` in `vendor/filament/widgets/resources/views/components/widget.blade.php`
- **Causa**: Uso improprio di widget Filament nel tema (`<x-filament-widgets::widget>`) invece di componenti Livewire
- **Prima Soluzione Temporanea**: 
  - Rimosso widget Filament e sostituito con componenti Blade nativi
  - Aggiunto JavaScript per dark mode toggle con localStorage
- **Soluzione Definitiva**: 
  - Utilizzare componenti Livewire correttamente registrati:
    - `@livewire("ui::dark-mode-switcher")` per il dark mode
    - `@livewire("lang::lang.switcher")` per il language switcher
  - I componenti sono auto-registrati da XotBaseServiceProvider
  - Mantengono funzionalità complete con persistenza e localizzazione

## File di Traduzione

Ogni tema deve avere i propri file di traduzione per la navigazione:

### Struttura File di Traduzione
```
Themes/{ThemeName}/lang/
├── it/
│   ├── navigation.php
│   └── ui.php
├── en/
│   ├── navigation.php
│   └── ui.php
└── de/
    ├── navigation.php
    └── ui.php
```

### Chiavi di Traduzione Standard

#### navigation.php
```php
return [
    'main' => 'Navigazione principale', // per aria-label
    'site_title' => 'TechPlanner',      // fallback per title/brand
    'home' => 'Home',                   // link predefinito
    'about' => 'Chi siamo',             // link predefinito
    'contact' => 'Contatti',            // link predefinito
    'services' => 'Servizi',            // link opzionale
    'portfolio' => 'Portfolio',         // link opzionale
    'news' => 'Notizie',               // link opzionale
    'blog' => 'Blog',                  // link opzionale
];
```

#### ui.php
```php
return [
    'dark_mode_toggle' => 'Cambia modalità scura',
    'light_mode' => 'Modalità chiara',
    'dark_mode' => 'Modalità scura',
    'language_switcher' => 'Cambia lingua',
    'search' => 'Cerca',
    'menu' => 'Menu',
    'close' => 'Chiudi',
    'open' => 'Apri',
];
```

## Collegamenti

- [Architettura del Progetto](./architecture.md)
- [Configurazione Temi](../laravel/config/local/techplanner/xra.php)
- [Modulo CMS](../laravel/Modules/Cms/)
- [Tema Sixteen](../laravel/Themes/Sixteen/)
- [Tema Two](../laravel/Themes/Two/)
- [Tema Zero](../laravel/Themes/Zero/)
- [File di Traduzione Sixteen](../laravel/Themes/Sixteen/lang/)
- [File di Traduzione Two](../laravel/Themes/Two/lang/)
- [File di Traduzione Zero](../laravel/Themes/Zero/lang/)
