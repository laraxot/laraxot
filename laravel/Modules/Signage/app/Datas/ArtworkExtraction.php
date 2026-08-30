<?php

declare(strict_types=1);

namespace Modules\Signage\Datas;

/**
 * Full result of parsing a Signage sign drawing (.ai / .pdf): the title-block
 * fields, the decoded sign code, the ordered component list and the technical
 * layers present in the artwork. This is the "list of all components" the
 * operator reviews before turning it into a quotation.
 */
final readonly class ArtworkExtraction
{
    /**
     * @param array{
     *     customer: ?string, referent: ?string, project: ?string, brand: ?string,
     *     shop: ?string, sign_type_code: ?string, quantity: ?int, painting: ?string,
     *     finishing: ?string, lighting: ?string, scale: ?string, size: ?string,
     *     revision: ?string, drawing: ?string
     * } $titleBlock
     * @param  array{brand_prefix: ?string, family: ?string, thickness_mm: ?float, color_ral: ?string, dimensions_mm: ?string}  $decodedCode
     * @param  list<SignComponent>  $components
     * @param  list<string>  $layers
     * @param  list<string>  $notes
     */
    public function __construct(
        public string $sourceFile,
        public ?string $drawingTitle,
        public ?string $creator,
        public array $titleBlock,
        public array $decodedCode,
        public array $components,
        public array $layers,
        public array $notes,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'source_file' => $this->sourceFile,
            'drawing_title' => $this->drawingTitle,
            'creator' => $this->creator,
            'title_block' => $this->titleBlock,
            'decoded_code' => $this->decodedCode,
            'components' => array_map(static fn (SignComponent $c): array => $c->toArray(), $this->components),
            'layers' => $this->layers,
            'notes' => $this->notes,
        ];
    }
}
