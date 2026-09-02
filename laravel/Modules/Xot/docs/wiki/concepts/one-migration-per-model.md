---
title: "Una migrazione per modello"
type: concept
module: Xot
tags: [migrations, xot-base-migration, forward-only, deduplication]
created: 2026-09-01
updated: 2026-09-01
related:
  - ./xotbase-migration-religion.md
  - ./migration-update-timestamps-only.md
  - ../stories/5.50.migration-deduplication-wave1.story.md
  - ../stories/5.51.migration-deduplication-wave2.story.md
---

# Una migrazione per modello

## Regola

**N modelli = N migrazioni `create_*`**: ogni modello Eloquent ha un solo file owner
`{timestamp}_create_{model_plural_snake}_table.php` che estende `XotBaseMigration`.

Evoluzione schema: solo `tableUpdate()` con guardie `hasColumn()`, mai file `add_*_to_*`.

## Duplicati storici (forward-only)

Quando esistono più `create_*` per la stessa tabella:

1. **Owner** = schema più completo (coverage `hasColumn` + size); bump timestamp se
   serve rieseguire `tableUpdate` idempotente.
2. **Merge**: colonne utili dei duplicati → `tableCreate` / `tableUpdate` dell'owner.
3. **Delete** i file loser (`git rm`) — **mai** cartelle `_archive_redundant/`, `_bak/`,
   `_legacy/` né stub no-op multipli. L'archivio è **git** (`git log` / `git show`),
   non una seconda copia sul disco.

Vietato: backup parallelo a git; confonde l'inventario e viola KISS/DRY.
## Perché

| Problema | Effetto | Soluzione |
|----------|---------|-----------|
| Due CREATE sulla stessa tabella | ordine migrate imprevedibile, 1050 duplicate | un owner, delete loser |
| `add_*_to_*` separati | schema frammentato, difficile audit | colonne in `tableUpdate` owner |
| `down()` con drop | perdita dati su rollback accidentale | forward-only, no-op down |
| Cartella `_archive_redundant` | backup ridondante rispetto a git | `git rm` + storia git |

## Audit

```bash
bash bashscripts/tools/migrations/audit-duplicate-create-migrations.sh
```

Doc: [bashscripts/docs/migrations-audit-tools.md](../../../../../bashscripts/docs/migrations-audit-tools.md),
[script-policy-no-module-bin.md](../../../../../bashscripts/docs/script-policy-no-module-bin.md).
Vietato: `laravel/Modules/*/bin/` — tooling solo in `bashscripts/tools/<area>/`.

Exit 0 = nessun duplicato attivo.

## Wave 1 (2026-09-01)

Moduli: Lang, Media, Progressioni, Rating, Xot (cache), IndennitaResponsabilita.
Story: [5.50 migration deduplication wave1](../stories/5.50.migration-deduplication-wave1.story.md).

## Wave 2 (2026-09-01)

Moduli: Activity, Notify, Performance, User (31 tabelle), chiusura IR.
Story: [5.51 migration deduplication wave2](../stories/5.51.migration-deduplication-wave2.story.md).
Epic completo: [01.34 one migration per model all modules](../stories/01.34.one-migration-per-model-all-modules.story.md).

## Collegamenti

- [XotBaseMigration — religione](./xotbase-migration-religion.md)
- [Migration updateTimestamps](./migration-update-timestamps-only.md)
- [Memoria bump timestamp](../../../../../docs/wiki/memories/one-migration-per-model-bump-timestamp.md)
- [Boundary temi](../../../../Themes/One/docs/one-migration-themes-boundary.md)
