<?php

declare(strict_types=1);

namespace Modules\Notify\Filament\Resources\ContactResource\Tables;

use Filament\Tables\Columns\Column;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Modules\Xot\Filament\Resources\Tables\XotBaseResourceTable;

class ContactsTable extends XotBaseResourceTable
{
    public function getTableFilters(): array
    {
        return [
            'active' => Filter::make('active')->query(fn (Builder $query): Builder => $query->where('active', true)),
            'inactive' => Filter::make('inactive')->query(
                fn (Builder $query): Builder => $query->where('active', false),
<<<<<<< HEAD
<<<<<<< HEAD
            )];
=======
            ),
        ];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            )];
>>>>>>> a988596b (first)
    }

    /**
     * @return array<string, Column>
     */
    public function getTableColumns(): array
    {
        return [
            'id' => TextColumn::make('id')->sortable(),
            'contact_type' => TextColumn::make('contact_type')->sortable(),
            'value' => TextColumn::make('value')->searchable(),
            'user_id' => TextColumn::make('user_id')->sortable(),
            'verified_at' => TextColumn::make('verified_at')->dateTime()->sortable(),
            'created_at' => TextColumn::make('created_at')->dateTime()->sortable(),
<<<<<<< HEAD
<<<<<<< HEAD
            'updated_at' => TextColumn::make('updated_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true)];
=======
            'updated_at' => TextColumn::make('updated_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
        ];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'updated_at' => TextColumn::make('updated_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true)];
>>>>>>> a988596b (first)
    }
}
