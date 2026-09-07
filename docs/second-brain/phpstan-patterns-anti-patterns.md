---
title: "PHPStan Patterns 1-5 + Anti-Pattern 1-28 + Regole Laraxot"
description: "Pattern PHPStan ricorrenti, anti-pattern vietati, regole Filament/Laraxot (Schemas split, label vietate, XotBaseResource diretta)"
module: Xot
type: rule
status: active
tags: [phpstan, laraxot, filament, patterns, anti-patterns, quality, php]
created: 2026-09-07
updated: 2026-09-07
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/18"
  - "https://github.com/laraxot/base_workorder_fila5/issues/52"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/25"
related:
  - "./phpstan-zero-2026-09-07.md"
  - "./filament-extension-rules.md"
  - "./module-quality-analysis-summary.md"
---

# PHPStan Patterns + Anti-Pattern + Regole Laraxot

## Pattern risolvibili (Pattern 1-5)

### Pattern 1: `nullCoalesce.offset`
**Errore**: `Offset 1 on array{list<string>, list<string>} on left side of ?? always exists`
**Causa**: `?? []` ridondante dopo `preg_match_all` (output già array definito)
**Fix**: Rimuovere `?? []`, usare ternario esplicito + `@var list<string>` motivato
**File**: `ArtisanAction.php` riga ~139
**Commit**: `20a732df`

### Pattern 2: `view-string` argument.type
**Errore**: `Parameter #1 $view of function view expects view-string|null, string given`
**Causa**: variabile `$view` tipizzata `string` ma `view()` richiede `view-string`
**Fix**: `/** @var view-string $view */` PRIMA di `view($view, ...)` — NO cast, NO suppress
**File**: `ArtisanAction.php` riga ~152

### Pattern 3: `function.alreadyNarrowedType`
**Errore**: `Call to function is_array() with list<string> will always evaluate to true`
**Causa**: dopo type narrowing `list<string>`, `is_array()` è ridondante
**Fix**: rimuovere `is_array()`, usare direttamente `$matches[1] as list<string>`
**File**: `ArtisanAction.php` riga ~140

### Pattern 4: `typed constants PHP 8.3+`
**Errore**: `constantTypeCoverage 49%` (tipo non inferibile su PHP 8.2)
**Causa**: `public const MASK: string = 'value'` richiede PHP 8.3+
**Fix**: NON correggibile su PHP 8.2 senza aggiornamento framework-wide (richiede approvazione esplicita utente)
**Documentato come limite di PHP 8.2, non errore modulo

### Pattern 5: `label/tooltip/placeholder` hardcoded vietati
**Errore**: `->label('...')` inline nel codice Filament
**Causa**: traduzioni gestite da `LangServiceProvider`, non inline
**Fix**: rimuovere tutti i `->label()`, `->tooltip()`, `->placeholder()` con literal-string
**Convenzione chiavi**: `<modulo>.<resource>.<field>.label` / `.tooltip` / `.placeholder`
**Esempio**: `->label(trans('blog::post.title.label'))` → OK; `->label('Title')` → VIETATO

---

## Anti-Pattern vietati (Anti-Pattern 1-28)

### Codice
1. `mixed` senza motivazione → motivare SEMPRE con commento breve (es. `dddx() payload: array<string, mixed>`)
2. `mixed` come divieto assoluto → è l'ultima spiaggia, non vietarlo
3. `mixed` senza provare prima: union type → generics (`@template`) → interface → `mixed`
4. `mixed` senza commento breve → mai lasciare `mixed` senza motivazione
5. `@phpstan-ignore-next-line` orfano → rimuovere se non c'è errore da ignorare
6. Cast `(string)` per zittire PHPStan → fixare la causa, non il sintomo
7. Baseline / suppress / `--level` → mai, `phpstan.neon` è read-only
8. `User`/`Profile` diretti → `UserContract`/`ProfileContract` + `XotData` per classe concreta
9. `GatedXotBaseResource` → VIETATO inventare varianti; usare SEMPRE `XotBaseResource` diretta
10. `extends \Filament\Resources\Resource` diretto → estendere solo `XotBaseResource`
11. Form/Infolist/Table inline nel Resource → SEMPRE classi separate in `Schemas/` + `Tables/`
12. `->label('...')` hardcoded → usare `LangServiceProvider` con chiavi traduzione
13. `RefreshDatabase` nei test → usare `DatabaseTransactions` + `.env.testing` MySQL
14. `pint` su whole repo → usare `pint --dirty --test`
15. Reports in `/tmp` → usare `laravel/build/`
16. Commit senza story BMAD assegnata → sempre prima `bmad create story`
17. Modulo dichiarato finito senza quality gate → PHPStan + Pest + PHPMD + PHPInsights
18. `git push --force` / `--force-with-lease` → vietati
19. `git commit --amend` → vietato; nuovo commit in avanti
20. `declare(strict_types=1)` mancante → SEMPRE al primo statement
21. Lock non controllato prima di modificare → `bashscripts/lock/check.sh`

