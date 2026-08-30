<?php

declare(strict_types=1);

namespace Modules\Signage\Datas;

/**
 * One physical component / material extracted from a Signage sign drawing.
 * Immutable, framework-free: this is the row that eventually becomes a
 * quotation line (distinta base).
 */
final readonly class SignComponent
{
    public function __construct(
        /** Stable role code: face|body|paint|led|cable|fixing|holes|cutting|text|dimension|other. */
        public string $role,
        /** Human-readable header as printed on the drawing, e.g. "LETTERS FRONT SIDE". */
        public string $label,
        /** Full raw text captured for this component. */
        public string $description,
        /** Material family, e.g. "OPAL PLEX", "ACRYLBLOCK", "STRIPLED". */
        public ?string $material = null,
        /** RAL colour code, e.g. "8019". */
        public ?string $colorRal = null,
        /** Thickness in mm, e.g. 25.0. */
        public ?float $thicknessMm = null,
        /** Colour temperature, e.g. "3500K". */
        public ?string $lighting = null,
        /** Where the datum came from: callout|layer|title_block|code. */
        public string $source = 'callout',
    ) {}

    /**
     * @return array{role: string, label: string, description: string, material: ?string, color_ral: ?string, thickness_mm: ?float, lighting: ?string, source: string}
     */
    public function toArray(): array
    {
        return [
            'role' => $this->role,
            'label' => $this->label,
            'description' => $this->description,
            'material' => $this->material,
            'color_ral' => $this->colorRal,
            'thickness_mm' => $this->thicknessMm,
            'lighting' => $this->lighting,
            'source' => $this->source,
        ];
    }
}
