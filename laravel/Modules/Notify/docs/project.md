<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
=======
=======
>>>>>>> a988596b (first)
---
title: "Base Fixcity Fila5 — project.md"
type: concept
tags: [project]
created: 2026-07-14
updated: 2026-07-14
<<<<<<< HEAD
=======
qmd: "project base fixcity fila5 — project.md"
>>>>>>> a988596b (first)
qmd: "project base ptv fila5 — project.md"
issues: ["https://github.com/provtv/base_ptv_fila5/issues/124"]
discussions: ["https://github.com/provtv/base_ptv_fila5/discussions/1"]
related:
  - "./00-index-1.md"
  - "./00-index-2.md"
  - "./00-index.md"
  - "./absolute-completion-100.md"
  - "./acronym-naming-conventions-1.md"
  - "./acronym-naming-conventions-2.md"
  - "./acronym-naming-conventions.md"
  - "./action-plan-immediate.md"
---

# Base Fixcity Fila5 — project.md

## Context
Laravel + Filament v5 modular monolith (Laraxot architecture).
Theme: Sixteen (Bootstrap Italia → Tailwind parity).
Frontend wizard: Ticket creation wizard via `CreateTicketWizardWidget` (Filament v5 Schemas).

## Current Milestone
**M0: Fixcity Ticket Wizard — Visual & HTML Parity** ✅ DONE

Target: `segnalazione-crea` wizard page → 90%+ parity with Design Comuni `segnalazione-02-dati.html`

### Phase Results
- **Phase 1**: Filament Schemas correction ✅
  - Replaced non-existent `Text::make()` with `Placeholder` (HTML) + `TextEntry` (data)
  - Verified: `Filament\Schemas\Components\Text` does NOT exist
- **Phase 2**: Filament CSS Visual Parity ✅
  - Created `filament-wizard-parity.css` with scoped overrides
  - Added `app-test.css` as Vite entry point (841KB build)
  - Added Filament `.fi-*` classes to Tailwind safelist
  - Updated `test.blade.php` to use `app-test.css`
- **Phase 3**: Bootstrap Italia section structure ✅
  - 3 sections: Luogo, Disservizio, Autore
  - Grid 3-col for author data (name, fiscal code, phone)
  - TextEntry with icons for read-only author info
- **Phase 4**: Responsive parity — pending
- **Phase 5**: Multilingual verification — IT/EN translations verified

## Rules
- Filament Schemas = unified system (v5). Forms + Infolists coexist.
- Widget → NO model binding (`getFormModel() → null`)
- CSS scoped overrides → never mutate Filament markup
<<<<<<< HEAD
=======
- Multilingual: all strings via `__('fixcity::ticket.*')`
>>>>>>> a988596b (first)
- Multilingual: all strings via `__('ptv::ticket.*')`

---

<!-- Merged from PROJECT.md, which collided with this file on case-insensitive filesystems. -->

<<<<<<< HEAD
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
=======
>>>>>>> a377e9e6 (.)
# Base Fixcity Fila5 — PROJECT.md

## Context
Laravel + Filament v5 modular monolith (Laraxot architecture).
Theme: Sixteen (Bootstrap Italia → Tailwind parity).
Frontend wizard: Ticket creation wizard via `CreateTicketWizardWidget` (Filament v5 Schemas).

## Current Milestone
**M0: Fixcity Ticket Wizard — Visual & HTML Parity** ✅ DONE

Target: `segnalazione-crea` wizard page → 90%+ parity with Design Comuni `segnalazione-02-dati.html`

### Phase Results
- **Phase 1**: Filament Schemas correction ✅
  - Replaced non-existent `Text::make()` with `Placeholder` (HTML) + `TextEntry` (data)
  - Verified: `Filament\Schemas\Components\Text` does NOT exist
- **Phase 2**: Filament CSS Visual Parity ✅
  - Created `filament-wizard-parity.css` with scoped overrides
  - Added `app-test.css` as Vite entry point (841KB build)
  - Added Filament `.fi-*` classes to Tailwind safelist
  - Updated `test.blade.php` to use `app-test.css`
- **Phase 3**: Bootstrap Italia section structure ✅
  - 3 sections: Luogo, Disservizio, Autore
  - Grid 3-col for author data (name, fiscal code, phone)
  - TextEntry with icons for read-only author info
- **Phase 4**: Responsive parity — pending
- **Phase 5**: Multilingual verification — IT/EN translations verified

## Rules
- Filament Schemas = unified system (v5). Forms + Infolists coexist.
- Widget → NO model binding (`getFormModel() → null`)
- CSS scoped overrides → never mutate Filament markup
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
- Multilingual: all strings via `__('fixcity::ticket.*')`
=======
- Multilingual: all strings via `__('ptv::ticket.*')`
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
- Multilingual: all strings via `__('ptv::ticket.*')`
>>>>>>> a988596b (first)
=======
- Multilingual: all strings via `__('fixcity::ticket.*')`
>>>>>>> a377e9e6 (.)
