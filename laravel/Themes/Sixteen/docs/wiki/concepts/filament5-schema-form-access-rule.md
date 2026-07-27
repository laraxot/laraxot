---
title: "Filament 5 Schema — accesso form nelle view Sixteen"
type: concept
module: Sixteen
tags: [filament, schema, form, view-cache, theme]
created: 2026-06-01
updated: 2026-07-24
related:
  - ./filament-multiple-forms.md
  - ../../../../../../docs/wiki/concepts/filament-v5-form-in-blade.md
  - ../../../../../../docs/wiki/memories/view-cache-gate-mandatory.md
  - ../../../../Modules/Xot/docs/wiki/concepts/filament-page-form-wrapper.md
---

# Filament 5 — schema form nelle view Sixteen

Upstream:
- Schema generico: [components/schema](https://filamentphp.com/docs/5.x/components/schema) → `{{ $this->{method} }}`
- Form: [components/form](https://filamentphp.com/docs/5.x/components/form) → `<form>` + `{{ $this->form }}` + `getState()`

Canon root: [filament-v5-schema-in-blade](../../../../../../docs/wiki/concepts/filament-v5-schema-in-blade.md).

## Regola tema

```blade
<form wire:submit="submit">
    {{ $this->form }}
</form>
```

Per infolist/read-only dal widget: `{{ $this->infolist }}` (metodo schema nominato, non wrapper inventati).

- Render: `{{ $this->form }}` (non `getForm('form')`, non `x-filament-schemas::form`)
- Submit widget: `$this->form->getState()`
- Schema owner: metodi `get{Name}Schema()` **senza** parametri runtime dal tema

## Confini

| Owner | Responsabilità |
|-------|----------------|
| Modulo / widget | Schema, validazione, `getState()`, redirect |
| Tema Sixteen | Markup, CTA Design Comuni, CSS parity |

## Gate chiusura

```bash
cd laravel && php artisan view:cache
```

Exit 0 obbligatorio dopo edit Blade del tema.
