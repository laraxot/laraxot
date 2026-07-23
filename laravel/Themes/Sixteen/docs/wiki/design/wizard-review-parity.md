# Wizard Review Step — Parity tema Sixteen

## Semantica schema (priorità tecnica Filament)

Prima delle regole visive vale la **vera parità DX/DI**: sul path **[`segnalazione-crea`](../../../../Modules/Fixcity/docs/ticket-wizard-frontoffice.md)** lo step **`form.summary::data::wizard-step`** mostra il recap degli step precedenti con **`TextEntry`** (Infolists, `TicketFormReviewInfolist` — classi **`fi-in-entry`**). **Sotto** il recap, autore e contatti restano **`TextInput`** (classi **`fi-fo-*`**) perché vanno compilati nello stesso step. Riferimento: [filament-summary-infolist-guidance.md](../../../../Modules/Fixcity/docs/filament-summary-infolist-guidance.md) e [overview Infolists Filament 5.x](https://filamentphp.com/docs/5.x/infolists/overview).

## Obiettivo Estetico
Raggiungere la massima fedeltà visiva con il template statico `segnalazione-03-riepilogo.html`.

### Grid & Layout
- **Container**: `max-width: 800px` (o 8 colonne Bootstrap) per il contenuto centrale.
- **Vertical Rhythm**: Margin-bottom di 32px tra le sezioni principali.

### Tipografia
- **H1**: Titillium Web, 700 weight, Institutional Blue.
- **dt**: Label in grigio scuro, 600 weight, uppercase leggero se previsto dal DS.
- **dd**: Testo utente in nero, 400 weight, padding-left per separazione visiva.

### Azioni (Bottoni)
- **Primary Action (Invia)**: Background solido, testo bianco, bordi arrotondati secondo specifiche AgID.
- **Secondary Action (Salva)**: Outline border, hover state con background-color trasparente o leggero.

### Micro-interazioni
- I link "Modifica" devono avere una transizione fluida e un'icona pencil centrata verticalmente rispetto al testo.

---
*Creato in risposta alla Story 8.51*
