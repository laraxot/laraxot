# Fix php artisan optimize - Report

## Data: 2026-02-07

## Errori Risolti

### 1. Conflitto Route `pages.blog`

**Problema**: Due route con lo stesso nome `pages.blog` causavano errore di serializzazione.

**File**: `Modules/TechPlanner/routes/web.php`

**Fix**: Cambiato nome route da `pages.blog` a `techplanner.blog` (e altre route simili).

**Note**: Per il frontend usiamo Folio+Volt+Laraxot, quindi le route tradizionali non sono necessarie, ma il conflitto bloccava `php artisan optimize`.

---

### 2. Conflitto Namespace `pub_theme` tra Temi

**Problema**: Entrambi i temi (Two e Sixteen) registravano il namespace `pub_theme`, causando conflitti nella risoluzione dei componenti Blade.

**File modificati**:
- `Themes/Two/app/Providers/ThemeServiceProvider.php`
- `Themes/Sixteen/app/Providers/ThemeServiceProvider.php` (pulizia codice)

**Fix**: Cambiato namespace del tema Two da `pub_theme` a `two`, lasciando `pub_theme` solo al tema attivo (Sixteen).

```php
// Prima (Theme Two)
$this->loadViewsFrom(__DIR__ . '/../../resources/views', 'pub_theme');

// Dopo (Theme Two)
$this->loadViewsFrom(__DIR__ . '/../../resources/views', 'two');
```

---

## Comandi Eseguiti

```bash
cd /var/www/html/ptvx/laravel
php artisan view:clear
php artisan cache:clear
php artisan optimize
```

**Risultato**: ✅ Completato con successo

---

## Componenti Verificati

Tutti i componenti vengono ora risolti correttamente:
- `pub_theme::ui.hero`
- `pub_theme::navigation.bottom-nav`
- `pub_theme::blocks.navigation.header-slim`
- `two::*` (per il tema Two)

---

## Best Practices per Temi Multipli

1. **Namespace Unici**: Ogni tema deve usare un namespace univoco (non `pub_theme` condiviso)
2. **Tema Attivo**: Solo il tema attivo dovrebbe usare `pub_theme` per compatibilità
3. **Folio+Volt**: Per il frontend preferire Folio+Volt invece delle route Laravel tradizionali

---

## Collegamenti

- [Tema Sixteen - Componenti](../../Themes/Sixteen/docs/components.md)
- [Tema Two - Componenti](../../Themes/Two/docs/components.md)
- [Configurazione Tema](../../../config/local/techplanner/xot.php)

---

*Documentazione generata automaticamente dopo fix*
