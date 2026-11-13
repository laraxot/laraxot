<?php

declare(strict_types=1);

namespace Themes\Sixteen\Enums;

use Filament\Support\Contracts\HasLabel;
use Themes\Sixteen\Enums\Concerns\HasLangLabel;

/**
 * Tipologie di unita' organizzativa.
 *
 * Sostituisce la costante OrganizationalUnit::TYPES. Le label vivono in
 * lang/<locale>/organizational_unit_type_enum.php (namespace `sixteen::`).
 */
enum OrganizationalUnitTypeEnum: string implements HasLabel
{
    use HasLangLabel;

    case MUNICIPALITY = 'municipality';
    case DEPARTMENT = 'department';
    case SECTOR = 'sector';
    case OFFICE = 'office';
    case SERVICE = 'service';
    case AREA = 'area';
    case DIVISION = 'division';
    case UNIT = 'unit';
    case COMMITTEE = 'committee';
    case COUNCIL = 'council';
    case BOARD = 'board';
    case AUTHORITY = 'authority';
    case AGENCY = 'agency';
}
