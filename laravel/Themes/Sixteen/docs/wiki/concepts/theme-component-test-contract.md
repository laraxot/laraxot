---
title: "Sixteen — contratto test componenti da modulo UI"
type: concept
tags: [sixteen, theme, testing, ui, components, pest]
created: 2026-06-13
updated: 2026-06-13
qmd: "Sixteen theme component test contract UI module ComponentFilesExistTest path"
issues:
  - "https://github.com/laraxot/module_fixcity_fila5/issues/52"
discussions:
  - "https://github.com/laraxot/module_fixcity_fila5/discussions/53"
related:
  - ../overviews/completion-roadmap.md
  - ../../../Modules/UI/docs/wiki/concepts/testing.md
  - ../../../Modules/Fixcity/docs/wiki/concepts/phpstan-pest-testcase-helpers.md
---

# Sixteen — contratto test componenti da modulo UI

## Perché

Il modulo **UI** contiene test che validano la **struttura file** del tema Sixteen (`Themes/Sixteen/resources/views/components/`). È un confine intenzionale: il design system FO vive nel tema; i test restano nel modulo UI per `phpunit.xml` centrale e PHPStan moduli.

## Path canonico

```php
base_path('Themes/Sixteen/resources/views/components')
```

Helper in `Modules/UI/tests/Feature/ComponentFilesExistTest.php`: `sixteenComponentsBasePath()`, `requireSixteenComponentsBasePath()`.

## Regole test

1. **Skip, non fail**, se directory tema assente (install parziale).
2. **Skip aggregato** se file legacy in root `components/` ancora presenti — evita loop `markTestSkipped` per PHPStan dead-code.
3. Verificare sotto-cartelle: `forms/`, `utilities/`, `layout/sections/`, `navigation/`, `overlays/`, `data-display/`, `feedback/`, `media/`, `auth/`, `footer/`, `blocks/forms/`.

## Wizard ticket

`CreateTicketWizardWidgetViewTest` (Fixcity) verifica anche:

- `Themes/Sixteen/resources/views/filament/widgets/create-ticket-wizard.blade.php`
- Nessun form annidato attorno a `{{ $this->form }}`
- Presenza `<x-filament-actions::modals />`

## Quando aggiungere un componente Sixteen

1. Creare file sotto la sottocartella corretta nel tema.
2. Aggiornare lista `$expected` in `ComponentFilesExistTest` se il componente è parte del contratto pubblico.
3. `npm run build` se il componente include asset.
4. Documentare in wiki tema se il componente è usato da CMS block o Folio.

## Verifica

```bash
cd laravel
./vendor/bin/pest Modules/UI/tests/Feature/ComponentFilesExistTest.php
./vendor/bin/pest Modules/Fixcity/tests/Unit/CreateTicketWizardWidgetViewTest.php
```
