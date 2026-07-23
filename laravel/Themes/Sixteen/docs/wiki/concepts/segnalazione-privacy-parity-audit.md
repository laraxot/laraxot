# segnalazione privacy parity audit

## Scope

Confronto visuale tra:
- locale: `/it/tests/segnalazione-crea`
- reference: [segnalazione-01-privacy](https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-01-privacy.html)

Breakpoints verificati: mobile, tablet, desktop.

## Differenze riscontrate e stato

- **Header slim (barra sotto "Nome della Regione")**
  - target: `rgb(0, 64, 43)`
  - locale: allineato dopo fix
- **Barra navigazione (riga con "Amministrazione")**
  - target: `rgb(0, 122, 82)`
  - locale: allineato dopo fix
- **CTA duplicata (`Successivo` + `Avanti`)**
  - target: una sola CTA primaria (`Avanti`)
  - locale: allineato, footer wizard Filament nascosto
- **Accedi all'area personale**
  - target: stesso verde dello sfondo logo comune (`rgb(0, 122, 82)`)
  - locale: allineato dopo fix (`rgb(0, 122, 82)`)
- **Posizione CTA `Avanti`**
  - regola applicata: deve stare sotto il checkbox privacy
  - locale: rispettata su mobile/tablet/desktop

## Metriche misurate

| breakpoint | locale `Avanti` | reference `Avanti` |
| --- | --- | --- |
| mobile | `x=44 y=1065 w=302 h=48` | `x=12 y=689 w=366 h=48` |
| tablet | `x=44 y=905 w=680 h=48` | `x=36 y=613 w=348 h=48` |
| desktop | `x=288 y=938 w=428 h=48` | `x=292 y=704 w=428 h=48` |

| area header | locale | target |
| --- | --- | --- |
| sotto "Nome della Regione" | `rgb(0, 64, 43)` | `rgb(0, 64, 43)` |
| sotto "Amministrazione" | `rgb(0, 122, 82)` | `rgb(0, 122, 82)` |
| bottone "Accedi all'area personale" | `rgb(0, 122, 82)` | `rgb(0, 122, 82)` |

## Nota responsive

Allineamento funzionale ottenuto per la richiesta utente:
- niente blu su barre header e pulsante "Accedi all'area personale";
- CTA `Avanti` unica e posizionata sotto il checkbox privacy;
- verifica eseguita su mobile, tablet e desktop.
