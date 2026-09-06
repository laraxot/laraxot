---
id: git-sync-pending-modules
slug: git-sync-pending-modules
scope: [project:base_workorder_fila5]
status: Pending
priority: High
created: 2026-09-06
---

## Problema
Moduli con modifiche locali non ancora pushati.

## Moduli con modifiche pendenti

| Modulo | Status |
|--------|--------|
| Blog | Modifiche img, non committato |
| Blogging | Modifiche img + file condivisi |
| Costing | WorkCenter.php modificato |
| Geo | docs/img modificato |
| Lang | lang + img modificati |
| Media | File phpstan eliminati |
| Notify | Screenshot modificati |
| Timber | 3 Models modificati |
| TimberBilling | File condivisi |
| UI | custom-theme.md modificato |

## Solution
1. git status per ogni modulo
2. git add + commit
3. git fetch laraxot dev
4. git merge laraxot/dev
5. git push

## Acceptance Criteria
- [ ] Tutti i moduli pushati e in sync
