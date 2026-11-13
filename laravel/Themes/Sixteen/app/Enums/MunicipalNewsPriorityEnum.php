<?php

declare(strict_types=1);

namespace Themes\Sixteen\Enums;

use Filament\Support\Contracts\HasLabel;
use Themes\Sixteen\Enums\Concerns\HasLangLabel;

/**
 * Livelli di priorita' di una notizia.
 *
 * Sostituisce la costante MunicipalNews::PRIORITY_LEVELS. Le label vivono in
 * lang/<locale>/municipal_news_priority_enum.php (namespace `sixteen::`).
 */
enum MunicipalNewsPriorityEnum: int implements HasLabel
{
    use HasLangLabel;

    case LOW = 1;
    case NORMAL = 2;
    case HIGH = 3;
    case URGENT = 4;
    case CRITICAL = 5;
}
