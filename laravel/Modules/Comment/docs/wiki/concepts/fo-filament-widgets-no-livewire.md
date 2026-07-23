---
title: FO commenti — Filament widget, no Livewire diretto
type: concept
tags: [comment, filament, widget, front-office, architecture]
qmd:
  index: true
issues:
  - https://github.com/laraxot/module_comment_fila5/issues/7
discussions:
  - https://github.com/laraxot/module_comment_fila5/discussions/11
  - https://github.com/laraxot/module_comment_fila5/discussions/15
created_at: 2026-06-10
updated_at: 2026-06-10
---

# FO commenti — Filament widget, no Livewire diretto

## Perché

- **Un solo entry point UI** FO: Filament widget su gerarchia `XotBase*` (qui `XotBaseSchemaWidget`) (form, azioni, policy, view composer).
- **Coerenza Laraxot**: come `LoginWidget`, `Ticket\ViewWidget` — niente `Http/Livewire\Component` per dominio.
- **Manutenibilità**: cartelle per contesto (`Commentable`, `Comment`, `Mention`), view sotto `resources/views/filament/widgets/`.
- **Niente alias Spatie**: rimossi `Livewire::component('comments', …)` da `CommentEngineServiceProvider`.

## Struttura

```
app/Filament/Widgets/
  Commentable/CommentsWidget.php   # lista + nuovo commento su Commentable
  Comment/CommentWidget.php        # singolo thread (edit, reply, reazioni)
  Mention/MentionSearchWidget.php  # autocomplete @
```

## Consumo

```blade
@livewire(\Modules\Comment\Filament\Widgets\Commentable\CommentsWidget::class, ['model' => $record])
```

## Vietato

- `Modules\Comment\Http\Livewire\*`
- `Livewire::component()` per UI commenti
- `<livewire:comments>` / alias stringa legacy

## Test

- `tests/Unit/CommentFilamentWidgetsTest.php`
- `tests/Unit/MentionsFoOptInTest.php`
- `Modules/Fixcity/tests/Feature/TicketSpatieCommentsTest.php`

## Collegamenti
- [ADR Commentable vs Comment naming](../../decisions/commentable-widget-naming.md)
- [Sottocartelle per soggetto](../../concepts/filament-widgets-subject-subfolders.md)

- [mentions-fo-opt-in](./mentions-fo-opt-in.md)
- [Integrazione tema FO](../../../../Themes/docs/concepts/comment-module-fo-integration.md)

## Base class

Mai `Filament\Widgets\Widget`. Minimo `XotBaseWidget`; FO commenti usano `XotBaseSchemaWidget` con view auto + UI Data bag.

- [Gerarchia XotBase](../../../Xot/docs/wiki/concepts/xotbase-filament-widget-hierarchy.md)
