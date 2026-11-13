---
title: "Comment: ingresso del modulo"
type: documentation
status: active
module: Comment
tags: [comment, module, docs]
created: 2026-09-26
updated: 2026-10-20
qmd: "comment modulo commenti moderazione reazioni notifiche ticket fixcity spatie internalizzazione limiti"
issues: []
discussions: []
---

# Comment

**Scopo:** abilitare commenti contestuali (thread, reazioni, moderazione, opt-in notifiche) su record dominio come Ticket e Article, cosi' Fixcity/Blog restano owner del contenuto e Comment resta owner di persistenza, policy e chrome Filament.

Ingresso della documentazione del modulo. Ogni affermazione e' stata confrontata con il codice il 2026-10-14. Hub BMAD: [bmad/decision-log.md](./bmad/decision-log.md), [bmad/filament-blueprint.md](./bmad/filament-blueprint.md).

## Identita' e ownership

- Namespace `Modules\Comment`, alias `comment`, repository git separato. Versione in `module.json`: 2.0.0.
- Provider in `module.json`: `CommentServiceProvider`, `CommentEngineServiceProvider`, `Providers\Filament\AdminPanelProvider`. In `laravel/config/app.php` e' registrato solo `CommentServiceProvider`.
- Stato: `module.json` dice `"enabled": false`, ma `laravel/modules_statuses.json` e `laravel/config/local/fixcity/modules_statuses.json` hanno `Comment: true` e il modulo e' usato da Fixcity e Blog. Vale lo stato dei file `modules_statuses.json`; la discrepanza non e' risolta.
- `module.json` dichiara `requires: Xot, User, Notification`: il modulo `Notification` non esiste, esiste `Notify`.
- Origine: motore commenti derivato da `spatie/laravel-comments` e `spatie/laravel-comments-livewire`, copiati in `packages/spatie/` e in corso di internalizzazione (hub: [native-comments-engine.md](./native-comments-engine.md)).

## Cosa contiene davvero

| Area | Contenuto verificato | Percorso |
|---|---|---|
| Modelli | `Comment`, `Reaction`, `CommentNotificationSubscription`, `CommentNotificationOptOut`; contratti `Commentable`, `CanComment`, `SupportsCommentNotifications`; concern `HasComments`, `InteractsWithComments` | `app/Models/` |
| Actions | 62 file: moderazione (`ApproveCommentAction`, `RejectCommentAction`, `ProcessCommentAction`), notifiche, reazioni, menzioni, sanificazione, risoluzione della configurazione | `app/Actions/` |
| Notifiche | `PendingCommentNotification`, `ApprovedCommentNotification`; eventi `CommentApprovedEvent`, `CommentRejectedEvent` | `app/Notifications/`, `app/Events/` |
| Interfaccia | `CommentResource` (Filament), widget `CommentsWidget`, `CommentWidget`, `MentionSearchWidget`; componenti Livewire `CommentComponent`, `CommentsComponent`, `MentionSearchComponent` | `app/Filament/`, `app/Http/Livewire/` |
| Configurazione | `config/comments.php` (modelli, policy, Actions, notifiche, reazioni, tag HTML ammessi); `config/config.php` ha solo `name` | `config/` |
| Dati | tre migrazioni: `comments`, `reactions`, `comment_notification_subscriptions` | `database/migrations/` |
| Viste | `livewire/`, `filament/widgets/`, `mail/`, `signed/` (approvazione e sottoscrizione via URL firmato), `folio-moderation/` | `resources/views/` |
| Test | 16 file, 44 chiamate `it()`/`test()` | `tests/` |

## Chi lo usa davvero in questo repository

- Fixcity: `Ticket` implementa `Commentable` con `HasComments`; il `User` di Fixcity implementa `CanComment` con `InteractsWithComments`. Il vecchio `TicketComment` resta come relazione di compatibilita'.
- Blog: `Article` usa `HasComments`; il tema TwentyOne monta `CommentsWidget` nella pagina articolo.

## Limiti

1. Rotte: `routes/web.php` e `routes/api.php` sono vuoti; `php artisan route:list --json` mostra 6 rotte, tutte del pannello Filament `comment/admin`. Le viste `folio-moderation/` non sono montate da nessun file di `app/` (grep); se siano raggiunte da Folio a livello di progetto non e' verificato.
2. Dipendenza residua da Spatie: `app/Actions/SanitizeCommentTextAction.php` importa `Spatie\Comments\Support\CommentSanitizer`; `packages/spatie/` e' ancora presente e risolto dall'autoload del progetto. La fase 4 (rimozione) dell'internalizzazione non e' fatta.
3. Documenti in ritardo sul codice: [ticket-comments.md](./ticket-comments.md) descrive il trait Spatie sul `Ticket` (oggi e' `Modules\Comment\Models\Concerns\HasComments`); lo stato di [native-comments-engine.md](./native-comments-engine.md) e' fermo al 2026-06-06.
4. Suite Pest non affidabile oggi: su DB usa-e-getta il 2026-10-14 3 test passano e 41 falliscono con `SQLiteDatabaseDoesNotExistException`.

## Quick start

Da `laravel/`:

```bash
# rotte del modulo (verificato il 2026-10-14: 6, tutte del pannello Filament comment/admin)
php artisan route:list --name=comment
# test su database usa-e-getta (la variabile vuota evita `unbound variable` dello script)
DB_DATABASE_LIMESURVEY= bash ../bashscripts/tools/pest-scratch-db.sh Modules/Comment/tests
```

## Mappa della documentazione

Indice operativo: [00-INDEX.md](./00-index.md). Indice per argomento: [index.md](./index.md). Hub del motore: [native-comments-engine.md](./native-comments-engine.md). Registro: [wiki/log.md](./wiki/log.md). Story: [stories/](./stories/).
