<?php

declare(strict_types=1);

namespace Themes\Sixteen\Enums;

use Filament\Support\Contracts\HasLabel;
use Themes\Sixteen\Enums\Concerns\HasLangLabel;

/**
 * Stati di un documento pubblico.
 *
 * Sostituisce la costante PublicDocument::DOCUMENT_STATUSES. Le label vivono in
 * lang/<locale>/public_document_status_enum.php (namespace `sixteen::`).
 */
enum PublicDocumentStatusEnum: string implements HasLabel
{
    use HasLangLabel;

    case DRAFT = 'draft';
    case REVIEW = 'review';
    case APPROVED = 'approved';
    case PUBLISHED = 'published';
    case EFFECTIVE = 'effective';
    case SUSPENDED = 'suspended';
    case REVOKED = 'revoked';
    case EXPIRED = 'expired';
    case ARCHIVED = 'archived';
}
