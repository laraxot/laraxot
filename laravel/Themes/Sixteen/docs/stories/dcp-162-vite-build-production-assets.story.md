---
type: story
tags: [deployment, vite, build, production]
created: 2026-10-14
updated: 2026-10-14
qmd: "vite build manifest public build deploy produzione"
issues: []
discussions: []
id: "2026-10-14-dcp-162"
epic: 162
title: "Build Vite e asset pubblici per la produzione"
slug: vite-build-production-assets
status: ready-for-dev
scope: theme:Sixteen
github:
  repository: https://github.com/laraxot/module_gdpr_fila5
  issues: https://github.com/laraxot/module_gdpr_fila5/issues/62
  discussions: https://github.com/laraxot/module_gdpr_fila5/discussions/63
  issue_status: "aperta"
related:
  - ../../Xot/docs/stories/phpstan-cleanup-xot-app.story.md
---

# 2026-10-14 DCP-162 Build Vite e asset pubblici per la produzione

## Stato attuale

- `node_modules/.bin/vite` disponibile.
- `public/build/` **vuoto** (nessun `manifest.json`).
- Nessun server in ascolto (`curl http://fixcity.local/` → 000).

## Obiettivo

Rendere il sito pronto per essere messo online:

1. Eseguire `npm run build` (o `vite build`) per generare `public/build/manifest.json` e gli asset.
2. Verificare che i manifest puntino ai path corretti (asset()).
3. Configurare `.env.production` (APP_ENV=production, APP_DEBUG=false, APP_KEY, DB, CACHE).
4. Eseguire `php artisan config:cache`, `route:cache`, `view:cache`, `optimize`.
5. Avviare il server web (o verificare il deployer).

## Accettazione

1. `public/build/manifest.json` esiste e contiene i chunk CSS/JS.
2. `.env.production` completo e sicuro (nessun segreto hardcodato).
3. `php artisan optimize` passa senza errori.
4. Pagina di home raggiungibile su `http://fixcity.local/`.