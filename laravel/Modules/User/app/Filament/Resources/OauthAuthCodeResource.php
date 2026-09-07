<?php

declare(strict_types=1);

namespace Modules\User\Filament\Resources;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
<<<<<<< HEAD
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\PageRegistration;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
<<<<<<< HEAD
=======
use Filament\Tables\Table;
<<<<<<< HEAD
>>>>>>> 60a2c9a9 (.)
=======
=======
use Filament\Resources\Pages\PageRegistration;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Modules\User\Filament\Clusters\Passport\Resources\OauthAuthCodeResource\Pages\ListOauthAuthCodes;
use Modules\User\Filament\Clusters\Passport\Resources\OauthAuthCodeResource\Pages\ViewOauthAuthCode;
<<<<<<< HEAD
<<<<<<< HEAD
=======
use Modules\User\Filament\Resources\OauthAuthCodeResource\Pages;
>>>>>>> 60a2c9a9 (.)
=======
use Modules\User\Filament\Resources\OauthAuthCodeResource\Pages;
=======
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
use Modules\User\Models\OauthAuthCode;
use Modules\Xot\Filament\Resources\XotBaseResource;

use function Safe\json_encode;

/**
 * Class OauthAuthCodeResource.
 */
class OauthAuthCodeResource extends XotBaseResource
{
    protected static ?string $model = OauthAuthCode::class;

    protected static ?string $recordTitleAttribute = 'id';

    /**
<<<<<<< HEAD
     * Get the form schema for the resource.
     *
     * @return array<string, Select|TextInput>
     */
    #[\Override]
    public static function getFormSchema(): array
    {
        return [
            'user_id' => Select::make('user_id')
                ->relationship('user', 'name')
                ->searchable(),
            'client_id' => Select::make('client_id')
                ->relationship('client', 'name')
                ->searchable()
                ->required(),
            'scopes' => TextInput::make('scopes'),
            'revoked' => TextInput::make('revoked')
                ->numeric()
                ->required(),
        ];
    }

    /**
=======
>>>>>>> 2024e2e7 (.)
     * Extend table callback for the resource.
     *
     * @return array<string, mixed>
     */
<<<<<<< HEAD
=======
    #[\Override]
>>>>>>> 2024e2e7 (.)
    public static function extendTableCallback(): array
    {
        return [
            'columns' => [
                TextColumn::make('id')
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(function (mixed $state): string {
                        if (! is_string($state)) {
                            return '';
                        }

                        return Str::limit($state, 15, '...');
                    }),
                TextColumn::make('user.name')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('client.name')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('scopes')
                    ->limit(30)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();
<<<<<<< HEAD
<<<<<<< HEAD
                        if ($state === null) {
=======
                        if (null === $state) {
>>>>>>> 60a2c9a9 (.)
=======
                        if (null === $state) {
=======
                        if ($state === null) {
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
                            return null;
                        }
                        if (is_array($state)) {
                            /* @var array<string, mixed> $state */
                            return json_encode($state);
                        }

                        return is_string($state) ? $state : null;
                    })
                    ->toggleable(),
                IconColumn::make('revoked')
                    ->boolean()
                    ->color(fn (bool $state): string => $state ? 'danger' : 'success'),
                TextColumn::make('expires_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ],
            'filters' => [
                // Add filters for revoked status, expiration, user, client
            ],
            'actions' => [
                DeleteAction::make(),
            ],
            'bulk_actions' => [
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ],
            'default_sort' => ['created_at', 'desc'],
        ];
    }

    /**
     * Get the pages available for the resource.
     *
     * @return array<string, PageRegistration>
     */
    #[\Override]
    public static function getPages(): array
    {
        return [
            'index' => ListOauthAuthCodes::route('/'),
            'view' => ViewOauthAuthCode::route('/{record}'),
        ];
    }

    /**
     * Modify the Eloquent query used to retrieve the records.
     */
    #[\Override]
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with(['user', 'client']);
    }
}
