<?php

declare(strict_types=1);

namespace Modules\User\Filament\Clusters\Socialite\Resources\SsoProviderResource\Tables;

use Filament\Tables\Columns\Column;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Modules\User\Models\SsoProvider;
use Modules\Xot\Filament\Resources\Tables\XotBaseResourceTable;

class SsoProvidersTable extends XotBaseResourceTable
{
    /**
     * @var class-string<SsoProvider>
     */
    protected static string $model = SsoProvider::class;

    /**
     * @return array<string, Column>
     */
    public function getTableColumns(): array
    {
        return [
            'display_name' => TextColumn::make('display_name')->searchable()->sortable(),
            'name' => TextColumn::make('name')->searchable()->sortable(),
            'type' => TextColumn::make('type')->toggleable(isToggledHiddenByDefault: true),
            'is_active' => IconColumn::make('is_active')->boolean()->sortable(),
            'entity_id' => TextColumn::make('entity_id')->toggleable(isToggledHiddenByDefault: true),
            'redirect_url' => TextColumn::make('redirect_url')->toggleable(isToggledHiddenByDefault: true),
            'id' => TextColumn::make('id')->sortable()->copyable()->toggleable(isToggledHiddenByDefault: true),
            'created_at' => TextColumn::make('created_at')->dateTime()->sortable()->placeholder('—'),
            'updated_at' => TextColumn::make('updated_at')->dateTime()->sortable()->placeholder('—')->toggleable(isToggledHiddenByDefault: true),
        ];
    }
}
