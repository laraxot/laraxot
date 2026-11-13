---
title: "MERGE CONFLICT RESOLUTION LOG"
type: note
tags: [documentation]
created: 2026-09-26
updated: 2026-09-26
qmd: "MERGE CONFLICT RESOLUTION LOG"
issues: []
discussions: []
status: draft
---

# Merge Conflict Resolution Log

> **Data inizio**: 2026-04-07
> **Obiettivo**: Risolvere TUTTI i conflitti di merge nel codebase
> **Regola**: Forward-only Git - NEVER revert/reset, sempre studiare e migliorare

---
## File Risolti

### 1. skills-lock.json
**Path**: `/var/www/_bases/base_fixcity_fila5/skills-lock.json`
**Conflitto**: HEAD vuoto vs origin/dev aggiunge skill `frontend-design`
**Risoluzione**: Mantenuto aggiunta da origin/dev - nuova skill utile
**Tipo**: JSON config

### 2. laravel/Modules/Xot/composer.json
**Path**: `/var/www/_bases/base_fixcity_fila5/laravel/Modules/Xot/composer.json`
**Conflitto**: HEAD ha `barryvdh/laravel-debugbar`, origin/dev no
**Risoluzione**: Mantenuto debugbar da HEAD - utile per debugging
**Tipo**: PHP dependencies

### 3. laravel/opencode.json
**Path**: `/var/www/_bases/base_fixcity_fila5/laravel/opencode.json`
**Conflitto**: 
- HEAD: solo MCP laravel-boost
- origin/dev: aggiunge agent BMAD + MCP context7
**Risoluzione**: Merged entrambi - agents BMAD + entrambi i MCP servers
**Tipo**: AI/MCP configuration

### 4. laravel/Themes/Sixteen/docs/00-index.md
**Path**: `/var/www/_bases/base_fixcity_fila5/laravel/Themes/Sixteen/docs/00-index.md`
**Conflitto**: origin/dev aggiunge docs segnalazione parity
**Risoluzione**: Mantenute nuove docs da origin/dev
**Tipo**: Documentation index

### 5. laravel/Themes/Sixteen/docs/design-comuni/00-index.md
**Path**: `/var/www/_bases/base_fixcity_fila5/laravel/Themes/Sixteen/docs/design-comuni/00-index.md`
**Conflitto**: 4 sezioni con conflitti
**Risoluzione**: Merged ALL unique entries from both HEAD and origin/dev
- Analysis docs aggiunte
- Homepage parity docs mantenuti  
- Segnalazione-flow docs inclusi
- Cross links merged (replikate.txt, STATUS.md, screenshots, pages)
**Tipo**: Documentation index

### 6. CSS Files (Precedentemente risolti)
**Paths**:
- `laravel/Themes/Sixteen/resources/css/app.css`
- `laravel/Themes/Sixteen/resources/css/homepage-visual-fix.css`
- `laravel/Themes/Sixteen/resources/css/design-comuni-global.css`
**Risoluzione**: Risolti in sessioni precedenti con build successful
**Tipo**: CSS (blocking build)

### 7. Blade Templates (Precedentemente risolti)
**Paths**:
- `laravel/Themes/Sixteen/resources/views/components/blocks/feedback/rating.blade.php`
- `laravel/Themes/Sixteen/resources/views/components/blocks/governance/cards.blade.php`
- `laravel/Themes/Sixteen/resources/views/components/blocks/topics/highlight.blade.php`
- `laravel/Themes/Sixteen/resources/views/components/blocks/hero/homepage.blade.php`
**Risoluzione**: Risolti in sessioni precedenti - strutture match reference
**Tipo**: Blade templates

### 8. Homepage Blocks Documentation (Nuovo)
**Paths**: Creati 11 file in `prompts/homepage/blocks/`
**Files**:
- `00-index.md` - Index con struttura completa homepage
- `01-header-slim.md` - Barra superiore (regione, lingua, accesso)
- `02-header-center.md` - Logo comune + social
- `03-header-navbar.md` - Navigazione principale
- `04-hero-section.md` - Notizia in evidenza
- `05-governance-calendar.md` - Cards governo + calendario eventi
- `06-evidence-section.md` - Argomenti evidenza + siti tematici
- `07-useful-links.md` - Ricerca + link utili
- `08-rating-feedback.md` - Valutazione stelle + multi-step
- `09-contacts-section.md` - Contatti comune + disservizi
- `10-footer.md` - Footer completo
**Tipo**: Documentation
**Paths**:
- `laravel/Themes/Sixteen/resources/views/components/blocks/feedback/rating.blade.php`
- `laravel/Themes/Sixteen/resources/views/components/blocks/governance/cards.blade.php`
- `laravel/Themes/Sixteen/resources/views/components/blocks/topics/highlight.blade.php`
- `laravel/Themes/Sixteen/resources/views/components/blocks/hero/homepage.blade.php`
**Risoluzione**: Risolti in sessioni precedenti - strutture match reference
**Tipo**: Blade templates

---

