# segnalazione privacy parity audit

## Perché (business)

Nel flusso di segnalazione il primo step deve essere immediato e non ambiguo:
un solo percorso di avanzamento, colori istituzionali coerenti
e leggibilità forte su mobile.

## Esito analisi

- rimosso conflitto cognitivo tra `Successivo` e `Avanti` (resta solo `Avanti`);
- colori header riallineati alla reference Design Comuni sul percorso `segnalazione-crea`;
- pulsante `Accedi all'area personale` riallineato al verde istituzionale;
- regola CTA rispettata: `Avanti` sotto il checkbox privacy su tutti i breakpoint.

## Valori chiave

- colore sotto "Nome della Regione": locale `rgb(0, 64, 43)`; target `rgb(0, 64, 43)`.
- colore sotto "Amministrazione": locale `rgb(0, 122, 82)`; target `rgb(0, 122, 82)`.
- colore bottone `Accedi all'area personale`: locale `rgb(0, 122, 82)`; target `rgb(0, 122, 82)`.
- posizione `Avanti`:
  - mobile: locale `x=68 y=1081 w=254 h=48`; sotto checkbox (`y=942`);
  - tablet: locale `x=68 y=921 w=632 h=48`; sotto checkbox (`y=804`);
  - desktop: locale `x=312 y=938 w=428 h=48`; sotto checkbox (`y=798`).

## Impatto

Ridotta confusione utente nel passaggio al passo successivo,
ma serve ulteriore tuning layout mobile-first
per completare la parity UX.
