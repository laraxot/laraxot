# Regola Critica: Namespace pub_theme:: nei Temi Laraxot

## ⚠️ ERRORE CRITICO DA NON RIPETERE

### Descrizione dell'Errore
Utilizzo di namespace specifico del tema (`sixteen::`, `two::`, `zero::`) invece dell'alias dinamico `pub_theme::` nelle configurazioni CMS e nei componenti.

### Regola Fondamentale

**SEMPRE utilizzare `pub_theme::`** per le view nei temi, **MAI** il namespace specifico del tema.

## Esempi

### ✅ CORRETTO
```json
{
    "view": "pub_theme::components.blocks.hero.main",
    "view": "pub_theme::components.blocks.navigation.simple",
    "view": "pub_theme::layouts.app"
}
```

```blade
@include('pub_theme::components.header')
<x-pub_theme::button>Click me</x-pub_theme::button>
@lang('pub_theme::navigation.home')
```

### ❌ ERRATO
```json
{
    "view": "sixteen::components.blocks.hero.main",
    "view": "two::components.blocks.navigation.simple",
    "view": "zero::layouts.app"
}
```

```blade
@include('sixteen::components.header')
<x-sixteen::button>Click me</x-sixteen::button>
@lang('sixteen::navigation.home')
```

## Architettura del Sistema

### 1. Configurazione Tema Attivo
```php
// config/local/techplanner/xra.php
'pub_theme' => 'Sixteen',  // Tema attualmente attivo
```

### 2. Registrazione Namespace
```php
// Themes/Sixteen/app/Providers/ThemeServiceProvider.php
public function boot(): void
{
    // Registra le view con namespace pub_theme (NON sixteen)
    $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'pub_theme');
    $this->loadTranslationsFrom(__DIR__ . '/../../lang', 'pub_theme');
}
```

### 3. Risoluzione Automatica
```
pub_theme::components.blocks.hero.main
    ↓ (risolve automaticamente a)
Themes/Sixteen/resources/views/components/blocks/hero/main.blade.php
```

## Benefici dell'Alias Dinamico

### 1. Portabilità Completa
- Un singolo JSON funziona con tutti i temi
- Cambio tema senza modificare configurazioni
- Consistenza tra ambienti diversi

### 2. Manutenibilità
- Aggiornamenti centralizzati
- Nessuna duplicazione di configurazioni
- Refactoring semplificato

### 3. Scalabilità
- Facile aggiungere nuovi temi
- Standardizzazione dell'interfaccia
- Compatibilità garantita

## Controlli di Prevenzione

### Checklist Prima di Creare View
- [ ] La view usa `pub_theme::` invece del nome del tema?
- [ ] Il JSON usa `pub_theme::` per tutte le view?
- [ ] Le traduzioni usano `pub_theme::` invece del tema specifico?
- [ ] I componenti Blade usano l'alias corretto?

### Validazione Automatica
```bash
# Cerca utilizzi errati di namespace specifici nei JSON
grep -r "sixteen::" config/local/techplanner/database/content/
grep -r "two::" config/local/techplanner/database/content/
grep -r "zero::" config/local/techplanner/database/content/

# Deve restituire risultati vuoti
```

## Casi d'Uso Specifici

### CMS Blocks
```json
{
    "type": "hero",
    "data": {
        "view": "pub_theme::components.blocks.hero.main"  // ✅ SEMPRE così
    }
}
```

### Blade Templates
```blade
{{-- ✅ CORRETTO --}}
@include('pub_theme::components.navigation')
<x-pub_theme::card>Content</x-pub_theme::card>

{{-- ❌ ERRATO --}}
@include('sixteen::components.navigation')
<x-sixteen::card>Content</x-sixteen::card>
```

### Traduzioni
```blade
{{-- ✅ CORRETTO --}}
{{ __('pub_theme::navigation.home') }}

{{-- ❌ ERRATO --}}
{{ __('sixteen::navigation.home') }}
```

## Implementazione nei Temi

### Ogni tema DEVE registrare con pub_theme
```php
// ThemeServiceProvider di OGNI tema
public function boot(): void
{
    parent::boot();
    
    // CRITICO: Usare 'pub_theme' come namespace
    $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'pub_theme');
    $this->loadTranslationsFrom(__DIR__ . '/../../lang', 'pub_theme');
}
```

### Struttura Directory Standardizzata
```
Themes/{NomeTema}/
└── resources/
    └── views/
        └── components/
            └── blocks/          # Blocchi CMS con namespace pub_theme::
                ├── hero/
                ├── features/
                ├── stats/
                └── cta/
```

## Risoluzione di Errori Comuni

### "View not found: pub_theme::..."
**Causa**: CmsServiceProvider non registrato nell'applicazione
**Soluzione**: 
1. Verificare che `Modules\Cms\Providers\CmsServiceProvider::class` sia presente in `config/app.php`
2. Verificare che `register_pub_theme` sia `true` in `config/local/techplanner/xra.php`
3. Pulire cache con `php artisan view:clear`

### "View not found: sixteen::..."
**Causa**: Utilizzo di namespace specifico del tema
**Soluzione**: Cambiare in `pub_theme::`

### "Translation key not found"
**Causa**: Utilizzo di namespace specifico per traduzioni
**Soluzione**: Usare `pub_theme::` per tutte le traduzioni del tema

### Blocchi CMS non renderizzati
**Causa**: View path errato nei JSON
**Soluzione**: Verificare che tutti i JSON usino `pub_theme::`

## Responsabilità degli Sviluppatori

### Durante lo Sviluppo
1. **SEMPRE** verificare che si stia usando `pub_theme::`
2. **MAI** utilizzare namespace specifici del tema nei JSON
3. **TESTARE** con cambio tema per verificare portabilità
4. **DOCUMENTARE** ogni nuovo tipo di blocco con namespace corretto

### Durante il Review
1. Verificare namespace in tutti i file JSON
2. Controllare view path nei blocchi CMS
3. Validare traduzioni e componenti Blade
4. Testare con temi diversi se possibile

## Memoria per il Futuro

**Questo documento serve come promemoria permanente per evitare errori di namespace nei temi.**

Ogni volta che si lavora con temi e CMS, consultare questa regola per garantire l'uso corretto di `pub_theme::`.

---

## Changelog Errori Risolti

### 2025-01-06 - Errore "view not found: pub_theme::components.blocks.navigation.simple"
**Causa**: `CmsServiceProvider` non registrato in `config/app.php`
**Impatto**: Namespace `pub_theme::` non risolto, blocchi CMS non funzionanti
**Soluzione**: Aggiunto `Modules\Cms\Providers\CmsServiceProvider::class` in `config/app.php`
**Prevenzione**: Verificare sempre la registrazione dei ServiceProvider dei moduli core

### 2025-01-06 - Utilizzo errato di namespace specifici
**Causa**: Utilizzo di `sixteen::` invece di `pub_theme::` nei blocchi CMS
**Soluzione**: Standardizzazione su `pub_theme::` per tutti i riferimenti ai temi

**Motto**: "pub_theme:: è dinamico, sixteen:: è statico - usa sempre il dinamico!"

## Collegamenti

- [CMS System Documentation](./cms_system.md)
- [Theme Components](./theme_components.md)
- [Configurazione Tema](../laravel/config/local/techplanner/xra.php)
- [ThemeServiceProvider Sixteen](../laravel/Themes/Sixteen/app/Providers/ThemeServiceProvider.php)
- [CmsServiceProvider](../laravel/Modules/Cms/app/Providers/CmsServiceProvider.php)

*Ultimo aggiornamento: Gennaio 2025*