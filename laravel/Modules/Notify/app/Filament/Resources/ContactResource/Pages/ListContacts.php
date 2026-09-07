<?php

declare(strict_types=1);

namespace Modules\Notify\Filament\Resources\ContactResource\Pages;

use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Modules\Notify\Filament\Resources\ContactResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;
use Override;

class ListContacts extends XotBaseListRecords
{
    protected static string $resource = ContactResource::class;

    /**
     * @return array<string, IconColumn|TextColumn>
     */
    public static function contactTableColumns(): array
    {
        return [
            'id' => TextColumn::make('id')->numeric()->sortable(),
            'name' => TextColumn::make('name')->searchable()->sortable(),
            'email' => TextColumn::make('email')->searchable()->sortable(),
            'phone' => TextColumn::make('phone')->searchable()->sortable(),
            'message' => TextColumn::make('message')->searchable()->sortable(),
            'is_read' => IconColumn::make('is_read')->boolean(),
            'created_at' => TextColumn::make('created_at')->dateTime()->sortable(),
<<<<<<< HEAD
<<<<<<< HEAD
            'updated_at' => TextColumn::make('updated_at')->dateTime()->sortable()];
=======
            'updated_at' => TextColumn::make('updated_at')->dateTime()->sortable(),
        ];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'updated_at' => TextColumn::make('updated_at')->dateTime()->sortable()];
>>>>>>> a988596b (first)
    }

    /**
     * @return array<string, Filter>
     */
    public static function contactTableFilters(): array
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

    #[Override]
    public function getTableColumns(): array
    {
        return self::contactTableColumns();
    }
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)

    #[Override]
    public function getTableFilters(): array
    {
        return self::contactTableFilters();
    }
<<<<<<< HEAD
=======
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
}
