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
     * @param  int|float|numeric-string|null  $count
     * @param  array<string, mixed>|callable(array<string, mixed>, Model|null): array<string, mixed>|null  $state
     * @return Factory<static>
     */
    public static function factory($count = null, $state = []): Factory
    {
        $factory = static::newFactory();

        if (is_numeric($count)) {
            $factory = $factory->count((int) $count);
        }

        if ($state !== null && $state !== []) {
            /** @var array<string, mixed>|callable(array<string, mixed>, Model|null): array<string, mixed> $stateArg */
            $stateArg = $state;
            $factory = $factory->state($stateArg);
        }

        return $factory;
    }
}