## Strategia di Risoluzione

### Per JSON
- Studiare entrambe le versioni
- Mantenere le aggiunte da entrambi i lati
- Assicurarsi sintassi JSON valida
- Mai rimuovere features utili

### Per CSS
- Risolvere conflitti mantenendo fixes da entrambi i lati
- Assicurarsi che build funzioni
- Rimuovere duplicati, tenere unique additions

### Per Blade
- Studiare struttura reference
- Scegliere versione più vicina al reference
- Merge improvements da entrambi i lati
- Garantire sintassi Blade valida

### Per Docs/Markdown
- Leggere ENTRAMBE le versioni completamente
- Preservare TUTTE le informazioni uniche
- Rimuovere duplicati
- Mantenere bidirectional links
- Creare documenti unificati e comprensivi

---

## File Rimasti da Risolvere

Controllare con:
```bash
grep -rl "<<<<<< HEAD\|<<<<<<< HEAD" . --include="*.md" | grep -v "node_modules\|vendor\|.git" | wc -l
```

### Per Modulo
- [ ] Modules/Activity/docs/* (4 file)
- [ ] Modules/User/docs/* (many files)
- [ ] Altri moduli...

### Per Tema
- [ ] Sixteen/docs/design-comuni/pages-analysis.md
- [ ] Sixteen/docs/design-comuni/analysis/INDEX.md
- [ ] Sixteen/docs/design-comuni/HTML_PARITY_*.md (3 file)
- [ ] Sixteen/docs/design-comuni/pages/homepage.md
- [ ] Sixteen/docs/design-comuni/homepage-structure-analysis.md

---

## Link Bidirezionali

- ← [Master Index Tema](./00-INDEX.md)
- ← [Design Comuni Index](./design-comuni/00-index.md)
- → [Sessione 90% Similarity](./prompts/homepage/html-similarity-90-percent-session.md)

---

## Audit 2026-10-26 — marker semantici nel frontoffice

`git status --short --unmerged` è vuoto: questi non erano conflitti Git aperti,
ma marker di merge già committati nel contenuto runtime. Sono stati studiati
entrambi i lati e risolti così:

| File | Lato scelto | Motivo |
|---|---|---|
| `config/local/fixcity/database/content/pages/blog/index.json` | terza soluzione: mantenere `ente: toll_free` | il numero verde deve arrivare dal tenant, non da un numero demo; il blocco calendario resta intatto |
| `config/local/fixcity/database/content/pages/events/index.json` | terza soluzione: mantenere `ente: toll_free` | stessa regola del blog, con contenuto JSON valido |
| `config/local/fixcity/database/content/sections/header.json` | current semantico: rimuovere `name`/`url` vuoti | evita placeholder che producono link/etichette regionali falsi; titolo e aria-label restano |
| `resources/views/components/blocks/ticket-list/design-comuni.blade.php` | terza soluzione: rimuovere entrambe le alternative | `$phoneItems` era inutilizzato e duplicava la voce `ente: toll_free` già passata ai partial condivisi |
| `resources/views/components/sections/footer/v1.blade.php` | **terza via**: struttura incoming + etichette current | l’incoming è l’unico ramo che chiude correttamente il blocco Design Comuni e definisce `columnsWithLinks`, `legalLinks` e `socialLinks` (il ramo Tailwind lasciava variabili e struttura incompatibili), ma leggeva le etichette da `pub_theme::footer.*`, che esiste solo in italiano: nel footer tedesco compariva «Telefono:» e orari e numero verde restavano senza etichetta. Le etichette tornano da `footer.json` → `contact.labels` (4 lingue), dal current, e il DPO diventa «Ruolo (Nome)». Provato da `FooterV1DesignComuniStructureTest` (rosso, poi verde: 58 test, 729 asserzioni con `EnteDataFrontOfficeTest`) |

**Criterio di scelta** (imparato su questi 5 file): non si sceglie il lato, si sceglie in base al codice **condiviso fuori dai marker**. Se le righe subito dopo il marker appartengono a un lato (qui `return $link; }, $activeLinks($legal_links));` era la coda del ramo incoming), prendere l’altro lascia un file rotto. Poi si confrontano i due lati funzione per funzione: ciò che l’uno ha e l’altro ha perso (qui le etichette in 4 lingue) va riportato, e va dimostrato con un test rosso prima di dirlo risolto. Un hunk con un lato vuoto non è per forza «niente da fare»: di solito è una rimozione deliberata (qui DCP-289 ha tolto `$phoneItems`), da verificare che nulla lo usi più.

Decisioni operative: Sixteen possiede markup, Blade e parity; Fixcity/Tenant
possiedono dati, Actions e contratti. La validazione minima è assenza di marker,
JSON valido e compilazione Blade; la parity visuale va verificata a 375, 768 e
1440 px prima della release.

**Stato**: ✅ marker runtime auditati e risolti; docs storiche precedenti restano archivio
**Build**: ✅ CSS/JS funzionano
**Prossimo**: Risolvere file docs rimanenti in batch