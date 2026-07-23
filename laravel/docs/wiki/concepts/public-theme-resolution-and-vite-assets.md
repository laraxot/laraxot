---
title: "Public Theme Resolution and Vite Assets"
type: "concept"
tags:
  - laraxot
  - pub-theme
  - tenant
  - vite
  - theme-two
created: "2026-06-06"
updated: "2026-06-06"
qmd: "tp-wiki-root"
issues:
  - "https://github.com/laraxot/base_techplanner_fila5/issues/4"
discussions:
  - "https://github.com/laraxot/base_techplanner_fila5/discussions/6"
---

# Public Theme Resolution and Vite Assets

## Rule

Do not diagnose the active frontoffice theme from one config file only.

The runtime theme is resolved through:

1. `.env` `APP_URL`, for example `http://techplanner.local`.
2. `Modules\Tenant\Actions\GetTenantNameAction`, which maps the host to a tenant config path, for example `local/techplanner`.
3. `Modules\Tenant\Services\TenantService::getConfig('xra')`.
4. `Modules\Xot\Datas\XotData::make()->pub_theme`.
5. `Modules\Cms\Providers\CmsServiceProvider`, which registers `pub_theme::` against `Themes/{pub_theme}/resources/views`.

Therefore, verify `XotData::make()->pub_theme` before changing Blade views, aliases, or theme assets.

## Runtime Check

From `laravel/`:

```bash
php -r 'require "vendor/autoload.php"; $app = require "bootstrap/app.php"; $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap(); var_dump(app(Modules\Tenant\Actions\GetTenantNameAction::class)->execute()); var_dump(Modules\Xot\Datas\XotData::make()->pub_theme);'
```

Expected for TechPlanner Theme Two:

```text
local/techplanner
Two
```

## Browser Check

`http://127.0.0.1:8002/it` must reference Theme Two assets when Theme Two is active:

```bash
curl -s http://127.0.0.1:8002/it | rg 'themes/(Two|Sixteen)'
```

If the response still references the previous theme after config changes, clear runtime caches and restart long-running `php artisan serve` processes.

## Vite Deploy Asset Guard

For every theme manifest in `public_html/themes/{Theme}/manifest.json`, each `file` entry must exist under the same public theme directory.

Example for Theme Two:

```bash
php -r '$m=json_decode(file_get_contents("public_html/themes/Two/manifest.json"), true); foreach ($m as $entry) { $f="public_html/themes/Two/".$entry["file"]; echo (is_file($f) ? "OK " : "MISSING ").$f.PHP_EOL; }'
```

Missing files cause browser `404` even if `laravel/Themes/{Theme}/public/manifest.json` is correct. Copy or build the public asset before pushing.

## Prevenzione errori (second brain)

### Checklist agente — prima di ogni fix frontend

1. Eseguire verifica runtime (`pub_theme` + path Folio pages).
2. Confermare che `/it` referenzi asset del tema atteso (`themes/Two/` per TechPlanner).
3. Se il tema è sbagliato: correggere **`config/local/techplanner/xra.php`**, non `config/xot.php` né override temporanei.
4. Dopo cambio config: `config:clear` + `view:clear` + riavvio `php artisan serve`.
5. Se manca una view: aggiungerla nel tema canonico del tenant, **non** cambiare tenant a Sixteen.

### Matrice tenant → tema

| Prodotto | Tenant tipico | `pub_theme` |
|----------|---------------|-------------|
| TechPlanner / Sottana | `local/techplanner`, `net/sottana1` | **Two** |
| Fixcity / Design Comuni | tenant dedicati | Sixteen |

### Segnali di regressione

- HTML con `themes/Sixteen/` su URL TechPlanner locale.
- Folio monta `Themes/Sixteen/resources/views/pages` con `APP_URL` techplanner.
- JSON CMS con path `/themes/Two/images/` ma shell Design Comuni in pagina.

### Build asset Theme Two

```bash
cd laravel/Themes/Two && npm run build && npm run copy
php -r '$m=json_decode(file_get_contents("public_html/themes/Two/manifest.json"), true); foreach ($m as $e) { $f="public_html/themes/Two/".$e["file"]; if (!is_file($f)) { echo "MISSING $f\n"; exit(1); } } echo "OK\n";'
```

## Collegamenti

- [pub_theme namespace rule](../../../../docs/pub_theme_namespace_rule.md)
- [Tenant: nome → pub_theme](../../../Modules/Tenant/docs/tenant-name-to-pub-theme.md)
- [Prevenzione GH008 push](../../../../docs/git-lfs-push-gh008-prevention.md)
