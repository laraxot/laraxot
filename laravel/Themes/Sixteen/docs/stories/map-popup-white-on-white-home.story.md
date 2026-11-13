# Popup marker: testo bianco su bianco nella home

- Stato: done (2026-10-07)
- Fase BMAD: Quick Flow (riproduzione, causa, fix, verifica a pixel)
- Owner: Themes/Sixteen (CSS), Modules/Geo (markup popup, non modificato)
- Collegata a: [tickets-ux-theme-symlink-geolocation-2026-10-07.story.md](./tickets-ux-theme-symlink-geolocation.story.md)

## Problema

Cliccando un marker della mappa il popup si apriva con parte del testo illeggibile
(bianco su bianco). L'utente lo ha segnalato dopo che il bug era passato inosservato.

## Riproduzione

Nessun ticket nel DB locale, quindi la richiesta geojson e `ticket-details` sono state
intercettate nel browser con un ticket finto nel formato reale di `BuildTicketsGeoJsonAction`.
Il bug compariva **solo sulla home** (`/it`, `/en`), non su `/it/tickets`: per questo non era emerso.
Tre testi con contrasto pixel 1 (identici allo sfondo): anteprima indirizzo sotto il titolo,
codice segnalazione, indirizzo.

## Causa radice

`Themes/Sixteen/resources/css/civic-design-visual-fix.css` ha regole dell'hero della home
(`main section:has(#welcome-heading) p`, `h1`, `h2`, `a`) che impostano il testo bianco.
La mappa sta nella stessa sezione, quindi le regole colpivano anche i `p` del popup Leaflet,
che ha il corpo bianco.

## Modifiche

- `civic-design-visual-fix.css`: le regole dell'hero escludono la mappa con `:not(.leaflet-container *)`.
- `listing-parity.css`: superficie e primo piano espliciti del popup. **Nota:** il selettore usa
  `.ticket-popup`, classe che il popup attuale (`article.popup`, `popup-ticket.js`) non emette;
  quel blocco oggi non ha effetto. Il fix efficace e' quello sopra.
- Nuovo strumento di controllo: `bashscripts/tools/visual/map-popup-contrast.cjs`.

## Verifica

- Prima (build precedente): home, 3 testi a contrasto pixel 1.
- Dopo (build `app-Dyqrx6R2.css`): minimo 5.38 su `/it`, `/it/tickets`, `/en` a 1280x900 e 390x844,
  in tre esecuzioni consecutive.
- Prova di rosso: iniettando `main section p{color:#fff!important}` lo strumento fallisce con gli
  stessi tre testi e uscita 1.
- Stati misurati anche con immagine nel dettaglio e con popup in caricamento (skeleton): leggibili.

## Aperto

- Il marker e' finto: con ticket reali (immagini grandi, icone di tipo) non e' stato provato.
- Dark mode del tema non misurata.
- Rimuovere o correggere il blocco `.ticket-popup` inerte in `listing-parity.css`.
- Altre pagine con la mappa (`/it/tests/...`, area personale) non sono nella lista dello strumento.
