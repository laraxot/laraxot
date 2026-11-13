<?php

declare(strict_types=1);

namespace Themes\Sixteen\Enums;

use Filament\Support\Contracts\HasLabel;
use Themes\Sixteen\Enums\Concerns\HasLangLabel;

/**
 * Sezioni di Amministrazione Trasparente.
 *
 * Sostituisce la costante PublicDocument::TRANSPARENCY_SECTIONS. Le label vivono in
 * lang/<locale>/transparency_section_enum.php (namespace `sixteen::`).
 */
enum TransparencySectionEnum: string implements HasLabel
{
    use HasLangLabel;

    case ORGANIZATION = 'organization';
    case CONSULTING = 'consulting';
    case PERSONNEL = 'personnel';
    case PERFORMANCE = 'performance';
    case PUBLIC_PROCUREMENT = 'public_procurement';
    case GRANTS = 'grants';
    case BUDGETS = 'budgets';
    case ASSETS = 'assets';
    case SERVICES = 'services';
    case PUBLIC_WORKS = 'public_works';
    case URBAN_PLANNING = 'urban_planning';
    case ENVIRONMENTAL_INFO = 'environmental_info';
    case SOCIAL_INTERVENTIONS = 'social_interventions';
    case OTHER = 'other';
}