### Documentazione
22. File `.md` senza frontmatter YAML → richiesto (title, description, module, type, status, tags, created, updated, issues, discussions)
23. File `.md` senza link GitHub issue/discussion → richiesto (≥1 di ciascuno)
24. File `.md` con date nel nome → vietato (es. `sessione-2026-08-24.md`)
25. File `.md` con meno di 5 link relativi → richiesti ≥5 + bidirezionalità
26. Breakage nascosto dopo merge → ri-eseguire quality gate dopo ogni merge

### Test
27. Test che passa senza esercitare il codice corretto → testare il comportamento pubblico, non l'implementazione
28. Coverage in discesa dopo fix → coverage deve SALIRE; registrare valore prima e dopo

---

## Regole Filament / Laraxot (criticali)

### Schemas + Tables split
**OBBLIGATORIO** per ogni Resource:
```
Schemas/<Name>Form.php extends XotBaseResourceForm
Schemas/<Name>Infolist.php extends XotBaseResourceInfolist
Tables/<PluralName>Table.php extends XotBaseResourceTable
```
**PERCHÉ**:
- Filament 4+ ha namespace separati (`Filament\Schemas\Schema`, `Filament\Tables\Table`)
- `XotBaseResource` auto-scopre queste classi via convenzione
- DRY: stessa Form per Create/Edit/View; stessa Table per List
- Type safety: ogni classe base tipizza il proprio dominio

### XotBaseResource diretta
**MAI** inventare varianti come `GatedXotBaseResource`:
- Autorizzazione gestita da **policy/scope** su `XotBaseResource`, non da sottoclasse con nome diverso
- Tutti i Resource estendono `XotBaseResource` direttamente

### Traduzioni via LangServiceProvider
**MAI** `->label('Title')`, `->tooltip('...')`, `->placeholder('...')` con literal-string:
- Traduzioni autoregistrate da `LangServiceProvider`
- Chiavi: `<modulo>.<resource>.<field>.label`
- Eccezione consentita: `->label(trans('...'))` / `->label(__('...'))`

---

## Quality Gate sequenza
```bash
# 1. Lock
bashscripts/lock/check.sh <file>

# 2. Syntax
php -l <file>

# 3. PHPStan (per modulo)
./vendor/bin/phpstan analyse Modules/<Modulo> --no-progress --memory-limit=-1

# 4. Pest (coverage)
./vendor/bin/pest --filter=<Modulo> --coverage

# 5. PHPMD
./tools/phpmd.sh Modules/<Modulo>

# 6. PHPInsights
./tools/phpinsights.sh analyse Modules/<Modulo> --min-quality=80 --min-complexity=80 --min-style=80

# 7. Pint
./vendor/bin/pint --dirty --test

# 8. Git
git add -A && git commit -m "fix(<modulo>): <cosa> [story: <id>]" && git push -u laraxot dev
```

---

## Commit format
```
fix(<modulo>): <descrizione> [story: <id>]
refactor(<modulo>): <descrizione> [story: <id>]
test(<modulo>): <descrizione> [story: <id>]
docs(<modulo>): <descrizione> [story: <id>]
chore(<modulo>): <descrizione> [story: <id>]
```
