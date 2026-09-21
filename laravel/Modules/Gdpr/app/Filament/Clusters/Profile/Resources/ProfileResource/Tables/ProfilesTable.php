<?php

declare(strict_types=1);

namespace Modules\Gdpr\Filament\Clusters\Profile\Resources\ProfileResource\Tables;

use Filament\Tables\Columns\Column;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Modules\Gdpr\Models\Profile;
use Modules\Xot\Filament\Resources\Tables\XotBaseResourceTable;

class ProfilesTable extends XotBaseResourceTable
{
    /**
     * @var class-string<Profile>
     */
    protected static string $model = Profile::class;

    /**
     * @return array<string, Column>
     */
    public function getTableColumns(): array
    {
        return [
            'first_name' => TextColumn::make('first_name')->searchable()->sortable(),
            'last_name' => TextColumn::make('last_name')->searchable()->sortable(),
            'email' => TextColumn::make('email')->searchable()->copyable()->wrap(),
            'phone' => TextColumn::make('phone')->searchable()->sortable(),
            'is_active' => IconColumn::make('is_active')->boolean()->sortable(),
            'type' => TextColumn::make('type')->searchable()->sortable()->toggleable(isToggledHiddenByDefault: true),
            'id' => TextColumn::make('id')->searchable()->toggleable(isToggledHiddenByDefault: true),
            'created_at' => TextColumn::make('created_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            'updated_at' => TextColumn::make('updated_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
        ];
    }
}
