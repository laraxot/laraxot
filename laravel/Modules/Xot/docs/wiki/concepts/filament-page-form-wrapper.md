---
title: "Wrapper form nelle Filament Page custom — plain <form>, non componenti inesistenti"
module: Xot
type: concept
status: approved
tags: [filament, blade, form, view-cache, cross-module]
created: "2026-07-20"
updated: "2026-07-24"
related:
  - "../../filament-v5-form-wrapper-blade-pattern.md"
  - "../../../../../../docs/wiki/concepts/filament-v5-form-in-blade.md"
  - "../../../../../../docs/wiki/memories/view-cache-gate-mandatory.md"
---

# Wrapper form nelle Filament Page custom

## Regola

Per avvolgere `{{ $this->form }}` in Page/widget Filament 5: **`<form wire:submit>`**, non `<x-filament-schemas::form>`.

Fonte: [filamentphp.com/docs/5.x/components/form](https://filamentphp.com/docs/5.x/components/form) + demo `resources/views/livewire/form.blade.php`.

```blade
<form wire:submit="save">
    {{ $this->form }}
    <x-filament::button type="submit">{{ __('Save') }}</x-filament::button>
</form>
<x-filament-actions::modals />
```

## Override vendor (rimosso)

Un override `laravel/resources/views/vendor/filament-schemas/components/form.blade.php`
(alias `<form {{ $attributes }}>{{ $slot }}</form>`) è esistito come rete di
sicurezza, ma `laravel/resources/views/vendor/` è **gitignored** e viene
azzerata da `composer run go` (`rm -rf ./resources/views/vendor/` +
`vendor:publish --all` — quest'ultimo non lo ricrea, perché `filament/schemas`
non pubblica alcun `form.blade.php`). Rimosso e non recuperabile (2026-07-24).

Il fix ora dipende **solo** dai 12 consumer diretti (vedi tabella sopra), non
più da un override. Se `composer run go` (o qualsiasi `rm -rf resources/views/vendor`)
gira di nuovo, il fix resta valido — verificalo comunque con `view:cache`.

## Verifica (obbligatoria)

```bash
cd laravel && php artisan view:cache
```

Exit 0. Verificato 2026-07-24 su questo checkout.
