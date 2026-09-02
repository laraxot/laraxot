<?php

declare(strict_types=1);

namespace Modules\User\Filament\Tables\Columns;

use Modules\User\Models\Role;
use Modules\Xot\Filament\Tables\Columns\XotBaseSelectColumn;

/**
 * Controparte in lista di {@see \Modules\User\Filament\Forms\Components\SingleRoleSelect}.
 *
 * Stesse opzioni (ruoli da `Role::query()`), esposte come select inline di riga.
 *
 * Usage:
 * ```php
 * 'role_id' => SingleRoleSelectColumn::make('role_id'),
 * ```
 *
 * @see Modules/User/docs/form-column-parity.md
 */
class SingleRoleSelectColumn extends XotBaseSelectColumn
{
    protected const string DEFAULT_NAME = 'role_id';

    public static function make(?string $name = null): static
    {
        return parent::make($name ?? static::DEFAULT_NAME);
    }

    /**
     * Opzioni ruolo: SSoT condivisa con SingleRoleSelect.
     *
     * @return array<int|string, string>
     */
    public static function roleOptions(): array
    {
        /** @var array<int|string, string> $options */
        $options = Role::query()->pluck('name', 'id')->toArray();

        return $options;
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->options(static fn (): array => static::roleOptions());
    }
}
