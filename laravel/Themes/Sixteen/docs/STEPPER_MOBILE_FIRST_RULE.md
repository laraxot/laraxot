# Stepper Mobile-First Rule - CSS Responsive Enforcement

**Status**: Active  
**Created**: 2026-04-12  
**Last Updated**: 2026-04-12  
**Category**: CSS / Responsive / Design Comuni  
**Related Stories**: [7-28](../../../_bmad-output/implementation-artifacts/7-28-segnalazione-02-dati-stepper-responsive-multilingual.md)

## Rule Statement

**SEMPRE** progettare CSS per gli stepper con approccio **mobile-first**.

## Scoping Rule

Per gli stepper Design Comuni:

- usare hook strutturali gia presenti nel markup finale
- preferire `main #main-container .steppers-*` e `.cmp-nav-steps`
- non usare `body` class custom
- non usare wrapper runtime come `.tests-view-wrapper`

## Breakpoints Obbligatori

| Breakpoint | Target | CSS Approach |
|-----------|--------|-------------|
| Base (no media query) | Mobile 375px | Default styles |
| `@media (min-width: 768px)` | Tablet | Override tablet |
| `@media (min-width: 1024px)` | Desktop | Override desktop |

## Stepper Components

Gli stepper del Design Comuni includono:

- `.steppers` - container principale
- `.steppers-header` - header con step tabs
- `.steppers-index` - indicatore "2/3"
- `.steppers-content` - contenuto form
- `.steppers-nav` - navigazione (Indietro, Salva, Avanti)
- `.steppers-btn-prev` - bottone indietro
- `.steppers-btn-save` - bottone salva
- `.steppers-btn-confirm` - bottone avanti/conferma

## Mobile-First Pattern

### CORRETTO

```css
/* BASE: Mobile 375px (default) */
.steppers-header {
  padding: 0 16px;
  height: 48px;
}

.steppers-header ul {
  gap: 8px;
  font-size: 0.875rem;
}

.steppers-nav {
  flex-direction: column;
  gap: 8px;
}

/* TABLET: 768px+ */
@media (min-width: 768px) {
  .steppers-header {
    padding: 0 24px;
    height: 56px;
  }
  
  .steppers-header ul {
    gap: 16px;
    font-size: 1rem;
  }
  
  .steppers-nav {
    flex-direction: row;
    gap: 16px;
  }
}

/* DESKTOP: 1024px+ */
@media (min-width: 1024px) {
  .steppers-header {
    padding: 0 32px;
    height: 64px;
  }
  
  .steppers-header ul {
    gap: 24px;
    font-size: 1.125rem;
  }
}
```

### SBAGLIATO (Desktop-first)

```css
/* SBAGLIATO: parte da desktop e usa max-width */
.steppers-header {
  padding: 0 32px;
  height: 64px;
}

@media (max-width: 1023px) {
  .steppers-header {
    padding: 0 16px; /* override per mobile */
  }
}
```

## Verification Checklist

- [ ] CSS base per mobile 375px (no media query)
- [ ] Media query `min-width: 768px` per tablet
- [ ] Media query `min-width: 1024px` per desktop
- [ ] Screenshot verification a 375px, 768px, 1280px
- [ ] No overflow orizzontale a nessun breakpoint
- [ ] Testi leggibili e bottoni tap-friendly su mobile
- [ ] Stepper-nav buttons non si sovrappongono

## Multilingual Compliance

**MAI** hardcoded Italian/English nei testi dello stepper.

| Testo | Translation Key |
|-------|----------------|
| "Indietro" | `fixcity::segnalazione.fields.step.back.label` |
| "Salva" | `fixcity::segnalazione.fields.step.save.label` |
| "Salva Richiesta" | `fixcity::segnalazione.fields.step.save_request.label` |
| "Avanti" | `fixcity::segnalazione.fields.step.next.label` |
| "Confermato" | `fixcity::segnalazione.fields.step.confirmed.label` |
| "Richiesta salvata con successo" | `fixcity::segnalazione.fields.step.saved_success.message` |

Formato 5 livelli: `namespace::context.collection.element.type`

## Related Documentation

- [BODY_CLASS_RULE.md](BODY_CLASS_RULE.md) - HTML parity: `<body>` senza classi
- [architecture/CSS-SCOPING-RULE.md](./architecture/CSS-SCOPING-RULE.md) - Hook CSS parity-safe
- [QWEN.md](../../../../QWEN.md) - Memory permanente del progetto
- [Story 7-28](../../../_bmad-output/implementation-artifacts/7-28-segnalazione-02-dati-stepper-responsive-multilingual.md) - Story principale
- [segnalazione-parity.css](../resources/css/segnalazione-parity.css) - CSS file principale

## CSS Files Hierarchy

1. `design-comuni-global.css` - stili base Bootstrap Italia parity
2. `design-comuni-global-fixes.css` - fix globali
3. `segnalazione-parity.css` - fix specifici pagine segnalazione
4. `style-apply.css` - Tailwind @apply utilities

## Notes

Questa regola e fondamentale per garantire che gli stepper siano responsive su tutti i device.
Il reference Design Comuni e mobile-first per design.
