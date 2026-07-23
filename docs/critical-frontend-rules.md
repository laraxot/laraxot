# CRITICAL Frontend Rules - Laraxot Framework

**ULTIMO AGGIORNAMENTO**: 2026-02-08
**STATUS**: ✅ REGOLE OBBLIGATORIE
**PRIORITÀ**: CRITICA - MAI VIOLARE

## 🚨 Regola Fondamentale: NO Controllers per Frontend

**MAI, MAI, MAI usare Controllers tradizionali per il frontend. SEMPRE usare Folio + Volt.**

### ❌ VIETATO - Controllers Tradizionali

```php
// ❌ SBAGLIATO - MAI FARE QUESTO
// app/Http/Controllers/PagesController.php
class PagesController extends Controller {
    public function about() {
        return view('pages.about');
    }
}

// ❌ SBAGLIATO - MAI FARE QUESTO
// routes/web.php
Route::get('/chi-siamo', [PagesController::class, 'about']);
```

### ✅ CORRETTO - Folio Pages

```blade
<!-- ✅ CORRETTO - resources/views/pages/chi-si-siamo.blade.php -->
<?php
use function Laravel\Folio\{name};
name('pages.about');
?>

<x-page side="content" slug="about">
    <h1>Chi Siamo</h1>
    <p>Contenuto della pagina chi siamo...</p>
</x-page>
```

## 📁 Struttura File Frontend

### Per Target Site Flat Routing (/chi-siamo)

```php
// ✅ CORRETTO
// resources/views/pages/chi-si-siamo.blade.php
// URL: http://127.0.0.1:8000/it/chi-si-siamo

// ❌ SBAGLIATO
// Non creare cartelle index.blade.php
// resources/views/pages/chi-siamo/index.blade.php
```

### Per Local Site Pages Routing (/it/pages/{slug})

```blade
<!-- ✅ CORRETTO - resources/views/pages/pages/[slug].blade.php -->
<?php
use function Laravel\Folio\{name};
name('pages.dynamic');
?>

<!-- Il componente Page carica automaticamente il JSON corrispondente -->
<x-page side="content" :slug="$slug" />
```

## 🗄️ File as Database - JSON Pages

### Struttura JSON

```json
{
    "content_blocks": {
        "it": [
            {
                "id": "block-1",
                "type": "hero",
                "data": {
                    "title": "Chi Siamo",
                    "subtitle": "La nostra missione",
                    "view": "pub_theme::components.blocks.hero.about"
                }
            }
        ]
    }
}
```

### Mapping URL - CRITICAL

| Target URL | Local URL | Slug | JSON File |
|------------|-----------|------|-----------|
| /chi-siamo | /it/pages/about | about | pages/about.json |
| /servizi | /it/pages/services | services | pages/services.json |
| /blog | /it/pages/blog | blog | pages/blog.json |
| /faq | /it/pages/faqs | faqs | pages/faqs.json |
| /contatti | /it/pages/contacts | contacts | pages/contacts.json |

**REGOLA**: `/it/pages/{slug}` → JSON file: `database/content/pages/{slug}.json`

## 🧩 Componenti Blade

### Percorso Componenti

```php
// ✅ CORRETTO
"view": "pub_theme::components.blocks.hero.about"
// File: laravel/Themes/Two/resources/views/components/blocks/hero/about.blade.php

// ✅ CORRETTO
"view": "pub_theme::components.blogs.hero.enhanced"
// File: laravel/Themes/Two/resources/views/components/blogs/hero/enhanced.blade.php
```

### Creazione Componenti

```blade
{{-- resources/views/components/blocks/hero/about.blade.php --}}
@section('hero')
<div class="bg-blue-600 text-white py-20">
    <h1>{{ $title ?? 'Chi Siamo' }}</h1>
    <p>{{ $subtitle ?? 'La nostra missione' }}</p>
</div>
@endsubsection
```

## 🔧 Page Component

### Passaggio Dati a Page Component

