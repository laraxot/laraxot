---
title: "case variant collisions"
type: note
tags: [documentation]
created: 2026-09-26
updated: 2026-09-26
qmd: "case variant collisions"
issues: []
discussions: []
status: draft
---

# Collisioni di nome per sola differenza di maiuscole

**Misurato**: 2026-08-31
**Regola violata**: `no-case-variations`, `case_sensitive_naming_critical`
**Quadro generale**: [Modules/Xot/docs/stato-qualita-progetto-2026-08-31.md](../../Xot/docs/stato-qualita-progetto-2026-08-31.md)

## Il problema

In questo modulo esistono percorsi che differiscono solo per maiuscole. Su Linux
convivono. Su filesystem case-insensitive, cioe' macOS di default e Windows, i due
percorsi sono lo stesso percorso: al clone uno dei due file sovrascrive l'altro, in
modo non deterministico. Il repository risulta corrotto senza che nulla segnali
l'errore.

Riproduzione:

```bash
cd laravel && find Modules/Comment -name '*.php' -not -path '*/vendor/*' \
  | awk '{print tolower($0)}' | sort | uniq -d
```

## Coppie a contenuto identico (44)

Pura duplicazione. Si tiene la variante conforme a PSR-4 e si cancella l'altra.

```
Modules/Comment/packages/spatie/laravel-comments-livewire/src/livewire/CommentComponent.php
Modules/Comment/packages/spatie/laravel-comments-livewire/src/Livewire/CommentComponent.php

Modules/Comment/packages/spatie/laravel-comments-livewire/src/livewire/CommentsComponent.php
Modules/Comment/packages/spatie/laravel-comments-livewire/src/Livewire/CommentsComponent.php

Modules/Comment/packages/spatie/laravel-comments-livewire/src/livewire/MentionSearchComponent.php
Modules/Comment/packages/spatie/laravel-comments-livewire/src/Livewire/MentionSearchComponent.php

Modules/Comment/packages/spatie/laravel-comments-livewire/src/policies/CommentPolicy.php
Modules/Comment/packages/spatie/laravel-comments-livewire/src/Policies/CommentPolicy.php

Modules/Comment/packages/spatie/laravel-comments-livewire/src/policies/ReactionPolicy.php
Modules/Comment/packages/spatie/laravel-comments-livewire/src/Policies/ReactionPolicy.php

Modules/Comment/packages/spatie/laravel-comments-livewire/src/support/Config.php
Modules/Comment/packages/spatie/laravel-comments-livewire/src/Support/Config.php

Modules/Comment/packages/spatie/laravel-comments-livewire/src/support/Gravatar.php
Modules/Comment/packages/spatie/laravel-comments-livewire/src/Support/Gravatar.php

Modules/Comment/packages/spatie/laravel-comments-livewire/src/view/components/Styles.php
Modules/Comment/packages/spatie/laravel-comments-livewire/src/View/Components/Styles.php

Modules/Comment/packages/spatie/laravel-comments-livewire/src/view/components/mentions/Mention.php
Modules/Comment/packages/spatie/laravel-comments-livewire/src/View/Components/Mentions/Mention.php

Modules/Comment/packages/spatie/laravel-comments/src/actions/ApproveCommentAction.php
Modules/Comment/packages/spatie/laravel-comments/src/Actions/ApproveCommentAction.php

Modules/Comment/packages/spatie/laravel-comments/src/actions/ProcessCommentAction.php
Modules/Comment/packages/spatie/laravel-comments/src/Actions/ProcessCommentAction.php

Modules/Comment/packages/spatie/laravel-comments/src/actions/RejectCommentAction.php
Modules/Comment/packages/spatie/laravel-comments/src/Actions/RejectCommentAction.php

Modules/Comment/packages/spatie/laravel-comments/src/actions/ResolveMentionsAutocompleteAction.php
Modules/Comment/packages/spatie/laravel-comments/src/Actions/ResolveMentionsAutocompleteAction.php

Modules/Comment/packages/spatie/laravel-comments/src/actions/SendNotificationsForApprovedCommentAction.php
Modules/Comment/packages/spatie/laravel-comments/src/Actions/SendNotificationsForApprovedCommentAction.php

Modules/Comment/packages/spatie/laravel-comments/src/actions/SendNotificationsForPendingCommentAction.php
Modules/Comment/packages/spatie/laravel-comments/src/Actions/SendNotificationsForPendingCommentAction.php

Modules/Comment/packages/spatie/laravel-comments/src/commenttransformers/CommentTransformer.php
Modules/Comment/packages/spatie/laravel-comments/src/CommentTransformers/CommentTransformer.php

Modules/Comment/packages/spatie/laravel-comments/src/commenttransformers/MarkdownToHtmlTransformer.php
Modules/Comment/packages/spatie/laravel-comments/src/CommentTransformers/MarkdownToHtmlTransformer.php

Modules/Comment/packages/spatie/laravel-comments/src/commenttransformers/MentionsTransformer.php
Modules/Comment/packages/spatie/laravel-comments/src/CommentTransformers/MentionsTransformer.php

Modules/Comment/packages/spatie/laravel-comments/src/enums/NotificationSubscriptionType.php
Modules/Comment/packages/spatie/laravel-comments/src/Enums/NotificationSubscriptionType.php

Modules/Comment/packages/spatie/laravel-comments/src/events/CommentApprovedEvent.php
Modules/Comment/packages/spatie/laravel-comments/src/Events/CommentApprovedEvent.php

Modules/Comment/packages/spatie/laravel-comments/src/events/CommentRejectedEvent.php
Modules/Comment/packages/spatie/laravel-comments/src/Events/CommentRejectedEvent.php

Modules/Comment/packages/spatie/laravel-comments/src/exceptions/CannotCreateComment.php
Modules/Comment/packages/spatie/laravel-comments/src/Exceptions/CannotCreateComment.php

Modules/Comment/packages/spatie/laravel-comments/src/exceptions/CannotSendPendingCommentNotification.php
Modules/Comment/packages/spatie/laravel-comments/src/Exceptions/CannotSendPendingCommentNotification.php

Modules/Comment/packages/spatie/laravel-comments/src/exceptions/InvalidConfig.php
Modules/Comment/packages/spatie/laravel-comments/src/Exceptions/InvalidConfig.php

Modules/Comment/packages/spatie/laravel-comments/src/http/controllers/ApproveCommentController.php
Modules/Comment/packages/spatie/laravel-comments/src/Http/Controllers/ApproveCommentController.php

Modules/Comment/packages/spatie/laravel-comments/src/http/controllers/RejectCommentController.php
Modules/Comment/packages/spatie/laravel-comments/src/Http/Controllers/RejectCommentController.php

Modules/Comment/packages/spatie/laravel-comments/src/http/controllers/UnsubscribeFromAllNotificationsController.php
Modules/Comment/packages/spatie/laravel-comments/src/Http/Controllers/UnsubscribeFromAllNotificationsController.php

Modules/Comment/packages/spatie/laravel-comments/src/http/controllers/UnsubscribeFromNotificationsController.php
Modules/Comment/packages/spatie/laravel-comments/src/Http/Controllers/UnsubscribeFromNotificationsController.php

Modules/Comment/packages/spatie/laravel-comments/src/jobs/SendNotificationsForApprovedCommentJob.php
Modules/Comment/packages/spatie/laravel-comments/src/Jobs/SendNotificationsForApprovedCommentJob.php

Modules/Comment/packages/spatie/laravel-comments/src/jobs/SendNotificationsForPendingCommentJob.php
Modules/Comment/packages/spatie/laravel-comments/src/Jobs/SendNotificationsForPendingCommentJob.php

Modules/Comment/packages/spatie/laravel-comments/src/models/Comment.php
Modules/Comment/packages/spatie/laravel-comments/src/Models/Comment.php

Modules/Comment/packages/spatie/laravel-comments/src/models/CommentNotificationOptOut.php
Modules/Comment/packages/spatie/laravel-comments/src/Models/CommentNotificationOptOut.php

Modules/Comment/packages/spatie/laravel-comments/src/models/CommentNotificationSubscription.php
Modules/Comment/packages/spatie/laravel-comments/src/Models/CommentNotificationSubscription.php

Modules/Comment/packages/spatie/laravel-comments/src/models/Reaction.php
Modules/Comment/packages/spatie/laravel-comments/src/Models/Reaction.php

Modules/Comment/packages/spatie/laravel-comments/src/models/collections/ReactionCollection.php
Modules/Comment/packages/spatie/laravel-comments/src/Models/Collections/ReactionCollection.php

Modules/Comment/packages/spatie/laravel-comments/src/models/concerns/HasComments.php
Modules/Comment/packages/spatie/laravel-comments/src/Models/Concerns/HasComments.php

Modules/Comment/packages/spatie/laravel-comments/src/models/concerns/InteractsWithComments.php
Modules/Comment/packages/spatie/laravel-comments/src/Models/Concerns/InteractsWithComments.php

Modules/Comment/packages/spatie/laravel-comments/src/models/concerns/interfaces/CanComment.php
Modules/Comment/packages/spatie/laravel-comments/src/Models/Concerns/Interfaces/CanComment.php

Modules/Comment/packages/spatie/laravel-comments/src/notifications/ApprovedCommentNotification.php
Modules/Comment/packages/spatie/laravel-comments/src/Notifications/ApprovedCommentNotification.php

Modules/Comment/packages/spatie/laravel-comments/src/notifications/PendingCommentNotification.php
Modules/Comment/packages/spatie/laravel-comments/src/Notifications/PendingCommentNotification.php

Modules/Comment/packages/spatie/laravel-comments/src/support/CommentSanitizer.php
Modules/Comment/packages/spatie/laravel-comments/src/Support/CommentSanitizer.php

Modules/Comment/packages/spatie/laravel-comments/src/support/CommentatorProperties.php
Modules/Comment/packages/spatie/laravel-comments/src/Support/CommentatorProperties.php

Modules/Comment/packages/spatie/laravel-comments/src/support/Config.php
Modules/Comment/packages/spatie/laravel-comments/src/Support/Config.php

Modules/Comment/packages/spatie/laravel-comments/src/view/components/mentions/Mention.php
Modules/Comment/packages/spatie/laravel-comments/src/View/Components/Mentions/Mention.php
```

## Come si chiude

1. Per ogni coppia divergente, confrontare il contenuto e decidere quale versione
   sopravvive; portare in quella le parti utili dell'altra.
2. Rinominare in avanti verso la forma PSR-4 (`Fixtures/`, `Unit/`, `Feature/`,
   `DataObjects/`, `Config/`, `Providers/`).
3. Rimuovere la variante superflua.
4. Verificare che il conteggio del comando qui sopra sia sceso a zero.

Serve un test che impedisca la ricomparsa del difetto: senza, il conteggio torna a
crescere. Vedi il punto 4 del quadro generale.

## 2026-10-16 — igiene docs

- Root UPPERCASE → stub redirect al twin minuscolo.
- Story `CMT-*` rinominate in `cmt-*` (kebab minuscolo).
- Indice: `00-INDEX.md`; alias redirect.
- FO track: [bmad/fo-comments/](./bmad/fo-comments/).
