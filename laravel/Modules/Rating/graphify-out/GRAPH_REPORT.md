# Graph Report - laravel/Modules/Rating  (2026-08-20)

## Corpus Check
- 301 files · ~64,032 words
- Verdict: corpus is large enough that graph structure adds value.

## Summary
- 1833 nodes · 1926 edges · 210 communities (183 shown, 27 thin omitted)
- Extraction: 100% EXTRACTED · 0% INFERRED · 0% AMBIGUOUS · INFERRED: 8 edges (avg confidence: 0.8)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `baaf757f`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- RatingData
- HasRatingsTrait.php
- Modules\Xot\Filament\Resources\Tables\XotBaseResourceTable
- composer.json
- Rating Module - FAQ
- Modules\Xot\Contracts\UserContract
- INDEX_GENERATED.md
- Dettaglio Metodi Duplicati
- BaseListRatings
- BaseRating
- devDependencies
- 🐄⚡ ANALISI METODI DUPLICATI - SUPER MUCCA EDITION
- Rating Module — Mappa Graphify
- prd.json
- Rating System - Architecture Analysis & Fixes
- `F_trait_name_collision`
- Rating Architecture
- Rating Module
- Rating {{TYPE^}} LLM Wiki Agent Instructions
- Rating {{TYPE^}} LLM Wiki Agent Instructions
- A — Hook framework con corpo identico (override ridondante / candidato default XotBase)
- Rating - Sprint Planning Meeting
- contributor-lines-report.mjs
- Rating Module LLM Wiki Agent Instructions
- Product Requirements Document (PRD)
- ⭐ Rating Module - Sistema di Valutazione
- Rating Module — PHPStan Type Compliance
- Rating Module - Product Strategy
- project-structure.md
- Rating Module - User Research
- 🏗️ Core Patterns
- Rating Module - Product Roadmap
- Error Categories
- Documentation by Category
- Fase 1 · Stabilizzazione della base tecnica
- performance-optimization.md
- Pre-Launch Checklist
- ⭐ Rating — English presentation
- wiki/index.md
- Code redundancy audit — Rating
- PHPStan Generic Type Limitations in Laravel
- Rating Module Wiki Index
- PHPStan Level 10 Errors Roadmap - Modulo Rating
- Rating - Product Launch Plan
- Schemaless Attributes — Rating Module
- architecture.md
- Cyclomatic Complexity Report - Module: Rating
- Graphify Knowledge Graph
- Rating Module — Migrations
- PHPStan Fixes - Modulo Rating
- Rating Module Schema
- 1.10: Scheda Search + Filter (Employee, Year, Status)
- 1.1: Create Scheda
- 1.3: Draft → Submitted State Flow
- 1.4: Soft-delete + Restore Within 30 Days
- 1.5: Bulk Create From CSV
- 1.8: Edit Submitted Scheda (Audit Trail)
- 1.9: Approval Workflow (Manager Sign-off)
- Rating Module LLM Wiki
- Illuminate\Database\Seeder
- Code Quality Analysis - Rating Module
- Rating - Product Strategy
- Problemi Trovati
- Fasi di Sviluppo del Modulo Rating
- 🏗️ Key Features
- Rating — test e coverage
- Filament Resource Zen Pattern (Rating Module)
- PHPStan Generic Limitation: MorphToMany with static
- BaseRatingForm.php
- Ottimizzazioni Applicate
- PHPStan Level 10 Compliance — Rating Module
- Errori Trovati
- Story 2.1: Rating — adottare il contratto Pest condiviso e alzare la coverage
- RatingsRelationManager
- docs/index.md
- Case Sensitivity Rules - Rating Module
- Report: Metodi con nome duplicato nei moduli e nei temi
- Report: Metodi con nome duplicato nei moduli e nei temi
- Handoff — multi-org sync (STORY-003)
- Metriche e obiettivi di qualità
- Rating Module - Sprint Planning
- Visual Testing con Playwright e Puppeteer — Modulo Rating
- wiki/log.md
- Rating Wiki Overview
- `composer.json` Dependencies
- Upgrade Laravel 13 - Rating 🐄✨
- Sincronizzazione multi-organizzazione (laraxot + provtv)
- Rating Module Architecture
- 📄 Documenti
- Best Practices – Rating
- 🐄 DRY & KISS Analysis - Rating
- Rating — Framework Integration Notes
- 4. Applico la regola/skill/command/memory
- ponytail-audit-over-engineering.md
- qmd-setup.md
- Cerca in docs/wiki/ + tutti i moduli
- Raw Sources — Rating
- Checklist Qualità Modulo Rating
- Visione del Modulo Rating
- Commands Index
- Rating Module — Context-Mode Discipline
- Memories Index
- Tabelle Filament e migration `ratings`
- Contributing
- CHANGELOG.md
- Bad Practices – Rating
- Bad Practices – Rating
- docs/CHANGELOG.md
- Conflict Resolution — Module Rating
- Contracts Naming & Placement in Rating
- on-demand-pattern.md
- On-Demand Pattern — Module **Rating**
- PRD: Rating Module
- Release e README marketing — Rating
- Rating e composer root minimale
- Pest PHP
- EventServiceProvider
- Metodi duplicati — Rating
- Metodi duplicati — Rating
- Headroom - Modulo Rating
- Product Launch Plan: Rating Module
- Product Strategy: Rating Module
- Gitmodules sync session
- Wiki Schema - Rating
- fromString
- Dashboard.php
- AdminPanelProvider.php
- RouteServiceProvider.php
- llm-wiki/log.md
- Changelog
- root-import/changelog.md
- root-files-hygiene.md
- root-md-files/changelog.md
- Sprint Planning: Rating Module
- User Research: Rating Module
- webpack.mix.js
- binary-assets.md
- confidence_guidelines.md
- confidence-guidelines.md
- docs-archive-policy.md
- FALSE_FRIENDS.md
- false-friends.md
- security.md
- single.blade.php
- vite.config.js
- Modules\Rating\Models\Contracts\HasRatingContract
- Illuminate\Database\Eloquent\Model
- Illuminate\Database\Eloquent\Relations\MorphToMany
- SafeStringCastAction
- BaseRatingMorph
- TestCase
- Modules\Xot\Filament\Resources\Pages\XotBaseCreateRecord
- BaseModel
- RatingData
- BaseMorphPivot
- Item
- RatingFactory
- HasRatingContract.php
- RatingServiceProvider.php

