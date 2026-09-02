---
title: "Document root: public_html, non laravel/public"
type: rule
theme: Zero
created: 2026-09-01
updated: 2026-09-01
qmd: "public_path public_html document root tema zero asset vite publish"
related:
  - "../../../Modules/Xot/docs/wiki/rules/public-path-public-html.md"
  - "../../../../docs/wiki/rules/public-path-public-html.md"
---

# Document root: `public_html`, non `laravel/public`

**Regola canonica**: [Modules/Xot/docs/wiki/rules/public-path-public-html.md](../../../Modules/Xot/docs/wiki/rules/public-path-public-html.md)

## In una riga

`public_path()` risolve `{repo}/public_html/`. **Mai** `{repo}/laravel/public/`.

L'applicazione sta in `laravel/`, ma il web server serve `public_html/` un livello sopra.
`App\Application::publicPath()` lo garantisce, e `laravel/bootstrap/app.php` istanzia quella
classe e non quella stock di Laravel.

## Cosa significa per questo tema

Il `vite.config.js` del tema ha `outDir: './public'`: e' la build **locale al tema**, non la radice pubblica. Da li' gli asset vengono pubblicati sotto `public_path()` — vedi `docs/themes-system-complete-guide.md`, dove il publish mappa `resources/css` su `public_path('css/zero')`.

## Come si sbaglia

Usare `base_path('public')` o scrivere `laravel/public/` a mano. Entrambi puntano a una
cartella che il web server **non serve**: gli asset finiscono fuori dalla radice pubblica e
tornano 404 in produzione, senza un solo errore nei log.

Usare sempre `public_path()`. E' l'unico punto che conosce la deviazione.

## Guardia

`Modules/Xot/tests/Unit/PublicPathTest.php` — verifica sia il risultato sia il meccanismo.
Se qualcuno rimuove l'override, la suite diventa rossa prima del deploy.
