---
title: "XotBaseSchemaWidget — pattern Filament 5 (codice reale)"
type: concept
module: Xot
tags: [xot, filament, schema, widget]
created: 2026-06-05
updated: 2026-07-24
qmd: "xotbase schemawidget filament 5 HasSchemas formClass getState"
issues:
  - "https://github.com/laraxot/base_techplanner_fila5/issues/18"
related:
  - ./wiki/concepts/filament-page-form-wrapper.md
  - ./filament-v5-form-wrapper-blade-pattern.md
  - ../../../../docs/wiki/concepts/filament-v5-schema-in-blade.md
  - ../../../../docs/wiki/concepts/filament-v5-form-in-blade.md
---

# XotBaseSchemaWidget — pattern Filament 5

Fonte codice: `Modules/Xot/app/Filament/Widgets/XotBaseSchemaWidget.php` (letto 2026-07-24).  
Upstream: [schema](https://filamentphp.com/docs/5.x/components/schema) · [form](https://filamentphp.com/docs/5.x/components/form).

## Contratto reale (estratto verificato)

```php
abstract class XotBaseSchemaWidget extends XotBaseWidget implements HasSchemas
{
    use InteractsWithSchemas; // Filament\Schemas\Concerns\…

    public ?array $data = [];

    protected static function formClass(): ?string { return null; }
    protected static function schemaMethod(): string { return 'getFormSchema'; }

    public function form(Schema $schema): Schema
    {
        // se formClass(): FormClass::{schemaMethod()}() → components + statePath('data')
        // else: $this->getFormSchema() → components + statePath('data')
    }

    public function mount(): void
    {
        $this->form->fill([]);
    }
}
```

## Religione

| Pezzo | Owner |
|-------|--------|
| Campi + rules | `*Form::get*Schema()` via `formClass` / `schemaMethod` |
| Widget | orchestrazione mount/submit/redirect |
| Submit | `$this->form->getState()` — mai `validateForm()` |
| Blade | `<form wire:submit>` + `{{ $this->form }}` |

## Schema non-form (infolist)

Per UI read-only: `XotBaseInfolistWidget` → metodo `infolist(Schema)` → Blade `{{ $this->infolist }}` (pattern [schema in Blade](https://filamentphp.com/docs/5.x/components/schema)).

## Documentazione obsoleta

Versioni precedenti di questo file citavano `Modules\Xot\Filament\Traits\InteractsWithSchemas` e firme `getFormSchema(Schema $schema): Schema` sulle Form class — **non corrispondono** al file PHP attuale. Ignorarle; usare questo aggiornamento.

## Verifica

```bash
php -l Modules/Xot/app/Filament/Widgets/XotBaseSchemaWidget.php
cd laravel && php artisan view:cache
```
