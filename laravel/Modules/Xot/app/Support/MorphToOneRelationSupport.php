<?php

declare(strict_types=1);

namespace Modules\Xot\Support;

use Illuminate\Database\Eloquent\Model;
use Modules\Xot\Contracts\MorphToOneRelationContract;
use Webmozart\Assert\Assert;

/**
 * Duck-typing helper for MorphToOne relations (Fidum package when installed).
 */
final class MorphToOneRelationSupport
{
    /**
<<<<<<< .merge_file_ILFu1w
     * @param  array<string, mixed>  $attributes
=======
     * <<<<<<< HEAD.
     *
     * @param array<string, mixed> $attributes
     *                                         =======
     *                                         <<<<<<< HEAD
     * @param array<string, mixed> $attributes
     *                                         =======
     *                                         <<<<<<< HEAD
     * @param array<string, mixed> $attributes
     *                                         =======
     * @param array<string, mixed> $attributes
     *                                         >>>>>>> laraxot/dev
     *                                         >>>>>>> laraxot/dev
     *                                         >>>>>>> laraxot/dev
>>>>>>> .merge_file_4OEKsu
     */
    public static function create(object $relation, array $attributes): Model
    {
        self::assertHasCreate($relation);

        $created = $relation->create($attributes);
        Assert::isInstanceOf($created, Model::class);

        return $created;
    }

    /**
     * @phpstan-assert MorphToOneRelationContract $relation
     */
    private static function assertHasCreate(object $relation): void
    {
        Assert::methodExists($relation, 'create');
    }
}
