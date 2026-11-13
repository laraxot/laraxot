<?php

declare(strict_types=1);

namespace Themes\Sixteen\Enums;

use Filament\Support\Contracts\HasLabel;
use Themes\Sixteen\Enums\Concerns\HasLangLabel;

/**
 * Livelli di privacy secondo GDPR.
 *
 * Sostituisce la costante PublicDocument::PRIVACY_LEVELS. Le label vivono in
 * lang/<locale>/public_document_privacy_level_enum.php (namespace `sixteen::`).
 */
enum PublicDocumentPrivacyLevelEnum: string implements HasLabel
{
    use HasLangLabel;

    case PUBLIC = 'public';
    case RESTRICTED = 'restricted';
    case CONFIDENTIAL = 'confidential';
    case CLASSIFIED = 'classified';
    case PERSONAL_DATA = 'personal_data';
    case SENSITIVE_DATA = 'sensitive_data';
}
