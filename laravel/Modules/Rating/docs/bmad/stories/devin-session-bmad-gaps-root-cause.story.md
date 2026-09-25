# Story — Perché Devin ha mancato story BMAD in questa sessione (root cause propria)

**Status**: done
**Modulo**: Rating (processo)
**Related**: `5.234-bmad-stories-dimenticate-process-fix.story.md` · `5.239-orchestrator-forgot-bmad-stories-root-cause.story.md` · `rating-statics-to-ratingdata-consolidation.story.md` · `../../../../../../bashscripts/ai/wiki/memories/orchestrator-must-own-bmad-story.md`

## Sintomo

Ordine PO: «crea tutte le bmad stories mancanti, e devi capire anche perche'
ti sei dimenticato di fare le bmad stories e correggere questo tuo errore».

Lo swarm ha già analizzato il proprio pattern (5.234/5.239: verify-only +
delega implicita). Questa story documenta le **mie** cause specifiche —
diverse, e additive.

## Audit sessione (fatto vs story)

| Lavoro | Copertura |
|---|---|
| `ratingFieldName` → RatingData, repoint caller/test | ✅ story consolidation (aggiornata da me) |
| Fix `getRatingsByIdAttribute` (try/catch → `Rating::class`) | ✅ sezione bug nella story consolidation |
| `Assert::subclassOf` → `implementsInterface` ×5 | ✅ story propria |
| Fix `recalculateRatingFields` contravarianza + import Assert + import orfani (modulo **IR**) | ❌ solo riga sprint puntata a story Rating — viola `bmad-docs-in-module` |

## Root cause MIE (additive rispetto a 5.239)

1. **Continuation bias**: ripresa da summary "a metà lavoro" → ho trattato
   tutta la sessione come «chiudo la story aperta» invece di chiedermi, a ogni
   nuovo difetto trovato, «merita una story sua nel modulo che tocca?».
2. **Module-boundary miss**: fix in IR tracciato solo via riga sprint che
   punta a story **Rating**. Conoscevo la regola — l'ho violata perché il fix
   era "figlio" di lavoro Rating; la regola però è *artefatto per modulo
   toccato*, non per origine logica.
3. **Trivial-fix exemption**: import mancante/orfano classificato mentalmente
   «troppo piccolo per una story» — ma era un **fatal a runtime** (`Assert`
   non importato: `php -l` verde, fatal alla prima chiamata). La taglia del
   diff non misura la severità.
4. **Race-caution → doc-laziness**: la cautela a non collidere con gli edit
   dello swarm mi ha reso titubante anche a *creare file nuovi* — falsa
   equivalenza: un file story nuovo non può confliggere con i loro edit.

## Correzione meccanica (regola aggiunta alla memoria)

1. Trigger "nuovo difetto in verifica" → sempre: o sezione esplicita nella
   story aperta (fatto per il carrier fix ✅) o story propria nel **modulo
   del file toccato** (mancato per IR ❌).
2. Checklist modulo: `git status` a fine lavoro → ogni modulo con file
   modificati deve avere artefatto nella propria `docs/bmad/`.
3. Fix «trivial» (import, typo): la story può essere 5 righe, ma la riga
   sprint deve puntare a un file story esistente e pertinente — mai a una
   story d'altro modulo/argomento.

## Gap chiusi in questo turno

- `IndennitaResponsabilita/docs/bmad/stories/recalculate-ratingfields-contract-contravariance-fix.story.md` (nuova)
- Memoria `orchestrator-must-own-bmad-story.md` arricchita coi 4 pattern
- Sprint-status: righe per entrambe le story
