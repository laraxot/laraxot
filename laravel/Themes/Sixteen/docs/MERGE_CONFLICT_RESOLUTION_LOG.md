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

- ← [Master Index Tema](./00-index.md)
- ← [Design Comuni Index](./design-comuni/00-index.md)
- → [Sessione 90% Similarity](./prompts/homepage/HTML_SIMILARITY_90_PERCENT_SESSION.md)

---

**Stato**: ✅ 7 categorie risolte, in progress per docs rimanenti
**Build**: ✅ CSS/JS funzionano
**Prossimo**: Risolvere file docs rimanenti in batch