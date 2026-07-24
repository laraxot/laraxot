---
title: "Debito D6 — copertura Pest field-service"
type: how-to
tags: [technical-debt, pest, testing, workorder, intervention]
created: 2026-07-24
updated: 2026-07-24
qmd: "debt pest field service coverage intervention actions policy"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/14"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/15"
related:
  - "./gestionale-technical-debt.md"
  - "./pest_testing_setup.md"
  - "./development/tdd-laravel-pest-guide.md"
---

# Debito D6 — Pest field-service (come chiudere il buco)

## Perché

Panel e schema wave sono up; la regressione di **business** non è protetta:

| Modulo | Stato test (baseline docs) |
|---|---|
| Customer / WorkOrder | Smoke classi (esistenza Model/SP) — poco dominio |
| Intervention | Directory test popolata da SRC; Platform-dependent → skip | [tests-migration.md](../../Modules/Intervention/docs/tests-migration.md) |
| Customer | `CustomerActionsTest` consolidato green | [tests-migration.md](../../Modules/Customer/docs/tests-migration.md) |
| WorkOrder | Feature migrati; Create green post schema sqlite | [tests-migration.md](../../Modules/WorkOrder/docs/tests-migration.md) |
| Catalog | **11 passed** (CRUD + Metel) — TestCase fixcity + `mergeConfigFrom(metel)` | [tests-migration.md](../../Modules/Catalog/docs/tests-migration.md) |
| HR | **3 passed / 5 skipped** (D1 media, D6 roles) — schema HR + stub users | [tests-migration.md](../../Modules/HR/docs/tests-migration.md) |

Playbook completo: [migrate-gestionale-tests.md](./migrate-gestionale-tests.md).

> **Nota D17:** `phpunit.xml` sqlite `:memory:` scavalca `.env.testing` — vedi [debt-phpunit-env-precedence.md](./debt-phpunit-env-precedence.md). Mitigazione wave: `prepareSharedFixcitySqliteForTesting()` + migrate path sul file fixture.

## Religione test

- **No** `RefreshDatabase` (dati sacri / `.env.testing` SQLite dedicato).
- Allineare env: `bashscripts/tools/sync-env-testing.sh` (wiki env-testing-parity).
- Filename test **PascalCase**.
- Module `TestCase` estende `XotBaseTestCase`.

## Come risolvere — piano a strati

### Strato 1 — Smoke (già parziale)

Per ogni modulo GC enabled:

- Model class exists + `$table` coerente
- ServiceProvider boots
- (Opzionale) `AdminPanelProvider` registrato → assert route name `filament.{alias}::admin.home`

### Strato 2 — Dominio minimo (priorità)

| Modulo | Casi minimi |
|---|---|
| WorkOrder | `ChangeWorkOrderStatusAction` happy path + status invalido |
| WorkOrder | `AssignUserToWorkOrderAction` |
| Intervention | create Intervention legato a WorkOrder; TimePunch start/stop |
| Quotation | create Quotation + item senza product_id |
| Signature | morph signable su Intervention (se API stabile) |
| AiAssistant | create thread con `created_by_user_id` string UUID |
| Customer | Customer + Site + Contact |

### Strato 3 — Filament HTTP (dopo Strato 2)

- `get` lista resource autenticato (actingAs User factory)
- Skip browser E2E finché non serve; Pest feature HTTP basta

### Strato 4 — Policy

- User senza permission → deny
- User con permission → allow

## Ordine moduli

1. Intervention (oggi zero test — massimo rischio operator)
2. WorkOrder Actions
3. Quotation
4. AiAssistant UUID user
5. Signature / Vehicle (dopo D1/D2)

## Comandi

```bash
cd laravel
vendor/bin/pest Modules/Intervention/tests
vendor/bin/pest Modules/WorkOrder/tests
# non usare RefreshDatabase nei nuovi file
```

## DoD

- [ ] Intervention: ≥1 Feature o Unit che tocchi persistenza reale su testing DB
- [ ] WorkOrder: ≥1 test Action
- [ ] CI/local green su path moduli
- [ ] Story BMAD collegata a #14

## Non fare

- Solo test “class_exists” e dichiarare copertura completa.
- `RefreshDatabase` su MySQL condiviso.
- Test che richiedono Document Media prima di D1.