```blade
{{-- pages/pages/[slug].blade.php --}}
<x-page side="content" :slug="$slug" />

{{-- Il componente Page usa: --}}
{{-- 1. Page Model con trait SushiToJsons --}}
{{-- 2. Carica JSON da database/content/pages/{slug}.json --}}
{{-- 3. Compila blocchi e passa ai componenti --}}
```

### Model Page - NO Database MySQL

```php
// ✅ CORRETTO - JSON Files come Database
class Page extends BaseModel
{
    use SushiToJsons;
    
    // NON usa table MySQL
    // Carica dati da JSON in database/content/pages/
}
```

## 📝 Translation Files

### Posizione

```
laravel/lang/{locale}/{module}/{file}.php

Esempi:
laravel/lang/it/techplanner/pages/about.php
laravel/lang/en/techplanner/pages/about.php
laravel/lang/de/techplanner/pages/about.php
```

### Struttura Translation

```php
// laravel/lang/it/techplanner/pages/about.php
return [
    'title' => 'Chi Siamo',
    'hero' => [
        'title' => 'Chi Siamo',
        'subtitle' => 'La nostra missione',
    ],
    'team' => [
        'title' => 'Il Nostro Team',
        'description' => 'Professionisti qualificati',
    ],
];
```

## 🎨 Theme Management

### Asset CSS/JS

```bash
# ✅ CORRETTO - Eseguire nel tema
cd laravel/Themes/Two
npm run build
npm run copy

# ❌ SBAGLIATO - Non eseguire nel root
cd laravel
npm run build  # NON FUNZIONA
```

### Configurazione Tema

```php
// laravel/config/local/techplanner/xra.php
'pub_theme' => 'Two',  // Nome tema
```

## 🔍 Troubleshooting

### Pagina Non Trovata

```bash
# 1. Verifica file JSON esista
ls laravel/config/local/techplanner/database/content/pages/{slug}.json

# 2. Verifica slug corretto
curl http://127.0.0.1:8000/it/pages/{slug}

# 3. Verifica componenti esistano
ls laravel/Themes/Two/resources/views/components/{path}

# 4. Clear cache
cd laravel
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

### Component Not Found

```php
// Verifica percorso corretto
// ❌ SBAGLIATO: "components.blocks.hero.about"
// ✅ CORRETTO: "pub_theme::components.blocks.hero.about"

// Verifica file esista
ls laravel/Themes/Two/resources/views/components/blocks/hero/about.blade.php
```

### CSS/JS Non Aggiornati

```bash
cd laravel/Themes/Two
npm run build
npm run copy

# Verifica asset copiati
ls public_html/themes/Two/css/app.css
ls public_html/themes/Two/js/app.js
```

## 📚 Riferimenti

- [Folio Documentation](https://laravel.com/docs/11.x/folio)
- [Volt Documentation](https://laravel.com/docs/11.x/volt)
- [XotBase Architecture](../../Modules/Xot/docs/critical-architecture-rules.md)
- [CMS Module Docs](../../Modules/Cms/docs/themes/folio-routing-system.md)

## ⚠️ Note Importanti

1. **Queste regole hanno PRIORITÀ ASSOLUTA** per il frontend
2. **Controllers tradizionali sono VIETATI** per il frontend
3. **Folio routing è OBBLIGATORIO** per tutte le pagine pubbliche
4. **JSON files sono il database** per il contenuto delle pagine
5. **Componenti Blade devono essere nel tema**, non in app/View
6. **Asset devono essere compilati nel tema**, non nel root

## 🎯 Checklist per Nuove Pagine

- [ ] Creare file JSON in `database/content/pages/{slug}.json`
- [ ] Definire content_blocks con view corretto
- [ ] Creare componenti Blade nel tema
- [ ] Verifica URL mapping (/it/pages/{slug})
- [ ] Testare pagina nel browser
- [ ] Clear cache se necessario
- [ ] Aggiornare traduzioni se necessario