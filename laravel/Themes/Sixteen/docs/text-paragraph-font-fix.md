# Text-Paragraph Font Fix

## Problema
Il testo privacy ("Il Comune di Firenze gestisce i dati personali...") usava font **Lora** (serif) invece di **Titillium Web** (sans-serif) come nel reference Bootstrap Italia.

## Root Cause
6 definizioni CSS conflittuali per `.text-paragraph`:

| File | Linea | Font | Color | Line-height |
|---|---|---|---|---|
| style-apply.css | 2227 | Lora ❌ | gray-700 | 1.6 |
| segnalazione-parity.css | 152 | - | #191919 ❌ | 1.5 ❌ |
| segnalazione-parity.css | 1699 | Titillium Web | #191919 ❌ | 24px |
| segnalazione-parity.css | 2363 | Titillium Web | #191919 ❌ | 24px |
| segnalazione-parity.css | 2420 | Titillium Web | #191919 ❌ | 24px |
| bootstrap-italia.css | 419 | - | #5C6F82 ✓ | 1.6 |

Reference Bootstrap Italia: `font-size: 1rem`, `line-height: 1.6`, `color: #5C6F82`, font ereditato dal body (Titillium Web).

## Fix Applicati

### 1. style-apply.css:2227
```css
/* PRIMA (SBAGLIATO) */
.text-paragraph {
  @apply text-base leading-relaxed text-gray-700;
  font-family: 'Lora', serif;
}

/* DOPO (CORRETTO) */
.text-paragraph {
  @apply text-base leading-relaxed;
  font-family: 'Titillium Web', sans-serif;
  color: #5C6F82;
  line-height: 1.6;
}
```

### 2. segnalazione-parity.css:152
```css
/* PRIMA */
.text-paragraph { font-size: 1rem; line-height: 1.5; color: #191919; }
/* DOPO */
.text-paragraph { font-size: 1rem; line-height: 1.6; color: #5C6F82; }
```

### 3. segnalazione-parity.css:1699 (segnalazione-privacy-page)
```css
/* PRIMA */
color: #191919 !important;
/* DOPO */
color: #5C6F82 !important;
```

### 4. segnalazione-parity.css:2363+2420 (body.page-tests override duplicato)
```css
/* PRIMA */
color: #191919 !important;
/* DOPO */
color: #5C6F82 !important;
```

## Regola per il Futuro
- **MAI** usare `font-family: 'Lora'` per `.text-paragraph`
- **Bootstrap Italia** usa `color: #5C6F82` per `.text-paragraph`
- Line-height: 1.6 (non 1.5)
- Verificare SEMPRE con il reference HTML prima di applicare override

## Build
```bash
cd laravel/Themes/Sixteen
npm run build && npm run copy
```

## See Also
- [CSS/JS Parity Phase](../css-js-parity.md)
- [Bootstrap Italia Classes](../../resources/css/components/bootstrap-italia-classes.css)
