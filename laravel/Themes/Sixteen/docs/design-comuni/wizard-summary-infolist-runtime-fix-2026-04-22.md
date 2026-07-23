# wizard summary infolist runtime fix 2026-04-22

## contesto tema

Il tema Sixteen fornisce il layout Design Comuni del flusso segnalazione, incluso lo step `segnalazione-03-riepilogo`.
Il widget applicativo owner del flusso e `Modules\Fixcity\Filament\Widgets\CreateTicketWizardWidget`.

## decisione

Il tema non deve sostituire il riepilogo con markup Blade custom quando il contenuto e un set di dati read-only.
Il riepilogo deve restare governato dallo schema Filament del widget Fixcity, usando entry Infolist (`TextEntry`, `ImageEntry`) dentro layout schema.

## impatto Design Comuni

- Il tema continua a governare wrapper, spaziature e parity CSS.
- Il mapping dati del riepilogo resta nel modulo Fixcity.
- Eventuali interventi visuali sullo step 3 devono rispettare la semantica Infolist e non reintrodurre `SchemaView` come primaria.

## fonti

- Filament 5 schema overview: schema come contenitore di fields, layout, infolist entries e prime components.
- Filament 5 infolists overview: entry read-only in `Filament\Infolists\Components`, stato tramite `state()`.
- Fixcity docs: `docs/stories/wizard-summary-infolist-runtime-fix-2026-04-22.md`.

