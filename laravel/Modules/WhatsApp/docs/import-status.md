---
title: "Import status — WhatsApp (replica)"
type: concept
tags: [whatsapp, replica, import, wave3, bmad]
created: 2026-07-23
updated: 2026-07-24
qmd: "WhatsApp import status wave3 replica enabled"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
  - "https://github.com/laraxot/base_workorder_fila5/issues/14"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
  - "https://github.com/laraxot/base_workorder_fila5/discussions/15"
related:
  - "./composer-manifest.md"
  - "./README.md"
  - "../../../../docs/gestionale-commesse-comparison/replica-singular-phase.md"
  - "../../../../laravel/Themes/docs/gc-modules-runtime-matrix.md"
---

# Import status — WhatsApp

> Replica Wave 3. **Non** merge. **Non** cancellare.

- [x] Layout `app/` + XotBase SP / Route / Resources principali
- [x] QueueableAction
- [x] `modules_statuses=true` (2026-07-24)
- [ ] EventServiceProvider → XotBaseEventServiceProvider (se ancora Illuminate)
- [ ] BaseModel / factories / Pest
- [ ] PHPStan L10 rieseguire post-enable

Matrice: [gc-modules-runtime-matrix.md](../../../../laravel/Themes/docs/gc-modules-runtime-matrix.md).

## Test migrati dal sorgente

Da `gestionale_commesse/tests/Feature/WhatsApp/`:

- `OrganizeWhatsAppRequestActionTest.php` → `tests/Feature/OrganizeWhatsAppRequestActionTest.php` (tutti gli 8 scenari: work-order mode, customer mode, appuntamento valido/non valido, match per telefono/nome, riuso work order aperto, fallback placeholder).
- `GetWhatsAppInboxQueryTest.php` → `tests/Feature/GetWhatsAppInboxQueryTest.php` (tutti e 4 gli scenari: riepilogo ordinato per recency, conversazione senza messaggi, limite, thread cronologico).
- `WhatsAppInboxPageTest.php` → `tests/Feature/WhatsAppInboxPageTest.php` (tutti e 3 gli scenari, testati via `Livewire::test()` senza autenticazione: `mount()`/`conversationList()`/`thread()` non invocano il Gate).
- `WhatsAppAiIntegrationTest.php` + `ExecuteOrganizeWhatsAppProposalTest.php` → `tests/Feature/WhatsAppAiIntegrationTest.php`: webhook Meta/YCloud idempotenti, invio outbound Meta/YCloud, esecuzione proposta `organize_whatsapp_request` (successo + fallimento senza messaggio collegato).

Adattamenti: `Modules\WhatsApp\{Application\Actions,Application\Queries,Application\Jobs,Infrastructure\Models}` → `Modules\WhatsApp\{Actions,Queries,Jobs,Models}`; `Modules\Customers\Infrastructure\Models` → `Modules\Customer\Models`; `Modules\WorkOrders\Infrastructure\Models` → `Modules\WorkOrder\Models`; `Modules\AiAssistant\Infrastructure\Models` → `Modules\AiAssistant\Models`; `Modules\Platform\Infrastructure\Models` → `Modules\Platform\Models`.

**Non portato:**
- "classifies an inbound WhatsApp message with OpenAI ..." e "keeps a single live proposal per conversation ..." (`ProcessWhatsAppMessageAction` end-to-end) e "executes a WhatsApp proposal by appending an activity ..." — stessa ragione documentata per Email: dipendono da `ShieldPermissionsSeeder`/ruoli admin generati via Artisan `shield:generate`, non eseguibile in modo affidabile in questo ambiente di test.
- "renders the WhatsApp AI settings page and persists configuration" — `WhatsAppSettingsPage::canAccess()` usa `Gate::allows('manage-whatsapp-ai')`, sempre negato dallo shim `App\Support\AccountFeatures::hasFeature()` (debito tecnico noto, non specifico di WhatsApp).

**Esito esecuzione (2026-07-24):** tutti i test con scritture DB falliscono con `SQLSTATE[HY000]: no such table` (`whatsapp_conversations`, `platform_settings`, `ai_threads`, …) — le migrazioni non sono applicate al DB `:memory:` di Pest in questo ambiente (vedi `laravel/docs/gestionale-commesse-debito-tecnico.md`), non un difetto di adattamento del test.

## Delta seconda tranche (2026-08-30)

Portato il delta sorgente: de-approvazione selettiva, eliminazione conversazioni,
segna-come-elaborata, guard AI sulle proposte.

- **Actions**: DeleteWhatsAppConversation, MarkWhatsAppConversationProcessed,
  ResetWhatsAppConversationApproval; ProcessWhatsAppMessageAction con catch
  `AiActionNotAllowedException` (messaggio processato, nessuna proposta, warning).
- **Console**: 3 comandi nuovi (whatsapp:deapprove / delete-conversation /
  mark-processed) + concern `PicksWhatsAppConversations` (in `Console/Concerns`,
  fuori dallo scanner comandi) + ReprocessWhatsAppMessagesCommand rifattorizzato.
- **Pages**: Inbox e Settings → `GatedXotBasePage`.
- **Test**: +6 file portati; helper inbox ora autentica un admin con feature
  `admin.whatsapp_ai` via matrix (niente shim permissivo).

Fix dest contestuali:
- `RouteServiceProvider`: `$module_dir` mancante → api.php mai caricato; webhook
  Meta/YCloud ora sotto `/api/webhooks/whatsapp/*` (aggiornare URL sul provider).
- **`use App\Models\User` nei moduli portati**: è una classe ORFANA della base
  (≠ `Modules\User\Models\User`) → ogni `instanceof`/Gate falliva in silenzio.
  Bonificati Email/Intervention/WhatsApp (11 file). Da controllare a ogni ciclo.
- `AccountFeatures::canAccessPanel`: default per panel modulo non in matrix →
  semantica admin (come currentPanelAllowsFeature).
- `CreateAiActionProposalAction`: `proposedByUserId` ora `int|string` (UUID).
- Scanner comandi Xot: manifest `_components.json` congela la discovery — va
  rimosso quando si aggiungono comandi.
- Fixture: ricreata `whatsapp_messages` (la sua migration aggiunge il checkpoint
  `approved_up_to_message_id` a conversations).

Suite modulo: 42/42 verdi.
