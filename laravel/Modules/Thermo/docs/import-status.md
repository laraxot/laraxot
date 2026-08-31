# Import status — Thermo

Modulo **nuovo**. Sorgente: `gestionale_commesse/app/Filament/Thermo` e
`app/Providers/Filament/ThermoPanelProvider`, codice che non era mai stato portato.

## Riorganizzazione

| Sorgente | Destinazione |
|---|---|
| `app/Filament/Thermo/Pages/*` | `app/Filament/Pages/*` |
| `app/Providers/Filament/ThermoPanelProvider` | `app/Providers/Filament/AdminPanelProvider` (`XotBasePanelProvider`) |
| `resources/views/filament/thermo/pages/*` | `resources/views/filament/pages/*`, hint `thermo::` |

## Adattamenti

- Le pagine astratte estendevano il `GatedPage` applicativo del sorgente: ora estendono
  `Modules\Platform\Filament\Pages\GatedXotBasePage`, che applica lo stesso gate di
  navigazione (`NavigationGate`) sopra `XotBasePage`.
- Le pagine si dichiarano `$isDiscovered = false` e sono elencate a mano nel
  PanelProvider: senza quell'elenco il pannello esporrebbe solo il login.
- Il render hook che nel sorgente disegnava lo switcher fra pannelli non e' stato
  portato: referenzia pannelli che in questa base non esistono tutti, e non serve al
  funzionamento del verticale.

## Test

`ThermoPanelPagesTest` apre tutte le pagine del pannello con un utente del ruolo
giusto e verifica che un ospite venga mandato al login. E' l'unico modo per accorgersi
subito di una pagina dimenticata nell'elenco del provider, che altrimenti resta un 404
fino alla demo davanti al cliente.
