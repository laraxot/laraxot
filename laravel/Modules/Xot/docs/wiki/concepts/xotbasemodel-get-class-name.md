---
title: "XotBaseModel::getClassName — sibling nel namespace leaf"
type: concept
module: Xot
tags: [xotbasemodel, getclassname, phpstan, dry, leaf, models]
created: 2026-07-27
updated: 2026-07-27
qmd: "XotBaseModel getClassName static sibling Models namespace CriteriOption BaseScheda"
related:
  - ./basemodel-connection-religion.md
  - ../../../Ptv/docs/wiki/concepts/criteri-model-class-resolution.md
  - ../../../Ptv/docs/dynamic-class-resolution-pattern.md
---

# XotBaseModel::getClassName

## Perché

I modelli base in moduli piattaforma (es. `Ptv\BaseScheda`) devono risolvere il **concreto del leaf** nello stesso namespace `Models\` (`Progressioni\Models\CriteriOption`), non restare sul prototype Ptv.

## API

```php
/** @param class-string<\Illuminate\Database\Eloquent\Model> $fallback */
public static function getClassName(string $fallback): string
```

1. `basename($fallback)` → es. `CriteriOption`
2. Candidato = `namespace(static::class) + \CriteriOption`
3. Se `class_exists` → candidato; altrimenti `$fallback`

## Chiamata corretta

```php
// dentro BaseScheda / leaf Scheda
static::getClassName(CriteriOption::class);
```

## Anti-pattern

```php
CriteriOption::getClassName();     // LSB sbagliato
CriteriOption::getClassName(...);  // stesso: static = CriteriOption, non Scheda
```

## Gate

Introdotti per azzerare PHPStan L10 su `Modules` (2026-07-27): 30 errori in `Ptv\BaseScheda` per metodo inesistente.

## Vedi

- [criteri-model-class-resolution](../../../Ptv/docs/wiki/concepts/criteri-model-class-resolution.md)
