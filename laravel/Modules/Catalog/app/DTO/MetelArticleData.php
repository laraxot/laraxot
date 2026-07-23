<?php

declare(strict_types=1);

namespace Modules\Catalog\DTO;

/**
 * A single parsed METEL article, normalized from a file record or an API object.
 * Immutable and framework-free.
 */
final readonly class MetelArticleData
{
    public function __construct(
        public string $metelCode,
        public string $description,
        public float $grossPrice,
        public ?float $netPrice = null,
        public ?string $unitOfMeasure = null,
        public ?string $packaging = null,
        public ?string $barcode = null,
        public ?string $etimCode = null,
        public ?string $brandCode = null,
    ) {}

    /**
     * Attributes to persist on a metel_catalog_items row (excluding foreign keys).
     *
     * @return array<string, mixed>
     */
    public function toItemAttributes(): array
    {
        return [
            'description' => $this->description,
            'gross_price' => $this->grossPrice,
            'net_price' => $this->netPrice,
            'unit_of_measure' => $this->unitOfMeasure,
            'packaging' => $this->packaging,
            'barcode' => $this->barcode,
            'etim_code' => $this->etimCode,
        ];
    }
}
