<?php

declare(strict_types=1);

namespace Modules\Wts\Datas;

final readonly class WtsInventoryRow
{
    public function __construct(
        public string $batchCode,
        public string $unitSubCode,
        public ?string $unitCode,
        public ?string $sheetCode,
        public ?string $sheetDescription,
        public ?string $hpCodes,
        public ?string $physicalStateCode,
        public ?string $physicalStateLabel,
        public ?string $treatmentCode,
        public ?string $treatmentLabel,
        public ?string $wasteCode,
        public ?float $weightKg,
        public ?float $volume,
        public ?int $colliCount,
        public ?string $zoneCode,
        public ?string $zoneLabel,
        public ?string $productionDescription,
        public ?string $packagingDescription,
        public ?string $note1,
        public ?string $note2,
        public ?string $formReference,
        public string $statusCode,
        public string $source,
        public ?string $producerName = null,
        public ?string $treatmentLineCode = null,
        public ?string $productionCycleCode = null,
    ) {}

    public function isAvailable(): bool
    {
        return $this->statusCode === 'available';
    }
}
