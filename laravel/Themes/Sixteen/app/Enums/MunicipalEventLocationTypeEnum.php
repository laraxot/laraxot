<?php

declare(strict_types=1);

namespace Themes\Sixteen\Enums;

use Filament\Support\Contracts\HasLabel;
use Themes\Sixteen\Enums\Concerns\HasLangLabel;

/**
 * Tipologie di luogo di un evento.
 *
 * Sostituisce la costante MunicipalEvent::LOCATION_TYPES. Le label vivono in
 * lang/<locale>/municipal_event_location_type_enum.php (namespace `sixteen::`).
 */
enum MunicipalEventLocationTypeEnum: string implements HasLabel
{
    use HasLangLabel;

    case PHYSICAL = 'physical';
    case ONLINE = 'online';
    case HYBRID = 'hybrid';
    case TBD = 'tbd';
}
