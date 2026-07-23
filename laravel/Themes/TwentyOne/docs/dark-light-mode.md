# Dark/Light Mode — TwentyOne Theme

**Data implementazione**: 2026-03-23
**Commit**: `47c64d08`

---

## Architettura

Il tema supporta dark/light mode tramite Tailwind CSS `dark:` variant con class strategy (classe `dark` su `<html>`).

### Meccanismo di Toggle

- **Componente**: `components/ui/light-dark-switch.blade.php`
- **Storage**: Alpine.js `$persist` con chiave `_x_dark_mode` in localStorage
- **Init FOUC prevention**: Script inline in `main.blade.php` prima del `<head>`

```html
<script>try{if(JSON.parse(localStorage.getItem('_x_dark_mode')))document.documentElement.classList.add('dark')}catch(e){}</script>
```

### File Coinvolti

| File | Ruolo |
| --- | --- |
| `components/layouts/main.blade.php` | Body bg/text adaptive + FOUC script |
| `components/layouts/app.blade.php` | Rimosso `body-class="bg-slate-950"` hardcoded |
| `components/ui/light-dark-switch.blade.php` | Toggle button con Alpine $persist |
| `css/components/filament-widgets.css` | Widget text/bg adaptive con `.dark` selector |

---

## Pattern CSS

### Body

```html
<body class="bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-slate-100">
```

### Card

```html
<div class="bg-white dark:bg-slate-950/80 border-slate-200 dark:border-slate-800/80 text-slate-900 dark:text-white">
```

### Badge/Stat

```html
<span class="bg-slate-50 dark:bg-white/6 border-slate-200 dark:border-white/10 text-slate-900 dark:text-white">
```

### CSS Widget (filament-widgets.css)

```css
.dark .filament-table-widget * { color: rgb(226 232 240); }
:not(.dark) .filament-table-widget * { color: rgb(51 65 85); }
```

---

## Regole

1. **MAI** usare colori dark hardcoded senza `dark:` variant
2. **SEMPRE** accoppiare `bg-slate-50` con `dark:bg-slate-950` (o equivalenti)
3. **SEMPRE** accoppiare `text-slate-900` con `dark:text-white`
4. **MAI** usare `!important` per colori nel CSS dei widget
5. **SEMPRE** usare `.dark` parent selector nel CSS custom, non media query
6. **FOUC**: lo script inline è OBBLIGATORIO prima del `<head>`

---

## Sezioni NON ancora adattate

- `predicts-hero.blade.php` — hero section (sempre dark, cinematic)
- Footer — sempre dark
- Cookie consent banner — sempre dark

---

## Screenshot

- [homepage-dark-light-v1.png](screenshots/homepage-dark-light-v1.png) — [analisi](screenshots/homepage-dark-light-v1.png.md)
