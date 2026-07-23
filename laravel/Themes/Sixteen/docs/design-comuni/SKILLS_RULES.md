# Design Comuni - Skills & Rules Documentation

## Panoramica

Documentazione delle skills, rules e best practices per il progetto Design Comuni Italia.

- **Data**: 2026-04-03
- **Progetto**: Replicazione 54 pagine Design Comuni con Tailwind CSS + Alpine.js
- **Stato**: ✅ 63.0% OK (34/54 pagine)

## 🎯 Skills Richieste

### 1. HTML Structure Analysis
- **Skill**: Analizzare struttura HTML reference vs local
- **Tool**: `capture-all-pages.js`
- **Output**: Screenshots comparativi + report strutturale
- **Target**: 90%+ match struttura HTML

### 2. JSON Content Creation
- **Skill**: Creare JSON content per pagine mancanti
- **Tool**: `create-missing-json-simple.js`
- **Output**: File JSON in `config/local/fixcity/database/content/pages/`
- **Struttura**: breadcrumb, hero, card-list, content, form

### 3. Blade Template Creation
- **Skill**: Creare blade templates per blocchi universali
- **Pattern**: `components/blocks/<tipo>/<variante>.blade.php`
- **Namespace**: `pub_theme::components.blocks.<tipo>.<variante>`
- **Target**: Match struttura Bootstrap Italia con Tailwind @apply

### 4. CSS Implementation
- **Skill**: Replicare design Bootstrap Italia con Tailwind @apply
- **File**: `Themes/Sixteen/resources/css/components/design-comuni.css`
- **Regola**: NO Bootstrap Italia, SI Tailwind + Alpine.js
- **Target**: 95%+ match visivo

### 5. Alpine.js Integration
- **Skill**: Implementare interattività con Alpine.js
- **Pattern**: `x-data`, `@click`, `:class`, `x-show`, `x-cloak`
- **Target**: Sostituire Bootstrap JS con Alpine.js

### 6. Screenshot Analysis
- **Skill**: Catturare e analizzare screenshots comparativi
- **Tool**: `capture-*.js` scripts
- **Output**: `Themes/Sixteen/docs/design-comuni/screenshots/`
- **Target**: Documentare differenze visive

### 7. Documentation Maintenance
- **Skill**: Mantenere documentazione con link bidirezionali
- **Pattern**: Indici per modulo, tema, master index
- **Target**: Ogni documento linka e è linkato da altri documenti

## 📋 Rules e Convenzioni

### Regole HTML
1. ✅ Struttura HTML deve matchare reference al 90%+
2. ✅ Usare classi Bootstrap Italia (replicate con Tailwind @apply)
3. ✅ NO import Bootstrap Italia CSS/JS
4. ✅ SI Tailwind CSS + Alpine.js
5. ✅ Mantenere attributi ARIA per accessibilità

### Regole JSON Content
1. ✅ Ogni pagina deve avere file JSON in `config/local/fixcity/database/content/pages/`
2. ✅ Naming: `tests.<nome-pagina>.json`
3. ✅ Struttura minima: breadcrumb + hero + content blocks
4. ✅ View namespace: `pub_theme::components.blocks.<tipo>.<variante>`

### Regole Blade Templates
1. ✅ File in `Themes/Sixteen/resources/views/components/blocks/`
2. ✅ Naming: `<tipo>/<variante>.blade.php`
3. ✅ Props: `@props(['data' => []])`
4. ✅ Match struttura HTML reference esattamente

### Regole CSS
1. ✅ File: `Themes/Sixteen/resources/css/components/design-comuni.css`
2. ✅ Usare `@apply` per classi Tailwind
3. ✅ Replicare classi Bootstrap Italia esattamente
4. ✅ NO import Bootstrap Italia

### Regole JavaScript
1. ✅ File: `Themes/Sixteen/resources/js/app.js`
2. ✅ Usare Alpine.js per interattività
3. ✅ NO Bootstrap Italia JS
4. ✅ `Alpine.start()` dopo setup componenti

### Regole Documentazione
1. ✅ Ogni modulo/tema deve avere index con link bidirezionali
2. ✅ Master Index in `docs/design-comuni/MASTER_INDEX.md`
3. ✅ Screenshots in `Themes/Sixteen/docs/design-comuni/screenshots/`
4. ✅ Scripts in `bashscripts/design-comuni/`
5. ✅ Script docs in `bashscripts/docs/`

## 🔧 Workflow Standard

### Per Nuova Pagina
1. Analizzare reference con `capture-*.js`
2. Creare JSON content con `create-missing-json-simple.js`
3. Creare/aggiornare blade templates se necessari
4. Aggiungere CSS se necessari
5. Build: `npm run build && npm run copy`
6. Verificare con `verify-*.js`
7. Documentare risultati

### Per Fix Pagina
1. Analizzare differenze con `analyze-*.js`
2. Identificare componenti mancanti
3. Fix JSON/Blade/CSS/JS
4. Clear cache: `php artisan view:clear && php artisan config:clear`
5. Build: `npm run build && npm run copy`
6. Verificare con `verify-*.js`
7. Documentare risultati

## 📊 Metriche Target

| Metrica | Target | Attuale |
|---------|--------|---------|
| OK (≥70%) | 90%+ (49/54) | 63.0% (34/54) |
| Warning (50-69%) | <10% (5/54) | 29.6% (16/54) |
| Fail (<50%) | 0% (0/54) | 7.4% (4/54) |

## 📚 Link Correlati

- **Master Index**: [docs/design-comuni/MASTER_INDEX.md](../../../docs/design-comuni/MASTER_INDEX.md)
- **Cms Index**: [laravel/Modules/Cms/docs/DESIGN_COMUNI_INDEX.md](../../../laravel/Modules/Cms/docs/DESIGN_COMUNI_INDEX.md)
- **UI Index**: [laravel/Modules/UI/docs/00-index.md](../../../laravel/Modules/UI/docs/00-index.md)
- **Theme Index**: [laravel/Themes/Sixteen/docs/design-comuni/00-index.md](../../../laravel/Themes/Sixteen/docs/design-comuni/00-index.md)
- **Progress Report**: [laravel/Themes/Sixteen/docs/design-comuni/PROGRESS_REPORT.md](../../../laravel/Themes/Sixteen/docs/design-comuni/PROGRESS_REPORT.md)

---

**Data**: 2026-04-03  
**Stato**: ✅ 63.0% OK  
**Target**: 90%+ OK  
**Manutenitore**: AI Agent Team
