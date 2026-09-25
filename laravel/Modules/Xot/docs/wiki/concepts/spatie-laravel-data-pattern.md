---
title: "Spatie Laravel Data Pattern"
type: concept
tags: [spatie, data, dto, laravel-data]
created: 2026-07-14
updated: 2026-07-14
qmd: Spatie Laravel Data usage pattern for Laraxot modules
issues: []
discussions: []
---

# Spatie Laravel Data Pattern

## Regola

In Laraxot non usiamo DTOs (Data Transfer Objects) ma **Datas** di Spatie Laravel Data.

## Pattern

```php
namespace Modules\ModuleName\Datas;

use Spatie\LaravelData\Data;

class EntityData extends Data
{
    public function __construct(
        public string $name,
        public int $age,
        /** @var array<string, mixed> */
        public array $metadata = []
    ) {}
}
```

## Locazione

- Moduli: `Modules/{Modulo}/app/Datas/`
- Temi: `Themes/{Tema}/app/Datas/`

## Chiamata

```php
use Modules\ModuleName\Datas\EntityData;

$data = EntityData::from([
    'name' => 'John',
    'age' => 30,
    'metadata' => ['key' => 'value']
]);

// o via Action
app(MergeTranslationsAction::class)->execute($files);
```

## Trait → Action

I trait vanno convertiti in Actions con QueueableAction:

```php
// Prima: App\Traits\MergeTranslationsTrait
// Dopo: Modules\Lang\Actions\MergeTranslationsAction
```

## Vietato

- `laravel/app/DTOs/` — cartella rimossa
- `laravel/app/Traits/` — cartella rimossa
- Namespace `App\DTOs` o `App\Traits`

## Canon

- Package: `spatie/laravel-data` v4.23.0
- Docs: https://spatie.be/docs/laravel-data
