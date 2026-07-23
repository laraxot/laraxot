---
title: frontend design fixcity overlay
type: concept
module: Sixteen
updated: 2026-06-03
related:
  - ../rules/frontend-stack-canonical.md
  - ../entities/design-comuni-class-mapping.md
  - ./ui-ai-tooling-on-demand.md
  - ./bootstrap-italia-tailwind-philosophy.md
---

# Frontend design — overlay Fixcity (da Anthropic plugin)

## scopo

Adattare la skill upstream [anthropics/claude-code/plugins/frontend-design](https://github.com/anthropics/claude-code/tree/main/plugins/frontend-design) al contesto **PA + Fixcity**: qualità visiva alta senza rompere parity Design Comuni né lo stack Tailwind/Alpine/Lit/Filament.

SSoT skill agente: `bashscripts/ai/skills/frontend-design/SKILL.md` (caricare on-demand). Mirror Cursor: `.cursor/skills/frontend-design-fixcity/SKILL.md`.

**Upstream autori:** Prithvi Rajasekaran, Alexander Bricken (Anthropic). Cookbook: [prompting_for_frontend_aesthetics.ipynb](https://github.com/anthropics/claude-cookbooks/blob/main/coding/prompting_for_frontend_aesthetics.ipynb).

## cosa prendere dal plugin Anthropic

| Principio upstream | Applicazione Fixcity |
|--------------------|----------------------|
| Design Thinking prima del codice | Sì — con domanda «parity o accent?» |
| Tipografia intenzionale | Titillium / token tema su FO; no Inter di default |
| Colore dominante + accento | Blu istituzionale header; verde `#007A52` solo dove documentato |
| Motion ad alto impatto | Skeleton popup, hover marker shell — non page transition ovunque |
| Anti AI-slop (purple gradient, font generici) | Sì, anche in admin se non Filament token |

## cosa **non** importare alla cieca

- Layout «editorial chaos» su wizard segnalazione
- Font display casuali al posto del sistema PA
- React + Motion library (stack non canonico FO)
- Dark mode sperimentale su pagine parity senza story

## modalità operative

### 1 — Parity Design Comuni (default `/it`)

- Reference: [design-comuni-pagine-statiche](https://italia.github.io/design-comuni-pagine-statiche/)
- Classi: mapping in [design-comuni-class-mapping.md](../entities/design-comuni-class-mapping.md)
- Verifica: screenshot Puppeteer/Playwright 375 e 768

### 2 — Accent prodotto Fixcity

- Esempi documentati: CTA verde guest, teaser mappa homepage
- Richiede nota in story/wiki «perché diverge dal blu PA»

### 3 — Componente greenfield

- Applicare skill Anthropic quasi integra + stack canonico
- Estrarre in `components/` con nome **inglese**

## design thinking (4 domande — da plugin Anthropic)

Prima del codice, rispondere (anche in breve):

1. **Purpose** — quale servizio pubblico? chi usa (cittadino mobile, operatore)?
2. **Tone** — su Fixcity: **industrial/utilitarian + civic refined** (non brutalist/random su parity)
3. **Constraints** — stack tabella sopra, WCAG, reference Design Comuni se `/it`
4. **Differentiation** — su parity: chiarezza stati/gerarchia; su greenfield: un dettaglio memorabile documentato

**Intentionality > intensity** (cit. plugin): minimal civic = spacing e tipo precisi, non animazioni sparse.

## checklist pre-merge UI

- [ ] Stack: Tailwind/DaisyUI/Alpine/Lit — no Bootstrap nuovo in Blade
- [ ] Filament-first su admin
- [ ] Contrasto WCAG su stati e CTA
- [ ] `prefers-reduced-motion` se animazioni non triviali
- [ ] Build tema: `cd laravel/Themes/Sixteen && npm run build`
- [ ] Path JS: inglese (`ticket`, non `segnalazione` nel filename)
- [ ] No AI slop: Inter/Roboto FO, purple gradient, Space Grotesk default, shadcn card grid clone
- [ ] Verifica browser (non solo HTTP 200): [visual-parity-verification-rule.md](./visual-parity-verification-rule.md)

## collegamenti

- [ui-ai-tooling-on-demand.md](./ui-ai-tooling-on-demand.md)
- [filament-first-frontoffice.md](./filament-first-frontoffice.md)
- [wizard-parity-documentation-map.md](./wizard-parity-documentation-map.md)
- Root skill: `bashscripts/ai/skills/frontend-design/SKILL.md`
- Geo mappa: `Modules/Geo/docs/wiki/concepts/geo-map-lit-reconstruction-guide.md`
