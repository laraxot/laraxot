<?php

declare(strict_types=1);

namespace Modules\Xot\Actions\Model;

use Illuminate\Database\Eloquent\Model as EloquentModel;
<<<<<<< HEAD
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
=======
>>>>>>> 77b1e2748 (first)
use Spatie\QueueableAction\QueueableAction;
use Webmozart\Assert\Assert;

class DeleteTableIndexByModelClassIndexNameAction
{
    use QueueableAction;

<<<<<<< HEAD
    /**
     * Elimina l'indice indicato dalla tabella del model, sulla connessione del model.
     *
     * Passa dal Schema builder di Laravel: il getDoctrineSchemaManager() non e' piu' esposto
     * dalla connessione e Doctrine non quota gli identificatori in dropIndex().
     */
=======
>>>>>>> 77b1e2748 (first)
    public function execute(string $modelClass, string $indexName): void
    {
        Assert::isInstanceOf($model = app($modelClass), EloquentModel::class);
        $table = $model->getTable();
        Assert::stringNotEmpty($table);
<<<<<<< HEAD
        Assert::stringNotEmpty($indexName);

        Schema::connection($model->getConnectionName())->table(
            $table,
            static function (Blueprint $blueprint) use ($indexName): void {
                $blueprint->dropIndex($indexName);
            },
        );
=======
        $formManager = app(GetSchemaManagerByModelClassAction::class)->execute($modelClass);
        $doctrineTable = $formManager->introspectTableByUnquotedName($table);
        // $doctrineTable=$formManager->listTableDetails($table);
        $doctrineTable->dropIndex($indexName);

        // ALTER TABLE `roles` DROP INDEX `roles_name_guard_name_unique`;
        // dddx(['res'=>$res,'doctrineTable'=>$doctrineTable,'indexName'=>$indexName]);
>>>>>>> 77b1e2748 (first)
    }
}
