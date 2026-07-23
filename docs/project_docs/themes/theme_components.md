# Componenti dei Temi

## Panoramica

Il sistema dei temi utilizza componenti Blade per garantire la coerenza dell'interfaccia utente attraverso diversi temi. Ogni tema deve implementare i componenti base per garantire la compatibilità con il sistema CMS.

## Tema Attivo

Il tema attivo è configurato in `laravel/config/local/techplanner/xra.php`:

```php
'pub_theme' => 'Sixteen',
```

Il namespace `pub_theme::` punta automaticamente al tema attivo.

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

## ⚠️ REGOLA CRITICA: Traduzioni nei Temi

### Namespace pub_theme:: per Traduzioni

**SEMPRE utilizzare `@lang('pub_theme::')` invece di `{{ __() }}` nelle view dei temi**

#### ✅ Pattern Corretto

```blade
{{-- In Themes/Sixteen/resources/views/filament/widgets/auth/login.blade.php --}}
<h2 class="text-2xl font-extrabold">
    @lang('pub_theme::auth.login.title')
</h2>
<p class="text-sm text-gray-600">
    @lang('pub_theme::auth.login.subtitle')
</p>
<span>@lang('pub_theme::auth.login.or')</span>
<a href="/register">
    @lang('pub_theme::auth.login.create_account')
</a>
```

#### ❌ Anti-Pattern (da evitare)

```blade
{{-- MAI usare nei temi --}}
<h2>{{ __('auth.login.title') }}</h2>
<p>{{ __('auth.login.subtitle') }}</p>
<span>{{ __('auth.login.or') }}</span>
```

### Motivazione Architettonica

1. **Namespace Dinamico**: `pub_theme::` punta automaticamente al tema attivo
2. **Isolamento**: Ogni tema ha le sue traduzioni specifiche
3. **Portabilità**: Cambiare tema senza modificare le traduzioni
4. **Fallback**: Sistema di fallback automatico tra temi
5. **Manutenibilità**: Traduzioni centralizzate per tema

### Struttura File di Traduzione

```
Themes/Sixteen/lang/
├── it/
│   ├── auth.php          # Traduzioni autenticazione
│   ├── navigation.php    # Traduzioni navigazione
│   └── ui.php           # Traduzioni interfaccia
├── en/
│   ├── auth.php
│   ├── navigation.php
│   └── ui.php
└── de/
    ├── auth.php
    ├── navigation.php
    └── ui.php
```

### Registrazione Traduzioni nel ServiceProvider

Il `ThemeServiceProvider` registra automaticamente le traduzioni:

```php
// In ThemeServiceProvider::boot()
$this->loadTranslationsFrom(__DIR__ . '/../../lang', 'pub_theme');
```

### Traduzioni Mancanti - Implementazione Completa

Per il file `login.blade.php` sono necessarie queste traduzioni aggiuntive:

```php
// Themes/Sixteen/lang/it/auth.php
'login' => [
    'subtitle' => 'Inserisci le tue credenziali per continuare',
    'submitting' => 'Accesso in corso...',
    // ... altre traduzioni esistenti
],
```

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

### Errore "view not found: pub_theme::components.blocks.navigation.simple"

**Sintomi**: 
- Errore `view not found: [pub_theme::components.blocks.navigation.simple] path: [/path/to/view]`
- L'errore si verifica durante il caricamento della pagina
- Il percorso mostrato nell'errore è corretto e il file esiste

**Diagnosi Rapida**:
```bash
# Verifica che la view esista
php artisan tinker --execute="dd(view()->exists('pub_theme::components.blocks.navigation.simple'));"

# Verifica namespace registrati
php artisan tinker --execute="dd(view()->getFinder()->getHints()['pub_theme'] ?? 'NOT_FOUND');"
```

**Soluzioni in Ordine di Priorità**:

1. **Cache (SOLUZIONE PRINCIPALE)**: 
   ```bash
   php artisan view:clear
   php artisan config:clear
   php artisan cache:clear
   composer dump-autoload
   ```

2. **Verifica tema attivo**: Controlla `laravel/config/local/techplanner/xra.php`
   ```php
   'pub_theme' => 'Sixteen',
   ```

3. **Verifica esistenza file**: Assicurati che il componente esista nel tema attivo
   ```bash
   ls -la "laravel/Themes/Sixteen/resources/views/components/blocks/navigation/simple.blade.php"
   ```

4. **Verifica ServiceProvider**: Controlla che `ThemeServiceProvider` sia registrato in `composer.json`
   ```json
   "extra": {
       "laravel": {
           "providers": [
               "Themes\\Sixteen\\Providers\\ThemeServiceProvider"
           ]
       }
   }
   ```

**Causa Radice**: 
Il problema si verifica quando il `BlockData` viene istanziato prima che tutti i service provider abbiano completato il processo di bootstrap, causando un controllo `view()->exists()` che fallisce temporaneamente.

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

### 2025-01-06 - Fix Errore "view not found" per pub_theme namespace
- **Problema**: `Exception: view not found: [pub_theme::components.blocks.navigation.simple]`
- **Causa**: Cache delle view corrotte che impedivano la risoluzione del namespace `pub_theme::`
- **Soluzione**: 
  - Pulito cache view, config e autoload
  - Verificato registrazione corretta ThemeServiceProvider
  - Aggiunta documentazione diagnostica completa
  - Identificata causa radice nel timing di caricamento service provider
- **Comando risoluzione**: `php artisan view:clear && php artisan config:clear && php artisan cache:clear && composer dump-autoload`

### 2024-01-XX - Standardizzazione Componente Navigation Simple
- **Problema**: Errore `view not found: pub_theme::components.blocks.navigation.simple`
- **Causa**: Inconsistenza nell'interfaccia tra i temi (Sixteen usava `$items`, altri usavano `$data['menu_items']`)
- **Soluzione**: 
  - Standardizzato tema Sixteen per usare `$data['menu_items']`
  - Migliorato supporto per branding e logo in Sixteen
  - Aggiunto supporto per traduzioni con `@lang()`
  - Aggiunta documentazione dell'interfaccia standardizzata
  - Implementati fallback consistenti per tutti i temi

## Collegamenti

- [Architettura del Progetto](./architecture.md)
- [Configurazione Temi](../laravel/config/local/techplanner/xra.php)
- [Modulo CMS](../laravel/Modules/Cms/)
- [Tema Sixteen](../laravel/Themes/Sixteen/)
- [Tema Two](../laravel/Themes/Two/)
- [Tema Zero](../laravel/Themes/Zero/)
