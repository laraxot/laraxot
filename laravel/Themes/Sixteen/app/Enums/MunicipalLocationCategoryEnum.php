<?php

declare(strict_types=1);

namespace Themes\Sixteen\Enums;

use Filament\Support\Contracts\HasLabel;
use Themes\Sixteen\Enums\Concerns\HasLangLabel;

/**
 * Categorie di luogo comunale.
 *
 * Sostituisce la costante MunicipalLocation::CATEGORIES. Le label vivono in
 * lang/<locale>/municipal_location_category_enum.php (namespace `sixteen::`).
 */
enum MunicipalLocationCategoryEnum: string implements HasLabel
{
    use HasLangLabel;

    case ADMINISTRATIVE = 'administrative';
    case CULTURAL = 'cultural';
    case EDUCATIONAL = 'educational';
    case SPORTS = 'sports';
    case SOCIAL = 'social';
    case HEALTHCARE = 'healthcare';
    case TOURIST = 'tourist';
    case COMMERCIAL = 'commercial';
    case ENVIRONMENTAL = 'environmental';
    case EMERGENCY = 'emergency';
}
