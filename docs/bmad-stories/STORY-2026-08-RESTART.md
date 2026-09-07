---
id: STORY-2026-08-RESTART-FULL-PHPSTAN-ZERO
title: RESTART — Da zero: PHPStan 0 errori su tutto Modules
status: restart
branch: dev
remote: laraxot/module_xot_fila5
---

# Come ri-avviare (se dimentichi tutto)

## 1. Leggi queste storie in ordine
1. `STORY-2026-08-COMPLETED.md` — cosa è stato fatto
2. `STORY-2026-08-UNFINISHED.md` — cosa manca
3. `STORY-2026-08-DISCOVERED.md` — architettura scoperta
4. `STORY-2026-08-SUGGESTED.md` — miglioramenti
5. `STORY-2026-08-STRUCTURAL-XOT-FORM-INFO.md` — form/infolist
6. `STORY-2026-08-HASXOTFORM-INSTANCE.md` — instance alignment

## 2. Controlla stato repo
```bash
cd laravel/Modules/Xot && git remote -v  # laraxot laraxot/module_xot_fila5
cd laravel && ./vendor/bin/phpstan analyse Modules/ --no-progress --memory-limit=-1 2>&1 | grep -E 'Found [0-9]+ error'
# Expected: ~165 (o meno se parte dei bootstrap è stato fissato)
```

## 3. Fix bootstrap (prima di tutto)
- `BaseProfileResource.php`: `#[\Override]` duplicato
- `BaseUserResource.php`: `#[\Override]` su `getFormSchema()` (se non esiste parent)
- `OauthPersonalAccessClientResource.php`: `#[\Override]` su `getTableColumns()`
- `AbsenceRequestResource.php`: `#[\Override]` `getHeaderWidgets()`
- `MediaTable.php`: `use MediaResource` duplicato
- `User/Filament/Resources/*/Tables/*Table.php`: `public static function table()` → `public function table()` se non è statico nel parent
- `Signage/Filament/Resources/*/Tables/*Table.php`: `protected getTableRecordTitleAttribute()` → `public`
- `XotBaseResourceTable.php`: assicurarsi `table()` sia istanza (non statico se trait richiede istanza)

## 4. Dopo bootstrap, fix errori per modulo
```bash
for mod in User Signage Catalog PublicProcurement Geo EnergyBroker HR Rating Activity Media Blog Bom; do
    echo "=== $mod ==="
    ./vendor/bin/phpstan analyse Modules/$mod --no-progress 2>&1 | tail -3
done
```

## 5. Coordinamento con altri agenti
- Prima di toccare `Modules/User`: `git fetch laraxot dev`; `git log --oneline -5 laraxot/dev`
- Se c'è commit in arrivo: `git merge laraxot/dev --allow-unrelated-histories -s resolve`
- Dopo merge: riesegui quality gate (pint + phpstan + pest)
- Commit: `fix(<modulo>): ... [story: RESTART-FULL-PHPSTAN-ZERO]`
- Push: `git push -u laraxot dev` (mai `--force`)

## 6. Non dimenticare
- `phpstan.neon` è SOLO LETTURA — non modificarlo, non usare `-c`
- `mixed` senza commento = errore aperto
- `declare(strict_types=1);` su ogni file
- `docs/` del modulo aggiornato
- BMAD entry creata
- Second brain aggiornato
- `pint --dirty --test` prima del commit
- `pest --filter=<modulo> --coverage` dopo il fix (coverage salire)

# Configurazione locale (se serve ripristinare)
```bash
export ANTHROPIC_BASE_URL=http://127.0.0.1:3456
export ANTHROPIC_AUTH_TOKEN=ccr-local
ccr status
```
