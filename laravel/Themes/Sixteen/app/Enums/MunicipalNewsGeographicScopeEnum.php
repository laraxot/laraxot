<?php

declare(strict_types=1);

namespace Themes\Sixteen\Enums;

use Filament\Support\Contracts\HasLabel;
use Themes\Sixteen\Enums\Concerns\HasLangLabel;

/**
 * Ambiti geografici di una notizia.
 *
 * Sostituisce la costante MunicipalNews::GEOGRAPHIC_SCOPES. Le label vivono in
 * lang/<locale>/municipal_news_geographic_scope_enum.php (namespace `sixteen::`).
 */
enum MunicipalNewsGeographicScopeEnum: string implements HasLabel
{
    use HasLangLabel;

    case MUNICIPAL = 'municipal';
    case DISTRICT = 'district';
    case REGIONAL = 'regional';
    case NATIONAL = 'national';
    case EUROPEAN = 'european';
    case INTERNATIONAL = 'international';
}
