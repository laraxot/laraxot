# visual parity report - segnalazione crea

## Analisi Comparativa

Confronto tra `it/tests/segnalazione-crea` e [Design Comuni Statiche](https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-01-privacy.html).

### 1. Header (Colori) - **REGOLA VERDE LOGO**
- **Top Slim Header** (Nome Regione):
  - Riferimento: Blu Scuro istituzionale (#003D73).
  - Decisione: **Verde Scuro (#00402B)** per allineamento al logo comunale.
- **Main/Center Header & Navbar**:
  - Riferimento: Blu istituzionale (#0066CC).
  - Decisione: **Verde (#007A52)** (Colore Logo Comune).
- **CTA Accedi**: Sostituito il Blu con il Verde (#007A52).
- **Conclusione**: La parità visuale è raggiunta non tramite i colori del template generico, ma tramite la **coerenza cromatica con il logo dell'ente**.

### 2. Posizionamento Tasto "Avanti"
- **Regola**: Il tasto `Avanti` deve trovarsi **direttamente sotto i campi di input** (es. checkbox privacy).
- **Layout Mobile-First**:
  - Implementato stack verticale (`flex-column`).
  - Il tasto `Avanti` è steso (`align-items-stretch`) per massimizzare la facilità di interazione su touch screen.
  - Rimossa l'icona chevron per pulizia visiva (Design Comuni 2026 style).

### 3. Responsività e Accessibilità
- **Touch Targets**: `min-height: 48px` per tutti i bottoni di navigazione.
- **Tablet/Mobile**: Layout a colonna piena per evitare clipping delle stringhe tradotte.

## Documenti Correlati
- [Header Green Branding Rule](./header-green-branding-rule.md)
- [Wizard Single Next CTA Rule](./wizard-single-next-cta-rule.md)
