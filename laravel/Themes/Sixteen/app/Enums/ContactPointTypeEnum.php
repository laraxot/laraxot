<?php

declare(strict_types=1);

namespace Themes\Sixteen\Enums;

use Filament\Support\Contracts\HasLabel;
use Themes\Sixteen\Enums\Concerns\HasLangLabel;

/**
 * Tipologie di punto di contatto (AGID).
 *
 * Sostituisce la costante ContactPoint::TYPES. Le label vivono in
 * lang/<locale>/contact_point_type_enum.php (namespace `sixteen::`).
 */
enum ContactPointTypeEnum: string implements HasLabel
{
    use HasLangLabel;

    case EMAIL = 'email';
    case PEC = 'pec';
    case PHONE = 'phone';
    case FAX = 'fax';
    case MOBILE = 'mobile';
    case WHATSAPP = 'whatsapp';
    case TELEGRAM = 'telegram';
    case ADDRESS = 'address';
    case WEBSITE = 'website';
    case SOCIAL_FACEBOOK = 'social_facebook';
    case SOCIAL_TWITTER = 'social_twitter';
    case SOCIAL_LINKEDIN = 'social_linkedin';
    case SOCIAL_YOUTUBE = 'social_youtube';
    case SOCIAL_INSTAGRAM = 'social_instagram';
    case APPOINTMENT_URL = 'appointment_url';
    case OTHER = 'other';
}
