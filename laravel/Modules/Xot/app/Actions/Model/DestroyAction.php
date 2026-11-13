<?php

declare(strict_types=1);

namespace Modules\Xot\Actions\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;
use Spatie\QueueableAction\QueueableAction;

class DestroyAction
{
    use QueueableAction;

    /**
<<<<<<< HEAD
=======
<<<<<<< .merge_file_rv9kbP
>>>>>>> 77b1e2748 (first)
     * @param  array<string, mixed>  $_data
     * @param  array<string, mixed>  $_rules
     */
    /**
     * @param  array<string, mixed>  $_data
     * @param  array<string, mixed>  $_rules
<<<<<<< HEAD
=======
=======
     * <<<<<<< HEAD.
     *
     * @param array<string, mixed> $_data
     * @param array<string, mixed> $_rules
     */
    /**
     * @param array<string, mixed> $_data
     * @param array<string, mixed> $_rules
     *                                     =======
     *                                     <<<<<<< HEAD
     *                                     <<<<<<< HEAD
     *                                     <<<<<<< .merge_file_dGbAu9.
     * @param array<string, mixed> $_data
     * @param array<string, mixed> $_rules
     *                                     =======
     *                                     <<<<<<< HEAD
     * @param array<string, mixed> $_data
     * @param array<string, mixed> $_rules
     */
    /**
     * @param array<string, mixed> $_data
     * @param array<string, mixed> $_rules
     *                                     =======
     *                                     <<<<<<< .merge_file_lqWfgU
     *                                     =======
     *                                     <<<<<<< HEAD
     *                                     <<<<<<< .merge_file_dGbAu9.
     * @param array<string, mixed> $_data
     * @param array<string, mixed> $_rules
     *                                     >>>>>>> laraxot/dev
     *                                     =======
     *                                     <<<<<<< .merge_file_7pWvkY.
     * @param array<string, mixed> $_data
     * @param array<string, mixed> $_rules
     *                                     =======
     *                                     <<<<<<< HEAD
     * @param array<string, mixed> $_data
     * @param array<string, mixed> $_rules
     */
    /**
     * @param array<string, mixed> $_data
     * @param array<string, mixed> $_rules
     *                                     =======
     *                                     <<<<<<< HEAD
     *                                     =======
     *                                     >>>>>>> .merge_file_wHn1MB
     *                                     >>>>>>> laraxot/dev
     * @param array<string, mixed> $_data
     * @param array<string, mixed> $_rules
     */
    /**
     * @param array<string, mixed> $_data
     * @param array<string, mixed> $_rules
     *                                     <<<<<<< HEAD
     *                                     =======
     *                                     <<<<<<< .merge_file_lqWfgU
     *                                     =======
     *                                     >>>>>>> laraxot/dev
     *                                     >>>>>>> laraxot/dev
     *                                     >>>>>>> .merge_file_Pqg8ia
     *                                     >>>>>>> .merge_file_0r7kiR
     *                                     =======
     * @param array<string, mixed> $_data
     * @param array<string, mixed> $_rules
     *                                     >>>>>>> 9e11d472 (Fix merge conflicts in PHPDoc comments across multiple action classes and contracts, ensuring consistent parameter annotations and removing redundant lines.)
     *                                     <<<<<<< HEAD
     *                                     =======
     *                                     >>>>>>> .merge_file_wHn1MB
     *                                     >>>>>>> laraxot/dev
     *                                     =======
     * @param array<string, mixed> $_data
     * @param array<string, mixed> $_rules
     */
    /**
     * @param array<string, mixed> $_data
     * @param array<string, mixed> $_rules
     *                                     >>>>>>> laraxot/dev
     *                                     >>>>>>> laraxot/dev
     *                                     >>>>>>> laraxot/dev
>>>>>>> .merge_file_FQwrjc
>>>>>>> 77b1e2748 (first)
     */
    public function execute(Model $model, array $_data, array $_rules): Model
    {
        // prende la chiave del modello

        // $id = $row->getKey();

        // nel mio caso nella pivot è la chiave 14 ma non nella tabella finale,
        // ma probabilmente è giusto perchè va disassociata se è many to many
        // ma forse il problema è che il modello è Keyword e non KeywordReport

        // DA FIXARE: se le tabelle pivot e tabella finale hanno id sfasati allora non CANCELLA giusto
        // e nemmeno EDIT lo fa giusto

        $res = $model->delete();
        if ($res) {
            Session::flash('status', 'eliminato');
        } else {
            Session::flash('status', 'NON eliminato');
        }

        return $model;
    }
}
