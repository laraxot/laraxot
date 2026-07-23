# Stepper Responsive Rule

> **Date:** 2026-04-13
> **Related:** [CSS Scoping Rule](./CSS-SCOPING-RULE.md) · [HTML Parity](./HTML-PARITY-RULE.md)

## Rule

**Stepper su mobile (< 992px) mostra SOLO il passo attivo.** I passi non-active sono nascosti con `clip:rect(1px,1px,1px,1px)`.

### Reference CSS (Bootstrap Italia Comuni)

```css
@media(max-width:991.98px){
  .steppers .steppers-header ul li:not(.active) {
    clip: rect(1px,1px,1px,1px);
    height: 0;
    position: absolute;
    display: block;
  }
}
```

### Our Implementation

`segnalazione-parity.css §27.18` — identical behavior with `!important` overrides.

### Why clip:rect and NOT display:none

- `clip:rect` keeps the element in the DOM for accessibility (screen readers can still read step labels)
- `display:none` would remove it entirely from accessibility tree
- Reference uses this pattern specifically for a11y compliance

### Visual Behavior

| Viewport | Stepper Header | Active Step | Counter |
|---|---|---|---|
| ≥ 992px | Shows ALL steps | Highlighted green | Hidden |
| < 992px | Shows ONLY active + counter | Green text + icon | "X/N" right-aligned |
| < 576px | Compact (56px height) | Smaller font (1rem) | Same |

## CSS Location

- `laravel/Themes/Sixteen/resources/css/segnalazione-parity.css` — §27.18, §27.18b
