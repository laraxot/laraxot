<?php

declare(strict_types=1);

namespace Modules\UI\Filament\Tables\Columns;

use Filament\Tables\Columns\TextColumn;

/**
 * Colonna `id` standard del progetto: ordinabile, copiabile, nascosta di
 * default (si mostra col toggle colonne).
 *
 * Stesso trio di opzioni (`sortable()->copyable()->toggleable(isToggledHiddenByDefault: true)`)
 * ripetuto identico in 32+ classi `Tables/*.php` (soprattutto `Modules/User`) —
 * un fatto di convenzione di progetto, non di dominio, quindi qui non e'
 * un `GroupColumn` come {@see PersonColumn}/{@see AddressColumn} (niente da
 * raggruppare in una cella): una sola colonna, la stessa configurazione
 * ovunque.
 *
 * Nome `SortableIdColumn`, non `IdColumn`: esiste gia' `IDColumn` (ancora di
 * riga per lo scroll, scopo diverso) — nome case-insensitive collidente,
 * evitato per intero invece di affidarsi al case-sensitivity del filesystem.
 *
 * Usage:
 * ```php
 * 'id' => SortableIdColumn::make(),
 * ```
 *
 * @see Modules/UI/docs/form-column-parity.md
 */
class SortableIdColumn extends TextColumn
{
    protected const string DEFAULT_NAME = 'id';

    public static function make(?string $name = null): static
    {
        return parent::make($name ?? static::DEFAULT_NAME)
            ->sortable()
            ->copyable()
            ->toggleable(isToggledHiddenByDefault: true);
    }
}
