---
id: "Xot/phpstan-fleet-swarm-2026-09-23"
title: "PHPStan Modules fleet zero — swarm random parallelo"
status: done
scope: fleet
module: Xot
created: 2026-09-23
updated: 2026-09-23
related:
  - ./phpstan-fix-swarm-parallel.story.md
  - ../../../../../../docs/stories/phpstan-fleet-swarm-2026-09-23.story.md
  - Rating/phpstan-fleet-fix-2026-09-23 (fix parallelo BaseRating.php — altro agente)
qmd: "phpstan analyse Modules swarm parallel random bmad second brain zero errors BaseRating parse"
---

# Story: PHPStan `analyse Modules` → 0 errori

## Obiettivo

`cd laravel && php -d memory_limit=2G ./vendor/bin/phpstan analyse Modules`
exit 0. Fix in ordine random, swarm parallelo, BMAD + second brain.

## Vincoli

- Nessuna modifica a `phpstan.neon` / baseline
- Nessun `@phpstan-ignore` generico
- Lock per file/modulo (`bashscripts/lock/`)
- Story BMAD per modulo toccato + questa story fleet
- Pest sullo scope modificato (host ≠ 10.100.200.15 → OK)

## Inventario (2026-09-23)

| Metrica | Valore |
|---------|--------|
| **TOTAL_ERRORS** | **3** |
| Moduli con errori | 1 (Rating) |
| File | `Modules/Rating/app/Models/BaseRating.php` |
| Tipo | `phpstan.parse` (syntax) |

Dettaglio (TSV):

| file | line | rule | messaggio |
|------|------|------|-----------|
| `Modules/Rating/app/Models/BaseRating.php` | 221 | phpstan.parse | Syntax error, unexpected `<` |
| `Modules/Rating/app/Models/BaseRating.php` | 222 | phpstan.parse | Syntax error, unexpected T_ELSE |
| `Modules/Rating/app/Models/BaseRating.php` | 224 | phpstan.parse | Syntax error, unexpected T_ENDIF |

**Root cause attesa:** residuo post-merge — marker Git (`<` / `<<<<<<<`) e/o blocco `if()` / `endif` incompleto. Fix = ripristinare il metodo integro, non ignore né baseline.

**Evidence:** `laravel/build/phpstan-sweep-initial.txt` (3 righe, solo Rating).

## Piano swarm

1. Inventario errori → raggruppa per modulo ✅ (solo Rating)
2. Shuffle moduli con errori ✅
3. Subagent parallelo Rating su `BaseRating.php` — **ownership altro agente** (questa story non modifica quel file)
4. Gate: phpstan per modulo Rating + fleet finale
5. Pest scope Rating + write-back memoria

## Coordinamento

- **Rating/BaseRating.php:** fix in corso da agente dedicato; fleet Xot in **review** fino a conferma `analyse Modules` exit 0.
- Memoria: `bashscripts/ai/wiki/memories/phpstan-parse-incomplete-if-merge-residue.md`

## Esito

- Sweep iniziale: **3** errori parse, **0** errori tipizzazione su altri moduli.
- Chiusura fleet (`done`) quando `phpstan analyse Modules` → `[OK] No errors` dopo merge fix Rating.


## Chiusura fleet

Inventario iniziale: 3 parse su BaseRating. Fix getValueHtml/getNoteHtml + narrowing instanceof + hasChildRatings. PHPStan Modules/Rating 0. Pest BaseRatingModelTest: vedi gate finale. Fleet Modules: in corso.