## God Nodes (most connected - your core abstractions)
1. `BaseRating` - 12 edges
2. `Rating Module — Mappa Graphify` - 12 edges
3. `Rating` - 11 edges
4. `Rating Module - FAQ` - 11 edges
5. `Rating {{TYPE^}} LLM Wiki Agent Instructions` - 11 edges
6. `Rating {{TYPE^}} LLM Wiki Agent Instructions` - 11 edges
7. `Rating Module LLM Wiki` - 11 edges
8. `RatingMorphPolicy` - 10 edges
9. `Rating Module` - 10 edges
10. `Rating Module Wiki Index` - 10 edges

## Surprising Connections (you probably didn't know these)
- `myRatings()` --calls--> `Rating`  [INFERRED]
  laravel/Modules/Rating/app/Models/Traits/HasRatingsTrait.php → laravel/Modules/Rating/app/Models/Rating.php
- `ratingObjectives()` --calls--> `Rating`  [INFERRED]
  laravel/Modules/Rating/app/Models/Traits/HasRatingsTrait.php → laravel/Modules/Rating/app/Models/Rating.php
- `ratings()` --calls--> `Rating`  [INFERRED]
  laravel/Modules/Rating/app/Models/Traits/HasRatingsTrait.php → laravel/Modules/Rating/app/Models/Rating.php
- `syncRatingsWhere()` --calls--> `Rating`  [INFERRED]
  laravel/Modules/Rating/app/Models/Traits/HasRatingsTrait.php → laravel/Modules/Rating/app/Models/Rating.php
- `BaseEditRatingStub` --inherits--> `BaseEditRating`  [EXTRACTED]
  laravel/Modules/Rating/tests/Unit/RatingFilamentExtendedTest.php → laravel/Modules/Rating/app/Filament/Resources/RatingResource/Pages/BaseEditRating.php

## Import Cycles
- None detected.

## Communities (210 total, 27 thin omitted)

### Community 0 - "RatingData"
Cohesion: 0.15
Nodes (10): self, RatingData, Rating, Block, Filament\Forms\Components\Builder\Block, Modules\Rating\Enums\SupportedLocale, RatingData, Spatie\LaravelData\Data (+2 more)

### Community 1 - "HasRatingsTrait.php"
Cohesion: 0.23
Nodes (10): getMyRatingAttribute(), getRatingsRules(), getRatingsWhere(), ratingRuleToString(), ratings(), syncRatingsWhere(), Illuminate\Support\Collection, Modules\Xot\Actions\Cast\SafeStringCastAction (+2 more)

### Community 2 - "Modules\Xot\Filament\Resources\Tables\XotBaseResourceTable"
Cohesion: 0.06
Nodes (14): BetTableAction, EditRatingMorph, RatingMorphsTable, RatingMorphTable, BaseEditRating, EditRating, BaseRatingsTable, RatingsTable (+6 more)

### Community 3 - "composer.json"
Cohesion: 0.04
Nodes (48): dealerdirect/phpcodesniffer-composer-installer, pestphp/pest-plugin, wikimedia/composer-merge-plugin, authors, autoload, autoload-dev, psr-4, psr-4 (+40 more)

### Community 4 - "Rating Module - FAQ"
Cohesion: 0.04
Nodes (47): Are ratings moderation-approved?, Average returns NULL, Cache becomes stale, Can I add extra columns?, Can I customize the Resource?, Can I delete a rating?, Can I downgrade?, Can I rate anything? (+39 more)

### Community 5 - "Modules\Xot\Contracts\UserContract"
Cohesion: 0.11
Nodes (19): dislikedBy(), isLikedBy(), likedBy(), RatingMorphPolicy, RatingPolicy, Rating, RatingMorph, bootHasLikes() (+11 more)

### Community 6 - "INDEX_GENERATED.md"
Cohesion: 0.05
Nodes (8): Disciplina agenti per massimizzare la confidenza, Massima confidenza agente, Filament Version Declaration — Rating, Rating redundancy audit 2026-05-21, Root file policy, User Interface - Modulo Rating, concepts Index — Rating, No rm, no archive folders, use .old suffix

### Community 7 - "Dettaglio Metodi Duplicati"
Cohesion: 0.05
Nodes (38): 1. Metodo: `user`, 2. Metodo: `casts`, 3. Metodo: `registerMediaConversions`, 4. Metodo: `model`, 5. Metodo: `scopeWithExtraAttributes`, 6. Metodo: `profile`, Analisi Metodi Duplicati - Modulo Rating, 📊 Analisi Refactoring (+30 more)

### Community 8 - "BaseListRatings"
Cohesion: 0.22
Nodes (4): ListRatingMorphs, BaseListRatings, ListRatings, Modules\Xot\Filament\Resources\Pages\XotBaseListRecords

### Community 9 - "BaseRating"
Cohesion: 0.15
Nodes (10): BaseRating, scopeWithRating(), Illuminate\Database\Eloquent\Builder, Modules\Media\Models\Media, SlugOptions, Spatie\MediaLibrary\HasMedia, Spatie\MediaLibrary\InteractsWithMedia, Spatie\MediaLibrary\MediaCollections\Models\Media (+2 more)

### Community 10 - "devDependencies"
Cohesion: 0.05
Nodes (36): autoprefixer, axios, cross-env, laravel-mix, laravel-mix-merge-manifest, laravel-vite-plugin, lodash, devDependencies (+28 more)

### Community 11 - "🐄⚡ ANALISI METODI DUPLICATI - SUPER MUCCA EDITION"
Cohesion: 0.06
Nodes (30): 🐄⚡ ANALISI METODI DUPLICATI - SUPER MUCCA EDITION, 📊 ANALISI QUANTITATIVA REALE, BaseModel - Confronto Reale, Blog BaseModel (BEN FATTO), 🏆 CONCLUSIONI SUPER MUCCA, Cosa Abbiamo Scoperto, Dati Chiave (VERIFICATI), 🎯 Executive Summary (+22 more)

