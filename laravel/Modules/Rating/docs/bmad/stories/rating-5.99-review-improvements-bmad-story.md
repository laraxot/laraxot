---
id: rating-5.99-review-improvements-bmad-story
slug: rating-5.99-review-improvements-bmad-story
title: "Review Rating/5.99 'altro' feature implementation and identified improvements"
description: "BMAD story reviewing the Rating 5.99 'Select+Textarea con altro' feature implementation, identifying corrections and improvements needed based on code analysis and GitHub diff review. Documented in parallel with the other agent's implementation work."
document_type: story
category: ptvx
status: ready-for-review
version: 1.0.0
language: it-IT
project: PTVX
ecosystem: Laraxot
domain: rating
priority: medium
source_of_truth: true
scope: [modules.Rating]
audience: [ai-agents, developers, maintainers]
depends_on:
- bmad-method.md
related:
- laravel/Modules/Rating/docs/bmad/stories/5.99-select-altro-note-obbligatoria.story.md
- laravel/Modules/Rating/docs/bmad/architecture/rating-select-altro-note.md
- laravel/Modules/Rating/docs/bmad/architecture/rating-select-altro-implementation-spec.md
- laravel/Modules/Rating/docs/bmad/brainstorming/rating-select-altro-note-obbligatoria.md
- laravel/Modules/Rating/docs/bmad/epics/rating-select-altro.md
github:
  repo: <repo progetto>
  issues:
  - https://github.com/laraxot/module_rating_fila5/issues/57
  - https://github.com/laraxot/module_rating_fila5/issues/58
  - https://github.com/laraxot/module_rating_fila5/issues/59
  - https://github.com/laraxot/module_rating_fila5/issues/60
tags: [ptvx, rating, review, altro, note, bmad, improvements]
created_at: '2026-09-16'
updated_at: '2026-09-16'
maintainer: PTVX
license: project-internal
---

# Review Rating/5.99 'altro' feature implementation — BMAD story

## Contesto

L'altro agente ha recentemente modificato la documentazione Rating (1354 file cambiati nel diff HEAD), aggiornando stories, architecture, brainstorming e spec implementazione per la feature "Select + Textarea con'opzione 'altro'" (issue #57/#58). Questa story rivede l'implementazione documentata e identifica correzioni e miglioramenti necessari.

## Analisi dell'implementazione attuale

### Cosa è stato fatto correttamente
- **Architettura coerente**: `rating-select-altro-note.md` definisce contratti chiari per Select+Textarea, chiave option `''`, placeholder `null`, `note` required solo su `select === ''`
- **Specifica tecnica**: `rating-select-altro-implementation-spec.md` fornisce pseudocodice completo per `buildRatingComponent()`, inclusa la gestione `OTHER_OPTION_KEY = ''`, `selectIsOther` helper, Group(Select+Textarea) sempre visibile
- **Stories allineate**: `5.99-select-altro-note-obbligatoria.story.md` cattura correttamente i criteri di accettazione, inclusa la distinzione `null` ≠ `''` (placeholder ≠ altro)
- **Second brain**: `rating-select-altro-textarea.md` memory documenta il contratto canone

### Correzioni e miglioramenti identificati

#### 1. Consumer IR gap (D-8 / issue #36 / #60)
- **Problema**: `CompilaIndennitaResponsabilita::fillFormWithInitialData()` e `save()` gestiscono solo `pivot.value`, mai `pivot.note`
- **Impatto**: Anche con trait perfetto, la nota scritta dall'utente "sparirebbe silenziosamente" al salvataggio e non ricomparirebbe al riapertura
- **Miglioramento**: Extendere `fill()` e `save()` in `CompilaIndennitaResponsabilita` per includere `pivot.note` + gestire `note` come obbligatoria quando `select === ''` (chiave `''`)
- **Riferimento**: `laravel/Modules/IndennitaResponsabilita/docs/bmad/stories/5.140-compila-altro-note-smoke.story.md`

#### 2. Filtro HasRatingValuesFilter interazione (D-8)
- **Problema**: `HasRatingValuesFilter` tratta `value null/0` come "non valutata". Quando si sceglie "altro" (`value = null` + `note` piena), quelle schede apparirebbero come "senza rating" finché non si decide filtro o sentinel
- **Miglioramento**: Documentare questo rischio e decidere strategia (estendere filtro considerare `note` non vuota, oppure sentinel numerico dedicato) — story separata #60
- **Riferimento**: `docs/bmad/architecture/rating-select-altro-note.md` § D-8

#### 3. Lang/translation verification
- **Problema**: Verificare che `trans('rating::fields.altro')` punti al valore corretto nei file lingua (`rating.php` o `fields.php`)
- **Miglioramento**: Aggiungere entry `altro` nei file lingua di tutti i moduli consumatori se non presenti, verificare costanza traduzioni

#### 4. Placeholder null vs '' confusa negli test
- **Problema**: I test Pest devono essere espliciti su `null` vs `''` — `selectIsOther` deve usare `=== ''` only, mai `blank()`
- **Miglioramento**: Aggiungere test Pest che verifichino:
  - `null` → placeholder visualizzato, `note` NON required
  - `''` → option "altro" selezionata, `note` REQUIRED
  - stato medio → `note` NON required

#### 5. Assenza di `->label()` sul Select (policy D-1 story 5.92)
- **Verifica**: Confermare che nel trait non vi sia `->label()` sul Select delle option, poiché le label provengono da `trans('rating::fields.altro')` nell'array `$options`, non da metodo componente
- **Stato**: Già rispettato nell'implementazione spec, ma va verificato nel codice finale

## Prossimi passi per questa story

1. [ ] Verificare consumer IR #36: estendere `fillFormWithInitialData()` e `save()` in `CompilaIndennitaResponsabilita` per includere `pivot.note`
2. [ ] Documentare rischio D-8: interazione `HasRatingValuesFilter` + `value null` + `note` piena
3. [ ] Verificare traduzioni `rating::fields.altro` in tutti i file lingua module
4. [ ] Aggiungere test Pest per la distinzione `null` ≠ `''` nello stato Select
5. [ ] Confermare assenza `->label()` sul Select nel trait finale

## Valutazione BMAD

Efficienza: l'implementazione documentata è tecnicamente corretta e coerente, ma manca l'allineamento con il consumer IR (CompilaIndennitaResponsabilita) e con il filtro HasRatingValuesFilter. Questi gap devono essere colmati per garantire end-to-end correctness (form fill → save → DB → render).

**Nota**: Questa story non implica nuova implementazione codice nella sessione corrente, ma documenta le migliorie necessarie che altri agenti (o lo stesso agente in sessioni successive) dovranno implementare per completare la feature end-to-end.