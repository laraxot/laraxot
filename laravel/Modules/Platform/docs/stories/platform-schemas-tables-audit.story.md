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