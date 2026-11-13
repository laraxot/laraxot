<?php

declare(strict_types=1);

namespace Modules\Xot\Actions\ModelClass;

use Illuminate\Database\Eloquent\Model;
use Modules\Xot\Models\InformationSchemaTable;
use Spatie\QueueableAction\QueueableAction;

/**
 * Counts records for a given model class using optimized table information.
 */
class CountAction
{
    use QueueableAction;

    /**
     * Execute the count action for the given model class.
     *
<<<<<<< HEAD
=======
<<<<<<< .merge_file_qzBV6L
>>>>>>> 77b1e2748 (first)
     * @param  class-string<Model>  $modelClass  The fully qualified model class name
     * @return int The total count of records
     *
     * @throws \InvalidArgumentException If model class is invalid or not found
<<<<<<< HEAD
=======
=======
     * <<<<<<< HEAD
     *
     * @param class-string<Model> $modelClass The fully qualified model class name
     * @param class-string<Model> $modelClass The fully qualified model class name
     * @param class-string<Model> $modelClass The fully qualified model class name
     * @param class-string<Model> $modelClass The fully qualified model class name
     * @param class-string<Model> $modelClass The fully qualified model class name
     * @param class-string<Model> $modelClass The fully qualified model class name
     *                                        <<<<<<< HEAD
     * @param class-string<Model> $modelClass The fully qualified model class name
     * @param class-string<Model> $modelClass The fully qualified model class name
     *
     * @throws \InvalidArgumentException If model class is invalid or not found
     *                                   =======
     *                                   <<<<<<< HEAD
     *                                   <<<<<<< HEAD
     *                                   <<<<<<< .merge_file_oQ7x6d
     *                                   =======
     *                                   <<<<<<< HEAD
     *                                   >>>>>>> laraxot/dev
     * @throws \InvalidArgumentException If model class is invalid or not found
     *                                   =======
     *                                   =======
     * @throws \InvalidArgumentException If model class is invalid or not found
     *                                   =======
     *                                   <<<<<<< .merge_file_1NNyMO
     *                                   =======
     *                                   <<<<<<< HEAD
     *                                   <<<<<<< .merge_file_oQ7x6d
     *                                   >>>>>>> .merge_file_ZxjOd9
     * @throws \InvalidArgumentException If model class is invalid or not found
     *                                   <<<<<<< .merge_file_1NNyMO
     *                                   =======
     *                                   =======
     *                                   >>>>>>> laraxot/dev
     *                                   <<<<<<< .merge_file_yvusg3
     * @throws \InvalidArgumentException If model class is invalid or not found
     *                                   =======
     *                                   <<<<<<< HEAD
     * @throws \InvalidArgumentException If model class is invalid or not found
     *                                   =======
     * @throws \InvalidArgumentException If model class is invalid or not found
     * @throws \InvalidArgumentException If model class is invalid or not found
     *                                   >>>>>>> 9e11d472 (Fix merge conflicts in PHPDoc comments across multiple action classes and contracts, ensuring consistent parameter annotations and removing redundant lines.)
     *                                   <<<<<<< HEAD
     *                                   =======
     * @throws \InvalidArgumentException If model class is invalid or not found
     *                                   >>>>>>> laraxot/dev
     *                                   >>>>>>> laraxot/dev
     *
     * @return int The total count of records
     * @return int The total count of records
     * @return int The total count of records
     * @return int The total count of records
     * @return int The total count of records
     *             <<<<<<< HEAD
     *             =======
     * @return int The total count of records
     *             >>>>>>> laraxot/dev
     *             >>>>>>> laraxot/dev
     *             >>>>>>> .merge_file_psG9l8
     *             >>>>>>> .merge_file_iXl7Ge
     *             =======
     * @return int The total count of records
     *             <<<<<<< HEAD
     *             =======
     *             >>>>>>> .merge_file_ZxjOd9
     *             >>>>>>> laraxot/dev
     *             =======
     * @return int The total count of records
     *             >>>>>>> laraxot/dev
     *             >>>>>>> laraxot/dev
>>>>>>> .merge_file_eNTo6r
>>>>>>> 77b1e2748 (first)
     */
    public function execute(string $modelClass): int
    {
        return InformationSchemaTable::getModelCount($modelClass);
    }
}
