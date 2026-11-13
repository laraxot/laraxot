<?php

declare(strict_types=1);

namespace Themes\Sixteen\Enums;

use Filament\Support\Contracts\HasLabel;
use Themes\Sixteen\Enums\Concerns\HasLangLabel;

/**
 * Stati di una notizia.
 *
 * Sostituisce la costante MunicipalNews::NEWS_STATUSES. Le label vivono in
 * lang/<locale>/municipal_news_status_enum.php (namespace `sixteen::`).
 */
enum MunicipalNewsStatusEnum: string implements HasLabel
{
    use HasLangLabel;

    case DRAFT = 'draft';
    case REVIEW = 'review';
    case APPROVED = 'approved';
    case PUBLISHED = 'published';
    case ARCHIVED = 'archived';
    case EXPIRED = 'expired';
    case RETRACTED = 'retracted';
}
