# Folio Mount Error Fix - 404 Pages Not Found

> **Risolto: Pagine Folio 404 perché `mount()` non esiste come funzione**

## 📋 Panoramica

**Data:** 2026-04-01  
**Stato:** ✅ Risolto  
**Tipo:** Errore routing Folio - Funzione inesistente

---

## 🐛 Problema

```
URL: http://127.0.0.1:8000/it/tests/homepage
Errore: 404 Not Found

Le pagine esistono ma Folio non le sta servendo!
```

---

## 🔍 Analisi

### Causa Principale

**File:** `Themes/Sixteen/app/Providers/ThemeServiceProvider.php`

```php
// ❌ SBAGLIATO: La funzione mount() NON esiste!
use function Laravel\Folio\mount;

mount(__DIR__.'/../../resources/views/pages');
```

**Perché è sbagliato:**
- `mount()` **NON è una funzione PHP** in Laravel Folio
- È un metodo della facade `Folio`
- L'import `use function Laravel\Folio\mount;` fallisce

---

## ✅ Soluzione

### Correzione

**File corretto:** `Themes/Sixteen/app/Providers/ThemeServiceProvider.php`

```php
// ✅ CORRETTO: Usa la facade Folio
use Laravel\Folio\Folio;

protected function mountFolioPages(): void
{
    Folio::path(__DIR__.'/../../resources/views/pages');
}
```

**Perché funziona:**
- ✅ `Folio::path()` è il metodo corretto
- ✅ La facade `Folio` esiste ed è registrata
- ✅ Le pagine vengono montate correttamente

---

## 📊 Confronto

### PRIMA (SBAGLIATO)

```php
use function Laravel\Folio\mount;  // ❌ Funzione inesistente

protected function mountFolioPages(): void
{
    mount(__DIR__.'/../../resources/views/pages');  // ❌ Error!
}
```

**Risultato:**
- ❌ Pagine 404
- ❌ Rotte non registrate
- ❌ Folio non montato

---

### DOPO (CORRETTO)

```php
use Laravel\Folio\Folio;  // ✅ Facade esistente

protected function mountFolioPages(): void
{
    Folio::path(__DIR__.'/../../resources/views/pages');  // ✅ Works!
}
```

**Risultato:**
- ✅ Pagine funzionano
- ✅ Rotte registrate
- ✅ Folio montato correttamente

---

## 🎯 API Folio Corretta

### Metodi Disponibili

```php
use Laravel\Folio\Folio;

// Mount pages
Folio::path('/path/to/pages');

// Mount with middleware
Folio::path('/path/to/pages')->middleware(['web', 'auth']);

// Mount with domain
Folio::path('/path/to/pages')->domain('admin.example.com');

// Get mount paths
$paths = Folio::mountPaths();

// Get data
$data = Folio::data();
```

---

## 🧪 Verifica

### 1. Controlla Rotte

```bash
cd /var/www/_bases/base_fixcity_fila5/laravel
php artisan route:list --path="tests"
```

**Atteso:**
```
GET /it/tests ........ tests.index
GET /it/tests/{slug} . tests.view
```

---

### 2. Test Pagina

```
http://127.0.0.1:8000/it/tests/homepage
```

**Atteso:** Homepage visibile con header, navigation, hero, footer

---

### 3. Debug Mount

```php
// In tinker
php artisan tinker
>>> use Laravel\Folio\Folio;
>>> Folio::mountPaths();
=> [
     "/var/www/.../Themes/Sixteen/resources/views/pages"
   ]
```

---

## 📚 Documentazione Ufficiale

### Laravel Folio

- **Docs:** https://laravel.com/docs/folio
- **Installation:** `composer require laravel/folio`
- **Usage:** Page-based routing per Laravel

### Mounting Pages

```php
// In AppServiceProvider o ThemeServiceProvider
use Laravel\Folio\Folio;

public function boot(): void
{
    Folio::path(resource_path('views/pages'));
}
```

---

## 🔗 Documenti Correlati

- [Homepage 404 Fix](./HOMEPAGE_404_FIX.md) - Risoluzione errore JSON mancante
- [Component Namespace](./architecture/component-namespace.md) - Namespace componenti
- [Layout Architecture](./architecture/layout-architecture.md) - Gerarchia layout

---

## 🎓 Lezioni Apprese

### 1. Funzioni vs Facade

```php
// ❌ SBAGLIATO: Assumere che esista una funzione
use function Laravel\Folio\mount;
mount('/path');

// ✅ CORRETTO: Usare la facade
use Laravel\Folio\Folio;
Folio::path('/path');
```

---

### 2. Verificare API Prima di Usare

```bash
# Controlla se una funzione esiste
php artisan tinker
>>> function_exists('Laravel\Folio\mount')
=> false

# Controlla metodi facade
>>> Laravel\Folio\Folio::getFacadeRoot()
=> Laravel\Folio\FolioManager { ... }

>>> get_class_methods(Laravel\Folio\Folio::getFacadeRoot())
=> ['path', 'middleware', 'domain', 'mountPaths', ...]
```

---

### 3. Theme Service Provider

```php
// I temi Laravel DEVONO montare le pagine Folio nel boot()
class ThemeServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // 1. Carica risorse
        $this->loadViewsFrom(...);
        
        // 2. Mount Folio pages
        Folio::path(...);
        
        // 3. Altre configurazioni
        ...
    }
}
```

---

## ✅ Checklist Debug Folio 404

### Verifiche Iniziali

```markdown
## File Existence
- [ ] Pagina esiste: pages/tests/[slug].blade.php
- [ ] JSON esiste: config/.../pages/tests.homepage.json
- [ ] Componente esiste: components/page.blade.php

## Routing
- [ ] Folio montato: Folio::path(...)
- [ ] Rotte registrate: php artisan route:list
- [ ] Route name corretto: tests.view

## Provider
- [ ] ThemeServiceProvider bootato
- [ ] mountFolioPages() chiamato in boot()
- [ ] Namespace corretto: use Laravel\Folio\Folio;

## Cache
- [ ] php artisan optimize:clear
- [ ] php artisan route:clear
- [ ] php artisan view:clear
```

---

## ✅ Stato Attuale

**Errore:** ✅ **RISOLTO**  
**Homepage:** ✅ **FUNZIONANTE**  
**Rotte:** ✅ **REGISTRATE**  
**Folio:** ✅ **MONTATO**

---

**Data Risoluzione:** 2026-04-01  
**Versione:** 1.0  
**Stato:** ✅ **Risolto**

---

*"Usa Folio::path(), NON mount() function"*  
*"Facade sempre, funzioni mai"*  
*"Verifica API prima di usare"*
