<?php

declare(strict_types=1);

namespace Modules\Wts\Actions;

use Modules\Wts\Models\WtsUnit;
use InvalidArgumentException;

final class MoveWtsUnitAction
{
    public function execute(WtsUnit $unit, string $areaCode, ?string $locationLabel): WtsUnit
    {
        $normalizedAreaCode = trim($areaCode);
        $configuredAreas = config('wts.stock_views', []);
        $allowedAreas = is_array($configuredAreas) ? array_keys($configuredAreas) : [];

        if ($normalizedAreaCode === '' || ! in_array($normalizedAreaCode, $allowedAreas, true)) {
            throw new InvalidArgumentException('Area WTS non valida.');
        }

        if ($unit->status_code === 'finished' && $normalizedAreaCode !== 'finished') {
            throw new InvalidArgumentException('Un collo finito non puo tornare operativo.');
        }

        $unit->forceFill([
            'current_area_code' => $normalizedAreaCode,
            'location_label' => trim((string) $locationLabel) !== '' ? trim((string) $locationLabel) : null,
        ])->save();

        return $unit->refresh();
    }
}