### Community 12 - "Rating Module — Mappa Graphify"
Cohesion: 0.07
Nodes (27): 🏗️ Architettura Essenziale, Checklist Copertura, ✅ Checklist Setup Nuovo Modulo con Rating, 🚀 Comandi Rapidi, 📌 Cosa fa il modulo Rating, 📋 Dependency Diagram, 🔗 Dipendenze Esterne, Entry Points (+19 more)

### Community 13 - "prd.json"
Cohesion: 0.07
Nodes (26): actionStandard, architectureRule, companionDocs, dependencies, goals, implementationPattern, name, nonGoals (+18 more)

### Community 14 - "Rating System - Architecture Analysis & Fixes"
Cohesion: 0.07
Nodes (26): 1. Form Enhancement - Show Total Points, 2. Validation Rules - Fix "tot" field issue, 3. Documentation Updates, Cross-Module References, Cross-Module Usage Pattern, 📋 Current Problem Analysis, Current Ratings (Anno: 2025), 📚 Documentation Updates Required (+18 more)

### Community 15 - "`F_trait_name_collision`"
Cohesion: 0.07
Nodes (27): `A_filament_framework` (22 metodi), Censimento omonimi metodi — Rating, `configureEmailVerification` — 2 classi, Dettaglio, `E_scheda_stack`, `F_trait_name_collision`, `fromArray` — 23 classi, `getActions` — 6 classi (+19 more)

### Community 16 - "Rating Architecture"
Cohesion: 0.08
Nodes (24): 1. **Rating Model** - Central Data Entity, 2. **HasRatingsTrait** - Shared Business Logic, 3. **RuleEnum** - Validation Rules Standardization, Code Quality Standards, Core Components Architecture, Cross-Module Communication Architecture, 🗄️ Database Optimization, 📖 Documentation Navigation (+16 more)

### Community 17 - "Rating Module"
Cohesion: 0.09
Nodes (23): Architecture, Best Practices, Core Components, Creazione Rating, Data Quality, Documentation, Events, Filament Resources (+15 more)

### Community 18 - "Rating {{TYPE^}} LLM Wiki Agent Instructions"
Cohesion: 0.10
Nodes (21): Before Committing, Create Module Page If:, Cross-Linking to Project Wiki, Directory Rules, Frontmatter Schema, Ingest, Lint, Naming Conventions (+13 more)

### Community 19 - "Rating {{TYPE^}} LLM Wiki Agent Instructions"
Cohesion: 0.10
Nodes (21): Before Committing, Create Module Page If:, Cross-Linking to Project Wiki, Directory Rules, Frontmatter Schema, Ingest, Lint, Naming Conventions (+13 more)

### Community 20 - "A — Hook framework con corpo identico (override ridondante / candidato default XotBase)"
Cohesion: 0.10
Nodes (21): A — Hook framework con corpo identico (override ridondante / candidato default XotBase), B — Business logic con corpo identico (consolidare: 1 owner), C — Corpo identico, nomi diversi (copy-paste con rename), `casts` — 2 classi · 9 righe · ~9 righe duplicate, `casts` — 3 classi · 10 righe · ~20 righe duplicate, Corpi metodo duplicati — Rating, `create` / `delete` / `forceDelete` / `restore` / `reverse` / `update` — 11 classi · 3 righe · ~30 righe duplicate, `create` / `delete` / `update` / `view` / `viewAny` — 10 classi · 3 righe · ~27 righe duplicate (+13 more)

### Community 21 - "Rating - Sprint Planning Meeting"
Cohesion: 0.10
Nodes (18): Dipendenze, M1 - Convergenza Core, M2 - Superfici Vere, M3 - Eccellenza Operativa, Milestone, Orizzonte 0-30 giorni, Orizzonte 30-90 giorni, Orizzonte 90-180 giorni (+10 more)

### Community 22 - "contributor-lines-report.mjs"
Cohesion: 0.16
Nodes (19): args, barChartSvg(), buildHtml(), buildSummary(), clocData, collectCloc(), collectGitChurn(), cwd (+11 more)

### Community 23 - "Rating Module LLM Wiki Agent Instructions"
Cohesion: 0.11
Nodes (18): Directory Rules, Directory Rules, Frontmatter Schema, Frontmatter Schema, Naming Conventions, Naming Conventions, Rating Module LLM Wiki Agent Instructions, Rating Module LLM Wiki Agent Instructions (+10 more)

### Community 24 - "Product Requirements Document (PRD)"
Cohesion: 0.11
Nodes (18): 1. Panoramica del Prodotto, 2. Problema, 3.1 Rating System, 3.2 Review System, 3.3 Schemaless, 3. Soluzione Proposta, 4. Scope, 5. Dipendenze (+10 more)

### Community 25 - "⭐ Rating Module - Sistema di Valutazione"
Cohesion: 0.11
Nodes (18): Activity Module, 🏗️ Architettura, Core Models, 📝 Database Schema, Directory Structure, 🔗 Integrazioni Cross-Module, 📄 License & Authors, Migrations (+10 more)

### Community 26 - "Rating Module — PHPStan Type Compliance"
Cohesion: 0.11
Nodes (17): CI/CD Pipeline, Controllers & HTTP, Enforcement, Host di `HasRatingsTrait`, Models & Attributes, Module Structure, Next Review, Pre-commit Hook (+9 more)

### Community 27 - "Rating Module - Product Strategy"
Cohesion: 0.12
Nodes (16): Executive Summary, Financial Projections, Go-to-Market Strategy, Market Analysis, Phase 1: Core (Q1 2026), Phase 2: Trust (Q2-Q3 2026), Phase 3: Intelligence (Q4 2026), Pillar 1: Authenticity (+8 more)

### Community 28 - "project-structure.md"
Cohesion: 0.12
Nodes (16): 1. Crea struttura wiki, 2. Crea INDEX files (già creati), 3. Aggiungi a QMD collection (opzionale, già incluso global), 4. Committa, Convenzioni, Directory Structure, ... etc, File Chiave (+8 more)

