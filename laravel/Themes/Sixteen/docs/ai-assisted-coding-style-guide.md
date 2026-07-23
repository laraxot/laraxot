---
title: "AI-assisted coding style guide for Sixteen theme (R13-R20)"
type: religion
tags: [sixteen, design-comuni, religion-r13-r20, ai-coding, harness, second-brain, reviewer-checklist, opencode-minimax-m3]
created: 2026-06-05
updated: 2026-06-05
qmd: "ai assisted coding style guide sixteen theme religion r13 r20 harness second brain reviewer checklist opencode minimax"
issues:
  - "https://github.com/laraxot/base_fixcity_fila5/issues/264"
  - "https://github.com/laraxot/base_fixcity_fila5/issues/248"
  - "https://github.com/laraxot/theme_sixteen_fila5/issues/58"
discussions:
  - "https://github.com/laraxot/base_fixcity_fila5/discussions/265"
related:
  - ../../../docs/architecture-ai-assisted-coding-2026-06-05.md
  - ../../../docs/chat/register-flow-religions-r1-r6.md
  - r2-ux-register-form-stacked-password.md
  - ../../Xot/docs/xotbase-schemawidget-pattern.md
  - ../../User/docs/r1-form-fields-self-validate.md
  - ../../Fixcity/docs/r3-one-migration-per-model.md
  - ../../Gdpr/docs/r8-gdpr-register-widget-wins.md
---

# AI-assisted coding style guide for Sixteen theme (R13-R20)

> Tema: `Sixteen` · Autore: opencode (MiniMax-M3) · Issue tracking: base #264, theme #58

## Scopo

Questo documento è il **manuale di stile AI-assisted** per il tema Sixteen. Definisce come gli agenti AI (Codex, opencode, futuro) devono scrivere/modificare file nel tema, in coerenza con le religioni R13-R20 derivate da HackerNoon Tips 001-022 + Code Smell 313/300 + Fowler/Google/Atlassian/IEEE/O'Reilly code review.

## Stack del tema Sixteen

- **Build:** Vite 5 + Tailwind 3 + Lit 3 + DaisyUI 4 + Flowbite
- **Frontoffice routing:** Folio+Volt (livewire)
- **Skin Design Comuni:** `fo-filament-form-shell.css` + `design-comuni-tokens.css`
- **Temi colore:** PA green `#007A52` (primary), Italia blue `#0066CC` (links)

## R13 — Harness install prima di prompt

### Pattern

Prima di scrivere/modificare un file nel tema, l'agente DEVE aver letto:

1. `AGENTS.md` root + `laravel/Themes/Sixteen/AGENTS.md` (nested pattern, Tip 014)
2. `bashscripts/ai/rules/wiki-italian-filename-no-italian.md` (R12)
3. `bashscripts/ai/rules/fo-pa-tokens-uniformity.mdc` (R2 PA tokens)
4. QMD: `bashscripts/docs/llm-wiki-qmd.sh search "<topic>" -n 5 --files`

Se il file tocca CSS: leggere `laravel/Themes/Sixteen/tailwind.config.js` + `design-comuni-tokens.css`.

### MAI senza harness

❌ Aprire chat e digitare "rendi più bello il bottone" senza prima leggere i file rilevanti
❌ Modificare `tailwind.config.js` senza consultare `PaDesignColors`
❌ Aggiungere CSS con hex literal (usare SEMPRE `var(--fixcity-*)`)

## R14 — Second brain (LLM Wiki on-demand)

### QMD search obbligatorio

```bash
bashscripts/docs/llm-wiki-qmd.sh search "sixteen auth css" -n 5 --files
bashscripts/docs/llm-wiki-qmd.sh search "design-comuni tokens" -n 5 --files
```

### Output: solo i file trovati (Tip 013 progressive disclosure)

MAI leggere un'intera cartella. MAI leggere `docs/wiki/rules/*.md` (50 file). MAI leggere `docs/wiki/memories/*.md` (238 file).

## R15 — Commit-first safety net

### Pattern

```bash
# PRIMA di chiedere aiuto all'AI:
git status                    # deve essere pulito O avere un commit baseline
git add . && git commit -m "feat: baseline prima di AI refactor CSS"
# ORA chiedi all'AI
# DOPO l'edit AI:
git diff                      # review delle modifiche
git reset --hard HEAD         # se pessimo
# Se OK:
git add . && git commit -m "refactor: AI-assisted CSS improvement"
```

## R16 — Spec-driven (BMAD)

### Obbligatorio per modifiche non banali

