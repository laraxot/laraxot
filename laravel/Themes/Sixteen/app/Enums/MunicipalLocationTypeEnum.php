<?php

declare(strict_types=1);

namespace Themes\Sixteen\Enums;

use Filament\Support\Contracts\HasLabel;
use Themes\Sixteen\Enums\Concerns\HasLangLabel;

/**
 * Tipologie di luogo comunale.
 *
 * Sostituisce la costante MunicipalLocation::LOCATION_TYPES. Le label vivono in
 * lang/<locale>/municipal_location_type_enum.php (namespace `sixteen::`).
 */
enum MunicipalLocationTypeEnum: string implements HasLabel
{
    use HasLangLabel;

    case HEADQUARTERS = 'headquarters';
    case OFFICE = 'office';
    case SERVICE_CENTER = 'service_center';
    case LIBRARY = 'library';
    case SCHOOL = 'school';
    case SPORTS_FACILITY = 'sports_facility';
    case CULTURAL_CENTER = 'cultural_center';
    case HEALTHCARE = 'healthcare';
    case SOCIAL_CENTER = 'social_center';
    case CEMETERY = 'cemetery';
    case MARKET = 'market';
    case PARKING = 'parking';
    case PARK = 'park';
    case SQUARE = 'square';
    case MONUMENT = 'monument';
    case TOURIST_OFFICE = 'tourist_office';
    case WASTE_CENTER = 'waste_center';
    case EMERGENCY = 'emergency';
    case OTHER = 'other';
}
