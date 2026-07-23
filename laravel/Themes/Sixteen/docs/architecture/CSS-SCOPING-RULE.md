# CSS Scoping Rule

Related:
- [HTML parity body policy](../html-parity-body-policy.md)
- [Page routing architecture](./PAGE_ROUTING_ARCHITECTURE.md)
- [Theme docs index](../00-INDEX.md)
- [Fixcity parity rule](../../../Modules/Fixcity/docs/html-body-parity-rule.md)
- [Agent parity rule](../../../../bashscripts/ai/.agents/docs/rules/html-parity.md)

## Rule

Per parity con Design Comuni:

1. Il tag `<body>` resta sempre plain.
2. Non si usano classi o wrapper pagina-specifici inventati.
3. Lo scoping CSS/JS deve partire dal wrapper canonico del progetto:
   `div.page-content[data-slug="..."][data-side="content"]`.
4. `tests-view-wrapper` non e un hook valido di parity: e un dettaglio infrastrutturale Volt/Livewire, instabile e non presente nel reference HTML.
5. Se nel DOM compaiono marker anomali tipo `|---LINE:37---|`, prima pulire le cache con `php laravel/artisan view:clear` e `php laravel/artisan optimize:clear`, poi rivalutare il markup.
6. Per ridurre token e churn, partire da hook runtime reali (`data-slug`, wrapper del widget, classi Filament del wizard) e da screenshot mirati mobile/tablet prima di leggere documentazione ampia.

## Correct pattern

```blade
<div class="page-content content" data-slug="{{ $pageSlug }}" data-side="content">
    @foreach($blocks as $block)
        @include($block->view, array_merge($data, ['data' => $block->data]))
    @endforeach
</div>
```

```css
.page-content[data-slug="tests.segnalazione-02-dati"] .steppers-header {
  /* page-specific parity rules */
}
```

## Wrong pattern

```html
<body class="page-tests-segnalazione-02-dati">
```

```blade
<div class="tests-view-wrapper">
```

```css
.tests-view-wrapper .steppers-header {
  /* coupled to runtime wrapper */
}
```

## Why

- `page-content[data-slug]` e semantico, stabile e gia usato nel Cms.
- `tests-view-wrapper` compare per esigenze di rendering runtime, non di dominio.
- La parity si misura contro il DOM di riferimento, non contro wrapper tecnici interni.
- Il wrapper canonico risolve anche il vincolo Livewire del singolo root element senza introdurre hook sbagliati.

## Enforcement

- Se una pagina test non ha `page-content[data-slug]`, va aggiunto il wrapper canonico.
- Se un CSS usa `tests-view-wrapper`, va migrato.
- Se una doc propone `tests-view-wrapper`, va corretta.

See also:
- [HTML parity body policy](../html-parity-body-policy.md)
- [Page routing architecture](./PAGE_ROUTING_ARCHITECTURE.md)
- [Theme docs index](../00-INDEX.md)
