# Theme CSS Only Parity Rule

## Regola per Sixteen

Il tema Sixteen e' il solo owner delle regole visuali Design Comuni. Le correzioni di parity non devono essere duplicate in `<style>` dentro Blade di modulo.

## Dove intervenire

- CSS sorgente: `laravel/Themes/Sixteen/resources/css/`
- Asset compilati: generati da `npm run build`
- Copia runtime: aggiornata da `npm run copy`

## Caso Fixcity

Per il wizard `segnalazione-crea`, il modulo Fixcity fornisce wrapper e classi stabili (`ticket-wizard-root`, `segnalazione-wizard-root`), ma Sixteen governa:

- visibilita' del footer nativo Filament;
- dimensioni CTA;
- layout responsive;
- allineamenti Bootstrap Italia;
- override visuali necessari alla parity.

## Anti-pattern

Sono vietati per parity:

- blocchi `<style>` in `Modules/Fixcity/resources/views/...`;
- `element.style.setProperty(...)` per nascondere o forzare layout;
- duplicazioni CSS tra modulo e tema.