1. Crea/aggiorna `docs/stories/STORY-NNN-sixteen-<slug>.md` con `## GitHub (tracciamento)`
2. Crea/aggiorna `*.dev.md` con piano d'azione
3. Lock file: `touch docs/stories/STORY-NNN-*.md.lock`
4. Apri issue + discussion su `laraxot/theme_sixteen_fila5` (multi-repo)
5. Solo DOPO: codice

## R17 — Comprehension check 3-sentence in PR

### Template PR Sixteen

```markdown
## Cosa è cambiato
[1 frase]

## Perché (vincoli / why)
[1 frase che spiega il constraint, Tip 019]

## Impatto
[1 frase su utenti/sistema]

## AI Context (Tip 016, R19)
- Agente: opencode (MiniMax-M3)
- Skill attive: design-comuni-tokens, fo-pa-tokens-uniformity, no-italian-filename
- Primo tentativo: [cosa ha provato l'AI]
- Correzione umana: [cosa hai cambiato]
- Lezione appresa → AGENTS.md/skills/memories: [link a file aggiornato]
```

## R18 — Why-not-what prompting

### Pattern

```markdown
❌ BAD: "Aggiungi animazione al submit button"

✅ GOOD: "Aggiungi una micro-animazione al submit button del form auth-register,
perché gli utenti mobile aspettano feedback visibile durante il submit (vincolo:
WCAG 2.1 SC 2.2.1 + user expectation), ma DEVE restare sotto 200ms e rispettare
`prefers-reduced-motion` (vincolo: accessibilità). Non usare librerie JS pesanti
(vincolo: budget bundle < 100KB aggiuntivi)."
```

## R19 — PRs teach next agent

### Obbligatorio post-merge

Dopo ogni PR mergiata su `theme_sixteen_fila5`, l'autore AGGIORNA:
- `laravel/Themes/Sixteen/AGENTS.md` se nuova regola di stile
- `bashscripts/ai/skills/sixteen-*.md` se nuovo pattern specifico del tema
- `docs/wiki/memories/sixteen-*.md` se nuova lezione riutilizzabile
- `docs/chat/sixteen-*.md` (append-only) per note di coordinamento

## R20 — No workslop, no package hallucination

### No workslop (Code Smell 313)

❌ CSS rules che non fanno nulla (`.foo { color: red; }` con `.foo` mai usata)
❌ View Blade con wrapper HTML inutile
❌ Helper function chiamata una volta sola
❌ Comment `// TODO` senza ticket

### No package hallucination (Code Smell 300)

❌ Importare un package da CDN che non esiste
❌ `composer require vendor/package-non-esistente`
❌ `npm install package-non-esistente`

### Verifier (TODO `bashscripts/ai/rules/check-no-workslop.sh`)

```bash
#!/bin/bash
# Scansiona Themes/Sixteen/resources/css/*.css per regole morte
# Scansiona Themes/Sixteen/resources/views/**/*.blade.php per wrapper HTML inutili
# Scansiona composer.json + package.json per package sconosciuti (richiede internet)
```

## Reviewer Checklist per Sixteen (R11 ref + §9 architecture doc)

### CSS (Fowler-style)

- [ ] Usa SOLO `var(--fixcity-*)` o `var(--italia-*)` token, MAI hex literal
- [ ] Touch target ≥ 44px (WCAG 2.5.5 AAA)
- [ ] Contrast ratio ≥ 4.5:1 per testo (WCAG 1.4.3)
- [ ] `prefers-reduced-motion` rispettato per animazioni
- [ ] Scope limitato a `body[data-page='...']` quando possibile
- [ ] Nessuna regola con `!important` (usare specificity corretta)
- [ ] Nessuna regola morta (verifier: `bashscripts/ai/rules/check-no-workslop.sh`)

### Blade (FO)

- [ ] Nomi file inglesi, kebab-case (R12)
- [ ] NO `class` Italianizzato (es. `class="segnalazione-card"`)
- [ ] Accessibility: `aria-label`, `aria-describedby`, `role`
- [ ] No magic strings: usa costanti da `config/`
- [ ] Folio+Volt pattern: NO controller, NO `Route::get`
- [ ] Lock file `*.lock` per pagine in editing concorrente

### Volt components

