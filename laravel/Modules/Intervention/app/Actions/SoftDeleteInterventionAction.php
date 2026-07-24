<?php

declare(strict_types=1);

namespace Modules\Intervention\Actions;

use Modules\Intervention\Models\Intervention;
use Spatie\QueueableAction\QueueableAction;

final class SoftDeleteInterventionAction
{
    use QueueableAction;

    public function execute(Intervention $intervention): void
    {
        $intervention->delete();
    }
}
