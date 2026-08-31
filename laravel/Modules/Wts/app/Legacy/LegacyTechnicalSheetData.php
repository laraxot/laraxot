<?php

declare(strict_types=1);

namespace Modules\Wts\Legacy;

final readonly class LegacyTechnicalSheetData
{
    /**
     * @param  array<string, mixed>  $payload
     */
    public function __construct(
        public string $sheetCode,
        public string $producerName,
        public string $wasteCode,
        public ?string $hpCodes,
        public ?string $packagingDescription,
        public ?string $productionDescription,
        public ?string $note1,
        public ?string $note2,
        public array $payload = [],
    ) {}
}
