---
id: filament-canonical-structure-themes
slug: filament-canonical-structure-themes
title: "Themes: Filament canonical structure (Schemas/Tables obbligatori)"
description: "Cross-reference dalla wiki dei temi alla regola canonica di XotBaseResource (Schemas/, Tables/, Infolist obbligatori) e al pattern zero ->label() (LangServiceProvider). I temi devono essere coerenti con i moduli per le view override."
document_type: rule
category: filament
status: active
version: 1.0.0
language: it-IT
project: base_workorder_fila5
domain: filament
source_of_truth: false
github_issue_url: https://github.com/laraxot/bashscripts_fila5/issues/10
github_discussion_url: null
created_at: '2026-09-07'
updated_at: '2026-09-07'
tags:
  - themes
  - filament
  - laraxot
  - xotbase
  - canonical-structure
depends_on:
  - ../../../Modules/Billing/docs/filament/patterns.md
  - ../../../../docs/wiki/filament-canonical-structure.md
  - ./xotbasefield-view-override.md
related:
  - ../../../Modules/Billing/docs/filament/patterns.md
  - ./xotbasefield-view-override.md
---

# Themes: Filament canonical structure

## Regola

Tutte le Resource Filament (in qualsiasi modulo) **DEVONO** avere:

- `Schemas/<Name>Form.php` extends `XotBaseResourceForm`
- `Schemas/<Name>Infolist.php` extends `XotBaseResourceInfolist`
- `Tables/<Plural>Table.php` extends `XotBaseResourceTable`

Niente `getFormSchema()` / `table()` / `getInfolistSchema()` inline nel Resource.

**Mai** `->label()` / `->tooltip()` / `->placeholder()` hardcoded — tutto auto da
`LangServiceProvider` con chiave `<modulo>.<resource>.<field>.label`.

**Mai** `GatedXotBaseResource` o varianti inventate — sempre `XotBaseResource` diretto.

## Impatto sui Temi

I temi forniscono **view overrides** dei componenti Filament. La struttura canonical
non cambia per i temi, ma:

- Una `Schemas/<Name>Form.php` (es. `Modules/Billing/app/Filament/Resources/DeliveryNoteResource/Schemas/DeliveryNoteForm.php`) usa componenti Filament (es. `TextInput::make('number')`).
- Il tema può fare override della **view** del componente (`pub_theme::filament.forms.components.text-input`).
- Il tema **NON** ridefinisce la classe PHP del componente.
- Il tema **NON** ridefinisce lo schema.

## Override di un campo specifico

Se un campo richiede un render custom (es. `MapPicker`), il modulo deve creare un
componente custom che estende `XotBaseField`. Il tema può fare override della view:

```text
Modules/{Module}/app/Forms/Components/MapPicker.php   → extends XotBaseField
Themes/{Theme}/resources/views/filament/forms/components/map-picker.blade.php
```

Vedi `rules/xotbasefield-view-override.md`.

## Cross-ref

- SSOT modulo: `Modules/Billing/docs/filament/patterns.md`
- Second brain: `docs/wiki/filament-canonical-structure.md`
- Audit prompt: `bashscripts/docs/prompts/06-filament-audit.md`
- View override rule: `rules/xotbasefield-view-override.md`

## Esempio pratico

```text
# Modulo (invariato)
Modules/Billing/app/Filament/Resources/DeliveryNoteResource/
  Schemas/DeliveryNoteForm.php           # XotBaseResourceForm
  Tables/DeliveryNotesTable.php          # XotBaseResourceTable

# Tema (solo views)
Themes/TwentyOne/resources/views/filament/forms/components/text-input.blade.php
Themes/TwentyOne/resources/views/filament/tables/columns/text-column.blade.php
```

Il tema non tocca le classi PHP dei moduli. Solo override view via
`pub_theme::filament.*`.
