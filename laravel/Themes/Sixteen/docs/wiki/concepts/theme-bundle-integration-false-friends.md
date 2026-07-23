# Theme Bundle Integration False Friends

## Context

Nel tema Sixteen sono emersi bug dovuti non al markup ma all'integrazione del bundle frontend con componenti di modulo.

Caso tipico: il Blade renderizza correttamente il tag custom, ma il JS relativo non e' importato in `resources/js/app.js`.

## Best Practices

- Considerare `resources/js/app.js` come registry esplicito dei custom elements runtime necessari sul frontoffice.
- Dopo ogni modifica JS/CSS tema eseguire `npm run build` e `npm run copy`.
- Trattare il tema come owner dell'integrazione asset, non della logica di dominio del modulo.
- Documentare nel wiki locale ogni dipendenza critica modulo -> bundle tema.

## Bad Practices

- Pensare che un asset del modulo venga caricato automaticamente solo perche' esiste.
- Aggiungere workaround CSS per coprire un componente JS che non e' mai stato registrato.
- Introdurre selettori per pagina per risolvere un problema di bundle globale.

## False Friends

- “Il tag HTML c'e', quindi il componente funziona.”
- “Il modulo pubblica asset, quindi il tema non deve importare nulla.”
- “Se la mappa a volte si vede, il bundle e' corretto.”

## Checklist

Per ogni web component frontoffice:

1. import presente nel bundle tema
2. build eseguita
3. copy eseguito
4. URL reale verificata in browser
5. assenza di regressioni visuali indotte da CSS locali
