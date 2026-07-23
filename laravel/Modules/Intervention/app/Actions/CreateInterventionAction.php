<?php

declare(strict_types=1);

namespace Modules\Intervention\Actions;

use Modules\Intervention\Models\Intervention;

final class CreateInterventionAction
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(array $data): Intervention
    {
        /** @var Intervention $intervention */
        $intervention = Intervention::query()->create($data);

        return $intervention;
    }
}