### Community 29 - "Rating Module - User Research"
Cohesion: 0.12
Nodes (16): Active Reviewer, Finding 1: Simplicity Drives Participation, Finding 2: Trust Signals Important, Finding 3: Timing Matters, Finding 4: Recognition Valued, Immediate, Key Findings, Long-Term (+8 more)

### Community 30 - "🏗️ Core Patterns"
Cohesion: 0.13
Nodes (15): 1. Polymorphic Rating Pattern, 2. Audit Trail Pattern, 3. Score Aggregation Pattern, 4. Category Management Pattern, 5. Hierarchical Ratings Pattern, Anti-Pattern #1: Denormalized Ratings, Anti-Pattern #2: Synchronous Aggregation on Every Request, Anti-Pattern #3: Missing Validation Rules (+7 more)

### Community 31 - "Rating Module - Product Roadmap"
Cohesion: 0.12
Nodes (15): Dependencies, LATER, Milestones, NEXT, NOW, Now / Next / Later, Q1 2026 - Core Ratings, Q2 2026 - Trust Features (+7 more)

### Community 32 - "Error Categories"
Cohesion: 0.11
Nodes (19): 1. Calculation Errors, 2. Sync Issues, 3. Audit Trail Failures, 4. Permission Errors, 5. Duplicate Ratings, 6. Category Conflicts, Error Categories, Error Pattern: "Audit log missing — no record of who rated" (+11 more)

### Community 33 - "Documentation by Category"
Cohesion: 0.13
Nodes (15): Archive (3 files), Archived (1 file), Documentation by Category, File Summary by Type, LLM Wiki (7 files), Quick Navigation, Rating Module Documentation Index, Raw (7 files) (+7 more)

### Community 34 - "Fase 1 · Stabilizzazione della base tecnica"
Cohesion: 0.14
Nodes (14): Actions, Criteri di accettazione per le fasi, Criteri e validazione, Documentazione integrazione, Fase 1 · Stabilizzazione della base tecnica, Fase 2 · Funzionalità core di rating, Fase 3 · Integrazione con moduli consumer, Filament dimostrativo (opzionale) (+6 more)

### Community 35 - "performance-optimization.md"
Cohesion: 0.14
Nodes (13): Cerca globalmente (solo se necessario), Cerca solo nel modulo corrente, Dimensione cache, Limitare lo Scope, ❌ MAI fare così, Monitoring, Prossimi Miglioramenti (TODO), Query QMD Efficienti (+5 more)

### Community 36 - "Pre-Launch Checklist"
Cohesion: 0.14
Nodes (13): Launch Day Activities, Launch Objectives, Post-Launch Activities, Pre-Launch Checklist, Rating Module - Product Launch Plan, Success Criteria, T-1 Week, T+1 Week (+5 more)

### Community 37 - "⭐ Rating — English presentation"
Cohesion: 0.14
Nodes (12): Certifications, Documentation, Join the team, ⭐ Rating — English presentation, Superpowers, Why it exists, Certificazioni, Documentazione (+4 more)

### Community 38 - "wiki/index.md"
Cohesion: 0.16
Nodes (8): Contratto wiki locale — stub Rating, Rating Module: XotBaseResourceTable Columns, Available Rules, Rules — Rating Module Wiki, Usage, Available Skills, Skills — Rating Module Wiki, Usage

### Community 39 - "Code redundancy audit — Rating"
Cohesion: 0.15
Nodes (12): Basename duplicati locali, Code redundancy audit — Rating, Consigli, Dubbi e perplessita, Evidenze, File grandi, Metriche, Nomi classe ripetuti (+4 more)

### Community 40 - "PHPStan Generic Type Limitations in Laravel"
Cohesion: 0.15
Nodes (12): False Friend, Files Affected (storico HasRating), Option 1: Document and Accept (Recommended), Option 2: Simplify Contracts, Permanent Guardrail, PHPStan Generic Type Limitations in Laravel, Problem, Related (+4 more)

### Community 41 - "Rating Module Wiki Index"
Cohesion: 0.17
Nodes (11): Concepts, Decisions, Entities, Module's Role in Project, Patterns, Project Wiki Cross-References, Rating Module Wiki Index, Related Project Concepts (+3 more)

### Community 42 - "PHPStan Level 10 Errors Roadmap - Modulo Rating"
Cohesion: 0.17
Nodes (11): 🧠 Analisi Errori, ✅ Checklist Implementazione, 📊 Errori Identificati, Fase 1: Verifica Classe Profile Corretta, Fase 2: Correzione PHPDoc, Pattern: PHPDoc con Classi Sconosciute, PHPStan Level 10 Errors Roadmap - Modulo Rating, 📋 Piano di Correzione (+3 more)

### Community 43 - "Rating - Product Launch Plan"
Cohesion: 0.17
Nodes (11): Audience interna, Collegamenti, Criteri di readiness, Fase 1 - Internal readiness, Fase 2 - Controlled rollout, Fase 3 - Post-launch review, Metriche di lancio, Obiettivo del lancio (+3 more)

### Community 44 - "Schemaless Attributes — Rating Module"
Cohesion: 0.17
Nodes (12): 1. Model — Correct Cast, 2. PHPDoc, 3. Migration, Architecture, Common Errors, Get & Set Attributes, Integration with Laravel Data, Query Patterns (+4 more)

### Community 45 - "architecture.md"
Cohesion: 0.11
Nodes (9): Dipendenze e confini del modulo Rating, Dipendenze in ingresso (il Rating dipende da), Dipendenze in uscita (chi usa il Rating), Rischio dipendenze circolari, Versioni minime (riferimento), Product Roadmap - Rating Module, Q1 2026: Foundation, 🗓️ Timeline (+1 more)

### Community 46 - "Cyclomatic Complexity Report - Module: Rating"
Cohesion: 0.18
Nodes (10): 📈 Complexity Distribution, 🎯 Cyclomatic Complexity Overview, Cyclomatic Complexity Report - Module: Rating, ✅ Excellent!, Interpretation Guidelines, 💡 Recommendations, 📚 References, 📐 Statistical Analysis (+2 more)

