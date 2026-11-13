<?php

declare(strict_types=1);

namespace Themes\Sixteen\Enums;

use Filament\Support\Contracts\HasLabel;
use Themes\Sixteen\Enums\Concerns\HasLangLabel;

/**
 * Livelli di visibilita' di un evento.
 *
 * Sostituisce la costante MunicipalEvent::VISIBILITY_LEVELS. Le label vivono in
 * lang/<locale>/municipal_event_visibility_enum.php (namespace `sixteen::`).
 */
enum MunicipalEventVisibilityEnum: string implements HasLabel
{
    use HasLangLabel;

    case PUBLIC = 'public';
    case RESTRICTED = 'restricted';
    case INTERNAL = 'internal';
    case INVITE_ONLY = 'invite_only';
}
