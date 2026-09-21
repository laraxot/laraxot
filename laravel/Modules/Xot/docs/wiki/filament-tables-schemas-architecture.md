# Filament Tables & Schemas — Obbligo Architetturale

## Perché esistono `/Tables` e `/Schemas`

Filament v3+ supporta due pattern di organizzazione per resource:

### Pattern Laravel normal (`app/Filament/Resources/<Resource>/`)

Tutto inline: `form()`, `table()`, `infolist()` come metodi della resource class.

### Pattern Laraxot (`app/Filament/Resources/<Resource>/Tables/` + `/Schemas/`)

Ogni resource ha classi dedicate:
- `Tables/<Resource>Table.php` — colonne, azioni, bulk actions
- `Schemas/<Resource>Form.php` — schema del form
- `Schemas/<Resource>Infolist.php` — schema readonly

## Perché è obbligatorio in questo progetto

`XotBaseResourceTable::configure()` viene chiamato da Filament tramite `discoverResources()`. La discovery cerca classi con nome `*Table` nella directory `Tables/`. Se manca la classe corrispondente, o se la classe estende `XotBaseResourceTable` con firma errata, il bootstrap fallisce per TUTTI i moduli — non solo quello mancante.

Pattern: `ResourceNameTable extends XotBaseResourceTable` → `public static function table(Table $table): Table`.

Errori comuni:
- `protected function getTableRecordTitleAttribute()` invece di `public`
- `public function table()` invece di `public static function table()`
- `#[\Override]` senza metodo genitore corrispondente

## Causa del bootstrap fail PHPStan

PHPStan durante `analyse` fa boot dell'applicazione Laravel. Il boot chiama `Filament::discoverResources()`, che istanzia ogni Table. Se anche UNA sola è malformata, tutto il bootstrap si interrompe con fatal.

File critici:
- `Modules/Xot/app/Filament/Resources/Tables/XotBaseResourceTable.php` — classe base
- `Modules/User/app/Filament/Resources/BaseProfileResource.php` — `#[Override]` duplicato
- `Modules/User/app/Filament/Resources/OauthPersonalAccessClientResource.php` — `#[Override]` senza parent

## Come verificare

```bash
# Verifica che ogni resource abbia la Table
find Modules/*/app/Filament/Resources -name '*Table.php' | wc -l
find Modules/*/app/Filament/Resources -maxdepth 2 -name '*Resource.php' | wc -l

# Verifica che table() sia static
grep -rn 'public static function table' Modules/*/app/Filament/Resources/Tables/
grep -rn 'public function table' Modules/*/app/Filament/Resources/Tables/  # BAD
```

## Riferimenti
- `Modules/Xot/app/Filament/Traits/HasXotTable.php` — consumo Tables
- `Modules/Xot/app/Providers/Filament/XotBasePanelProvider.php:100` — discoverResources()

## Schema contract and source audit (2026-09-10)

`XotBaseResourceTable::getTableColumns()` returns `array<string, Column>`. String keys preserve identity for translation/overrides. Cache and CacheLock migrations provide key/expiration (plus owner for locks), not id/timestamps. Session provides user_id/ip_address/user_agent/last_activity (Unix seconds). Extra provides model_type/model_id. Log Sushi rows provide name/size; Module Sushi rows provide name/description/status/priority/path. Do not infer fields from generic scaffolds or model annotations when getRows/migrations contradict them.

Sources: `app/Models/{Cache,CacheLock,Session,Extra,Log,Module}.php`, `database/migrations/*_{cache,cache_locks,sessions,extra}_table.php`. Related: [[filament-v5-hybrid-pattern]], [[filament-tables-schemas-architecture]], [[index]].
