<?php

declare(strict_types=1);

namespace Modules\Quotation\DTO;

use InvalidArgumentException;

/**
 * Allegato di un sopralluogo gia' caricato su disco.
 */
final readonly class SiteSurveyAttachment
{
    public const KINDS = ['photo', 'video', 'audio'];

    /**
     * @param  array<string, mixed>|null  $meta
     */
    public function __construct(
        public string $kind,
        public string $path,
        public string $disk = 'public',
        public ?string $title = null,
        public ?array $meta = null,
    ) {
        if (! in_array($kind, self::KINDS, true)) {
            throw new InvalidArgumentException(
                sprintf('Tipo allegato sopralluogo non valido: %s.', $kind)
            );
        }

        if (trim($path) === '') {
            throw new InvalidArgumentException('Il percorso dell\'allegato non puo\' essere vuoto.');
        }
    }
}
