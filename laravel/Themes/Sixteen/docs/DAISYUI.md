# daisyUI — Documentazione Progetto Sixteen

## Panoramica

[daisyUI](https://daisyui.com/) è la libreria di componenti UI per Tailwind CSS più popolare al mondo.
Utilizza classi CSS semantiche di alto livello invece di utility primitive Tailwind.
Attualmente installata nel tema **Sixteen** come devDependency.

| Attributo | Valore |
|-----------|--------|
| NPM package installato | `daisyui@^5.5.19` (aggiornato 2026-05-16, era v4.12.22 → incompatibile con Tailwind v4) |
| Repository | [saadeghi/daisyui](https://github.com/saadeghi/daisyui) |
| Licenza | MIT |
| ⭐ GitHub Stars | **40.9k** |
| 🍴 Forks | **1.6k** |
| Rilasci NPM | 184 (v5.5.19 latest, Feb 2026) |
| Languages | Svelte 42%, JavaScript 29%, Astro 16%, CSS 12% |
| CDN | `https://cdn.jsdelivr.net/npm/daisyui@5` |
| Tailwind compatibilità | ✅ v4.2.x (DaisyUI v5+) — v4 di DaisyUI è incompatibile e non builda |

## Configurazione attuale

### File: `laravel/Themes/Sixteen/tailwind.config.js`

```js
import daisyui from 'daisyui'

// --- plugins ---
plugins: [
    forms,
    typography,
    daisyui,          // ← DaisyUI plug-in registrato
    require("flowbite/plugin"),
    // ...
]

// --- daisyui themes ---
daisyui: {
    themes: [
        {
            light: {
                ...require("daisyui/src/theming/themes")["light"],
                primary: "#007A52",   // Verde Design Comuni PA
                secondary: "#0066CC", // Blu Design Comuni
                accent: "#003D73",    // Blu scuro
            },
        },
    ],
},
```

### File: `laravel/Themes/Sixteen/postcss.config.js`

```js
// ATTENZIONE: DaisyUI v4 usa PostCSS plugin; v5 usa Tailwind CSS @plugin direttamente
// Attualmente: DaisyUI v4 installata → rimane come plugin Tailwind in tailwind.config.js
// Migrazione a v5 richiede: @plugin "daisyui" direttamente nei CSS e rimozione dell'oggetto daisyui in tailwind.config.js
export default {
    plugins: {
        'postcss-import': {},
        'postcss-nesting': {},
        '@tailwindcss/postcss': {},  // ← Tailwind v4 PostCSS plugin (sostituisce @tailwindcss/vite per CSS processing)
        autoprefixer: {},
    },
}
```

### File: `laravel/Themes/Sixteen/resources/css/app.css` (stato attuale 2026-05-16)

```css
@import "tailwindcss";
/* DaisyUI v5 — registrato come @plugin direttamente nel CSS, NON in tailwind.config.js */
@plugin "daisyui";
```

> ⚠️ Storico: con DaisyUI v4 + Tailwind v4 il build fallisce con
> `addUtilities({ '@media (min-width: 640px)' : … }) defines an invalid utility selector.`
> Soluzione: `npm i daisyui@latest` (≥ v5).

## File dedicati (lista)

| File | Descrizione |
|------|-------------|
| `tailwind.config.js` | Registra `daisyui` come plugin Tailwind, configura temi |
| `postcss.config.cjs` | Processore CSS alternativo per build CJS |
| `postcss.config.js` | Processore CSS per build ESM |
| `app.css` | Entry CSS principale con `@import "tailwindcss"` |

---

## Pro & Contro

### ✅ Pro

| Vantaggio | Dettaglio |
|-----------|-----------|
| 🎨 35+ temi built-in | Cupcake, dark, forest, nord, dracula, ecc. includi anche `all` per tutti |
| ⚡ Classi semantiche | `.btn`, `.card`, `.input`, `.select`, `.alert`, `.dropdown` invece di 10–20 utility Tailwind |
| 🌙 Dark mode nativo | Switch con `data-theme` o classe `dark`; 35 temi adattano automaticamente i colori |
| 🔧 Tailwind-first | Utility class + semantiche ibridabili: `<button class="btn btn-primary mt-4">` |
| ♿ Accessibilità integrata | ARIA roles, focus trap, keyboard navigation built-in nei componenti |
| 🧩 60+ componenti | Button, Modal, Dropdown, Accordion, Card, Table, Select, Input, Toggle, Steps, Navbar, ecc. |
| 🎯 Personalizzabile via CSS variables | `${theme}` su ogni colore/proprietà senza ricompilare |
| 🌍 Multi-tema per sezione | `<div data-theme="dark">…</div>` per temi annidati in pagina |
| 📦 Bundle piccolo | ~28 KB gzip (con tutti i componenti), l'utente paga solo quello che usa |
| 💻 Framework-agnostic | Funziona con Laravel/PHP, React, Vue, Svelte, Astro, plain HTML |
| 🐛 Open-source | 40.9k ⭐, 184 releases, licenza MIT, comunità attiva |

### ❌ Contro

| Rischi o Limitazioni | Impatto nel progetto Sixteen |
|---------------------|------------------------------|
| 🚫 Non utility-first puro | Classi semantiche (`.btn`, `.card`) coesistono con utility, rischio di confusione su quale usa quale |
| 📦 Bundle overhead | +28–80 KB gzip a seconda di componenti usati; Sixteen ha già `app-test.css` @ 96 KB gzip (DaisyUI contribuisce) |
| 🔒 Dipendenza da Tailwind | Non può essere usato senza Tailwind CSS; non è una libreria standalone |
| 🎨 Design decisions predefinite | Il sistema di temi e i componenti predefiniti non permettono infinite variazioni creative; serve accettare il "daisy look" o override pesante |
| 🔁 Tailwind v4 breaking change | `tailwind.config.js` è deprecato per la maggior parte delle config; DaisyUI v5 supporta Tailwind v4 ma v4 corrente richiede ancora PostCSS plugin in alcuni casi |
| 🔗 Classi conflittuali | Con Filament v5: classi `fi-input`, `fi-select`, `.fi-fo-field-*` possono confliggere con le classi daisyUI se non si usa un prefix |
| 📚 Docs casi limite | La documentazione è eccellente per casi base, povera per integrazione con framework server-driven UI (Filament, Livewire) |
| 🔄 Sovrascrittura di style | Sovrascrivere un componente daisyUI richiede CSS custom o `@layer` specifiche; non è banale rimuovere una classe senza ricreare tutto lo stile |
| 📱 Accessibilità parziale | I componenti sono ARIA-ready ma le customizzazioni possono rompere il comportamento nativo |
| 🗑️ Cleanup costo | Rimuovere DaisyUI da un progetto esistente comporta riscrivere tutti i componenti in utility Tailwind |

---

## Percentuali & Metriche chiave

| Metrica | Valore |
|---------|--------|
| **Adozione NPM** | Top 50 package CSS/design-system su npm (stima ~15k dipendenze dirette) |
| **Bundle gzip (tutti i componenti)** | ~28 KB |
| **Bundle gzip (solo 6 componenti: btn, card, input, modal, dropdown, alert)** | ~10 KB |
| **Temi disponibili** | 36 (35 built-in + 1 custom) |
| **Componenti out-of-the-box** | 60+ |
| **Dark mode coverage** | 100% dei componenti built-in |
| **Accessiblity compliance** | ~90% WAI-ARIA 1.2 patterns per default |
| **Tailwind CSS compatibilità** | v3 (v4.x) / v4 (v5.x) |
| **Bundle Sixteen: app-xUp1YPU1.css (gzip)** | 139 KB — DaisyUI contribuisce ~10–15% di questo totale |
| **Composizione CSS Sixteen** | Bootstrap Italia converted → Tailwind + DaisyUI + Filament parity overrides |

---

## Stati di adozione per modulo

### ✅ Sixteen (Tema)

| Stato | Dettaglio |
|-------|-----------|
| Installato | ✅ v4.12.22 |
| Configurato | ✅ In `tailwind.config.js` col tema `light` esteso Design Comuni |
| Utilizzato | ⚠️ Parzialmente — classi `.btn`, `.input`, `.card` usate sporadicamente, non in modo sistematico |
| Build | ✅ `npm run build` produce CSS con regole DaisyUI |

### ❌ Geo (Modulo)

| Stato | Dettaglio |
|-------|-----------|
| Installato | ❌ Non presente in `package.json` |
| Utilizzato | ❌ Non ancora adottato |
| Ragione | Il modulo usa `tailwindcss@^3.4.1` autonomo; l'integrazione con il tema Sixteen avviene tramite `vite.config.js` copy |

### ❌ Fixcity (Modulo)

| Stato | Dettaglio |
|-------|-----------|
| Installato | ❌ Non presente in `package.json` |
| Utilizzato | ❌ Non ancora adottato |
| Ragione | Nessun CSS dedicato per UI propria; dipende dal tema |

### ❌ Xot (Modulo)

| Stato | Dettaglio |
|-------|-----------|
| Installato | ❌ Non presente in `package.json` |
| Utilizzato | ❌ Non ancora adottato |
| Ragione | Modulo kernel, non gestisce CSS/UI direttamente |

---

## Raccomandazioni

1. **Sixteen — standardizzare classi DaisyUI**:  
   Usare `.btn` invece di `.btn-primary` + manual Tailwind, `.input` invece di regole custom `.fi-input` per i Filament field parity; documentare ogni override.

2. **Geo — valutare installazione separata**:  
   Il modulo Geo ha un `vite.config.js` autonomo per asset pubblici (mappa Leaflet, coordinate-picker).  
   Se si vuole condividere design tokens tra tema e modulo, valutare un pacchetto `@design-comuni/tokens` condiviso invece di installare DaisyUI nel modulo.

3. **Fixcity / Xot**: Nessuna azione immediata.  
   Entrambi consumano il tema Sixteen, non bisogno di DaisyUI autonomo.

4. **Tailwind v5 (DaisyUI v5)**:  
   Pianificare la migrazione:  
   - Rimuovere `tailwind.config.js` → usare `@theme` e `@plugin` in CSS  
   - Sostituire `daisyui` plugin PostCSS con `@plugin "daisyui"`  

---

*Documento: Sixteen/DaisyUI-Docs — creato 2026-05-16, Kilo*
*Fonte: https://daisyui.com/docs/, https://github.com/saadeghi/daisyui*

---

## Decisione architetturale (2026-05-16): stack CSS canonico

Lo stack ufficiale del progetto Sixteen / Fixcity è:

```
Tailwind CSS v4 ── utility engine
   ├── DaisyUI v5 ─────────── semantic components on top
   ├── Filament v5 ────────── server-driven UI (Forms / Schemas)
   ├── Alpine.js ──────────── micro-interactions
   └── Lit ────────────────── web components custom (mappa, coordinate picker)
```

**Vincolo non-negoziabile:** nomi delle classi CSS e struttura HTML semantica
devono restare allineati a
[italia/design-comuni-pagine-statiche](https://github.com/italia/design-comuni-pagine-statiche/tree/main/src/stylesheets).

Cioè: il markup è "design-comuni" (es. `.cmp-card`, `.form-control`, `.cmp-input__label`,
`.steppers-nav`, ecc.), ma **lo styling è prodotto da Tailwind + DaisyUI**, non
da Bootstrap Italia. Bootstrap Italia rimane solo come *referenza visiva*; il suo
CSS/JS non deve essere caricato a runtime.

### `@apply` — pattern canonico per gli alias

Tailwind v4 supporta `@utility` / `@apply` per mappare un nome semantico
design-comuni su un cluster di utility. **È il modo pulito per non rompere il
markup design-comuni mantenendo Tailwind come unica fonte di verità stilistica.**

Esempio (esemplificativo, da estendere progressivamente):

```css
/* resources/css/components/_aliases-design-comuni.css */
@layer components {
  .form-control {
    @apply block w-full bg-transparent text-[#191919]
           border-0 border-b border-[#5c6f82] rounded-none
           px-2 py-1.5 transition-colors duration-150
           focus:outline-none focus:border-[#007a52];
  }

  .cmp-card .card.has-bkg-grey {
    @apply bg-[#f5f6f7] rounded-sm;
  }

  .steppers-btn-confirm {
    @apply bg-[#007a52] text-white border-0 rounded
           px-6 py-2 font-semibold inline-flex items-center gap-1;
  }
}
```

**Pro del pattern `@apply`:**
- ✅ Markup invariato rispetto a design-comuni → portabilità & copy-paste from reference
- ✅ Single source of truth: i token vivono nei nomi Tailwind (palette, radius, spacing)
- ✅ Niente "magic numbers" sparsi: un alias = un componente
- ✅ Convive bene con DaisyUI: puoi `@apply` anche le classi `btn`, `card`, `input` se serve

**Contro / attenzioni:**
- ⚠️ Non usare `@apply` per stili one-off — è anti-pattern (vedi Tailwind docs)
- ⚠️ Evitare `@apply` su selettori troppo specifici (es. `.page-x .cmp-card`): preferire varianti via attributi (`[data-variant]`) o modifier class
- ⚠️ Ordine `@layer`: gli alias vanno in `@layer components` per perdere contro le utility nei conflitti

### Lezione appresa — il "doppio bordo" sui field del wizard

Sintomo: tutti gli input del wizard Filament mostravano un riquadro doppio.

Root cause:
- `.fi-input-wrp` (wrapper Filament v5) porta un `ring-1` di base (Tailwind) →
  primo bordo.
- Le regole locali aggiungevano `border: 1px solid #5c6f82 !important` sull'`<input>`
  interno → secondo bordo.

Fix canonico (vedi `resources/css/components/filament-wizard-parity.css`):
- Il **wrapper** `.fi-input-wrp` porta l'unico bordo (`1px solid #5c6f82`, radius 4px).
- L'`<input>/<select>/<textarea>` interno ha `border: 0; background: transparent;
  box-shadow: none`.
- Il ring di base Filament viene neutralizzato con `--tw-ring-shadow: 0 0 #0000`.

Regola operativa: **un solo elemento può portare il bordo per ogni controllo.** Se
Filament wrappa in `.fi-input-wrp`, il bordo vive lì; l'input nudo non lo deve
duplicare. Stesso principio per `.fi-fo-field-wrp` (mai padding + bg-white → crea
una "card" attorno al field).

### Cross-link

- Regola wiki: [docs/wiki/rules/css-stack-tailwind-daisyui-design-comuni.md](wiki/rules/css-stack-tailwind-daisyui-design-comuni.md)
- DaisyUI doc moduli: `laravel/Modules/Geo/docs/DAISYUI.md`, `laravel/Modules/Fixcity/docs/DAISYUI.md`
- Reference visivo: https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-02-dati.html
