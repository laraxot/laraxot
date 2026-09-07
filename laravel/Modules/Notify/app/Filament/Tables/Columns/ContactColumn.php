<?php

declare(strict_types=1);

namespace Modules\Notify\Filament\Tables\Columns;

use Filament\Tables\Columns\ViewColumn;
use Modules\Notify\Enums\ContactTypeEnum;

/**
 * ContactColumn - Colonna Filament riutilizzabile per rendering contatti
 *
 * Utilizza ViewColumn + Blade view per separare completamente
 * logica e presentazione seguendo i principi DRY/KISS
 *
 * PATTERN CORRETTO:
 * - ViewColumn per layout complessi
 * - Blade view separata per HTML
 * - Accessibilità WCAG 2.1 AA compliant
 *
 * @author Laraxot Team
 *
 * @version 2.0 - REFACTOR COMPLETO
 *
 * @since 2025-01-06
 */
class ContactColumn extends ViewColumn
{
    /**
     * View Blade per il rendering della colonna
     */
    protected string $view = 'notify::filament.tables.columns.contact';

    protected function setUp(): void
    {
        parent::setUp();

        // Passa i tipi di contatto alla view
        $contact_types = ContactTypeEnum::cases();

        /** @var array<string> $searchableArray */
        $searchableArray = ContactTypeEnum::getSearchable();

        $this->view(static::getView(), [
<<<<<<< HEAD
<<<<<<< HEAD
            'contact_types' => $contact_types])
=======
            'contact_types' => $contact_types,
        ])
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'contact_types' => $contact_types])
>>>>>>> a988596b (first)
            ->label(__('notify::columns.contact.label'))
            ->searchable($searchableArray)
            ->sortable(false)
            ->toggleable(isToggledHiddenByDefault: false);
    }
}
