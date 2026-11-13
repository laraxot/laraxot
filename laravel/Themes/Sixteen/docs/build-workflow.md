---
title: "BUILD WORKFLOW"
type: note
tags: [documentation, build, vite]
created: 2026-09-26
updated: 2026-11-11
qmd: "BUILD WORKFLOW"
issues: []
discussions: []
status: active
---

# Sixteen Theme Build Workflow

## Overview

`npm run build` (in `laravel/Themes/Sixteen`) is the only step between the sources and what the browser loads. There is **no copy step**: Vite writes
straight into `./public`, and `public_html/themes/Sixteen` is a symlink to it.

```
laravel/Themes/Sixteen/resources/{css,js}  --vite build-->  laravel/Themes/Sixteen/public/{assets,manifest.json}
public_html/themes/Sixteen  ->  ../../laravel/Themes/Sixteen/public      (symlink, created by deploy.sh)
```

`public_html` is the document root: never delete or empty it. The same rule holds for modules: `public_html/assets/<module in lowercase>` is
`ln -s ../../laravel/Modules/<Module>/public`. See `laravel/Modules/Xot/docs/theme-assets-workflow.md`.

## Commands

| Command | What it does |
|---|---|
| `npm run build` | `prepare:static-assets` (`resources/scripts/prepare-static-assets.mjs`), then `vite build` into `./public`, then a fresh `manifest.json` with hashed names |
| `npm run dev` | Vite dev server with HMR |
| `build:analyze`, `build:production`, `build:filament` | variants of the same build (`--mode`) |

**Never** run `npm run copy` (it was removed from `package.json`) and never copy files by hand into `public_html/themes/Sixteen/`: it is a symlink, so a
manual copy lands in the theme `public/` folder and is overwritten by the next build.

## Where to edit

- Edit `resources/css/**` and `resources/js/**` (entry points listed in `vite.config.js`), then rebuild.
- Do not edit `public/` by hand: `vite config` states that `outDir` is `./public` and that `publicDir` is the source `vite build` copies as is.
- `public/assets/*` and `public/manifest.json` are tracked: a build that changes them is a change to commit with the source.

## Check after every build

1. `npm run build` ends without errors. A missing import is a hard failure (Rollup `Could not resolve`), not a warning.
2. `public/manifest.json` points to the new hashed CSS/JS (it changes at every CSS change).
3. A page of the front office loads the new file: `curl -s http://127.0.0.1:8081/it | grep -o 'app-[A-Za-z0-9_-]*\.css'`.
4. After a build that touched Blade views: `php artisan view:clear`, then reload.

## Troubleshooting

### The build does not start (`Could not resolve "./components/..."`)

A sync or mirror commit deleted source files that are still imported. Happened on 2026-11-11: the commit «.» `6cb55f0d` of the theme repo removed 5 JS
components (`carousel`, `dropdown`, `mobile-menu`, `modal`, `motion-reveal`), 9 CSS files under `resources/css/components/` and a Blade partial, and the
theme could no longer be built. Fix forward, never with `git checkout`/`git reset`: take the files from `<bad-commit>^` and check that their line
counts match the commit statistics (`git show --stat <bad-commit>`). Files nothing imports stay deleted.

Guards that fail when it happens again (run from the repo root with `bashscripts/tools/pest-scratch-db.sh`):

- `tests/Unit/WizardIncludesResolveTest.php`: every `@include` under `components/wizard` points to an existing view (including `$prefix.` includes).
- `tests/Unit/PhpSourcesCompileTest.php`: every PHP source of the theme compiles.
- `tests/Unit/PartsIncludesResolveTest.php`: the includes of the page parts resolve.

### A tracked asset is missing (404 on `/themes/Sixteen/assets/...`)

1. `ls -l public_html/themes/Sixteen` must show the symlink to `laravel/Themes/Sixteen/public`.
2. The file must exist in `laravel/Themes/Sixteen/public/assets/`. A path ignored by `.gitignore` is not deployed: check with `git check-ignore -q <path>`
   (this is how the Design Comuni icon sprite went missing: `DesignComuniIconSpriteIsDeployableTest`).
3. `public/manifest.json` must name a file that exists.

### Volt fragment loses `data-dc` after `view:cache`

Volt writes the fragment of `@volt('name')` to `storage/framework/views/<md5 of the name>.blade.php`; the file path is not part of the name. Two pages with
the same name overwrite each other and the last one compiled wins. Dead folders (`pages_old`, `pages_old1`) must use names prefixed with
`pages-old.` / `pages-old1.`. Guard: `tests/Unit/VoltFragmentNamesAreUniqueTest.php`.

### Performance

The built CSS is about 3.3 MB (270 KB gzip) and the JS about 1.5 MB: purge and per-route bundles are open (UX-03 in
`stories/front-office-production-readiness-audit.story.md`). Use production builds only, enable caching headers on the web server, check bundle size
after each change.
