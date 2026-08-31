# Import status — Giveback

Modulo **nuovo**. Sorgente: `gestionale_commesse/app/Filament/GivebackPlatform` e
`app/Providers/Filament/GivebackPlatformPanelProvider`, codice che non era mai stato portato.

## Riorganizzazione

| Sorgente | Destinazione |
|---|---|
| `app/Filament/GivebackPlatform/Pages/*` | `app/Filament/Pages/*` |
| `app/Providers/Filament/GivebackPlatformPanelProvider` | `app/Providers/Filament/AdminPanelProvider` (`XotBasePanelProvider`) |
| `resources/views/filament/giveback-platform/pages/*` | `resources/views/filament/pages/*`, hint `giveback::` |

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

`GivebackPanelPagesTest` apre tutte le pagine del pannello con un utente del ruolo
giusto e verifica che un ospite venga mandato al login. E' l'unico modo per accorgersi
subito di una pagina dimenticata nell'elenco del provider, che altrimenti resta un 404
fino alla demo davanti al cliente.
