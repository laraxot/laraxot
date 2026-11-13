<?php

declare(strict_types=1);

namespace Modules\Xot\Actions\ModelClass;

use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\Eloquent\Model;
use Spatie\QueueableAction\QueueableAction;

class SelectAction
{
    use QueueableAction;

    /**
     * Execute a select query.
     *
<<<<<<< HEAD
     * @param  class-string<Model>  $modelClass
=======
<<<<<<< .merge_file_L49JqJ
     * @param  class-string<Model>  $modelClass
=======
     * <<<<<<< HEAD
     *
     * @param class-string<Model> $modelClass
     *                                        =======
     *                                        <<<<<<< HEAD
     *                                        <<<<<<< HEAD
     *                                        <<<<<<< .merge_file_3M00Bo
     * @param class-string<Model> $modelClass
     *                                        =======
     *                                        <<<<<<< .merge_file_NGxl9P
     * @param class-string<Model> $modelClass
     *                                        =======
     *                                        <<<<<<< HEAD
     * @param class-string<Model> $modelClass
     *                                        =======
     * @param class-string<Model> $modelClass
     *
     * >>>>>>> laraxot/dev
     *
     * >>>>>>> .merge_file_5oNhQo
     *
     * >>>>>>> .merge_file_vbvucT
     *
     * =======
     * <<<<<<< HEAD
     * @param class-string<Model> $modelClass
     *                                        =======
     *                                        <<<<<<< .merge_file_4PMnzD
     *                                        =======
     *                                        <<<<<<< HEAD
     *                                        <<<<<<< .merge_file_3M00Bo
     *                                        >>>>>>> .merge_file_4eMRLe
     * @param class-string<Model> $modelClass
     *
     * <<<<<<< .merge_file_4PMnzD
     * =======
     * >>>>>>> laraxot/dev
     * =======
     * @param class-string<Model> $modelClass
     *                                        >>>>>>> 9e11d472 (Fix merge conflicts in PHPDoc comments across multiple action classes and contracts, ensuring consistent parameter annotations and removing redundant lines.)
     *
     * <<<<<<< HEAD
     * =======
     * >>>>>>> .merge_file_4eMRLe
     *
     * >>>>>>> laraxot/dev
     *
     * =======
     * @param class-string<Model> $modelClass
     *                                        >>>>>>> laraxot/dev
     *
     * >>>>>>> laraxot/dev
     *
     * >>>>>>> laraxot/dev
     *
>>>>>>> .merge_file_MIViQo
>>>>>>> 77b1e2748 (first)
     * @return array<mixed>
     */
    public function execute(string $modelClass, string $sql): array
    {
        /** @var Model $model */
        $model = app($modelClass);

        /** @var ConnectionInterface $connection */
        $connection = $model->getConnection();

        return $connection->select($sql);
    }
}
