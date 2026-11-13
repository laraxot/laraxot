---
title: "folio slug routing pattern"
type: note
tags: [documentation]
created: 2026-09-26
updated: 2026-09-26
qmd: "folio slug routing pattern"
issues: []
discussions: []
status: draft
---

# Folio Slug Routing Pattern

## REGOLA PERMANENTE: Mai creare file blade specifici per slug in pages/

### Architettura corretta

```
laravel/Themes/Sixteen/resources/views/pages/tests/[slug].blade.php  ← UNICO file pagina
        ↓ usa Folio\name() + Volt\Component
        ↓ legge slug da URL (es. "segnalazione-crea")
        ↓ carica blocchi da Page::getBlocksBySlug('tests.'.$slug)

laravel/Themes/Sixteen/resources/views/components/blocks/tests/segnalazione-crea.blade.php  ← BLOCCO specifico
        ↓ @include() dal ciclo @foreach($blocks as $block)
        ↓ monta Livewire widget (es. CreateTicketWizardWidget)
```

### Perché non creare pages/segnalazione-crea.blade.php

- **Folio** gestisce il routing dinamico: `[slug].blade.php` cattura TUTTI gli slug sotto `/tests/`
- Creare file specifici rompe il pattern DRY e duplica logica mount()
- Il contenuto viene da `laravel/config/local/fixcity/database/content/pages/tests.segnalazione-crea.json`
- I blocchi sono componenti riutilizzabili in `components/blocks/`

### Pattern corretto da seguire

```blade
{{-- pages/tests/[slug].blade.php --}}
@props(['data' => []])
@livewire(\Modules\Fixcity\Filament\Widgets\CreateTicketWizardWidget::class, ['blockData' => $data])
```

### Verifica anti-regressione

```bash
# Nessun file specifico per slug in pages/
find laravel/Themes/Sixteen/resources/views/pages -name "segnalazione-*.blade.php" | grep -v "[slug]" | grep -v "[id]"
# Deve ritornare 0 righe
```

### Mappatura URL → JSON → Blocchi

| URL | Slug | JSON config | Blocchi caricati |
|-----|------|-------------|-----------------|
| `/it/tests/segnalazione-crea` | `segnalazione-crea` | `tests.segnalazione-crea.json` | `blocks/tests/segnalazione-crea.blade.php` |
| `/it/tests/altro-test` | `altro-test` | `tests.altro-test.json` | `blocks/tests/altro-test.blade.php` |

### Documentazione correlata

- `laravel/Themes/Sixteen/docs/wiki/concepts/blade-component-extraction-rule.md`
- `bashscripts/ai/.claude/rules/no-page-specific-css.md`
- Story: 8-41 (Folio routing architecture)

Correzione verificata 2026-10-13 (DCP-35 S4): `tests.segnalazione-crea.json` è stato eliminato (commit 260f3bcc5, `git log --diff-filter=D`) e oggi non esiste in nessun tenant; la risposta di `/it/tests/segnalazione-crea` (200) non contiene il wizard (0 occorrenze di `fi-sc-wizard` e `$wire.save()`). Il flusso vive su `/it/tickets/create` (`tickets.create.json`, blocco `components/blocks/tests/ticket-crea.blade.php`). Questo documento descrive lo stato precedente; stato attuale in `Modules/Fixcity/docs/wiki/concepts/ticket-crea-url-routing-chain.md`.
