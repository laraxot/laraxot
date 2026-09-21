<?php

declare(strict_types=1);

namespace Modules\User\Filament\Resources\TeamResource\Tables;

use Filament\Tables\Columns\Column;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Modules\User\Models\Team;
use Modules\Xot\Filament\Resources\Tables\XotBaseResourceTable;

class TeamsTable extends XotBaseResourceTable
{
    /**
     * @var class-string<Team>
     */
    protected static string $model = Team::class;

    /**
     * @return array<string, Column>
     */
    public function getTableColumns(): array
    {
        return [
            'name' => TextColumn::make('name')->searchable()->sortable(),
            'slug' => TextColumn::make('slug')->searchable()->sortable(),
            'personal_team' => IconColumn::make('personal_team')->boolean()->sortable(),
            'description' => TextColumn::make('description')->limit(60)->wrap()->toggleable(isToggledHiddenByDefault: true),
            'user_id' => TextColumn::make('user_id')->sortable(),
            'id' => TextColumn::make('id')->sortable()->copyable()->toggleable(isToggledHiddenByDefault: true),
            'uuid' => TextColumn::make('uuid')->copyable()->toggleable(isToggledHiddenByDefault: true),
            'created_at' => TextColumn::make('created_at')->dateTime()->sortable()->placeholder('—'),
            'updated_at' => TextColumn::make('updated_at')->dateTime()->sortable()->placeholder('—')->toggleable(isToggledHiddenByDefault: true),
        ];
    }
}
