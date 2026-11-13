<?php

declare(strict_types=1);

namespace Modules\Xot\Actions\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Spatie\QueueableAction\QueueableAction;
use Webmozart\Assert\Assert;

class StoreAction
{
    use QueueableAction;

    /**
<<<<<<< HEAD
     * @param  array<string, mixed>  $data
     * @param  array<string, mixed>  $rules
=======
<<<<<<< .merge_file_fUwGFn
     * @param  array<string, mixed>  $data
     * @param  array<string, mixed>  $rules
=======
     * <<<<<<< HEAD.
     *
     * @param array<string, mixed> $data
     * @param array<string, mixed> $rules
     *                                    =======
     *                                    <<<<<<< HEAD
     *                                    <<<<<<< HEAD
     *                                    <<<<<<< .merge_file_EBFvzt.
     *                                    =======
     *                                    <<<<<<< HEAD.
     *                                    >>>>>>> laraxot/dev.
     * @param array<string, mixed> $data
     * @param array<string, mixed> $rules
     *                                    =======
     *                                    <<<<<<< HEAD
     *                                    =======
     *                                    <<<<<<< .merge_file_pJAByG.
     * @param array<string, mixed> $data
     * @param array<string, mixed> $rules
     *                                    =======
     *                                    <<<<<<< HEAD
     *                                    <<<<<<< .merge_file_EBFvzt.
     * @param array<string, mixed> $data
     * @param array<string, mixed> $rules
     *                                    =======
     *                                    >>>>>>> laraxot/dev
     *                                    <<<<<<< .merge_file_AXPigS.
     * @param array<string, mixed> $data
     * @param array<string, mixed> $rules
     *                                    =======
     *                                    <<<<<<< HEAD
     * @param array<string, mixed> $data
     * @param array<string, mixed> $rules
     *                                    =======
     * @param array<string, mixed> $data
     * @param array<string, mixed> $rules
     *                                    >>>>>>> laraxot/dev
     *                                    >>>>>>> .merge_file_0Whyk8
     *                                    >>>>>>> .merge_file_zPKMkq
     *                                    =======
     * @param array<string, mixed> $data
     * @param array<string, mixed> $rules
     *                                    >>>>>>> 9e11d472 (Fix merge conflicts in PHPDoc comments across multiple action classes and contracts, ensuring consistent parameter annotations and removing redundant lines.)
     *                                    <<<<<<< HEAD
     *                                    =======
     *                                    >>>>>>> .merge_file_DoCQvn
     *                                    >>>>>>> laraxot/dev
     *                                    =======
     * @param array<string, mixed> $data
     * @param array<string, mixed> $rules
     *                                    >>>>>>> laraxot/dev
     *                                    >>>>>>> laraxot/dev
     *                                    >>>>>>> laraxot/dev
>>>>>>> .merge_file_4fqtPY
>>>>>>> 77b1e2748 (first)
     */
    public function execute(Model $model, array $data, array $rules): Model
    {
        if (! isset($data['lang']) && \in_array('lang', $model->getFillable(), false)) {
            $data['lang'] = app()->getLocale();
        }
        $data['updated_by'] = authId();
        $data['created_by'] = authId();
        /*if (
         * ! isset($data['user_id'])
         * && \in_array('user_id',  $row->getFillable(), false)
         * && 'user_id' !== $row->getKeyName()
         * ) {
         * $data['user_id'] = \Auth::id();
         * }*/

        $validator = Validator::make($data, $rules);
        $validator->validate();

        $model = $model->fill($data);

        $model->save();

        $relations = app(FilterRelationsAction::class)->execute($model, $data);

        foreach ($relations as $relation) {
            // Ottieni il tipo di relazione dal nome della classe
            $relationClass = $relation::class;
            $relationshipType = class_basename($relationClass);

            $action_class = __NAMESPACE__.'\\Store\\'.$relationshipType.'Action';
            $action = app($action_class);
            Assert::object($action);
            if (! method_exists($action, 'execute')) {
                throw new \Exception('method [execute] not found in ['.$action_class.']');
            }
            $action->execute($model, $relation);
        }

        // $msg = 'created! ['.$model->getKey().']!';

        // Session::flash('status', $msg); // .

        return $model;
    }
}
