---
title: "Debito tecnico gestionale — inventario e ordine di risoluzione"
type: concept
tags: [technical-debt, gestionale-commesse, playbook, bmad, laravel-docs]
created: 2026-07-24
updated: 2026-07-24
qmd: "gestionale technical debt inventory priority document media migrate pest login billing"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
  - "https://github.com/laraxot/base_workorder_fila5/issues/14"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
  - "https://github.com/laraxot/base_workorder_fila5/discussions/15"
related:
  - "./README.md"
  - "./debt-document-media-conflict.md"
  - "./debt-vehicle-document-media.md"
  - "./debt-legacy-migrations-triage.md"
  - "./debt-users-uuid-foreign-keys.md"
  - "./debt-replica-auth-hr-enable.md"
  - "./debt-login-route-collision.md"
  - "./debt-pest-field-service-coverage.md"
  - "./debt-themes-docs-uppercase.md"
  - "./debt-soft-deps-export.md"
  - "./debt-fo-operator-folio.md"
  - "./debt-billing-pages-xotbase.md"
  - "./debt-auth-users-migration-duplicate.md"
  - "./debt-phpunit-modules-registration.md"
  - "./debt-wave3-event-providers.md"
  - "./debt-phpunit-env-precedence.md"
---

# Debito tecnico gestionale — inventario e ordine di risoluzione

> **SSoT in `laravel/docs`:** qui si documenta **come** chiudere il debito (playbook operativi).  
> **Non** implementare leggendo questo file: ogni item richiede story BMAD + conferma utente dove tocca dati/enable/login.  
> Religioni: dati sacri (niente `migrate --force` / `RefreshDatabase`), forward-only git, replica singolare (niente delete Auth/AiAssistant/HR «perché esiste User»).

## Perché esiste questo debito

Il porting `gestionale_commesse` → Laraxot ha portato:

1. **Schema e ORM** Document su nome tabella `media` già usata da Spatie.
2. **Migrazioni** legacy Pending pericolose se lanciate in blocco.
3. **Coesistenza replica ↔ piattaforma** (Auth/User, HR/Employee, AiAssistant/AI) con moduli **enabled**.
4. **Collisione route** login e ownership `users`.
5. **Qualità** (Pest, PHPStan Wave3, Pages Billing non-XotBase, soft-deps export).
6. **Igiene docs temi** (UPPERCASE).

## Inventario (priorità)

| ID | Debito | Severità | Blocca? | Playbook |
|---|---|---|---|---|
| D1 | Document `Media` ↔ Spatie `media` | **P0** | Sì se Document/VehicleDocument | [debt-document-media-conflict.md](./debt-document-media-conflict.md) |
| D2 | VehicleDocument → Media dominio | **P0** | Documenti mezzo | [debt-vehicle-document-media.md](./debt-vehicle-document-media.md) |
| D3 | ~95 migrazioni Pending legacy | **P1** | Se `migrate` globale | [debt-legacy-migrations-triage.md](./debt-legacy-migrations-triage.md) |
| D4 | FK `users` UUID vs `foreignId` | **P1** | Nuove migrazioni | [debt-users-uuid-foreign-keys.md](./debt-users-uuid-foreign-keys.md) |
| D5 | Auth/HR enabled vs User/Employee | **P1** | Policy/UI doppie | [debt-replica-auth-hr-enable.md](./debt-replica-auth-hr-enable.md) |
| D10 | Collisione `GET /login` | **P0/P1** | Login FO | [debt-login-route-collision.md](./debt-login-route-collision.md) |
| D12 | Auth `create_users` vs User | **P1** | Fresh migrate | [debt-auth-users-migration-duplicate.md](./debt-auth-users-migration-duplicate.md) |
| D14 | Wave3 Event SP + PHPStan post-enable | **P1** | Runtime Email/WA/Platform | [debt-wave3-event-providers.md](./debt-wave3-event-providers.md) |
| D6 | Pest field-service | **P2** | Regressioni silenziose | [debt-pest-field-service-coverage.md](./debt-pest-field-service-coverage.md) |
| D13 | phpunit/CI non esegue moduli | **P2** | CI cieca | [debt-phpunit-modules-registration.md](./debt-phpunit-modules-registration.md) |
| D11 | Billing Pages Filament diretto | **P2** | UX/coerenza | [debt-billing-pages-xotbase.md](./debt-billing-pages-xotbase.md) |
| D7 | Soft-deps DomPDF / PhpWord | **P2** | Solo export | [debt-soft-deps-export.md](./debt-soft-deps-export.md) |
| D8 | Operator FO Folio | **P3** | UX | [debt-fo-operator-folio.md](./debt-fo-operator-folio.md) |
| D9 | Themes docs UPPERCASE | **P3** | Second brain | [debt-themes-docs-uppercase.md](./debt-themes-docs-uppercase.md) |
| D15 | Auth: `App\Models\User` invece di `Modules\User\Models\User` — bypass login approvazione + 2FA email mai eseguiti | **P0 (fixato 2026-07-24)** | Sicurezza login | Vedi `Modules/Auth/docs/import-status.md`; manca test di regressione (D6) |
| D16 | `app/Timber` + `app/Filament/Timber` SRC (66+file, mai copiati in nessuna wave) referenziati a vuoto da `TimberBilling` (`App\Timber\Models\TimberDocument` ecc.) | **P1 (migrazione in corso 2026-07-24)** | PHPStan TimberBilling (29 errori), test Timber (32 file) | Migrazione in corso verso `Modules/TimberBilling/app/`; vedi `laravel/docs/gestionale-commesse-technical-debt.md` |
| D17 | `phpunit.xml` sqlite `:memory:` scavalca `.env.testing` (mysql) per connessione `xot` — **blocca D13/D6**, verificato portando 2 test `Vehicle` | **P1** | Ogni Feature test su modello `xot` | [debt-phpunit-env-precedence.md](./debt-phpunit-env-precedence.md) |

