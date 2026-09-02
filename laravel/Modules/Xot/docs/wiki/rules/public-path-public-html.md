---
title: "public_path → public_html (non laravel/public)"
type: rule
tags: [laravel, public_path, public_html, document-root, laraxot]
created: 2026-09-01
updated: 2026-09-01
qmd: "public_path public_html document root Application laravel public mai"
related:
  - ../../../../../../docs/wiki/rules/public-path-public-html.md
  - ../concepts/document-root-public-html.md
  - ../../stories/public-path-public-html.story.md
  - ../../testing/testing-setup.md
---

# public_path → public_html (non laravel/public)

## Regola (SSoT)

In **tutti** i progetti Laraxot di questa famiglia:

| Concetto | Path canonico | Vietato assumere |
|---|---|---|
| Document root HTTP | `{repo}/public_html/` | `laravel/public/` |
| `public_path()` / `publicPath()` | `{repo}/public_html/` (+ segmento opzionale) | `base_path('public')` |
| Entry point web | `public_html/index.php` | `laravel/public/index.php` |

`laravel/public/` può esistere (default Laravel, artefatti Vite intermedi) ma **non è** la root servita
dal web server e **non** è ciò che restituisce `public_path()` a runtime.

## Implementazione

```16:39:laravel/app/Application.php
public function publicPath($path = ''): string
{
    $publicRoot = $this->basePath.'/../public_html';
    // … realpath + fallback …
}
```

Bootstrap: `laravel/bootstrap/app.php` usa `App\Application` (non la classe stock di Laravel).

Dev server: `laravel/server.php` risolve `realpath(__DIR__.'/../public_html')`.

## Perché

1. **Separazione deploy**: codice Laravel in `laravel/`, asset pubblici in `public_html/` alla root repo
   (Apache/Nginx DocumentRoot = `public_html`, non sotto `laravel/`).
2. **Multi-modulo**: asset modulo/tema finiscono in `public_html/modules/`, `public_html/themes/`,
   `public_html/assets/` — fuori dal tree Composer.
3. **DRY**: ogni `public_path('build/manifest.json')`, publish Filament, PDF con logo, test asset
   devono puntare allo stesso albero che il browser raggiunge via URL.

## Cosa fare / non fare

| ✅ Corretto | ❌ Errato |
|---|---|
| `public_path('css/filament/...')` | `base_path('public/...')` |
| `mkdir(public_path('assets/foo'), …)` | Hardcode `laravel/public/...` |
| Vite `outDir` → poi `cp` in `public_html/` (temi) | Configurare Apache su `laravel/public` |
| Test: `ApplicationPublicPathCoverageTest` | Bind test `path.public` → `laravel/public_html` |

## Perche' la regressione e' pericolosa: non fa rumore

Se `public_path()` torna a `laravel/public`, **niente va in errore**:

| Cosa si rompe | Sintomo |
|---|---|
| `php artisan storage:link` | symlink creato in `laravel/public/storage`, che il web server non serve → allegati 404 |
| build Vite | manifest scritto dove nessuno lo legge → pagina senza CSS/JS, nessun errore PHP |
| `asset()` / `Vite::asset()` | URL formalmente corretti verso file inesistenti → 404 solo in produzione |
| upload e file generati | finiscono fuori dalla radice pubblica → «in locale funzionava» |

I log restano puliti in tutti e quattro i casi. E' il tipo di guasto che scopre l'utente, non
il monitoraggio — ed e' la ragione per cui questa regola ha una guardia automatica invece di
una raccomandazione.

## Test e harness

- Guardia attiva: `Modules/Xot/tests/Unit/PublicPathTest.php` (7 test). Copre sia il
  **risultato** (`public_path()` finisce con `public_html`, non contiene `/laravel/`) sia il
  **meccanismo** (`app()` e' un `App\Application` e `publicPath()` e' dichiarato li'): togliere
  l'override o rimettere `Illuminate\Foundation\Application` in `bootstrap/app.php` fa diventare
  rossa la suite prima del deploy.
- Trait test moduli: `Modules/Xot/tests/CreatesApplication.php` — bind `path.public` su
  `{basePath}/../public_html` (allineato a `Application::publicPath()`).

## Trigger agente

Vedi [00-TRIGGER_MAP.md](../../../../../../docs/wiki/rules/00-TRIGGER_MAP.md) — riga
`public_path`, `public_html`, document root.

Memoria: [public-path-public-html.md](../../../../../../bashscripts/ai/wiki/memories/public-path-public-html.md).
