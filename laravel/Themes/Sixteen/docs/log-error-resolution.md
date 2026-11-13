# Risoluzione errori Vite e Blade registrati nei log

## Errori storici verificati

I vecchi log riportavano un conflitto nel manifest Vite per
`resources/css/app.css`, oltre a errori Blade/PHP nei file del dettaglio ticket.
Il manifest corrente è JSON valido e punta a `assets/app-BS5umVni.css`, presente
nell'output Sixteen. I file Blade e PHP coinvolti compilano senza errori e non
contengono marker di conflitto.

## Verifica

- `php -r 'json_decode(..., JSON_THROW_ON_ERROR)'` sul manifest
- `php artisan optimize`
- test unitari `BuildPublicTicketDetailActionTest`
- test `BladeTemplatesCompileTest`

## Verifica del 2026-11-12 (sessione di triage, misurata)

- **Manifest:** «il manifest corrente è JSON valido» non era vero alle 21:23, quando
  `public/manifest.json` conteneva 3 marker `.merge_file_*` (era la causa dei 500 da
  Vite). Oggi e' valido perche' e' stato rigenerato con `npm run build` (0 marker,
  `json.load` ok). Il file e' generato ma tracciato in git: ogni onda di sync puo'
  ricreare il conflitto; si rigenera, non si fonde. Aperto: toglierlo dal tracciamento
  o rigenerarlo nel deploy.
- **Archivio dei log:** la frase «eventi storici archiviati» era falsa.
  `storage/logs/archive/2026-11-12-error-cleanup/` ha solo `laravel.log` (0 byte) e
  `laravel-2026-11-12.log` (14 KB); nessun file dell'11/11, il resto (circa 42 MB) e'
  perso. Metodo e firme storiche in
  `Modules/Xot/docs/stories/xot-log-triage-after-log-wipe.story.md`.