- [ ] `$this->pageSlug` con prefisso `$this->` (no shadowing, vedi #252)
- [ ] Proprietà `#[Locked]` o `#[Validate]` quando serve
- [ ] NO `$this->dispatch()` se non c'è listener

### JS (Vite + Lit + DaisyUI + Flowbite)

- [ ] NO hex inline (usa design-comuni-tokens)
- [ ] NO `class="bg-primary-600"` literal (usa classi DaisyUI semantiche)
- [ ] Component Lit: typed properties, `static styles = css\`...\``
- [ ] `prefers-reduced-motion` per animazioni JS

### Build

- [ ] `npm run build` → output CSS < 1.5MB
- [ ] `npm run dev` HMR funzionante
- [ ] Nessun warning su Lit/DaisyUI/console

## Code review standards (Fowler/Google/Atlassian/IEEE 1028-2008)

> Adattato per AI-assisted context. Vedere [`docs/architecture-ai-assisted-coding-2026-06-05.md` §9](../../../docs/architecture-ai-assisted-coding-2026-06-05.md).

| Standard | Religion Sixteen | Note |
|----------|------------------|------|
| Single responsibility | R13 harness | Ogni file CSS/Blade ha UN purpose |
| No dead code | R20 no-workslop | Cancellare, non commentare |
| No premature optimization | (Pragmatic) | Misurare, non intuire |
| DRY/KISS | (R7) | Accettabile duplicazione se astrazione peggiora |
| Naming reveals intent | R12 | English, kebab-case, no abbreviations |
| Tests cover behavior | R7 BMAD | `npm run test` + playwright-mcp |
| API contracts espliciti | (n/a) | Per Blade: docblock su `@props` |
| Backward compatibility | (n/a) | Tema può rompere visual; document in CHANGELOG |
| Documentation aggiornata | R6 R19 | AGENTS.md + memories post-merge |
| Security OWASP | (n/a) | NO innerHTML, NO eval |
| PR size < 400 LOC | R17 | Reviewable in 15 min |
| At least 1 reviewer | (R7) | Codex o opencode firma review |
| Tests pass in CI | R7 | GitHub Actions su push |
| No merge di codice non compreso | R17 | 3-sentence check |
| AI Context in PR | R19 | Sezione obbligatoria |
| Lessons learned → AGENTS.md | R19 | Aggiornare pre-close |

## Esempi completi

### Esempio 1: refactor auth-register.css (R2 + R13 + R20)

```bash
# 1. Harness (R13)
cat laravel/Themes/Sixteen/AGENTS.md
cat laravel/Themes/Sixteen/tailwind.config.js
cat laravel/Themes/Sixteen/resources/css/components/design-comuni-tokens.css

# 2. QMD search (R14)
bashscripts/docs/llm-wiki-qmd.sh search "fo-auth-input" -n 5 --files

# 3. Spec (R16)
echo "## GitHub (tracciamento)" >> docs/stories/STORY-145-sixteen-auth-register-css-refactor.md
touch docs/stories/STORY-145-sixteen-auth-register-css-refactor.md.lock

# 4. Commit baseline (R15)
git add . && git commit -m "feat: baseline auth-register.css pre-refactor"

# 5. Codice (R1, R2, R20)
# Edita solo:
# - Touch target 44px
# - Stacked password (no Grid(2))
# - var(--fixcity-*) tokens
# - WCAG 2.1 AA contrast
# - prefers-reduced-motion

# 6. Verifier (R20)
bashscripts/ai/rules/check-no-workslop.sh laravel/Themes/Sixteen/resources/css/app/14-auth-register.css

# 7. Review diff (R17)
git diff

# 8. PR (R17, R19)
gh pr create --title "refactor: auth-register.css WCAG 2.1 AA" \
  --body "[3 sentences + AI Context]"

# 9. Post-merge (R19)
# Update AGENTS.md, skills, memories
```

### Esempio 2: aggiungere dark mode (R13 + R18 + R19)

```bash
# 1. PRIMA chiedi all'AI con VINCOLI (R18):
# "Aggiungi dark mode al tema Sixteen.
# Perché: FO usato di sera da dipendenti comunali anziani.
# Vincoli: contrasto AA su TUTTI i token PA, rispettare prefers-color-scheme,
# NO JS aggiuntivo (solo CSS variables + media query), backward compat con light."
```

## Riferimenti

- Issue base: [#264](https://github.com/laraxot/base_fixcity_fila5/issues/264)
- Discussion base: [#265](https://github.com/laraxot/base_fixcity_fila5/discussions/265)
- Issue theme: [#58](https://github.com/laraxot/theme_sixteen_fila5/issues/58)
- Architecture doc completo: [`docs/architecture-ai-assisted-coding-2026-06-05.md`](../../../docs/architecture-ai-assisted-coding-2026-06-05.md)

---
*opencode (MiniMax-M3) · 2026-06-05 · Style guide for AI-assisted coding in Sixteen theme*
