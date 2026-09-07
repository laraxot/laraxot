---
id: STORY-2026-08-UNFINISHED-BOOTSTRAP-FATALS
slug: unfinished-bootstrap-fatals-user-signage-catalog-publicprocurement
title: UNFINISHED — Bootstrap fatals in User / Signage / Catalog / PublicProcurement / Media
status: open
module: User, Signage, Catalog, PublicProcurement, Media, WorkOrder
branch: dev
---

# Unfinished — 23 bootstrap errors → 165 PHPStan errors blocked

## Fatals identificati
1. `Modules/User/app/Filament/Resources/BaseUserResource.php:58` — `#[\Override]` duplicato / ripetuto (RIMOSSO — ma può ricomparire)
2. `Modules/User/app/Filament/Resources/OauthPersonalAccessClientResource.php:48` — `#[\Override]` senza parent (RIMOSSO — verificare)
3. `Modules/User/app/Filament/Resources/BaseProfileResource.php:22` — `#[\Override]` duplicato (RIMOSSO — ma potrebbe esserci ancora)
4. `Modules/Media/app/Filament/Resources/MediaResource/Tables/MediaTable.php` — `use` duplicato `MediaResource` (FISSATO)
5. `Modules/Employee/app/Filament/Resources/AbsenceRequestResource.php` — `#[\Override]` `getHeaderWidgets()` senza parent (FISSATO approssimativo — verificare)
6. `Modules/Signage/app/Filament/Resources/SignCatalogItemResource/Tables/SignCatalogItemsTable.php:117` — `protected function getTableRecordTitleAttribute()` deve essere `public` (dal log iniziale)
7. `Modules/PublicProcurement/app/Filament/Resources/Tables/PublicProcurementContractsTable.php:17` — `static function table()` vs non-static in parent

## Pattern ricorrente
- Tutti i `Table` subclass con `#[\Override]` su metodi che non esistono nel parent `XotBaseResourceTable`
- Tutti i `Table` con `public static function table()` che creano conflitto con `HasXotTable::table()` (istanza)
- Tutti i `getTableRecordTitleAttribute()` dichiarati `protected` invece di `public`

## Azioni per ri-avviare
```bash
# 1. Verifica stato corrente
cd laravel/Modules/User && git status; git fetch laraxot dev; git merge laraxot/dev --allow-unrelated-histories -s resolve
# 2. Correggi i remaining Table subclass
bash -c 'for f in $(find Modules/Signage Modules/PublicProcurement Modules/Catalog -name "*Table.php"); do echo "$f"; grep -n "protected.*getTableRecordTitleAttribute\|public static function table" "$f"; done'
# 3. Rimuovi tutti gli #[\Override] sbagliati (già fatto globalmente — ma verificare se ne sono rimasti)
grep -rn '#\[\\Override\]' Modules/Signage/ Modules/PublicProcurement/ Modules/Catalog/ 2>/dev/null | head -20
# 4. Esegui PHPStan
cd laravel && ./vendor/bin/phpstan analyse Modules/ --no-progress --memory-limit=-1
```

# Coordinamento
- Non lavorare da solo: creare BMAD story per ogni modulo (Signage, Catalog, PublicProcurement)
- Aggiornare `docs/bmad-stories/` con risultati intermedi
- Se un altro agente sta lavorando su `Modules/User`, verificare con `git log --oneline -5 laraxot/dev`
- Dopo ogni fix: `git add -A; git commit -m "fix(<modulo>): correct table/static/override [story: UNFINISHED-BOOTSTRAP-FATALS]"; git push -u laraxot dev`
