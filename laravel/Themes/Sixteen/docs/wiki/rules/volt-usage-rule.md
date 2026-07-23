---
title: "Volt in pagine Folio — @volt statico obbligatorio"
type: rule
tags: [volt, folio, livewire, architecture, blade, mount, container0, container1]
created: 2026-04-15
updated: 2026-06-05
qmd: "volt folio static name mount container0 container1 view list no dynamic pageSlug"
issues:
  - "https://github.com/laraxot/theme_sixteen_fila5/issues/50"
discussions:
  - "https://github.com/laraxot/base_fixcity_fila5/discussions/259"
related:
  - ../../folio-page-pattern.md
  - ../concepts/folio-route-params-mount.md
  - ../../../../../../docs/wiki/memories/volt-route-params-mount-contract.md
  - ../../../../../../docs/wiki/rules/laravel/volt-route-params-via-mount.md
---

# Volt in pagine Folio — `@volt` statico obbligatorio

## Regola

Pagine Folio con `new class extends Component` **richiedono** `@volt('…')` **statico**, identico a `name('…')`.

| Livello path | `name()` | `@volt()` |
|--------------|----------|-----------|
| `[container0]/index` | `container0.index` | `@volt('container0.index')` |
| `[container0]/[slug0]/index` | `container0.view` | `@volt('container0.view')` |
| `[container0]/[slug0]/[container1]/index` | `container1.index` | `@volt('container1.index')` |

Senza `@volt` statico → `VoltDirectiveMissingException`.

## Corretto

```blade
<x-layouts.app>
    @volt('container1.index')
    <div class="page-content content" data-slug="{{ $pageSlug }}" data-side="content">
        <x-page side="content" :slug="$pageSlug" :data="$data" />
    </div>
    @endvolt
</x-layouts.app>
```

`$pageSlug` resta slug **CMS** per `<x-page>` — non è il nome Volt.

## Vietato

```blade
@volt($pageSlug)
@volt('folio.' . $container0 . '.view')
@php $container0 = request()->route('container0'); @endphp
```

## Distinzioni

| Concetto | Uso |
|----------|-----|
| `name('container1.index')` | Named route Folio (`folio:list`) |
| `@volt('container1.index')` | Template SFC — stringa fissa |
| `$pageSlug` (`services.view`) | Slug blocchi CMS in `<x-page>` |
| Widget FO (auth, dropdown) | Volt separati in `resources/views/livewire/` |

## Verifica

- `tests/Unit/FolioPageMountContractTest.php`
- `rg "@volt\\(\\$" Themes/Sixteen/resources/views/pages`

## Backlink

- [folio-page-pattern.md](../../folio-page-pattern.md)
- [folio-route-params-mount.md](../concepts/folio-route-params-mount.md)
- [volt-route-params-mount-contract](../../../../../../docs/wiki/memories/volt-route-params-mount-contract.md)
