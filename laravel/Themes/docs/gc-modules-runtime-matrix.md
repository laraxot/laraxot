---
title: "Matrice runtime moduli GC vs Themes"
type: concept
tags: [themes, gestionale-commesse, modules-statuses, bmad, runtime]
created: 2026-07-24
updated: 2026-07-27
qmd: "gc modules runtime matrix statuses enabled Auth Document media themes"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/14"
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/15"
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
related:
  - "./gestionale-docs-index.md"
  - "./new-modules-panel-mapping.md"
  - "./copy-normalize-modules-vs-themes.md"
  - "./module-vs-theme-boundaries.md"
  - "../../../docs/gestionale-commesse-comparison/functional-readiness.md"
  - "../../../docs/gestionale-commesse-comparison/replica-singular-phase.md"
  - "../../../docs/stories/2.4.module-theme-docs-hygiene.story.md"
---

# Matrice runtime moduli GC vs Themes

> **Aggiornamento 2026-07-27**: `laravel/modules_statuses.json` (root) NON è più l'unica SSoT.
> `laravel/config/local/workorder/modules.php` sovrascrive `activators.file.statuses-file` per
> puntare a `base_path('config/local/workorder/modules_statuses.json')`, e
> `Modules\Tenant\Actions\Modules\GetTenantModulesAction` legge quello stesso file (via
> `GetTenantFilePathAction`, path derivato da `APP_URL` → tenant `local/workorder`) per la
> navigazione. Verificare sempre entrambi i file, non solo la root — dettagli completi:
> [Tenant — tenant module status registry](../../Modules/Tenant/docs/tenant-module-status-registry.md),
> [WorkOrder — modules_statuses workorder](../../Modules/WorkOrder/docs/modules-statuses-workorder.md).
>
> SSoT **stato enable** letto da `laravel/modules_statuses.json` (2026-07-24, superato dall'aggiornamento sopra). Themes non cambiano questi flag.
> Religione: [replica-singular-phase.md](../../../docs/gestionale-commesse-comparison/replica-singular-phase.md).

> **Sync script** (dopo aggiunta/rimozione moduli):
> `bash bashscripts/tools/sync-tenant-modules-statuses.sh local/workorder`
>
> Workorder overlay: **41 chiavi** (38 moduli con `module.json` + Blog/Comment senza manifest + `TestModule: false`).
> Rimossi fantasma storici `DbForge`, `FormBuilder`.

| Modulo | `statuses` | Panel tipico | Note docs |
|---|---|---|---|
| Customer | true | `/customer/admin` | OK |
| WorkOrder | true | `/workorder/admin` | Soft PDF/DOCX |
| Intervention | true | `/intervention/admin` | OK |
| Quotation | true | `/quotation/admin` | OK |
| Billing | true | (resources in panel) | Pages XotBase wave |
| Catalog | true | (resources) | OK |
| AiAssistant | true | `/aiassistant/admin` | **non** merge AI; **non** nel Theme |
| Vehicle | true | `/vehicle/admin` | OK |
| Signature | true | `/signature/admin` | OK |
| Inventory | true | (resources) | OK |
| Production | true | (resources) | OK |
| Bom | true | (resources) | OK |
| Fiscal | true | n/a (motore calcolo) | OK Pest |
| Auth | true | overlap User | Replica; **non** merge User |
| HR | true | overlap Employee | Replica; **non** merge Employee |
| Email | true | — | Replica; overlap Notify |
| WhatsApp | true | — | Replica |
| Platform | true | settings/company | Replica |
| Document | **true** | — | **RISCHIO**: model `Media` → tabella `media` (Spatie). Migrate/enable cieco pericoloso |
| Compliance | false | — | Gate PHPStan/enable aperto |
| EnergyBroker | false | — | PHPStan ok; enable solo con ordine |
| PublicProcurement | false | — | Gate aperto |
| TimberBilling | false | — | Gate aperto |

## Themes

| Tema | Ruolo | BL gestionale |
|---|---|---|
| Sixteen | FO attivo | **No** — solo Folio/skin |
| Zero / TwentyOne / … | alternativi | **No** |

Canon hub: [gestionale-docs-index.md](./gestionale-docs-index.md) · Story [2.4](../../../docs/stories/2.4.module-theme-docs-hygiene.story.md).
