# BMAD Story — Fix PHPStan Comment module: remove redundant HasXotFactory + Model extends

## Understand

**Problema**: Modelli in `Modules/Comment/app/Models/` estendono `Model` direttamente (tramite `HasXotFactory` ridondante) invece di `BaseModel`.

**Root cause**: `XotBaseModel` (genitore di `BaseModel`) già ha `use HasXotFactory;`. Aggiungerlo nei sottomodelli è ridondante e causa errore PHPStan `method.notFound` / `return.type` su `HasXotFactory.php (in context of class ...)`.

**Models analizzati**:
- `CommentNotificationSubscription.php`: estende `BaseModel` ✅, MA ha `use HasXotFactory` ridondante e `use Illuminate\Database\Eloquent\Model` inutilizzato. **NON è pivot** (struttura: morphs + type + timestamps + relazioni MorphTo proprie).
- `CommentNotificationOptOut.php`: estende `BaseModel` ✅, MA ha `use HasXotFactory` ridondante. `Model::class` usato per `BelongsTo` fallback.
- `Comment.php`: estende `BaseModel` ✅, `HasXotFactory` rimosso da altro agente. `use Illuminate\Database\Eloquent\Model` inutilizzato.

**Regole applicate**: §9 (mixed), §10 (User → UserContract), non modificare phpstan.neon, no suppress.

## Plan

1. **Fase 0 - Ricognizione**: verificare stato git, claim coordinamento
2. **Fase 1 - BMAD story**: questa story
3. **Fase 2 - Implementazione**:
   - `CommentNotificationSubscription.php`: rimuovere `use HasXotFactory` (ridondante, ereditato da XotBaseModel), rimuovere `use Illuminate\Database\Eloquent\Model` (inutilizzato)
   - `CommentNotificationOptOut.php`: rimuovere `use HasXotFactory` (ridondante), mantenere `use Illuminate\Database\Eloquent\Model` (necessario per `Model::class` su riga 38)
   - `Comment.php`: rimuovere `use Illuminate\Database\Eloquent\Model` (inutilizzato)
4. **Fase 3 - Quality gate**: phpstan + phpmd + phpinsights + pest
5. **Fase 4 - Git sync**: add + commit + fetch + merge + push
6. **Fase 5 - Documentazione**: aggiornare docs/ del modulo e second brain

## Implement

### File 1: CommentNotificationSubscription.php
```diff
- use Illuminate\Database\Eloquent\Model;
- use Modules\Xot\Models\Traits\HasXotFactory;
+ use Modules\Comment\Models\BaseModel;
- class CommentNotificationSubscription extends Model
+ class CommentNotificationSubscription extends BaseModel
-     use HasXotFactory;
```

### File 2: CommentNotificationOptOut.php
```diff
- use Modules\Xot\Models\Traits\HasXotFactory;
+ // keep use Illuminate\Database\Eloquent\Model; (needed for Model::class fallback)
-     use HasXotFactory;
```

### File 3: Comment.php
```diff
- use Illuminate\Database\Eloquent\Model;
  // keep rest, already fixed by other agent
```

## Verify

- `phpstan analyse Modules/Comment --no-progress` → 0 errori (escludendo paramTypeCoverage)
- `phpmd Modules/Comment text phpmd.xml` → 0 violazioni
- `phpinsights analyse Modules/Comment --no-interaction` → soglie rispettate
- `pest --filter=Comment --coverage` → tutti test verdi, coverage ≥ partenza

## Document

- `Modules/Comment/docs/wiki/` → aggiornare note su architettura modelli (BaseModel vs BasePivot)
- `docs/wiki/bmad-method.md` → aggiornare pattern ricorrente: `HasXotFactory` già in `XotBaseModel`, non ridichiarare nei sottomodelli
- Second brain → regola generalizzabile

## Status
- Branch: dev
- Module: Comment
- Story ID: CMT-002
- Commit: `7ffcbf2 fix(comment): remove redundant HasXotfactory from models extends BaseModel [story: CMT-002]`
- Pushed: ✅

## Final Report

| Campo | Valore |
|---|---|
| Modulo | Comment |
| Commit | 7ffcbf2 |
| Files toccati | 4 (3 models + 1 story) |
| Errori PHPStan iniziali | 3 (method.notFound/return.type su HasXotFactory context) |
| Errori PHPStan finali | 0 (HasXotFactory) / 1 residuo (method.deprecated ListCommentsTest, strutturale) |
| mixed residui | 0 |
| Violazioni PHPMD | 0 (solo packages/spatie/ esterno) |

## Cosa fatto
- `CommentNotificationSubscription.php`: rimosso `use HasXotFactory` + `use Illuminate\Database\Eloquent\Model` inutilizzato
- `CommentNotificationOptOut.php`: rimosso `use HasXotFactory` ridondante (Model::class mantenuto per fallback)
- `Comment.php`: rimosso `use Illuminate\Database\Eloquent\Model` inutilizzato

## Residuo noto
`method.deprecated` in `tests/Feature/Filament/Pages/ListCommentsTest.php` line 22: chiama `getTableActions()` deprecato su `CommentsTable`. **Root cause**: trait condiviso `HasXotTable.php` (Modules/Xot/) segna il metodo come deprecato. Altro agente sta già lavorando su Platform/HasXotTable migration (file: docs/chat/2026-09-07-phpstan-blog-bom-production-comment-done.md).

## Pattern ricorrente generalizzato
`use HasXotFactory` NON va aggiunto nei modelli che estendono `BaseModel` (→ XotBaseModel) — è già nel parent chain. Documentato in: docs/wiki/conventions/hasxotfactory-reductant-pattern.md
