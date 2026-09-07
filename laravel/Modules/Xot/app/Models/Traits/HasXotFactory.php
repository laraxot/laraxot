<?php

declare(strict_types=1);

namespace Modules\Xot\Models\Traits;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use Modules\Xot\Actions\Factory\GetFactoryAction;

/**
 * Provides factory support for models using GetFactoryAction.
 *
 * Usage: just use the trait in your model. No type parameters needed.
 *
 * DO NOT DELETE public static function factory() BELOW. It has been removed by
 * mistake and restored 3 times in this repo's history (2026-09-06/07 — see
 * `git log -- app/Models/Traits/HasXotFactory.php`, commits c81bf340 and 85435893).
 * Without it, EVERY model using this trait (i.e. nearly every model in every module,
 * via XotBaseModel) loses its `Model::factory()` static entry point at BOTH the
 * PHPStan level (staticMethod.notFound cascades) AND at PHP runtime
 * (BadMethodCallException on any `SomeModel::factory()->create()` call — this is not
 * just a type-checker annotation, PHP actually resolves this trait method at
 * call time). `newFactory()` alone does nothing: nobody calls it directly.
 * See second-brain memories `project_phpstan_neon_duplicate_includes_pest_bump.md`,
 * `project_larastan_factory_mixed_needs_newfactory_override.md`, and
 * `docs/chat/2026-09-06-hasxotfactory-factory-method-deleted-root-cause.md` for the
 * full incident history before touching this file again.
 *
 * @mixin Model
 */
trait HasXotFactory
{
    /**
     * Create a new factory instance for the model.
     *
     * @return Factory<static>
     */
    protected static function newFactory(): Factory
    {
        /** @var Factory<static> $factory */
        $factory = app(GetFactoryAction::class)->execute(static::class);

        return $factory;
    }

    /**
     * Get a new factory instance for the model.
     *
     * The return type MUST stay the self-referential `Factory<static>` (not a
     * bare `Factory`, not omitted) — PHPStan level max requires fully-specified
     * generics on every declared return type. If you override newFactory()/
     * factory() on a concrete model, annotate ITS return as `Factory<static>`
     * too (not `Factory<ConcreteClass>`) — a concrete generic argument there is
     * NOT considered covariant with this trait's `Factory<static>` and fails
     * PHPStan's return-type check, even though at runtime they're the same type.
     * Confirmed 2026-09-07 across Catalog/Comment/Costing/Customer/Intervention
     * models that had this exact mismatch.
     *
     * @param  int|float|numeric-string|null  $count
     * @param  array<string, mixed>|callable(array<string, mixed>, Model|null): array<string, mixed>|null  $state
     * @return Factory<static>
     */
    public static function factory($count = null, $state = [])
    {
        $factory = static::newFactory() ?? Factory::factoryForModel(static::class);

        if (is_numeric($count)) {
            $factory = $factory->count((int) $count);
        }

        if ($state !== null && $state !== []) {
            $factory = $factory->state($state);
        }

        return $factory;
    }
}