### Community 47 - "Graphify Knowledge Graph"
Cohesion: 0.18
Nodes (10): Documentation Integration, Generating Updated Graphs, Graph Interpretation, Graphify Knowledge Graph, Key Files, Overview, Quick Start, References (+2 more)

### Community 48 - "Rating Module — Migrations"
Cohesion: 0.18
Nodes (10): 2023_01_01_000005 — `create_rating_morph_table.php`, 2026_07_15_120003 — `create_rating_morph_table.php`, History & Corrections, Migrations, Model-Migration Parity, Overview, Pattern, Rating Module — Migrations (+2 more)

### Community 49 - "PHPStan Fixes - Modulo Rating"
Cohesion: 0.18
Nodes (11): 1. Rimozione Generic Type da HasXotFactory ✅, 1. Trait Usage Pattern, 2. BaseModel Pattern, 2. Rimozione Assert Ridondante ✅, 📊 Correzioni Gennaio 2025, 📊 Correzioni Implementate, 📈 Metriche di Qualità, 🎯 Pattern Applicati (+3 more)

### Community 50 - "Rating Module Schema"
Cohesion: 0.18
Nodes (10): Ingest Workflow, Lint Workflow, Module Identity, Query Workflow, Rating Module Schema, raw/ Rules, Wiki Rules, wiki/ Rules (+2 more)

### Community 51 - "1.10: Scheda Search + Filter (Employee, Year, Status)"
Cohesion: 0.18
Nodes (10): 1.10: Scheda Search + Filter (Employee, Year, Status), Acceptance Criteria, Dependency Maps, Dev Agent Record, Dev Notes, Learnings from Previous Stories, Owned File/Module Scope, Story (+2 more)

### Community 52 - "1.1: Create Scheda"
Cohesion: 0.18
Nodes (10): 1.1: Create Scheda, Acceptance Criteria, Dependency Maps, Dev Agent Record, Dev Notes, Learnings from Previous Stories, Owned File/Module Scope, Story (+2 more)

### Community 53 - "1.3: Draft → Submitted State Flow"
Cohesion: 0.18
Nodes (10): 1.3: Draft → Submitted State Flow, Acceptance Criteria, Dependency Maps, Dev Agent Record, Dev Notes, Learnings from Previous Stories, Owned File/Module Scope, Story (+2 more)

### Community 54 - "1.4: Soft-delete + Restore Within 30 Days"
Cohesion: 0.18
Nodes (10): 1.4: Soft-delete + Restore Within 30 Days, Acceptance Criteria, Dependency Maps, Dev Agent Record, Dev Notes, Learnings from Previous Stories, Owned File/Module Scope, Story (+2 more)

### Community 55 - "1.5: Bulk Create From CSV"
Cohesion: 0.18
Nodes (10): 1.5: Bulk Create From CSV, Acceptance Criteria, Dependency Maps, Dev Agent Record, Dev Notes, Learnings from Previous Stories, Owned File/Module Scope, Story (+2 more)

### Community 56 - "1.8: Edit Submitted Scheda (Audit Trail)"
Cohesion: 0.18
Nodes (10): 1.8: Edit Submitted Scheda (Audit Trail), Acceptance Criteria, Dependency Maps, Dev Agent Record, Dev Notes, Learnings from Previous Stories, Owned File/Module Scope, Story (+2 more)

### Community 57 - "1.9: Approval Workflow (Manager Sign-off)"
Cohesion: 0.18
Nodes (10): 1.9: Approval Workflow (Manager Sign-off), Acceptance Criteria, Dependency Maps, Dev Agent Record, Dev Notes, Learnings from Previous Stories, Owned File/Module Scope, Story (+2 more)

### Community 58 - "Rating Module LLM Wiki"
Cohesion: 0.18
Nodes (11): AI / second brain, Bad Practices, Best Practices, Compiled Pages, False Friends, On-Demand Entry Points, Rating Module LLM Wiki, Regole collegate (+3 more)

### Community 59 - "Illuminate\Database\Seeder"
Cohesion: 0.27
Nodes (4): RatingDatabaseSeeder, RatingMorphSeeder, RatingSeeder, Illuminate\Database\Seeder

### Community 60 - "Code Quality Analysis - Rating Module"
Cohesion: 0.20
Nodes (10): 1. GetSumByModelRatingIdAction.php, Code Quality Analysis - Rating Module, 🔍 Dettagli Fix Implementati, 📚 Documentazione Correlata, 📈 Metriche Qualità, PHPInsights, PHPMD, PHPStan Level 10 (+2 more)

### Community 61 - "Rating - Product Strategy"
Cohesion: 0.20
Nodes (9): Collegamenti, Cosa non fare, Metriche strategiche, Missione, Principi strategici, Problema da risolvere, Rating - Product Strategy, Regola architetturale (+1 more)

### Community 62 - "Problemi Trovati"
Cohesion: 0.20
Nodes (9): 1. 🟠 BaseMorphPivot NON estende XotBaseMorphPivot, 2. 🟠 BaseRating — Duplicato in Xot, 3. 🟡 EventServiceProvider — Non usa XotBaseEventServiceProvider, 4. 🟠 Filament — Table e widget duplicati nello stesso modulo, 5. 🔴 Migrazioni `ratings` — Doppia create, Problemi Trovati, Redundancy Report — Modulo Rating, Riepilogo (+1 more)

### Community 63 - "Fasi di Sviluppo del Modulo Rating"
Cohesion: 0.20
Nodes (10): Attività, Attività, Attività, Fase 1 · Stabilizzazione della base tecnica, Fase 2 · Funzionalità core di rating, Fase 3 · Integrazione con moduli consumer, Fasi di Sviluppo del Modulo Rating, Obiettivi (+2 more)

### Community 64 - "🏗️ Key Features"
Cohesion: 0.20
Nodes (9): 1. Rating System, 2. Reviews, 3. Aggregation, 4. Analytics, 📊 Current Status, 🏗️ Key Features, Overall Progress: 75% Complete, Rating Module Roadmap (+1 more)

