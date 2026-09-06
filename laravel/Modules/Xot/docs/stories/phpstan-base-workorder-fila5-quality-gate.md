---
id: phpstan-quality-gate-001
slug: phpstan-base-workorder-fila5
scope:
  - project:base_workorder_fila5
  - modules:All (52 modules)
status: In Progress
epic: Quality Gates
priority: Critical
related:
  - /var/www/_bases/base_workorder_fila5/laravel/phpstan.neon
  - /var/www/_bases/base_workorder_fila5/laravel/composer.json
created: 2026-09-06
---

## Problema

PHPStan analysis su base_workorder_fila5 restituisce **1000+ errori** a level max.

## Stack Tecnologico

| Pacchetto | Versione |
|-----------|---------|
| phpstan/phpstan | 2.2.13 (latest) |
| larastan/larastan | 3.11.0 (latest) |

## Errori Principali Identificati

1. **Cast errors**: `Cannot cast mixed to int` - 110+ occorrenze
2. **Factory errors**: `staticMethod.notFound` - missing factory() methods
3. **Method errors**: `method.nonObject` - calling methods on mixed
4. **Larastan bug**: `isForwardedQueryBuilderMethod` - già risolto con @phpstan-ignore

## Moduli con Errori

| Modulo | Errori Stimati | Status |
|--------|----------------|--------|
| Xot | Core, da verificare | In progress |
| User | Da verificare | Pending |
| Activity | Da verificare | Pending |
| AI | Da verificare | Pending |
| Intervention | 110+ | Partially fixed |
| Timber | 50+ | Pending |
| Altri 48 moduli | Da verificare | Pending |

## Solution Overview

1. **BMAD stories** per ogni modulo con errori
2. **Fix pattern-by-pattern**:
   - `mixed` → tipi concreti
   - `factory()` → genera con php artisan
   - `@phpstan-ignore` per bug Larastan interni
3. **Git sync** dopo ogni modulo
4. **Verification**: phpstan + phpmd + phpinsights + pest

## Acceptance Criteria

- [ ] PHPStan level max passa senza errori
- [ ] PHPMD passa senza violazioni critical
- [ ] PHPInsights > 90% quality score
- [ ] Pest coverage incrementato per ogni modulo fixato

## Stories Figlie

- [ ] `phpstan-xot-001` - Xot module fix
- [ ] `phpstan-intervention-002` - Intervention module fix
- [ ] `phpstan-timber-003` - Timber module fix
- [ ] `phpstan-others-004` - Remaining modules fix

## Blocker

- Nessuno blocker critico
- Dipendenza: utente per modifiche phpstan.neon se necessario

## Dev Notes

### Pattern comuni da fixare

1. **Cast mixed to int**:
```php
// ❌ Wrong
$id = (int) $data['id'];

// ✅ Fixed
$id = is_numeric($data['id'] ?? null) ? (int) $data['id'] : 0;
```

2. **Factory method**:
```php
// ❌ Error: factory() not found
$model = ModelName::factory()->create();

// ✅ Fixed: run artisan first
php artisan ide-helper:models --write
```

3. **Method on mixed**:
```php
// ❌ Wrong
$query->where(...)->get();

// ✅ Fixed: type assertion
if ($query instanceof Builder) {
    return $query->get();
}
```
