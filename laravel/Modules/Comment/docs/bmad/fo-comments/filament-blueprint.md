---
title: "Filament Blueprint — CommentsWidget + CommentResource"
type: filament-blueprint
status: active
module: Comment
created: 2026-10-16
updated: 2026-10-16
tags: [bmad, filament, blueprint, comment, widget]
qmd: "Filament blueprint CommentsWidget CommentResource Form Infolist Table FO"
related:
  - ./architecture.md
  - ../../concepts/filament-resource-schemas-zen.md
  - ../../concepts/xot-base-filament-widgets.md
  - ../../../../Blog/docs/blueprints/inbound-blog-events.md
issues: []
discussions: []
---

# Filament Blueprint — Comment

## Scopo

Contratto Filament per FO e BO: widget di dialogo + resource di moderazione.
Completa il Blueprint Blog (authoring Article) sul morph commenti.

## FO — `CommentsWidget`

| Campo | Valore |
|---|---|
| Class | `Modules\Comment\Filament\Widgets\Commentable\CommentsWidget` |
| Base | `XotBaseSchemaWidget` |
| View | `comment::filament.widgets.commentable.comments` |
| Discovered | `false` (mount esplicito) |

### Mount canonico

```blade
@auth
  @livewire(\Modules\Comment\Filament\Widgets\Commentable\CommentsWidget::class, [
      'commentableType' => $model::class,
      'commentableKey' => (string) $model->getKey(),
      'readOnly' => false,
      'hideNotifications' => true,
      'noReplies' => false,
      'noReactions' => true,
  ], key('comments-'.$model->getKey()))
@endauth
@guest
  @livewire(\Modules\Comment\Filament\Widgets\Commentable\CommentsWidget::class, [
      'commentableType' => $model::class,
      'commentableKey' => (string) $model->getKey(),
      'readOnly' => true,
      'hideNotifications' => true,
      'noReplies' => false,
      'noReactions' => true,
  ], key('comments-ro-'.$model->getKey()))
@endguest
```

### Campi (CMT-022)

| Widget | Form class | Schema | Campi |
|---|---|---|---|
| `CommentsWidget` | `CommentTextForm` | `getCommentFormSchema()` | `text`: `MarkdownEditor`, 2-2000 caratteri, toolbar ridotta, senza allegati |
| `CommentWidget` | `CommentTextForm` | `getCommentFormSchema()` | stesso campo `text` (stato `data.text`) per risposta e modifica; l'id del campo e' per istanza Livewire |

Aggiornato 2026-10-27: `CommentForm::getFrontOfficeCommentSchema()` e `getFrontOfficeReplySchema()` (campi `reply_text` e `edit_text`) sono stati tolti; lo schema vive in `Filament/Widgets/Schemas/CommentTextForm.php`, uno solo per i tre usi. Test: `FrontOfficeCommentThreadTest`.

- Il submit legge `$this->form->getState()`; la pipeline MarkdownToHtml + sanitizer produce `text`.
- Le risposte hanno un livello: il pulsante "Rispondi" apre l'editor sotto il commento.
- Le opzioni passano a `CommentWidget` con la chiave `ui`, mai `uiConfig` (Livewire la assegnerebbe alla proprieta' tipizzata prima di `mount`).
- L'avatar e' `commentatorProperties()->avatar`: media del profilo, altrimenti Gravatar `d=mp`, altrimenti le iniziali. L'header usa la stessa fonte.

### Vietato

- `Modules\Comment\Http\Livewire\*`
- Prop `writable` come sostituto di `readOnly`
- Wrappare il mount in `@if ($comments->isNotEmpty())`
- `<textarea>` raw nel widget: i campi stanno in `CommentForm`

## BO — `CommentResource`

Triade: `CommentForm` · `CommentInfolist` · `CommentsTable`.

## Host models

| Model | Module | Trait |
|---|---|---|
| `Article` (post\|event) | Blog | `HasComments` |
| `Ticket` | Fixcity | `HasComments` |
| Commentator | Fixcity `User` | `CanComment` + `InteractsWithComments` |
