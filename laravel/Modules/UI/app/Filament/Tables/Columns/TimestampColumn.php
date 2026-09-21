<?php

declare(strict_types=1);

namespace Modules\UI\Filament\Tables\Columns;

use Filament\Tables\Columns\TextColumn;

/**
 * Colonna data/ora standard del progetto: `dateTime()`, `sortable()`,
 * placeholder `—` per i valori nulli.
 *
 * Stesso trio (`dateTime()->sortable()->placeholder('—')`) ripetuto
 * identico per `created_at`/`updated_at` in 30+ classi `Tables/*.php`.
 * `toggleable()` NON e' incluso qui: `created_at` e `updated_at` differiscono
 * su questo punto nel codice esistente (`updated_at` di solito nascosta di
 * default, `created_at` no) — resta una scelta del chiamante, componibile
 * come qualunque altro metodo Filament:
 *
 * ```php
 * 'created_at' => TimestampColumn::make('created_at'),
 * 'updated_at' => TimestampColumn::make('updated_at')->toggleable(isToggledHiddenByDefault: true),
 * ```
 *
 * @see Modules/UI/docs/form-column-parity.md
 */
class TimestampColumn extends TextColumn
{
    public static function make(?string $name = null): static
    {
        return parent::make($name)
            ->dateTime()
            ->sortable()
            ->placeholder('—');
    }
}
