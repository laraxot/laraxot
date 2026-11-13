<?php

declare(strict_types=1);

namespace Themes\Sixteen\Enums;

use Filament\Support\Contracts\HasLabel;
use Themes\Sixteen\Enums\Concerns\HasLangLabel;

/**
 * Tipologie di evento.
 *
 * Sostituisce la costante MunicipalEvent::EVENT_TYPES. Le label vivono in
 * lang/<locale>/municipal_event_type_enum.php (namespace `sixteen::`).
 */
enum MunicipalEventTypeEnum: string implements HasLabel
{
    use HasLangLabel;

    case COUNCIL_MEETING = 'council_meeting';
    case COMMITTEE_MEETING = 'committee_meeting';
    case PUBLIC_MEETING = 'public_meeting';
    case PUBLIC_HEARING = 'public_hearing';
    case CONFERENCE = 'conference';
    case WORKSHOP = 'workshop';
    case SEMINAR = 'seminar';
    case TRAINING = 'training';
    case CULTURAL_EVENT = 'cultural_event';
    case SPORTS_EVENT = 'sports_event';
    case CELEBRATION = 'celebration';
    case CEREMONY = 'ceremony';
    case EXHIBITION = 'exhibition';
    case FAIR = 'fair';
    case FESTIVAL = 'festival';
    case COMPETITION = 'competition';
    case TENDER_OPENING = 'tender_opening';
    case PUBLIC_CONSULTATION = 'public_consultation';
    case OTHER = 'other';
}
