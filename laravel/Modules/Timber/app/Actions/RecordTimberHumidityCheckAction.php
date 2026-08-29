<?php

declare(strict_types=1);

namespace Modules\Timber\Actions;

use InvalidArgumentException;
use Modules\Timber\Models\TimberDryingCycle;
use Modules\Timber\Models\TimberHumidityCheck;

final class RecordTimberHumidityCheckAction
{
    public function execute(
        TimberDryingCycle $cycle,
        float $humidityPercent,
        int|string|null $userId,
        ?string $notes,
    ): TimberHumidityCheck {
        if ($cycle->status_code !== 'in_progress') {
            throw new InvalidArgumentException('Il ciclo di essiccazione non e in corso.');
        }

        if ($humidityPercent < 0 || $humidityPercent > 100) {
            throw new InvalidArgumentException('L umidita rilevata deve essere compresa tra 0 e 100.');
        }

        /** @var TimberHumidityCheck $check */
        $check = $cycle->humidityChecks()->create([
            'checked_at' => now(),
            'humidity_percent' => $humidityPercent,
            'checked_by_user_id' => $userId,
            'notes' => trim((string) $notes) !== '' ? trim((string) $notes) : null,
        ]);

        return $check;
    }
}
