<?php

declare(strict_types=1);

namespace Themes\Sixteen\Enums;

use Filament\Support\Contracts\HasLabel;
use Themes\Sixteen\Enums\Concerns\HasLangLabel;

/**
 * Stati di pubblicazione di un documento.
 *
 * Sostituisce la costante PublicDocument::PUBLICATION_STATUSES. Le label vivono in
 * lang/<locale>/public_document_publication_status_enum.php (namespace `sixteen::`).
 */
enum PublicDocumentPublicationStatusEnum: string implements HasLabel
{
    use HasLangLabel;

    case UNPUBLISHED = 'unpublished';
    case SCHEDULED = 'scheduled';
    case PUBLISHED = 'published';
    case UPDATED = 'updated';
    case WITHDRAWN = 'withdrawn';
}
