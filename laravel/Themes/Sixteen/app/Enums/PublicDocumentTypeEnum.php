<?php

declare(strict_types=1);

namespace Themes\Sixteen\Enums;

use Filament\Support\Contracts\HasLabel;
use Themes\Sixteen\Enums\Concerns\HasLangLabel;

/**
 * Tipologie di documento pubblico.
 *
 * Sostituisce la costante PublicDocument::DOCUMENT_TYPES. Le label vivono in
 * lang/<locale>/public_document_type_enum.php (namespace `sixteen::`).
 */
enum PublicDocumentTypeEnum: string implements HasLabel
{
    use HasLangLabel;

    case STATUTE = 'statute';
    case REGULATION = 'regulation';
    case ORDINANCE = 'ordinance';
    case DIRECTIVE = 'directive';
    case DELIBERATION = 'deliberation';
    case DETERMINATION = 'determination';
    case DECREE = 'decree';
    case RESOLUTION = 'resolution';
    case CIRCULAR = 'circular';
    case INSTRUCTION = 'instruction';
    case PLAN = 'plan';
    case PROGRAM = 'program';
    case BUDGET = 'budget';
    case REPORT = 'report';
    case CONTRACT = 'contract';
    case AGREEMENT = 'agreement';
    case CONCESSION = 'concession';
    case AUTHORIZATION = 'authorization';
    case PERMIT = 'permit';
    case LICENSE = 'license';
    case TRANSPARENCY_ACT = 'transparency_act';
    case PUBLICATION_NOTICE = 'publication_notice';
    case SELECTION_NOTICE = 'selection_notice';
    case TENDER_NOTICE = 'tender_notice';
    case FORM = 'form';
    case GUIDE = 'guide';
    case MANUAL = 'manual';
    case PROCEDURE = 'procedure';
    case SPECIFICATION = 'specification';
    case MINUTES = 'minutes';
    case OPINION = 'opinion';
    case CERTIFICATE = 'certificate';
    case OTHER = 'other';
}
