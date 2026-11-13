<?php

declare(strict_types=1);

namespace Modules\Xot\Actions\ModelClass;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphPivot;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Spatie\QueueableAction\QueueableAction;
use Webmozart\Assert\Assert;

class GuessMorphPivotAction
{
    use QueueableAction;

    /**
     * Guess the pivot class for a many-to-many relationship.
     *
<<<<<<< HEAD
     * @param  string|class-string<Model>  $related  The related model class name
     * @param  string|class-string<Model>  $class  The class
=======
<<<<<<< .merge_file_BsfiYZ
     * @param  string|class-string<Model>  $related  The related model class name
     * @param  string|class-string<Model>  $class  The class
=======
     * <<<<<<< HEAD
     *
     * @param string|class-string<Model> $related The related model class name
     * @param string|class-string<Model> $class   The class
     *                                            =======
     *                                            <<<<<<< HEAD
     *                                            <<<<<<< HEAD
     *                                            =======
     *                                            <<<<<<< .merge_file_bHRAaz
     *                                            >>>>>>> laraxot/dev
     * @param string|class-string<Model> $related The related model class name
     * @param string|class-string<Model> $class   The class
     *                                            =======
     *                                            <<<<<<< HEAD
     *                                            <<<<<<< .merge_file_Cof58c
     * @param string|class-string<Model> $related The related model class name
     * @param string|class-string<Model> $class   The class
     *                                            =======
     *                                            =======
     *                                            <<<<<<< HEAD
     * @param string|class-string<Model> $related The related model class name
     * @param string|class-string<Model> $class   The class
     *                                            =======
     *                                            <<<<<<< .merge_file_rSrRjv
     * @param string|class-string<Model> $related The related model class name
     * @param string|class-string<Model> $class   The class
     *                                            =======
     *                                            <<<<<<< HEAD
     * @param string|class-string<Model> $related The related model class name
     * @param string|class-string<Model> $class   The class
     *                                            =======
     *                                            <<<<<<< .merge_file_Cof58c
     * @param string|class-string<Model> $related The related model class name
     * @param string|class-string<Model> $class   The class
     *                                            =======
     *                                            >>>>>>> laraxot/dev
     *                                            <<<<<<< HEAD
     * @param string|class-string<Model> $related The related model class name
     * @param string|class-string<Model> $class   The class
     *                                            =======
     * @param string|class-string<Model> $related The related model class name
     * @param string|class-string<Model> $class   The class
     *                                            >>>>>>> laraxot/dev
     *                                            <<<<<<< HEAD
     *                                            >>>>>>> .merge_file_0xKkpD
     *                                            =======
     *                                            >>>>>>> .merge_file_0xKkpD
     *                                            >>>>>>> laraxot/dev
     *                                            >>>>>>> .merge_file_E7y1aE
     *                                            >>>>>>> laraxot/dev
     *                                            >>>>>>> .merge_file_GQREes
     *                                            =======
     * @param string|class-string<Model> $related The related model class name
     * @param string|class-string<Model> $class   The class
     *                                            >>>>>>> laraxot/dev
     *                                            >>>>>>> laraxot/dev
     *                                            >>>>>>> laraxot/dev
>>>>>>> .merge_file_nnhhZ9
>>>>>>> 77b1e2748 (first)
     */
    public function execute(string $related, string $class): MorphPivot
    {
        $pivot_name = class_basename($related).'Morph';

        $pivot_class = app(GuessPivotFullClassAction::class)->execute($pivot_name, $related, $class);
        $pivot = app($pivot_class);
        Assert::isInstanceOf($pivot, MorphPivot::class);

        return $pivot;
    }
}
