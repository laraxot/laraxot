<?php

declare(strict_types=1);

namespace Themes\Sixteen\Enums;

use Filament\Support\Contracts\HasLabel;
use Themes\Sixteen\Enums\Concerns\HasLangLabel;

/**
 * Categorie di persona pubblica.
 *
 * Sostituisce la costante PublicPerson::CATEGORIES. Le label vivono in
 * lang/<locale>/public_person_category_enum.php (namespace `sixteen::`).
 */
enum PublicPersonCategoryEnum: string implements HasLabel
{
    use HasLangLabel;

    case POLITICIAN = 'politician';
    case MANAGER = 'manager';
    case EMPLOYEE = 'employee';
    case CONSULTANT = 'consultant';
    case COMMISSION_MEMBER = 'commission_member';
    case BOARD_MEMBER = 'board_member';
    case AUTHORITY_MEMBER = 'authority_member';
    case OTHER = 'other';
}
