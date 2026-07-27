---
title: "XotBaseInfolistWidget — schema read-only Filament 5"
type: concept
module: Xot
tags: [filament, schema, infolist, hasSchemas]
created: 2026-07-24
updated: 2026-07-24
related:
  - ../../xotbase-schemawidget-pattern.md
  - ../../../../../../docs/wiki/concepts/filament-v5-schema-in-blade.md
---

# XotBaseInfolistWidget

Codice: `Modules/Xot/app/Filament/Widgets/XotBaseInfolistWidget.php`.

Allineato a [schema in Blade](https://filamentphp.com/docs/5.x/components/schema):

- `implements HasSchemas` + `InteractsWithSchemas`
- Metodo `infolist(Schema $schema): Schema` → `->components(...)` (+ `->record($model)` se presente)
- Vista default `xot::filament.widgets.infolist`: **`{{ $this->infolist }}`** (verificato sul file Blade)

Non è un form: niente `statePath` / `getState()` obbligatori. Per form FO usare `XotBaseSchemaWidget`.
