---
id: base-workorder-fila5-master-story
slug: base-workorder-fila5-master
scope: [project:base_workorder_fila5]
status: In Progress
priority: Critical
created: 2026-09-06
---

# Master Story - base_workorder_fila5 Quality Gates

## Panoramica

| Quality Gate | Status | Stories |
|--------------|--------|---------|
| PHPStan | 1000+ errors | 52 module stories |
| PHPMD | Not run | `phpmd-quality-gate` |
| PHPInsights | Not run | `phpinsights-quality-gate` |
| Pest Coverage | Not run | `pest-coverage-increase` |
| Factory/Seeder | 8+ errors | `factory-seeder-plan` |
| Migration | Not run | `migration-audit` |
| Routes | Not run | `route-list-validation` |
| Dependencies | Not run | `dependency-audit` |
| Filament | Partial | `filament-resources-audit` |
| Git Sync | 10 pending | `git-sync-pending-modules` |

## Git Workflow per Moduli Indipendenti

```bash
cd Modules/[Module]
git add -A
git commit -m "fix: [description]"
git fetch laraxot dev
git merge laraxot/dev --allow-unrelated-histories -s resolve
git push -u
```

## Verification Commands

```bash
# PHPStan
./vendor/bin/phpstan analyse Modules/[Module] --memory-limit=2G

# PHPMD
php tools/phpmd.phar Modules/[Module] text codesize,controversial,design,naming,unusedcode

# PHPInsights
php tools/phpinsights.sh Modules/[Module]

# Pest
vendor/bin/pest Modules/[Module]/tests --coverage

# Migrations
php artisan migrate:status --path=Modules/[Module]

# Routes
php artisan route:list --path=[module]
```

## Stories Figlie

| ID | Story | Status |
|----|-------|--------|
| 1 | phpstan-xot-module-fix | In Progress |
| 2 | phpstan-intervention-module-fix | In Progress |
| 3 | phpstan-timber-module-fix | Pending |
| 4 | phpstan-other-modules-fix | Pending |
| 5 | phpmd-quality-gate | Pending |
| 6 | phpinsights-quality-gate | Pending |
| 7 | pest-coverage-increase | Pending |
| 8 | factory-seeder-plan | Pending |
| 9 | migration-audit | Pending |
| 10 | filament-resources-audit | Pending |
| 11 | route-list-validation | Pending |
| 12 | dependency-audit | Pending |
| 13 | git-sync-pending-modules | Pending |

## Last Updated

2026-09-06
