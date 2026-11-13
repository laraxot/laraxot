<?php

declare(strict_types=1);

namespace Themes\Sixteen\Enums;

use Filament\Support\Contracts\HasLabel;
use Themes\Sixteen\Enums\Concerns\HasLangLabel;

/**
 * Tipi di servizio comunale prenotabile.
 *
 * Sostituisce le costanti Appointment::SERVICE_*. Le label vivono in
 * lang/<locale>/appointment_service_type_enum.php (namespace `sixteen::`).
 */
enum AppointmentServiceTypeEnum: string implements HasLabel
{
    use HasLangLabel;

    case ANAGRAFE = 'anagrafe';
    case TRIBUTI = 'tributi';
    case SUAP = 'suap';
    case URP = 'urp';
    case OTHER = 'other';
}
