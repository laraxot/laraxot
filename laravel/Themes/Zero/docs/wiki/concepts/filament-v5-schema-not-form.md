---
title: "Filament 5 — Schema API (non tornare a Forms\\Form)"
type: concept
module: Zero
tags: [filament, schema, form, migration, correction]
created: 2026-07-24
updated: 2026-07-24
related:
  - ../../../../../../docs/wiki/concepts/filament-v5-form-in-blade.md
  - ../../root-md-files/conflict-resolution-summary.md
---

# Filament 5 — `Schema`, non `Form`

## Correzione storica

Documenti legacy Zero (`conflict-resolution-summary`, ecc.) descrivono una migrazione **Schema → Form** tipica di conflitti Git su Filament 4 intermedi. Su **Filament 5** il canon ufficiale è:

| Corretto (v5) | Errato (legacy) |
|---------------|-----------------|
| `Filament\Schemas\Schema` | `Filament\Forms\Form` come tipo del metodo `form()` |
| `HasSchemas` + `InteractsWithSchemas` | solo `InteractsWithForms` come API primaria |
| Schema generico: metodo nominato + `{{ $this->nome }}` | inventare componenti Blade `filament-schemas::form` |
| Form = specializzazione con `fill`/`getState` | trattare Form e Schema come API alternative incompatibili |

Fonti: [schema](https://filamentphp.com/docs/5.x/components/schema) · [form](https://filamentphp.com/docs/5.x/components/form).  
Repo: [filament-v5-schema-in-blade](../../../../../../docs/wiki/concepts/filament-v5-schema-in-blade.md) (include tabella `composer show` / Blade grid+fieldset).

## Laraxot

FO form: `XotBaseSchemaWidget`. FO infolist: `XotBaseInfolistWidget`. Gate: `php artisan view:cache`.

I file storici sotto `docs/root-md-files/` restano audit di conflitto — **non** sono la religione attuale.
