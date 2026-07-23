---
name: no-page-specific-css
description: CSS globale per componenti — vietato selettori per pagina o widget specifico
type: concept
---

# No Page-Specific CSS Rule

## REGOLA PERMANENTE: CSS globale, non per pagina

### Problema

Usare selettori CSS come `.ticket-wizard-root` o `.page-content[data-slug="tests.segnalazione-crea"]`
crea CSS accoppiato a una singola pagina/widget. Se il wizard viene spostato o riutilizzato, il CSS non funziona.

### Principio

Il Design System Design Comuni non ha CSS per pagina — solo CSS per componenti.
Un wizard è un **componente**, non "la pagina segnalazione-crea".

### Vincolo

```
VIETATO: .ticket-wizard-root { ... }
VIETATO: .page-content[data-slug="..."] { ... }
OBBLIGATORIO: selettori sul componente stesso: coordinate-picker-lit, .filament-wizard-step
```

### Riferimento

- https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-02-dati.html
- Rule: `bashscripts/ai/.claude/rules/no-page-specific-css.md`
