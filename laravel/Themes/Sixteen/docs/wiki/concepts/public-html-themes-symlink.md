---
title: "public_html/themes/Sixteen e' un symlink al public del tema"
type: concept
tags: [sixteen, vite, build, symlink, public_html]
created: 2026-10-07
updated: 2026-10-07
qmd: "sixteen public_html themes symlink vite build emptyOutDir copy manifest 404 asset"
issues: []
discussions: []
status: draft
---

# public_html/themes/Sixteen -> laravel/Themes/Sixteen/public

```bash
cd public_html/themes && rm -rf Sixteen && ln -s ../../laravel/Themes/Sixteen/public/ Sixteen
```

Voluto: `public_html/themes` e' gitignorato, `Themes/Sixteen/public` e' tracciato. Il webroot serve direttamente il tema.

## Dopo il symlink

- La vecchia build in `public_html/themes/Sixteen` sparisce: ricostruire. Sintomo di build mancante:
  404 su `app-<hash>.css/js` elencati in `manifest.json`, JS del tema assente (bottoni morti).
- `vite.config.js`: `outDir` punta al symlink, quindi `emptyOutDir: false` e `publicDir: false`
  (altrimenti vite svuota `public/` tracciato).
- Build sicura: `npx vite build --outDir <scratch> --emptyOutDir` poi `cp -a <scratch>/assets/. public/assets/`
  e `cp <scratch>/manifest.json public/manifest.json`; poi `npm run copy` (immagini design-comuni, MarkerCluster).
- `npm ci` nel tema: `PUPPETEER_SKIP_DOWNLOAD=1` (il download del browser puo' fallire e non serve alla build).
- Verifica: ogni `/themes/Sixteen/...` richiesto dalla pagina deve dare 200.

## CSS `body[data-page='...']`

Le regole di `listing-parity.css` sono scoped su `body[data-page]`. Una pagina Folio senza `bodyPage="..."`
su `<x-layouts.app>` non le riceve (es. `ticket-list` per `pages/tickets/index.blade.php`).

## Geolocalizzazione

`navigator.geolocation` esiste anche su HTTP, ma Chrome/Edge lo rifiutano fuori da un contesto sicuro
(`window.isSecureContext === false`) senza prompt. `localhost` conta come sicuro, `http://<IP LAN>` no.
