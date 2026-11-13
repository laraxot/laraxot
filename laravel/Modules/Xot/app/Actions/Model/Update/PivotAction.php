<?php

declare(strict_types=1);

namespace Modules\Xot\Actions\Model\Update;

use Illuminate\Database\Eloquent\Model;
use Modules\Xot\Datas\RelationData as RelationDTO;
use Spatie\QueueableAction\QueueableAction;

class PivotAction
{
    use QueueableAction;

<<<<<<< HEAD
    public function execute(Model $_model, RelationDTO $_relationDTO): void
    {
        // Come Store\PivotAction: stub non implementato, senza caller. Prima dddx('wip') uccideva la richiesta.
        throw new \LogicException('Update\PivotAction not implemented');
=======
    /**
     * Undocumented function.
     */
    public function execute(Model $_model, RelationDTO $relationDTO): void
    {
        $rows = $relationDTO->rows;
        // $rows is already typed as Relation in RelationDTO
        dddx('wip');

>>>>>>> 77b1e2748 (first)
        /*
         *
         * $parent_panel = $this->panel->getParent();
         * if (null !== $parent_panel) {
         * $parent_row = $parent_panel->getRow();
         * $panel_name = $this->panel->getName();
         * $parent_row->{$panel_name}()->updateExistingPivot($model->getKey(), $data);
         * }
         *
         *
         */
    }
}
