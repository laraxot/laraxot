---
title: "Filament v5 — wrapper form nelle Blade view custom"
type: how-to
tags: [filament, blade, form, view-cache]
created: 2026-07-20
updated: 2026-07-24
module: Xot
related:
  - ./wiki/concepts/filament-page-form-wrapper.md
  - ../../../../docs/wiki/concepts/filament-v5-form-in-blade.md
  - ../../../../docs/wiki/memories/view-cache-gate-mandatory.md
---

# Filament v5 — wrapper form nelle Blade view custom

Upstream: [Form in Blade (5.x)](https://filamentphp.com/docs/5.x/components/form).

## Regola

Avvolgere `{{ $this->form }}` con **`<form>` HTML** + `wire:submit`. Mai `<x-filament-schemas::form>`.

```blade
{{-- ✅ CORRETTO (allineato a filamentphp/demo) --}}
<form wire:submit="save">
    {{ $this->form }}
    <x-filament::actions :actions="$this->getFormActions()" />
</form>
<x-filament-actions::modals />
```

```blade
{{-- ❌ ERRATO — rompe php artisan view:cache --}}
<x-filament-schemas::form wire:submit="save">
    {{ $this->form }}
</x-filament-schemas::form>
```

## Perché

`filament/schemas` espone Blade solo `grid` e `fieldset`. Nessun componente `form`. Il wrapper v2/v3 `x-filament::form` è rimosso.

## Diagnosi / fix

```bash
rg -l 'x-filament-schemas::form|x-filament::form' --glob '*.blade.php' Modules Themes
cd laravel && php artisan view:clear && php artisan view:cache
```

Un override vendor (`resources/views/vendor/filament-schemas/components/form.blade.php`) è esistito come rete di sicurezza ma è stato cancellato da `composer run go` (la cartella è gitignored, `vendor:publish` non lo ricrea) — non recuperabile. Il fix dipende ora solo dai consumer diretti (HTML nativo), non da un override.

## PHP (widget)

`XotBaseSchemaWidget`: `HasSchemas`, `fill()`, `getState()`. Validazione solo in `*Form`.

## Collegamenti

- [filament-page-form-wrapper](./wiki/concepts/filament-page-form-wrapper.md)
- [filament-form-schema-conventions.md](./filament-form-schema-conventions.md)
- Root wiki: [filament-v5-form-in-blade](../../../../docs/wiki/concepts/filament-v5-form-in-blade.md)
