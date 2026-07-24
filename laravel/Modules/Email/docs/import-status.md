---
title: "Import status — Email (replica)"
type: concept
tags: [email, replica, import, wave3, bmad]
created: 2026-07-23
updated: 2026-07-24
qmd: "Email import status wave3 replica enabled"
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

# Import status — Email

> Replica Wave 3. **Non** merge in Notify/User. **Non** cancellare.

- [x] Layout `app/` + XotBase SP / Route / Resources principali
- [x] QueueableAction sulle Action con `execute()`
- [x] `modules_statuses=true` (2026-07-24)
- [ ] EventServiceProvider → XotBaseEventServiceProvider (se ancora Illuminate)
- [ ] BaseModel / factories / Pest
- [ ] PHPStan L10 rieseguire post-enable (baseline storica reshape non più SSoT)

Matrice: [gc-modules-runtime-matrix.md](../../../../laravel/Themes/docs/gc-modules-runtime-matrix.md).

## Test migrati dal sorgente

Da `gestionale_commesse/tests/Feature/Email/EmailAiIntegrationTest.php` → `tests/Feature/EmailInboxActionsTest.php`:

- "stores incoming signed email webhooks idempotently"
- "polls the configured inbox and stores unread emails"
- "marks a processed message as processed without reprocessing" (variante minima, non presente nel sorgente, aggiunta per coprire l'early-return di `ProcessEmailMessageAction` senza dipendere da Shield)

Adattamenti: `Modules\Customers\Infrastructure\Models\{Contact,Customer}` → `Modules\Customer\Models\*`; `Modules\WorkOrders\Infrastructure\Models\*` → `Modules\WorkOrder\Models\*`; `Modules\AiAssistant\Infrastructure\Models\*` → `Modules\AiAssistant\Models\*`; `Modules\Email\Application\*` → `Modules\Email\*` (Actions/Inbox/Jobs diretti nel modulo, non più sotto `Application`).

**Non portato:**
- "classifies an inbound email with OpenAI and creates an approval proposal" e "executes an email proposal by appending an activity to the work order" — richiedono `ShieldPermissionsSeeder` (che invoca `Artisan::call('shield:generate', ...)`) e un ruolo `admin` con permessi generati; nell'ambiente di test attuale (sqlite `:memory:` senza migrazioni applicate) l'intera catena Shield→permessi→policy non è eseguibile in modo affidabile.
- "renders the Email AI settings page and persists inbox configuration" — `EmailSettingsPage::canAccess()` usa `Gate::allows('manage-email-ai')`, che dipende da `App\Support\AccountFeatures::hasFeature()`: uno shim che ritorna sempre il `$default` passato (qui `false`), quindi il gate risulta sempre negato indipendentemente dall'utente autenticato. Debito tecnico noto, non specifico di Email.

**Esito esecuzione (2026-07-24):** i test portati falliscono con `SQLSTATE[HY000]: no such table` (`platform_settings`, `email_conversations`, …) — le migrazioni non sono applicate al DB `:memory:` di Pest in questo ambiente (vedi `laravel/docs/gestionale-commesse-debito-tecnico.md`), non un difetto di adattamento.
