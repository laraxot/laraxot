<?php

declare(strict_types=1);

namespace Themes\Sixteen\Enums;

use Filament\Support\Contracts\HasLabel;
use Themes\Sixteen\Enums\Concerns\HasLangLabel;

/**
 * Servizi disponibili presso un luogo comunale.
 *
 * Sostituisce la costante MunicipalLocation::AVAILABLE_SERVICES. Le label vivono in
 * lang/<locale>/municipal_location_service_enum.php (namespace `sixteen::`).
 */
enum MunicipalLocationServiceEnum: string implements HasLabel
{
    use HasLangLabel;

    case CITIZEN_SERVICES = 'citizen_services';
    case DOCUMENT_COLLECTION = 'document_collection';
    case PAYMENTS = 'payments';
    case APPOINTMENTS = 'appointments';
    case INFORMATION = 'information';
    case COMPLAINTS = 'complaints';
    case WIFI = 'wifi';
    case PHOTOCOPIES = 'photocopies';
    case PARKING = 'parking';
    case ACCESSIBILITY = 'accessibility';
    case TRANSLATION = 'translation';
    case ASSISTANCE = 'assistance';
}
