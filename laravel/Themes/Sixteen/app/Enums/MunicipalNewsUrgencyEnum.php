<?php

declare(strict_types=1);

namespace Themes\Sixteen\Enums;

use Filament\Support\Contracts\HasLabel;
use Themes\Sixteen\Enums\Concerns\HasLangLabel;

/**
 * Livelli di urgenza di una notizia.
 *
 * Sostituisce la costante MunicipalNews::URGENCY_LEVELS. Le label vivono in
 * lang/<locale>/municipal_news_urgency_enum.php (namespace `sixteen::`).
 */
enum MunicipalNewsUrgencyEnum: int implements HasLabel
{
    use HasLangLabel;

    case NOT_URGENT = 1;
    case NORMAL = 2;
    case URGENT = 3;
    case VERY_URGENT = 4;
    case EMERGENCY = 5;
}
