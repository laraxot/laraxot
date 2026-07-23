<?php

declare(strict_types=1);

namespace Modules\Intervention\Actions;

use Modules\Intervention\Models\Intervention;

final class SoftDeleteInterventionAction
{
    public function execute(Intervention $intervention): void
    {
        $intervention->delete();
    }
}
