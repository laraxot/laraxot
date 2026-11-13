<?php

declare(strict_types=1);

namespace Themes\Sixteen\Enums;

use Filament\Support\Contracts\HasLabel;
use Themes\Sixteen\Enums\Concerns\HasLangLabel;

/**
 * Ruoli principali secondo AGID.
 *
 * Sostituisce la costante PublicPerson::ROLES. Le label vivono in
 * lang/<locale>/public_person_role_enum.php (namespace `sixteen::`).
 */
enum PublicPersonRoleEnum: string implements HasLabel
{
    use HasLangLabel;

    case MAYOR = 'mayor';
    case DEPUTY_MAYOR = 'deputy_mayor';
    case COUNCILLOR = 'councillor';
    case PRESIDENT = 'president';
    case VICE_PRESIDENT = 'vice_president';
    case SECRETARY = 'secretary';
    case GENERAL_MANAGER = 'general_manager';
    case MANAGER = 'manager';
    case SUPERVISOR = 'supervisor';
    case EMPLOYEE = 'employee';
    case CONSULTANT = 'consultant';
    case COLLABORATOR = 'collaborator';
}