## Evidenza runtime (aggiornare qui)

| Check | Esito osservato (2026-07-24) |
|---|---|
| Tabella `media` | Schema **Spatie** |
| Tabella `document_media` | **Assente** |
| Migrazione Document `create_media` | Spesso **Pending** (landmine) |
| `Document` / `Auth` / `HR` statuses | **true** |
| Auth `0001_01_01_000000_create_users_table.php` | **Presente** → D12 |
| Billing Pages `extends ListRecords|…` | ~16 file → D11 |
| Migrazioni Pending | ~**95** → D3 |

Matrice enable moduli: `Themes/docs/gc-modules-runtime-matrix.md`.

## Ordine di attacco consigliato

```text
1.  D10 Login route collision          ← sicurezza/UX immediata (spesso prima di tutto)
2.  D1  Document media rename/bridge   ← sblocca D2
3.  D2  VehicleDocument
4.  D12 Auth create_users ownership
5.  D3  Triage migrate legacy
6.  D4  Audit FK users residui
7.  D5  Policy Auth/HR (decisione prodotto)  ↔ alimentata da D10/D12
8.  D14 Wave3 Email/WhatsApp/Platform
9.  D6  Pest dominio + D13 CI registration
10. D11 Billing Pages XotBase
11. D7  Soft-deps export (quando serve PDF)
12. D8  FO Folio operator
13. D9  Rename docs temi
```

**Non invertire D1 e D2.**  
**D10 può essere parallelo a D1** (lock file diversi).

## Religioni operative

1. Dati sacri — mai `migrate --force`, mai wipe prod.
2. Path migrate — `--path=Modules/{X}/database/migrations`.
3. Forward-only — niente `git restore`.
4. Replica — Auth/HR/AiAssistant/Document non si cancellano per twin.
5. BMAD — Issue + Discussion + story prima di rename tabella / cambio login.
6. Lock multi-agente su migrazioni/model contesi.

## Cosa NON è debito (non “risolvere” cancellando)

| Situazione | Azione |
|---|---|
| AiAssistant coesiste con `AI` | Tenere; merge solo con mandato |
| Auth coesiste con User | D5 + D10 + D12, non `rm -rf` |
| Panel per-modulo `*/admin` | Design Laraxot |

## File ridondanti in questa cartella (alias)

| File | Ruolo |
|---|---|
| **Questo file** | Inventario + ordine |
| `debt-*.md` | Playbook **come risolvere** |
| [gestionale-commesse-technical-debt-resolution-guide.md](./gestionale-commesse-technical-debt-resolution-guide.md) | Approfondimento narrativo (login/media/migrate) — **complementare**, non secondo inventario |
| [gestionale-commesse-debito-tecnico.md](./gestionale-commesse-debito-tecnico.md) | Alias → punta qui |
| [gestionale-commesse-technical-debt.md](./gestionale-commesse-technical-debt.md) | Alias → inventario + PHPStan index |

## Tracciamento BMAD

| Artefatto | URL / path |
|---|---|
| Epic | https://github.com/laraxot/base_workorder_fila5/issues/7 |
| Deep quality / docs | https://github.com/laraxot/base_workorder_fila5/issues/14 |
| Discussion | https://github.com/laraxot/base_workorder_fila5/discussions/15 |
| Sprint | `docs/sprint-status.yaml` (root) |
| Errori agente | `docs/gestionale-commesse-comparison/agent-errors-lessons.md` |

## Definition of Done per chiudere un item D*

1. Decisioni nel playbook chiuse o `deciso:` su Issue.
2. Story BMAD `review`/`done` con link.
3. Evidenza comando nel commento GitHub.
4. Aggiornare **questa** tabella inventario.
5. Aggiornare `Modules/{X}/docs/import-status.md` **dopo** il fix (il fix non si fa da questo file).

## Anti-pattern

- `Schema::drop('media')` per Document.
- `migrate` globale “per finire le Pending”.
- Merge Auth→User senza gap + OK utente.
- Dichiarare Pest “ok” se CI non esegue i moduli (D13).
