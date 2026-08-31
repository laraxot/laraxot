<?php

declare(strict_types=1);

namespace Modules\Wts\Actions;

use Modules\Wts\Legacy\LegacyTechnicalSheetGateway;
use Modules\Wts\Models\WtsTechnicalSheetSnapshot;

final readonly class LookupLegacyTechnicalSheetAction
{
    public function __construct(
        private LegacyTechnicalSheetGateway $gateway,
    ) {}

    public function execute(string $sheetCode): ?WtsTechnicalSheetSnapshot
    {
        $normalizedSheetCode = trim($sheetCode);
        if ($normalizedSheetCode === '') {
            return null;
        }

        $legacyData = $this->gateway->findBySheetCode($normalizedSheetCode);
        if ($legacyData === null) {
            return null;
        }

        /** @var WtsTechnicalSheetSnapshot $snapshot */
        $snapshot = WtsTechnicalSheetSnapshot::query()->updateOrCreate(
            ['sheet_code' => $legacyData->sheetCode],
            [
                'legacy_system' => 'legacy',
                'producer_name' => $legacyData->producerName,
                'waste_code' => $legacyData->wasteCode,
                'hp_codes' => $legacyData->hpCodes,
                'packaging_description' => $legacyData->packagingDescription,
                'production_description' => $legacyData->productionDescription,
                'note_1' => $legacyData->note1,
                'note_2' => $legacyData->note2,
                'legacy_payload' => $legacyData->payload,
            ],
        );

        return $snapshot;
    }
}