### Community 65 - "Rating — test e coverage"
Cohesion: 0.18
Nodes (10): Cosa non è testabile a unit, Coverage, Eseguire la suite, HasLikes e modello `Like` assente, Helper dei test, Lacune trovate, non colmate, Rating — test e coverage, Skip condizionato, non skip permanente (+2 more)

### Community 66 - "Filament Resource Zen Pattern (Rating Module)"
Cohesion: 0.20
Nodes (10): Core Zen Rules, Filament Resource Zen Pattern (Rating Module), Generic Type Limitation (Known Issue), Key Fixes Applied (2026-05-06), Overview (2026-05-06), PHPStan Results, Rating Module Resources Status, RatingMorphResource.php (+2 more)

### Community 67 - "PHPStan Generic Limitation: MorphToMany with static"
Cohesion: 0.20
Nodes (9): Error Messages, PHPStan Generic Limitation: MorphToMany with static, Problem, References, Root Cause, Solution, Status, What we did (+1 more)

### Community 68 - "BaseRatingForm.php"
Cohesion: 0.08
Nodes (15): BaseRatingMorphResource, BaseRatingResource, RatingMorphResource, RatingMorphForm, RatingMorphInfolist, RatingResource, BaseRatingForm, BaseRatingInfolist (+7 more)

### Community 69 - "Ottimizzazioni Applicate"
Cohesion: 0.22
Nodes (9): 1. On-Demand Loading (principale), 2. Cache Esterna al Repo, 3. Node Modules Puliti, 4. Wiki Indici Locali, Best Practice per Sviluppatori, Caricamento Efficiente, Metriche Attuali, Ottimizzazioni Applicate (+1 more)

### Community 70 - "PHPStan Level 10 Compliance — Rating Module"
Cohesion: 0.22
Nodes (8): 1. Rating Model Types, 2. Relation Generics, 3. Aggregation Types, Patterns Applied, PHPStan Level 10 Compliance — Rating Module, Related Docs, Summary, Verification

### Community 71 - "Errori Trovati"
Cohesion: 0.22
Nodes (9): 1. Rating estende BaseModel invece di BaseRating (DRY Violation), 2. Scope scopeWithExtraAttributes() Conflittuale, 3. Migrazione Mancante per extra_attributes, 4. PHPDoc Mismatch, 5. Deprecazione della Proprietà `$casts` per Schemaless Attributes ✅ RISOLTO, Errori Trovati, Pattern Corretto (da usare in tutti i moduli), Riferimenti (+1 more)

### Community 72 - "Story 2.1: Rating — adottare il contratto Pest condiviso e alzare la coverage"
Cohesion: 0.22
Nodes (8): Acceptance Criteria, Contesto, Dev Notes, Dipendenze, Story, Story 2.1: Rating — adottare il contratto Pest condiviso e alzare la coverage, Tasks / Subtasks, Testing

### Community 73 - "RatingsRelationManager"
Cohesion: 0.36
Nodes (5): RatingsRelationManager, RatingsRelationManager, Filament\Resources\RelationManagers\RelationManager, Filament\Tables\Table, ratingRelationManagerColumnNames()

### Community 74 - "docs/index.md"
Cohesion: 0.22
Nodes (3): Architectural Rules & Guidelines, ⚠️ Architectural Rules, Rating Module — Documentation Index

### Community 75 - "Case Sensitivity Rules - Rating Module"
Cohesion: 0.25
Nodes (7): Case Sensitivity Rules - Rating Module, Convenzioni, Directory Structure, File/Directory Rimossi da Rating Module, Motivazione, Problema / Problem, Update Log

### Community 76 - "Report: Metodi con nome duplicato nei moduli e nei temi"
Cohesion: 0.25
Nodes (7): Allegati, Conclusioni, Introduzione, Metodologia, Osservazioni, Report: Metodi con nome duplicato nei moduli e nei temi, Risultati

### Community 77 - "Report: Metodi con nome duplicato nei moduli e nei temi"
Cohesion: 0.25
Nodes (7): Allegati, Conclusioni, Introduzione, Metodologia, Osservazioni, Report: Metodi con nome duplicato nei moduli e nei temi, Risultati

### Community 78 - "Handoff — multi-org sync (STORY-003)"
Cohesion: 0.25
Nodes (7): Handoff — multi-org sync (STORY-003), Link, Note owner, Perché, Regole rapide, Scopo, Sync 2026-07-23 (batch 5-item)

### Community 79 - "Metriche e obiettivi di qualità"
Cohesion: 0.33
Nodes (6): Documentazione, Integrazione (Fase 3), Metriche e obiettivi di qualità, Qualità codice, Test, Traduzioni

### Community 80 - "Rating Module - Sprint Planning"
Cohesion: 0.25
Nodes (7): Capacity Planning, Definition of Done, Sprint Goal, Rating Module - Sprint Planning, Risks, Sprint Backlog, User Stories

### Community 81 - "Visual Testing con Playwright e Puppeteer — Modulo Rating"
Cohesion: 0.25
Nodes (7): Best Practices, Collocazione Test, Esempio Base, Panoramica, Playwright vs Puppeteer, Risorse, Visual Testing con Playwright e Puppeteer — Modulo Rating

### Community 82 - "wiki/log.md"
Cohesion: 0.25
Nodes (7): [2026-06-05] docs | HackerNoon harness — tips 001-022 in wiki locale, [2026-06-05] docs | HackerNoon harness — tips 001-022 in wiki locale, [2026-06-10] phpstan | Modulo Rating zero errori codice, [2026-06-10] phpstan | Modulo Rating zero errori codice, Activity Log — Rating, Format, Log Entries

### Community 83 - "Rating Wiki Overview"
Cohesion: 0.25
Nodes (7): For AI Agents, For Humans, How to Use, Quick Stats, Rating Wiki Overview, Structure, What This Wiki Is

### Community 84 - "`composer.json` Dependencies"
Cohesion: 0.29
Nodes (6): `composer.json` Dependencies, Rating Module Configuration, `repositories` Section, `require-dev` Section, `require` Section, Scripts and Configuration

