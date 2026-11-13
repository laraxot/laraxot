<?php

declare(strict_types=1);

namespace Modules\Xot\Actions\Model;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Schema\AbstractSchemaManager;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use Spatie\QueueableAction\QueueableAction;
use Webmozart\Assert\Assert;

class GetSchemaManagerByModelClassAction
{
    use QueueableAction;

    /**
     * Ottiene lo schema manager Doctrine per una classe di modello Eloquent.
     *
<<<<<<< HEAD
     * @param  string  $modelClass  La classe del modello
=======
<<<<<<< .merge_file_vcYjzo
     * @param  string  $modelClass  La classe del modello
=======
     * <<<<<<< HEAD
     *
     * @param string $modelClass La classe del modello
     *                           =======
     *                           <<<<<<< HEAD
     *                           <<<<<<< HEAD
     *                           <<<<<<< .merge_file_xyZp2T
     * @param string $modelClass La classe del modello
     *                           =======
     *                           <<<<<<< .merge_file_7Y7dge
     * @param string $modelClass La classe del modello
     *                           =======
     *                           <<<<<<< HEAD
     * @param string $modelClass La classe del modello
     *                           =======
     * @param string $modelClass La classe del modello
     *
     * >>>>>>> laraxot/dev
     *
     * >>>>>>> .merge_file_BepykK
     *
     * >>>>>>> .merge_file_k0zmqY
     *
     * =======
     * <<<<<<< HEAD
     * @param string $modelClass La classe del modello
     *                           =======
     *                           <<<<<<< .merge_file_meUCp6
     *                           =======
     *                           <<<<<<< HEAD
     *                           <<<<<<< .merge_file_xyZp2T
     *                           >>>>>>> .merge_file_rWeDnJ
     * @param string $modelClass La classe del modello
     *
     * <<<<<<< .merge_file_meUCp6
     * =======
     * >>>>>>> laraxot/dev
     * =======
     * @param string $modelClass La classe del modello
     *                           >>>>>>> 9e11d472 (Fix merge conflicts in PHPDoc comments across multiple action classes and contracts, ensuring consistent parameter annotations and removing redundant lines.)
     *
     * <<<<<<< HEAD
     * =======
     * >>>>>>> .merge_file_rWeDnJ
     *
     * >>>>>>> laraxot/dev
     *
     * =======
     * @param string $modelClass La classe del modello
     *                           >>>>>>> laraxot/dev
     *
     * >>>>>>> laraxot/dev
     *
     * >>>>>>> laraxot/dev
     *
>>>>>>> .merge_file_mN9rCp
>>>>>>> 77b1e2748 (first)
     * @return AbstractSchemaManager<AbstractPlatform> Lo schema manager di Doctrine
     */
    public function execute(string $modelClass): AbstractSchemaManager
    {
        Assert::isInstanceOf($model = app($modelClass), EloquentModel::class);
        $connection = $model->getConnection();

        // In Laravel 9+ il metodo getDoctrineSchemaManager è stato deprecato
        // ma getDoctrineConnection() non esiste, dobbiamo usare getDoctrineSchemaManager direttamente
        if (method_exists($connection, 'getDoctrineSchemaManager')) {
            $schemaManager = $connection->getDoctrineSchemaManager();

            Assert::isInstanceOf($schemaManager, AbstractSchemaManager::class);

            return $schemaManager;
        }

        // Se in futuro il metodo getDoctrineConnection diventa disponibile, possiamo usare questo
        throw new \RuntimeException('Non è possibile ottenere lo schema manager Doctrine per questo modello.');
    }
}
