---
title: "Convenzione namespace Actions\\Arr"
module: "xot"
type: reference
status: approved
tags: [actions, arr, namespace, laravel-way, deduplication]
created: 2026-08-19
updated: 2026-08-19
audited: 2026-08-19
qmd: "xot actions arr namespace convention laravel illuminate support arr deduplicazione arrays arrayaction fase2 legacy"
related:
  - "./actions-standardization.md"
  - "./actions-pattern.md"
  - "./array/save-json-array-action.md"
  - "../case-sensitivity-rules.md"
  - "../../../../docs/chat/arr-actions-namespace-deduplication.md"
  - "../../../../docs/chat/arr-actions-swarm-handoff.md"
  - "https://github.com/provtv/base_ptv_fila5/issues/235"
---

# Convenzione namespace `Actions\Arr`

## Scopo

Le operazioni su array strutturati nel modulo Xot vivono sotto
`Modules\Xot\Actions\Arr\`, allineate all'helper Laravel
[`Illuminate\Support\Arr`](https://laravel.com/docs/13.x/helpers#arrays-and-objects-method-list).

## Regola

| ✅ Canonico | ❌ Deprecato / da eliminare |
|-------------|----------------------------|
| `app/Actions/Arr/` | `app/Actions/Arrays/` |
| `Modules\Xot\Actions\Arr\*` | `Modules\Xot\Actions\Arrays\*` |
| `tests/Unit/Actions/Arr/` | `tests/Unit/Actions/Arrays/`, `Array/` |

## Perché `Arr` e non `Arrays`

1. **Laravel way**: la facade/helper ufficiale è `Arr`, non `Arrays`
2. **DRY**: un solo namespace evita classi duplicate (stesso body, namespace diverso)
3. **Coerenza interna**: cartelle actions per dominio singolare (`Cast`, `String`, `File`)
4. **Produzione**: Tenant, Lang, Ptv e Xot stesso importano già `Actions\Arr\`

## Actions incluse

- `DiffAssocRecursiveAction`
- `RangeIntersectAction`
- `SaveArrayAction` (facade formato php/json)
- `SaveJsonArrayAction`
- `SavePhpArrayAction`
- `ArrayToRawJsAction`

## Pattern di utilizzo

```php
$result = app(\Modules\Xot\Actions\Arr\SaveArrayAction::class)->execute($data, $path, 'json');
```

Vedi [`actions-pattern.md`](./actions-pattern.md) per il pattern Spatie QueueableAction.

## Stato deduplicazione (2026-08-19)

| Fase | Stato | Note |
|------|-------|------|
| **1 — namespace** | ✅ completata | `app/Actions/Arrays/` eliminato; Sigma migrato; `rg Actions\\Arrays\\` → 0 |
| **2 — legacy root** | ✅ completata | audit 2026-08-19: 0 chiamanti PHP; `@deprecated` su `ArrayAction.php` |
| **3 — docs/regole** | parziale | questo file canonico; restano TRIGGER_MAP e `save-json-array-action.md` |

Esito swarm e quality gates: [`docs/chat/arr-actions-swarm-handoff.md`](../../../../docs/chat/arr-actions-swarm-handoff.md).

## Legacy — `ArrayAction` (root namespace)

| Metodo statico legacy | Sostituto canonico |
|-----------------------|-------------------|
| `ArrayAction::rangeIntersect()` | `app(RangeIntersectAction::class)->execute($a0, $b0, $a1, $b1)` |
| `ArrayAction::diff_assoc_recursive()` | `app(DiffAssocRecursiveAction::class)->execute($arr1, $arr2)` |

**Audit 2026-08-19:** zero chiamanti PHP in `laravel/` (grep su `ArrayAction::`,
import `Actions\ArrayAction`, metodi statici). Consumer già su `Arr/`: Sigma
(`FunctionExtraQuery`, `FunctionExtra`), Ptv (`GetWorkersByYear`).

`app/Actions/ArrayAction.php` marcato `@deprecated`; **non eliminato** — restano
riferimenti in docs/wiki e copia storica `root-uppercase-folders/services/array-service.php`.

## Migrazione consumer

```php
// ❌ prima (eliminato 2026-08-19)
use Modules\Xot\Actions\Arrays\DiffAssocRecursiveAction;

// ✅ dopo
use Modules\Xot\Actions\Arr\DiffAssocRecursiveAction;
```

Tutti i consumer produzione usano `Actions\Arr\` (verificato: 0 match su `Actions\\Arrays\\`).

## Collegamenti

- Coordinamento multi-agente: [`docs/chat/arr-actions-namespace-deduplication.md`](../../../../docs/chat/arr-actions-namespace-deduplication.md)
- Handoff swarm parallelo: [`docs/chat/arr-actions-swarm-handoff.md`](../../../../docs/chat/arr-actions-swarm-handoff.md)
- Precedente dedup Cast/String: [`actions-standardization.md`](./actions-standardization.md)