### Community 85 - "Upgrade Laravel 13 - Rating 🐄✨"
Cohesion: 0.29
Nodes (6): 🛠️ Modifiche Eseguite, 📝 Note Operative, 🧘 Principi Applicati, 🚀 Quality Gates (Target), Upgrade Laravel 13 - Rating 🐄✨, 🎯 Visione Architetturale

### Community 86 - "Sincronizzazione multi-organizzazione (laraxot + provtv)"
Cohesion: 0.29
Nodes (6): Caso User 2026-07-23 (unrelated), Cosa è stato fatto, Playbook push dual-remote (2026-07-22, canon UI), Problemi riscontrati e risolti, Regola per il futuro, Sincronizzazione multi-organizzazione (laraxot + provtv)

### Community 87 - "Rating Module Architecture"
Cohesion: 0.33
Nodes (5): Components, Features, Integration, Overview, Rating Module Architecture

### Community 88 - "📄 Documenti"
Cohesion: 0.33
Nodes (5): Development, 📄 Documenti, Product, 📚 RATING Module - Documentation Index, 🔗 Riferimenti

### Community 89 - "Best Practices – Rating"
Cohesion: 0.33
Nodes (5): Best Practices – Rating, Componenti, Documentazione, Principi DRY/KISS, Test

### Community 90 - "🐄 DRY & KISS Analysis - Rating"
Cohesion: 0.33
Nodes (5): 🐄 DRY & KISS Analysis - Rating, ⚠️ MIGLIORAMENTI MINIMI, ✅ PERFETTO, 🎯 Score: 9/10 🟢 **ECCELLENTE**, 📊 Struttura

### Community 91 - "Rating — Framework Integration Notes"
Cohesion: 0.33
Nodes (5): Architecture (graphify), Code Quality (ponytail), Code Review (caveman), Planning (bmad-method + headroom), Rating — Framework Integration Notes

### Community 92 - "4. Applico la regola/skill/command/memory"
Cohesion: 0.33
Nodes (6): 4. Applico la regola/skill/command/memory, Local vs Global, Quick Reference, Regole Critiche per Module, Riferimenti, Struttura di Questo Module

### Community 93 - "ponytail-audit-over-engineering.md"
Cohesion: 0.33
Nodes (4): Collegamenti, Esito run 2026-06-30, Ponytail audit — Rating (over-engineering), Ponytail audit — Rating

### Community 94 - "qmd-setup.md"
Cohesion: 0.33
Nodes (5): Cerca solo in ./laravel/Modules/Rating/docs/wiki/, Collection Configuration, Configurazione QMD per Questo Module, QMD Setup per Module **Rating**, Ricerca Locale vs Globale

### Community 95 - "Cerca in docs/wiki/ + tutti i moduli"
Cohesion: 0.33
Nodes (6): Cache Location, Cerca in docs/wiki/ + tutti i moduli, Integrazione con l'On-Demand Pattern, Performance Tips, Riferimenti, Troubleshooting

### Community 96 - "Raw Sources — Rating"
Cohesion: 0.33
Nodes (5): Cosa appartiene al layer raw, Dove va la sintesi, Raw Sources — Rating, Regola, Schema di riferimento

### Community 97 - "Checklist Qualità Modulo Rating"
Cohesion: 0.33
Nodes (5): Checklist Qualità Modulo Rating, Filament e UI (se presenti), Qualità del codice, Static analysis e testing, Traduzioni e documentazione

### Community 98 - "Visione del Modulo Rating"
Cohesion: 0.33
Nodes (5): Confini del modulo, Non-goals (fuori ambito), Obiettivi di business, Obiettivi tecnici, Visione del Modulo Rating

### Community 99 - "Commands Index"
Cohesion: 0.33
Nodes (5): Aggiungere una Nuova COMMANDS, Commands Index, Note, Pattern di caricamento, Regola

### Community 100 - "Rating Module — Context-Mode Discipline"
Cohesion: 0.33
Nodes (5): Context Savings, File Wiki Limits — Max 100K, On-Demand Loading, Rating Module — Context-Mode Discipline, Vedi anche

### Community 101 - "Memories Index"
Cohesion: 0.33
Nodes (5): Aggiungere una Nuova MEMORIES, Memories Index, Note, Pattern di caricamento, Regola

### Community 102 - "Tabelle Filament e migration `ratings`"
Cohesion: 0.33
Nodes (5): Classi Table (stesso resource), Migration, Tabelle Filament e migration `ratings`, Tracker, Widget

### Community 103 - "Contributing"
Cohesion: 0.33
Nodes (5): Contributing, Etiquette, Procedure, Requirements, Viability

### Community 104 - "CHANGELOG.md"
Cohesion: 0.40
Nodes (4): 1.0.0 - 202X-XX-XX, 1.0.0 - 202X-XX-XX, Changelog, Changelog

### Community 105 - "Bad Practices – Rating"
Cohesion: 0.40
Nodes (4): Bad Practices – Rating, ❌ Calcolare la media in query N+1, ❌ Consentire rating duplicati senza validazione, ❌ Usare float per star_rating

### Community 106 - "Bad Practices – Rating"
Cohesion: 0.40
Nodes (4): Bad Practices – Rating, ❌ Calcolare la media in query N+1, ❌ Consentire rating duplicati senza validazione, ❌ Usare float per star_rating

### Community 107 - "docs/CHANGELOG.md"
Cohesion: 0.40
Nodes (4): 1.0.0 - 202X-XX-XX, Changelog, Changelog, [Unreleased]

### Community 108 - "Conflict Resolution — Module Rating"
Cohesion: 0.40
Nodes (4): Backlinks, Config Files, Conflict Resolution — Module Rating, Summary

### Community 109 - "Contracts Naming & Placement in Rating"
Cohesion: 0.40
Nodes (4): Contracts Naming & Placement in Rating, Rule, Verification, Why

### Community 110 - "on-demand-pattern.md"
Cohesion: 0.40
Nodes (4): 1. Identifico il trigger nel task, 2. Consulto la trigger map globale, 3. Carico on-demand la risorsa, OPPURE

