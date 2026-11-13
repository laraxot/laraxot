<?php

declare(strict_types=1);

namespace Themes\Sixteen\Enums;

use Filament\Support\Contracts\HasLabel;
use Themes\Sixteen\Enums\Concerns\HasLangLabel;

/**
 * Tipologie di notizia.
 *
 * Sostituisce la costante MunicipalNews::NEWS_TYPES. Le label vivono in
 * lang/<locale>/municipal_news_type_enum.php (namespace `sixteen::`).
 */
enum MunicipalNewsTypeEnum: string implements HasLabel
{
    use HasLangLabel;

    case NEWS = 'news';
    case PRESS_RELEASE = 'press_release';
    case PUBLIC_NOTICE = 'public_notice';
    case ANNOUNCEMENT = 'announcement';
    case ALERT = 'alert';
    case SERVICE_UPDATE = 'service_update';
    case REGULATION_UPDATE = 'regulation_update';
    case EVENT_ANNOUNCEMENT = 'event_announcement';
    case TENDER_NOTICE = 'tender_notice';
    case JOB_POSTING = 'job_posting';
    case COUNCIL_UPDATE = 'council_update';
    case MAYOR_MESSAGE = 'mayor_message';
    case CITIZEN_INFO = 'citizen_info';
    case EMERGENCY = 'emergency';
    case OTHER = 'other';
}
