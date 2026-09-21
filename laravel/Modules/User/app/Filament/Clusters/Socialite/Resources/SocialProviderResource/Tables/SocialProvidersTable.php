<?php

declare(strict_types=1);

namespace Modules\User\Filament\Clusters\Socialite\Resources\SocialProviderResource\Tables;

use Filament\Tables\Columns\Column;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Modules\User\Models\SocialProvider;
use Modules\Xot\Filament\Resources\Tables\XotBaseResourceTable;

class SocialProvidersTable extends XotBaseResourceTable
{
    /**
     * @var class-string<SocialProvider>
     */
    protected static string $model = SocialProvider::class;

    /**
     * @return array<string, Column>
     */
    public function getTableColumns(): array
    {
        return [
            'name' => TextColumn::make('name')->searchable()->sortable(),
            'active' => IconColumn::make('active')->boolean()->sortable(),
            'socialite' => IconColumn::make('socialite')->boolean()->sortable(),
            'stateless' => IconColumn::make('stateless')->boolean()->sortable(),
            'scopes' => TextColumn::make('scopes')->badge()->toggleable(isToggledHiddenByDefault: true),
            'id' => TextColumn::make('id')->sortable()->copyable()->toggleable(isToggledHiddenByDefault: true),
            'created_at' => TextColumn::make('created_at')->dateTime()->sortable()->placeholder('—'),
            'updated_at' => TextColumn::make('updated_at')->dateTime()->sortable()->placeholder('—')->toggleable(isToggledHiddenByDefault: true),
        ];
    }
}
