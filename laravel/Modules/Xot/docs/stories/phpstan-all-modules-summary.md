---
id: phpstan-all-modules-summary
slug: phpstan-all-modules-summary
scope:
  - project:base_workorder_fila5
  - modules:All 52 modules
status: In Progress
epic: PHPStan Quality Gates
priority: Critical
created: 2026-09-06
---

# PHPStan Quality Gate - base_workorder_fila5

## Panoramica

| Metrica | Valore |
|---------|--------|
| Totale Moduli | 52 |
| Errori Totali | 1000+ |
| Errori Xot | 215 |
| Errori Intervention | 110+ |
| Errori Timber | 50+ |
| Errori Altri | ~700 |

## Stack Tecnologico

| Pacchetto | Versione |
|-----------|---------|
| phpstan/phpstan | 2.2.13 (latest) |
| larastan/larastan | 3.11.0 (latest) |

## Storie Principali

| ID | Modulo | Status |
|----|--------|--------|
| 1 | `phpstan-xot-module-fix` | In Progress |
| 2 | `phpstan-intervention-module-fix` | In Progress |
| 3 | `phpstan-timber-module-fix` | Pending |
| 4 | `phpstan-other-modules-fix` | Pending |

## Pattern Errori Comuni

### 1. Cast mixed to int
```php
// ❌ Wrong
$id = (int) $data['id'];

// ✅ Fixed
$id = is_numeric($data['id'] ?? null) ? (int) $data['id'] : 0;
```

### 2. Factory method
```php
// ❌ Error
$model = Model::factory()->create();

// ✅ Fixed
php artisan ide-helper:models --write
```

### 3. Pest internal calls
```php
// ❌ Error
expect()->toBe($value);

// ✅ Fixed - use public API
expect($value)->toBe($expected);
```

### 4. Method on mixed
```php
// ❌ Wrong
$query->where(...)->get();

// ✅ Fixed
if ($query instanceof Builder) {
    return $query->get();
}
```

## Git Workflow

```bash
# Per ogni modulo dopo fix:
cd Modules/[ModuleName]
git add -A
git commit -m "fix: phpstan quality gate pass"
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
```

## Continuation Instructions

1. Run `phpstan-xot-module-fix` story
2. Verify with all tools
3. Git sync
4. Continue with next module

## Last Updated

2026-09-06
