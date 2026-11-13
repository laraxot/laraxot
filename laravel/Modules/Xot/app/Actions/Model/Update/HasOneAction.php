<?php

declare(strict_types=1);

namespace Modules\Xot\Actions\Model\Update;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Modules\Xot\Datas\RelationData as RelationDTO;
use Spatie\QueueableAction\QueueableAction;
use Webmozart\Assert\Assert;

/**
 * Class HasOneAction.
 *
 * Handles the update operation for HasOne relationships in Eloquent models.
 *
 * @template TModel of Model
 */
class HasOneAction
{
    use QueueableAction;

    /**
     * Execute the update operation for a HasOne relationship.
     *
<<<<<<< HEAD
=======
<<<<<<< .merge_file_YEzYiV
>>>>>>> 77b1e2748 (first)
     * @param  Model  $model  The parent model instance
     * @param  RelationDTO  $relationDTO  Data transfer object containing relationship information
     *
     * @throws \InvalidArgumentException When relationship type is invalid
     * @throws \RuntimeException When relationship data is invalid
<<<<<<< HEAD
=======
=======
     * <<<<<<< HEAD
     *
     * @param Model       $model       The parent model instance
     * @param RelationDTO $relationDTO Data transfer object containing relationship information
     * @param Model       $model       The parent model instance
     * @param RelationDTO $relationDTO Data transfer object containing relationship information
     * @param Model       $model       The parent model instance
     * @param RelationDTO $relationDTO Data transfer object containing relationship information
     * @param Model       $model       The parent model instance
     * @param RelationDTO $relationDTO Data transfer object containing relationship information
     * @param Model       $model       The parent model instance
     * @param RelationDTO $relationDTO Data transfer object containing relationship information
     * @param Model       $model       The parent model instance
     * @param RelationDTO $relationDTO Data transfer object containing relationship information
     *                                 <<<<<<< HEAD
     *                                 =======
     * @param Model       $model       The parent model instance
     * @param RelationDTO $relationDTO Data transfer object containing relationship information
     * @param Model       $model       The parent model instance
     * @param RelationDTO $relationDTO Data transfer object containing relationship information
     *                                 >>>>>>> laraxot/dev
     *
     * @throws \InvalidArgumentException When relationship type is invalid
     * @throws \RuntimeException         When relationship data is invalid
     *                                   =======
     *                                   <<<<<<< HEAD
     *                                   <<<<<<< HEAD
     *                                   <<<<<<< .merge_file_T31w2M
     *                                   =======
     *                                   <<<<<<< HEAD
     *                                   >>>>>>> laraxot/dev
     * @throws \InvalidArgumentException When relationship type is invalid
     * @throws \RuntimeException         When relationship data is invalid
     *                                   =======
     *                                   <<<<<<< HEAD
     *                                   =======
     *                                   <<<<<<< .merge_file_nJ1IDE
     *                                   =======
     *                                   <<<<<<< HEAD
     *                                   <<<<<<< .merge_file_T31w2M
     *                                   >>>>>>> .merge_file_rWwxNa
     * @throws \InvalidArgumentException When relationship type is invalid
     * @throws \RuntimeException         When relationship data is invalid
     *                                   <<<<<<< .merge_file_nJ1IDE
     *                                   =======
     *                                   =======
     *                                   >>>>>>> laraxot/dev
     *                                   <<<<<<< .merge_file_3gk7Nx
     * @throws \InvalidArgumentException When relationship type is invalid
     * @throws \RuntimeException         When relationship data is invalid
     *                                   =======
     *                                   <<<<<<< HEAD
     * @throws \InvalidArgumentException When relationship type is invalid
     * @throws \RuntimeException         When relationship data is invalid
     *                                   =======
     * @throws \InvalidArgumentException When relationship type is invalid
     * @throws \RuntimeException         When relationship data is invalid
     *                                   >>>>>>> laraxot/dev
     *                                   >>>>>>> .merge_file_SqQcWu
     *                                   >>>>>>> .merge_file_tFlRWf
     *                                   =======
     * @throws \InvalidArgumentException When relationship type is invalid
     * @throws \RuntimeException         When relationship data is invalid
     *                                   >>>>>>> 9e11d472 (Fix merge conflicts in PHPDoc comments across multiple action classes and contracts, ensuring consistent parameter annotations and removing redundant lines.)
     *                                   <<<<<<< HEAD
     *                                   =======
     *                                   >>>>>>> .merge_file_rWwxNa
     *                                   >>>>>>> laraxot/dev
     *                                   =======
     * @throws \InvalidArgumentException When relationship type is invalid
     * @throws \RuntimeException         When relationship data is invalid
     *                                   >>>>>>> laraxot/dev
     *                                   >>>>>>> laraxot/dev
     *                                   >>>>>>> laraxot/dev
>>>>>>> .merge_file_v4OMat
>>>>>>> 77b1e2748 (first)
     */
    public function execute(Model $model, RelationDTO $relationDTO): void
    {
        // Validate that the relationship is of type HasOne
        Assert::isInstanceOf(
            $relationDTO->rows,
            HasOne::class,
            sprintf('Expected HasOne relationship, got %s', get_debug_type($relationDTO->rows)),
        );

        /** @var HasOne<Model, Model> $relation */
        $relation = $relationDTO->rows;

        // Validate that the relationship data is not empty
        if (empty($relationDTO->data)) {
            throw new \RuntimeException('Relationship data cannot be empty');
        }

        // Check if the related model exists
        if ($relation->exists()) {
            $related = $model->{$relationDTO->name};
            if ($related instanceof Model) {
                $related->update($relationDTO->data);

                return;
            }
        }

        // If the related model does not exist, create a new one
        $relation->create($relationDTO->data);
    }
}
