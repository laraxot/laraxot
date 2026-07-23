# Errori Runtime Home `/it` - Theme TwentyOne

## 1. Manifest Vite mancante

### Sintomo

```text
Illuminate\Foundation\ViteManifestNotFoundException
Vite manifest not found at: /var/www/_bases/base_predict_fila5/public_html/themes/TwentyOne/manifest.json
```

### Causa reale

Il layout del tema usa:

```blade
@vite(['resources/css/app.css'], 'themes/TwentyOne')
@vite(['resources/js/app.js'], 'themes/TwentyOne')
```

Laravel quindi legge il manifest runtime da:

```text
/var/www/_bases/base_predict_fila5/public_html/themes/TwentyOne/manifest.json
```

`npm run build` genera il manifest locale in `laravel/Themes/TwentyOne/public/manifest.json`.
`npm run copy` lo pubblica nel path letto realmente dall'applicazione.

### Procedura corretta

```bash
cd /var/www/_bases/base_predict_fila5/laravel/Themes/TwentyOne
npm install
npm run build
npm run copy
```

### Regole

- Se il tema usa `@vite(..., 'themes/TwentyOne')`, il manifest runtime deve stare in `public_html/themes/TwentyOne/`.
- Lo script `copy` deve creare la directory di destinazione se manca.
- I path CSS verso `vendor/filament/*` devono essere relativi alla posizione reale del file nel tema.

## 2. Section template `v1` mancante

### Sintomo

```text
View [components.sections.header.v1] not found.
```

### Causa reale

Il componente CMS `Modules\Cms\View\Components\Section` usa di default `tpl = 'v1'` e risolve sempre:

```text
pub_theme::components.sections.<slug>.v1
```

Con:

```blade
<x-section slug="header" />
<x-section slug="footer" />
```

il tema deve quindi esporre almeno:

- `resources/views/components/sections/header/v1.blade.php`
- `resources/views/components/sections/footer/v1.blade.php`

In TwentyOne esistevano `header.blade.php` e `footer.blade.php`, ma non i wrapper `v1`, quindi la home `/it` cadeva in `500`.

### Fix corretto

Creare wrapper compatibili che delegano ai template già presenti:

```blade
@include('pub_theme::components.sections.header', ['blocks' => $blocks])
@include('pub_theme::components.sections.footer', ['blocks' => $blocks])
```

### Regola strutturale

Se un layout usa `<x-section slug="..."/>`, il tema deve rispettare il contratto del CMS e fornire `components/sections/<slug>/v1.blade.php`, oppure deve passare esplicitamente `tpl`.

## 3. Schema Blog base mancante su `predict_data`

### Sintomo

```text
SQLSTATE[42S02]: Base table or view not found: 1146 Table 'predict_data.banners' doesn't exist
```

Poi a cascata:

- `predict_data.categories`
- `predict_data.articles`

### Causa reale

Il tema `TwentyOne` e il modulo `Predict` usano modelli che estendono il layer Blog:

- `Modules\Predict\Models\Banner` estende `Modules\Blog\Models\Banner`
- `Modules\Predict\Models\Category` estende `Modules\Blog\Models\Category`
- `Modules\Predict\Models\Predict` estende `Modules\Blog\Models\Article`

Quindi il database `predict_data` deve contenere anche le tabelle base Blog necessarie:

- `categories`
- `banners`
- `articles`

Se queste migration non sono state eseguite, la homepage `/it` cade in `500` anche se il tema e le Blade sono corretti.

### Fix corretto

Eseguire le migration Blog pendenti che creano lo schema base condiviso:

```bash
php artisan migrate \
  --path=Modules/Blog/database/migrations/2023_11_23_000011_create_blog_categories_table.php \
  --path=Modules/Blog/database/migrations/2024_01_01_000004_create_banners_table.php \
  --path=Modules/Blog/database/migrations/2024_01_01_000011_create_articles_table.php \
  --no-interaction
```

### Nota infrastrutturale

`Modules\Xot\Database\Migrations\XotBaseMigration` usava `getDoctrineSchemaManager()`, non disponibile in questo setup Laravel 12. E' stato aggiunto un fallback compatibile che usa l'update schema standard quando Doctrine non e' presente.
