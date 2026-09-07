---
title: "Platform: Schemas/Tables audit (AuditLogResource, PlatformSettingResource)"
type: bmad-story
module: Platform
status: done
priority: P2
github_issue_url: https://github.com/laraxot/base_workorder_fila5/issues/25
github_discussion_url: https://github.com/laraxot/base_workorder_fila5/discussions/23
---

# BMAD Story — Platform: Schemas/Tables audit

## Understand

Collegamento operativo a [STORY-2.10](https://github.com/laraxot/base_workorder_fila5/issues/25)
per il modulo Platform. Stato locale prima dell'intervento:

- `AuditLogResource`: solo `Tables/AuditLogsTable.php`. `Schemas/` mancante.
- `PlatformSettingResource`: solo `Pages/`. `Schemas/` e `Tables/` mancanti.

Regole canoniche Laraxot:
- `Modules\<M>\Filament\Resources\<R>\Schemas\<Model>Form` estende `XotBaseResourceForm`
- `Modules\<M>\Filament\Resources\<R>\Schemas\<Model>Infolist` estende `XotBaseResourceInfolist`
- `Modules\<M>\Filament\Resources\<R>\Tables\<ModelPlural>Table` estende `XotBaseResourceTable`
- `declare(strict_types=1);` su ogni file
- zero `->label()`/`->tooltip()`/`->placeholder()` hardcoded (LangServiceProvider auto-registra)
- non ridefinire `table()` sulla Resource (fornito da `HasXotTable::table()`)
- non toccare `phpstan.neon`

## Plan

1. Creare `AuditLogResource/Schemas/AuditLogForm.php` (read-only: tutti i campi `disabled`)
2. Creare `AuditLogResource/Schemas/AuditLogInfolist.php` (TextEntry + KeyValueEntry per before/after)
3. Creare `PlatformSettingResource/Schemas/PlatformSettingForm.php` (key TextInput + value_json KeyValue)
4. Creare `PlatformSettingResource/Schemas/PlatformSettingInfolist.php`
5. Creare `PlatformSettingResource/Tables/PlatformSettingsTable.php`
6. Lint `php -l` su tutti i file

## Implement

- `AuditLogResource::getFormSchema()` resta con `return [];` (View-only, ereditato da `XotBaseResource::form()` → `Schemas\AuditLogForm`)
- `AuditLogResource::table()` resta l'ereditato che delega a `getTableClass()` → `Tables\AuditLogsTable` (esistente)
- Aggiunto `PlatformSettingResource\Schemas\PlatformSettingForm` + `PlatformSettingInfolist` + `Tables\PlatformSettingsTable`
- Aggiunto `AuditLogResource\Schemas\AuditLogForm` + `AuditLogInfolist`
- `PlatformSettingResource::toStringValue()` resta come (pubblico cross-class per `PlatformSettingsTable`)

## Verify

- `php -l` su tutti i 5 file nuovi → "No syntax errors detected"
- nessuna modifica a `phpstan.neon`
- nessun `->label()`/`->tooltip()`/`->placeholder()` hardcoded
- nessun override di `table()` sulle Resource

## Document

- File creati (vedi "Implement")
- Backlink: questa story → issue #25, discussion #23

## Status

- Branch: dev
- Module: Platform
- Done: 5/5 file nuovi, lint OK
- Next: orchestratore → PHPStan + PHPMD + PHPInsights + Pest

## Addendum — 2026-09-07, orchestratore PHPStan-zero (wave 3)

Il "Next" sopra non era mai stato eseguito: l'orchestratore PHPStan ha trovato
5 errori reali generati proprio da questa estrazione, ancora non committata:

- `PlatformSettingResource::toStringValue()` (nota "resta pubblico" sopra) era
  in realtà rimasto `private` — bug di trascrizione in questa story, non
  intenzionale.
- `formatValueForDisplay()`/`badgeColorForValue()`/`quickEditFieldFor()`
  (i 3 helper del vecchio `table()`) erano rimasti `private` e MAI richiamati
  dal nuovo `Tables/PlatformSettingsTable.php` → 3x `method.unused`.
- **Bug funzionale più serio, non solo dead code**: `Schemas/PlatformSettingForm.php`
  (punto 3 del Plan, "key TextInput + value_json KeyValue") aveva sostituito il
  form ricco originale (un campo tipizzato Select/Toggle/TextInput per ciascuna
  `platform_settings.key` nota) con un form generico, ma
  `Pages/EditPlatformSetting.php` (non toccato da questa story, già committato)
  continua a leggere/scrivere quei nomi di campo tipizzati in
  `mutateFormDataBeforeFill()`/`mutateFormDataBeforeSave()`. Risultato: salvare
  una qualunque chiave nota tramite la pagina "Avanzato" avrebbe silenziosamente
  sovrascritto il valore con il default hardcoded, invece di persistere
  l'input dell'admin — perdita dati silenziosa, non solo un errore PHPStan.

Fix applicato (root cause, non baseline/ignore):
- Ripristinato l'intero form originale (da `git show 76f2b98:...PlatformSettingResource.php`)
  dentro `Schemas/PlatformSettingForm.php`, canonico (`Section` + chiavi
  associative, come `AuditLogForm.php`).
- Ripristinato il `table()` originale (colonna badge, azione quick-edit
  slide-over, "Avanzato") dentro `Tables/PlatformSettingsTable.php`.
- I 4 helper sulla Resource sono ora `public static` (uso cross-class
  documentato in PHPDoc).

Verifica: PHPStan `Modules/Platform` 0 errori (cache pulita), `php -l` su tutti
i file toccati, PHPMD nessuna nuova violazione, Pest 17 failed/28 passed
invariato (nessun fallimento nei file toccati — dettaglio in
`docs/coverage.md`). Dettagli completi: `docs/coverage.md` sezione "wave 3".