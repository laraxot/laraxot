# BMAD — 272 PHPStan errori: piano di risoluzione

**Repo coordinatore:** `git@github.com:laraxot/module_xot_fila5.git`
**Stato:** totale `phpstan analyse Modules` = 272 errori
**Data:** 2026-09-07
**Vincolo:** PHPStan livello 6 (da `phpstan.neon`, immutabile)

## Distribuzione per modulo (top 20)

| # | Modulo | Errori | Note |
|---|---|---|---|
| 1 | Billing | 28 | Resource + Schemas |
| 2 | Intervention | 21 | Resource + Schemas |
| 3 | Inventory | 20 | Resource + Schemas |
| 4 | Compliance | 18 | Resource + Schemas |
| 5 | Notify (test) | 14 | test files |
| 6 | Quotation | 13 | Resource + Schemas |
| 7 | User (test) | 12 | test files |
| 8 | Catalog | 12 | Resource + Schemas |
| 9 | Activity (test) | 11 | test files |
| 10 | Media (test) | 10 | test files |
| 11 | AiAssistant | 10 | Resource |
| 12 | Customer | 8 | Resource |
| 13 | Bom | 7 | Resource |
| 14 | Cms (test) | 7 | test files |
| 15 | WorkOrder | 6 | Resource |
| 16 | UI (test) | 6 | test files |
| 17 | HR | 6 | Resource |
| 18 | Xot (test+app) | 10 | post-refactor HasXotForm/Infolist |
| 19 | Signage | 5 | Resource |
| 20 | Geo | ~5 | Resource |
|  | altri 10+ moduli | ~75 | Resource minore |
| | **TOTALE** | **272** | |

## Tipologie ricorrenti (da sample)

| Errore | Identificatore | Azione tipica |
|---|---|---|
| `Call to static method ... with array will always evaluate to true` | `staticMethod.alreadyNarrowedType` | Rimuovere `assertIsArray` su valori già tipizzati |
| `Static call to instance method` | `method.staticCall` | Convertire chiamata statica in `app(Class::class)->method()` |
| `Parameter #1 expects string, mixed given` | `argument.type` | Tipizzare esplicitamente o cast mirato |
| `Method X should return Y but returns mixed` | `return.type` | Aggiungere tipo di ritorno esplicito |
| `Class X contains 1 abstract method` | `method.abstract` | Implementare metodo astratto dimenticato |
| `Cannot call method form() on mixed` | `method.nonObject` | Garantire istanza (`app(static::class)`) |
| `RecursiveDirectoryIterator expects string, mixed given` | `argument.type` | Tipizzare variabile prima della chiamata |

## Strategia (regola BMAD: una story per modulo)

1. **Ordine di priorità**: prima i moduli dipendenza (Xot, User, Geo, UI, Notify), poi i dipendenti.
2. **Story BMAD per modulo**: una story per gruppo omogeneo di errori (<=10 file).
3. **Coordinamento**: `docs/chat/INDEX.md` per sincronizzare con altri agenti AI.
4. **Definition of Done**: 0 errori su file sessione, Pest passa, `php -l` verde, no baseline/ignore.
5. **Constraint check**: `phpstan.neon` read-only, no nuovi pacchetti, no cast forzati, no `mixed` per pigrizia.

## Stories create (finora)

- `stories/01-refactor-table-trans.md` — ✅ DONE
- `stories/02-refactor-hasxotform.md` — ✅ DONE
- `stories/03-refactor-resource-form.md` — ✅ DONE
- `stories/04-refactor-infolist.md` — ✅ DONE
- `stories/05-viewrecord-infolist-override.md` — ✅ DONE
- `stories/06-phpstan-272-reduction.md` — TODO (questo file)
- (in arrivo) per ogni modulo > 5 errori

## Regola architetturale (second brain)

Per ogni risorsa Filament devono esistere:
- `Modules/*/app/Filament/Resources/*/Tables/*.php`
- `Modules/*/app/Filament/Resources/*/Schemas/*.php` (Form + Infolist)
- `Pages/*` solo `XotBase*` (no override di `getInfolistSchema`)

## Esecuzione

```bash
cd /var/www/_bases/base_workorder_fila5/laravel
./vendor/bin/phpstan analyse Modules --no-progress --memory-limit=-1 > build/phpstan-272-baseline.txt
```

Poi per ogni modulo:

```bash
cd /var/www/_bases/base_workorder_fila5/laravel/Modules/<Modulo>
git status
git fetch laraxot dev
./vendor/bin/phpstan analyse . --no-progress --memory-limit=-1 > ../../../build/phpstan-<modulo>.txt
# Correggi, poi Quality Gate 03, poi commit
```
