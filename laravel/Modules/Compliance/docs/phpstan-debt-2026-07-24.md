---
title: "Debito tecnico PHPStan — Compliance (RISOLTO 2026-07-24)"
type: concept
tags: [compliance, phpstan, debt, technical-debt, resolved]
created: 2026-07-24
updated: 2026-07-24
qmd: "Compliance PHPStan debt json_encode safe cast mixed unused constant resolved"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
related:
  - "../../../../docs/gestionale-commesse-comparison/reshape-completion-report.md"
  - "./README.md"
---

# Debito tecnico PHPStan — Compliance

> **Aggiornamento 2026-07-24 (sessione successiva): tutto il debito descritto in questo file è stato risolto.**
> `phpstan analyse Modules/Compliance --memory-limit=-1` → **0 errori**. Vedi `README.md` per il report completo di chiusura, inclusi i bug funzionali reali trovati (migration con closure `static function` che chiamavano `$this->updateTimestamps()`, fatali a runtime). Il contenuto sottostante è mantenuto come storico del debito originario.

`php vendor/bin/phpstan analyse Modules/Compliance --memory-limit=-1` (livello configurato nel repo) riporta **17 errori reali**, tutti in file **non toccati** dal reshape del 2026-07-23/24 (verificato con `git log -1 -- app/Filament` e `git status`):

| File | Errore |
|---|---|
| `app/Actions/GeneratePosDraftForWorkOrderAction.php` | `json_encode` non-safe (usare `Safe\json_encode`); nullsafe `?->name` inutile prima di `??` |
| `app/Actions/GenerateRceePdfAction.php` | `Pdf::loadView()` si aspetta `view-string\|null`, riceve `string` generico |
| `app/Filament/Pages/HeatingDeadlinesPage.php` | `getNavigationGroup()` dichiarato `?string` ma non ritorna mai `null` |
| `app/Filament/Resources/ComplianceDocumentResource/Pages/{Create,Edit}ComplianceDocument.php` | parametro `$uploadedByUserId` tipizzato `int\|string\|null` invece di `int\|null` |
| `app/Filament/Resources/CustomerSystemResource.php` | 2 costanti `ACCOUNT_FEATURE_ADMIN*` dichiarate e mai usate |
| `app/Filament/Resources/CustomerSystemResource/RelationManagers/SchemasRelationManager.php` | concatenazione stringa con `mixed` |
| `app/Filament/Resources/HeatingSystemResource.php` | 2 costanti `ACCOUNT_FEATURE_*` inutilizzate; cast `mixed` a stringa in interpolazione |
| `app/Filament/Resources/HeatingSystemResource/RelationManagers/BoilersRelationManager.php` | 2× cast `mixed` a stringa in interpolazione; `array` generico passato dove serve `array<string, mixed>` |
| `database/seeders/ComplianceDemoSeeder.php` | 2× parametro tipizzato `string` riceve `mixed` |

**Origine:** porting iniziale da `gestionale_commesse` (prima del reshape struttura), mai passato da un audit PHPStan. Non è una regressione di questa sessione.

**Non risolto qui perché:** fuori scope della story "reshape struttura moduli" (Owned File/Module Scope limitato a model/migration/provider); richiede decisioni di design (es. tipizzare `$uploadedByUserId` correttamente lungo tutta la catena Filament→Action, non solo silenziare l'errore).

**Prossimo passo:** aprire story dedicata `2.2.compliance-phpstan-cleanup` (vedi story BMAD collegata).
