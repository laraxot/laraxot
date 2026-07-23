# Segnalazione Riepilogo Reference Gap Plan

## Fonte

- Reference: `https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-03-riepilogo.html`
- Runtime locale verificato: `http://127.0.0.1:8000/it/tests/segnalazione-crea?step=form.riepilogo%3A%3Adata%3A%3Awizard-step`

## Regole operative

- CSS Design Comuni nel tema: selettori site/component-level, non per pagina e non per dominio ticket.
- Markup semantico del flusso nel widget/Blade owner, evitando fork Folio e workaround nello slug.
- Il bottone submit deve essere uno solo a livello UX; se esiste il footer Filament nativo ma viene nascosto, il submit visibile deve stare nella nav Design Comuni.

## Header - Differenze da verificare per prime

Reference: slim bar con regione/lingua/accesso, center header con brand/social/search, navbar con `Servizi` attivo.

Locale: elementi principali presenti (`Nome della Regione`, `Accedi all'area personale`, `Il mio Comune`, `Cerca`, `Servizi`), da verificare visualmente su colore slim, navbar chiara, spacing logo/search, stato attivo e mobile toggler.

## Stepper

Reference: `Autorizzazioni e condizioni`, `Dati di segnalazione`, `Riepilogo`, indice `3/3`.

Locale: `Privacy e condizioni`, `Dati della segnalazione`, `Riepilogo`, indice `3/3`.

Decisione: allineare copy tramite traduzioni, non hardcode Blade.

## Contenuto Riepilogo

Reference: alert `Attenzione`, testo dichiarativo, sezioni `Segnalazione` > `Disservizio`, azione `Modifica`, campi indirizzo/tipo/titolo/dettagli/immagini, sezione `Dati Generali`, autore e contatti.

Locale: schema Filament `Riepilogo` con entry read-only, ma non replica ancora la gerarchia concettuale Design Comuni.

Decisione: mantenere `TextEntry`/`ImageEntry` per i dati, avvolgendoli in sezioni/heading coerenti con la reference.

## Azioni finali

Reference: `Indietro`, `Salva Richiesta`, `Salva`, `Invia`, poi modale termini con `Conferma e invia` e `Annulla`.

Locale: footer Filament nativo contiene `Invia`, ma e' nascosto; nav custom contiene `Indietro`, `Salva Richiesta`, `Conferma e invia`.

Decisione: scegliere un unico owner visuale. Per Design Comuni, la nav custom deve esporre l'azione finale e, se richiesto dalla reference, aprire modale termini prima di chiamare `submit`.

## Piano

1. Header: screenshot desktop/mobile locale vs reference, con focus su colore, spacing, stato attivo e dropdown.
2. Stepper: allineare traduzioni label step alla reference.
3. Riepilogo: aggiungere alert `Attenzione` e sezioni `Segnalazione` / `Dati Generali`.
4. Dati summary: mantenere Infolist entries raggruppate per `Disservizio` e `Autore`.
5. Azioni: rendere visibile una sola azione finale coerente e valutare modale termini.
6. CSS: solo pattern site/component-level (`.it-form-wizard`, `.steppers-*`, `[data-step-section]`), mai slug pagina o wrapper ticket.
7. Verifica: build/copy tema, smoke HTTP, screenshot parity.
