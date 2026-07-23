# wizard single next cta rule

## Regola

Nel wizard di segnalazione deve esserci una sola CTA primaria di avanzamento visibile per step:
- testo coerente: `Avanti`;
- una sola riga di navigazione attiva;
- niente doppio pattern parallelo (`Successivo` + `Avanti`).

## Motivazione

La doppia CTA crea ambiguita cognitiva e peggiora la parity con il flusso Design Comuni.

## Applicazione

- owner view: `fixcity::filament.widgets.ticket-create-wizard`;
- footer wizard Filament HIDDEN via PHP override in `CreateTicketWizardWidget` (`configureWizardNextAction`, `configureWizardPreviousAction`) calling `->hidden()`;
- CSS fallback in widget view: `.fi-sc-wizard-footer { display: none !important; }`;
- navigazione custom resta la sola fonte visuale.

## Riferimenti

- [segnalazione 01 privacy](https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-01-privacy.html)