### Community 111 - "On-Demand Pattern — Module **Rating**"
Cohesion: 0.40
Nodes (5): Come Funziona, On-Demand Pattern — Module **Rating**, Perché On-Demand?, Principio, Step-by-Step

### Community 112 - "PRD: Rating Module"
Cohesion: 0.40
Nodes (4): 🎯 Goals & Success Metrics, 📋 Overview, PRD: Rating Module, ❓ Problem Statement

### Community 113 - "Release e README marketing — Rating"
Cohesion: 0.40
Nodes (4): Confidenza, File canonici locali, Release e README marketing — Rating, Scopo

### Community 114 - "Rating e composer root minimale"
Cohesion: 0.40
Nodes (4): Merge root — solo moduli, Rating e composer root minimale, Regola, Riferimento

### Community 115 - "Pest PHP"
Cohesion: 0.40
Nodes (4): Convenzioni locali, Pest PHP, Quality Gate, Testing in Rating

### Community 117 - "Metodi duplicati — Rating"
Cohesion: 0.50
Nodes (3): Metodi duplicati, Metodi duplicati — Rating, Riflessioni

### Community 118 - "Metodi duplicati — Rating"
Cohesion: 0.50
Nodes (3): Metodi duplicati, Metodi duplicati — Rating, Riflessioni

### Community 119 - "Headroom - Modulo Rating"
Cohesion: 0.50
Nodes (3): Comandi, Headroom - Modulo Rating, Regole

### Community 120 - "Product Launch Plan: Rating Module"
Cohesion: 0.50
Nodes (3): 🎯 Launch Goals, 🚀 Launch Overview, Product Launch Plan: Rating Module

### Community 121 - "Product Strategy: Rating Module"
Cohesion: 0.50
Nodes (3): 🌍 Market Context, Product Strategy: Rating Module, 💎 Unique Value Proposition

### Community 122 - "Gitmodules sync session"
Cohesion: 0.50
Nodes (3): Canon, Cosa fare su questo owner, Gitmodules sync session

### Community 123 - "Wiki Schema - Rating"
Cohesion: 0.50
Nodes (3): Convenzioni, Struttura, Wiki Schema - Rating

### Community 189 - "Modules\Rating\Models\Contracts\HasRatingContract"
Cohesion: 0.33
Nodes (5): GetCountByModelRatingIdAction, GetRatingOptsByModelAction, GetSumByModelRatingIdAction, Modules\Rating\Models\Contracts\HasRatingContract, Spatie\QueueableAction\QueueableAction

### Community 190 - "Illuminate\Database\Eloquent\Model"
Cohesion: 0.31
Nodes (7): Illuminate\Database\Eloquent\Model, Illuminate\Database\Eloquent\Relations\MorphMany, Modules\Rating\Models\Traits\HasLikes, Like, LikeableNativeRelationStub, LikeableStub, MorphMany

### Community 191 - "Illuminate\Database\Eloquent\Relations\MorphToMany"
Cohesion: 0.25
Nodes (7): myRatings(), ratingObjectives(), Illuminate\Database\Eloquent\Relations\HasMany, Illuminate\Database\Eloquent\Relations\MorphToMany, RatingsHostStub, HasRatingContract, ratingMockHost()

### Community 192 - "SafeStringCastAction"
Cohesion: 0.22
Nodes (6): StatsOverview, StatsOverview, getRatingsValidationAttributes(), ratingAvgHtml(), Modules\Xot\Filament\Widgets\XotBaseStatsOverviewWidget, SafeStringCastAction

### Community 193 - "BaseRatingMorph"
Cohesion: 0.33
Nodes (3): BaseRatingMorph, Illuminate\Database\Eloquent\Relations\BelongsTo, Illuminate\Database\Eloquent\Relations\MorphTo

### Community 194 - "TestCase"
Cohesion: 0.36
Nodes (4): Illuminate\Foundation\Application, Illuminate\Foundation\Testing\DatabaseTransactions, Modules\Xot\Tests\XotBaseTestCase, TestCase

### Community 195 - "Modules\Xot\Filament\Resources\Pages\XotBaseCreateRecord"
Cohesion: 0.38
Nodes (4): CreateRatingMorph, BaseCreateRating, CreateRating, Modules\Xot\Filament\Resources\Pages\XotBaseCreateRecord

### Community 196 - "BaseModel"
Cohesion: 0.38
Nodes (4): AbstractRatingsHost, BaseModel, Modules\Rating\Models\Traits\HasRatingsTrait, Modules\Xot\Models\XotBaseModel

### Community 198 - "BaseMorphPivot"
Cohesion: 0.60
Nodes (3): BaseMorphPivot, Illuminate\Database\Eloquent\Relations\MorphPivot, Modules\Xot\Traits\Updater

## Knowledge Gaps
- **949 isolated node(s):** `name`, `description`, `laraxot`, `laravel`, `filament` (+944 more)
  These have ≤1 connection - possible missing edges or undocumented components.
- **27 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Rating Module - FAQ` connect `Rating Module - FAQ` to `architecture.md`?**
  _High betweenness centrality (0.027) - this node is a cross-community bridge._
- **Why does `Censimento omonimi metodi — Rating` connect ``F_trait_name_collision`` to `INDEX_GENERATED.md`?**
  _High betweenness centrality (0.022) - this node is a cross-community bridge._
- **What connects `name`, `description`, `laraxot` to the rest of the system?**
  _949 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `RatingData` be split into smaller, more focused modules?**
  _Cohesion score 0.14761904761904762 - nodes in this community are weakly interconnected._
- **Should `Modules\Xot\Filament\Resources\Tables\XotBaseResourceTable` be split into smaller, more focused modules?**
  _Cohesion score 0.0641025641025641 - nodes in this community are weakly interconnected._
- **Should `composer.json` be split into smaller, more focused modules?**
  _Cohesion score 0.04081632653061224 - nodes in this community are weakly interconnected._
- **Should `Rating Module - FAQ` be split into smaller, more focused modules?**
  _Cohesion score 0.0425531914893617 - nodes in this community are weakly interconnected._