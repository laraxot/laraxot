# HTML Parity Body Policy

Related:
- [CSS scoping rule](architecture/CSS-SCOPING-RULE.md)
- [Theme docs index](00-INDEX.md)
- [Fixcity parity rule](../../Modules/Fixcity/docs/html-body-parity-rule.md)
- [Agent parity rule](../../../bashscripts/ai/.agents/docs/rules/html-parity.md)

## Permanent policy

- Il tag `<body>` deve restare plain: `<body>`.
- Per parity visiva si lavora su CSS/JS, non aggiungendo classi al body.
- Per scoping pagina-specifico si usa il wrapper canonico `page-content[data-slug]`.
- Non usare `tests-view-wrapper`: e un wrapper tecnico del runtime, non una contract di markup.

## Correct implementation

```blade
<x-layouts.app>
    @volt('tests.view')
    <div class="page-content content" data-slug="{{ $pageSlug }}" data-side="content">
        @foreach($blocks as $block)
            @include($block->view, array_merge($data, ['data' => $block->data]))
        @endforeach
    </div>
    @endvolt
</x-layouts.app>
```

```css
.page-content[data-slug="tests.segnalazione-02-dati"] .steppers-index {
  /* parity fix */
}
```

## Wrong implementation

```blade
<body class="page-tests-segnalazione-02-dati">
```

```blade
<div class="tests-view-wrapper" data-slug="segnalazione-02-dati">
```

```css
body:has(.tests-view-wrapper[data-slug="segnalazione-02-dati"]) .stepper {
}
```

## Decision note

La route `pages/tests/[slug].blade.php` deve usare lo stesso wrapper semantico del Cms (`page-content[data-slug]`) per mantenere coerenza architetturale, linkabilita dei docs e scoping robusto.

See also:
- [CSS scoping rule](architecture/CSS-SCOPING-RULE.md)
- [Fixcity parity rule](../../Modules/Fixcity/docs/html-body-parity-rule.md)
