<?php

declare(strict_types=1);

namespace Modules\Xot\Actions\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Webmozart\Assert\Assert;

class FilterRelationsAction
{
    /**
<<<<<<< HEAD
     * @param  array<string, mixed>  $relations
=======
<<<<<<< .merge_file_ivfYCW
     * @param  array<string, mixed>  $relations
=======
     * <<<<<<< HEAD.
     *
     * @param array<string, mixed> $relations
     *                                        =======
     *                                        <<<<<<< HEAD
     *                                        =======
     *                                        <<<<<<< HEAD
     *                                        >>>>>>> laraxot/dev
     *                                        <<<<<<< HEAD
     * @param array<string, mixed> $relations
     *                                        =======
     * @param array<string, mixed> $relations
     *
     * >>>>>>> laraxot/dev
     *
     * <<<<<<< HEAD
     * =======
     * =======
     * @param array<string, mixed> $relations
     *                                        >>>>>>> laraxot/dev
     *
     * >>>>>>> laraxot/dev
     *
     * >>>>>>> laraxot/dev
     *
>>>>>>> .merge_file_67SMwf
>>>>>>> 77b1e2748 (first)
     * @return array<string, Relation<Model, Model, mixed>>
     */
    public function execute(Model $_model, array $relations): array
    {
        $filtered = [];

<<<<<<< HEAD
        foreach ($relations as $relation) {
=======
        foreach ($relations as $name => $relation) {
>>>>>>> 77b1e2748 (first)
            Assert::isInstanceOf($relation, Relation::class);
            $related = $relation->getRelated();
            Assert::isInstanceOf($related, Model::class);

            $className = class_basename($related);
            $filtered[$className] = $relation;
        }

        return $filtered;
    }
}
