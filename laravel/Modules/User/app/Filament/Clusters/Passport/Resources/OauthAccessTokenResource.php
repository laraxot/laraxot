<?php

declare(strict_types=1);

namespace Modules\User\Filament\Clusters\Passport\Resources;

<<<<<<< HEAD
<<<<<<< HEAD
=======
=======
>>>>>>> 87273113 (.)
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
<<<<<<< HEAD
=======
>>>>>>> 60a2c9a9 (.)
=======
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
<<<<<<< HEAD
<<<<<<< HEAD
use Filament\Tables\Columns\Column;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\BaseFilter;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
=======
use Illuminate\Database\Eloquent\Builder;
>>>>>>> 60a2c9a9 (.)
=======
use Illuminate\Database\Eloquent\Builder;
=======
use Filament\Tables\Columns\Column;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\BaseFilter;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Modules\User\Actions\Passport\RevokeAllUserTokensAction;
use Modules\User\Actions\Passport\RevokeTokenAction;
use Modules\User\Filament\Clusters\Passport;
<<<<<<< HEAD
<<<<<<< HEAD
=======
use Modules\User\Filament\Clusters\Passport\Resources\OauthAccessTokenResource\Pages\EditOauthAccessTokens;
use Modules\User\Filament\Clusters\Passport\Resources\OauthAccessTokenResource\Pages\ListOauthAccessTokens;
use Modules\User\Filament\Clusters\Passport\Resources\OauthAccessTokenResource\Pages\ViewOauthAccessToken;
>>>>>>> 60a2c9a9 (.)
=======
use Modules\User\Filament\Clusters\Passport\Resources\OauthAccessTokenResource\Pages\EditOauthAccessTokens;
use Modules\User\Filament\Clusters\Passport\Resources\OauthAccessTokenResource\Pages\ListOauthAccessTokens;
use Modules\User\Filament\Clusters\Passport\Resources\OauthAccessTokenResource\Pages\ViewOauthAccessToken;
=======
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
use Modules\User\Filament\Resources\UserResource;
use Modules\User\Models\OauthAccessToken;
use Modules\Xot\Filament\Resources\XotBaseResource;

use function Safe\json_encode;

class OauthAccessTokenResource extends XotBaseResource
{
    protected static ?string $cluster = Passport::class;

    protected static ?string $model = OauthAccessToken::class;

<<<<<<< HEAD
<<<<<<< HEAD
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
=======
=======
>>>>>>> 87273113 (.)
    public static function table(\Filament\Tables\Table $table): \Filament\Tables\Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('id')
>>>>>>> 60a2c9a9 (.)
                    ->searchable()
                    ->sortable()
                    ->copyable(),

<<<<<<< HEAD
                TextColumn::make('user.name')
=======
                \Filament\Tables\Columns\TextColumn::make('user.name')
>>>>>>> 60a2c9a9 (.)
                    ->searchable()
                    ->sortable()
                    ->url(function (mixed $record): ?string {
                        if (! $record instanceof OauthAccessToken) {
                            return null;
                        }
                        $user = $record->user;
<<<<<<< HEAD
                        if ($user !== null && method_exists($user, 'exists') && $user->exists) {
=======
                        if (null !== $user && method_exists($user, 'exists') && $user->exists) {
>>>>>>> 60a2c9a9 (.)
                            return UserResource::getUrl('view', ['record' => $user]);
                        }

                        return null;
                    })
                    ->openUrlInNewTab(),

<<<<<<< HEAD
                TextColumn::make('client.name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('scopes')
                    ->limit(30)
                    ->tooltip(function (mixed $state): ?string {
                        if ($state === null) {
=======
                \Filament\Tables\Columns\TextColumn::make('client.name')
                    ->searchable()
                    ->sortable(),

                \Filament\Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                \Filament\Tables\Columns\TextColumn::make('scopes')
                    ->limit(30)
                    ->tooltip(function (mixed $state): ?string {
                        if (null === $state) {
>>>>>>> 60a2c9a9 (.)
                            return null;
                        }
                        if (is_array($state)) {
                            /* @var array<string, mixed> $state */
                            return json_encode($state);
                        }

                        return is_string($state) ? $state : null;
                    }),

<<<<<<< HEAD
                IconColumn::make('revoked')
                    ->boolean()
                    ->color(fn (bool $state): string => $state ? 'danger' : 'success'),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),

                TextColumn::make('expires_at')
=======
                \Filament\Tables\Columns\IconColumn::make('revoked')
                    ->boolean()
                    ->color(fn (bool $state): string => $state ? 'danger' : 'success'),

                \Filament\Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),

                \Filament\Tables\Columns\TextColumn::make('expires_at')
>>>>>>> 60a2c9a9 (.)
                    ->dateTime()
                    ->sortable()
                    ->formatStateUsing(function (mixed $state): string {
                        if ($state instanceof Carbon) {
                            $now = Carbon::now();
                            if ($state->lt($now)) {
                                return $state->format('Y-m-d H:i:s').' (Expired)';
                            }

                            return $state->format('Y-m-d H:i:s');
                        }

                        return 'N/A';
                    }),
            ])
            ->filters([
<<<<<<< HEAD
                Filter::make('revoked')
                    ->query(fn (Builder $query) => $query->where('revoked', true)),

                Filter::make('expired')
                    ->query(fn (Builder $query) => $query->where('expires_at', '<', now())),

                Filter::make('valid')
                    ->query(fn (Builder $query) => $query->where('revoked', false)->where('expires_at', '>', now())),
            ])
            ->recordActions([
                Action::make('revoke')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(function (mixed $record): void {
                        if ($record instanceof Model) {
                            $key = $record->getKey();
                            if ((is_int($key) || is_string($key)) && app(RevokeTokenAction::class)->execute((string) $key)) {
=======
                \Filament\Tables\Filters\Filter::make('revoked')
                    ->query(fn (Builder $query) => $query->where('revoked', true)),

                \Filament\Tables\Filters\Filter::make('expired')
                    ->query(fn (Builder $query) => $query->where('expires_at', '<', now())),

                \Filament\Tables\Filters\Filter::make('valid')
                    ->query(fn (Builder $query) => $query->where('revoked', false)->where('expires_at', '>', now())),
            ])
            ->recordActions([
                \Filament\Actions\Action::make('revoke')
                    ->label(static::trans('actions.revoke.label'))
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading(static::trans('actions.revoke.label'))
                    ->action(function (mixed $record) {
                        if ($record instanceof \Illuminate\Database\Eloquent\Model) {
                            if (app(RevokeTokenAction::class)->execute((string) $record->getKey())) {
>>>>>>> 60a2c9a9 (.)
                                Notification::make()
                                    ->title(static::trans('actions.revoke.success'))
                                    ->success()
                                    ->send();
                            }
                        }
                    })
                    ->visible(fn (mixed $record) => $record instanceof OauthAccessToken && ! $record->revoked),
<<<<<<< HEAD
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkAction::make('revoke_all_for_user')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(function (Collection $records): void {
=======
                \Filament\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                \Filament\Actions\BulkAction::make('revoke_all_for_user')
                    ->label(static::trans('actions.revoke_all_for_user.label'))
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(function (Collection $records) {
>>>>>>> 60a2c9a9 (.)
                        $users = $records->pluck('user_id')->unique();
                        $count = 0;
                        foreach ($users as $userId) {
                            if (is_string($userId) || is_int($userId)) {
                                $count += app(RevokeAllUserTokensAction::class)->execute((string) $userId);
                            }
                        }
                        Notification::make()
                            ->title(static::trans('actions.revoke_all_for_user.success'))
                            ->success()
                            ->send();
                    }),
<<<<<<< HEAD
                DeleteBulkAction::make(),
=======
                \Filament\Actions\DeleteBulkAction::make(),
>>>>>>> 60a2c9a9 (.)
            ])
            ->defaultSort('created_at', 'desc');
    }

    /**
<<<<<<< HEAD
     * @return array<string, Column>
     */
    public function getTableColumns(): array
    {
        return [
            'id' => TextColumn::make('id')
                ->searchable()
                ->sortable()
                ->copyable(),

            'user.name' => TextColumn::make('user.name')
                ->searchable()
                ->sortable()
                ->url(function (mixed $record): ?string {
                    if (! $record instanceof OauthAccessToken) {
                        return null;
                    }
                    $user = $record->user;
                    if ($user !== null && method_exists($user, 'exists') && $user->exists) {
                        return UserResource::getUrl('view', ['record' => $user]);
                    }

                    return null;
                })
                ->openUrlInNewTab(),

            'client.name' => TextColumn::make('client.name')
                ->searchable()
                ->sortable(),

            'name' => TextColumn::make('name')
                ->searchable()
                ->sortable(),

            'scopes' => TextColumn::make('scopes')
                ->limit(30)
                ->tooltip(function (mixed $state): ?string {
                    if ($state === null) {
                        return null;
                    }
                    if (is_array($state)) {
                        /* @var array<string, mixed> $state */
                        return json_encode($state);
                    }

                    return is_string($state) ? $state : null;
                }),

            'revoked' => IconColumn::make('revoked')
                ->boolean()
                ->color(fn (bool $state): string => $state ? 'danger' : 'success'),

            'created_at' => TextColumn::make('created_at')
                ->dateTime()
                ->sortable(),

            'expires_at' => TextColumn::make('expires_at')
                ->dateTime()
                ->sortable()
                ->formatStateUsing(function (mixed $state): string {
                    if ($state instanceof Carbon) {
                        $now = Carbon::now();
                        if ($state->lt($now)) {
                            return $state->format('Y-m-d H:i:s').' (Expired)';
                        }

                        return $state->format('Y-m-d H:i:s');
                    }

                    return 'N/A';
                }),
        ];
    }

    /**
     * @return array<string, BaseFilter>
     */
    public static function getTableFilters(): array
    {
        return [
            'revoked' => Filter::make('revoked')
                ->query(fn (Builder $query) => $query->where('revoked', true)),

            'expired' => Filter::make('expired')
                ->query(fn (Builder $query) => $query->where('expires_at', '<', now())),

            'valid' => Filter::make('valid')
                ->query(fn (Builder $query) => $query->where('revoked', false)->where('expires_at', '>', now())),
        ];
    }

    /**
     * @return array<string, Action|ActionGroup>
     */
    public static function getTableActions(): array
    {
        return [
            'revoke' => Action::make('revoke')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->requiresConfirmation()
                ->action(function (mixed $record): void {
                    if ($record instanceof Model) {
                        $key = $record->getKey();
                        if ((is_int($key) || is_string($key)) && app(RevokeTokenAction::class)->execute((string) $key)) {
                            Notification::make()
                                ->title(static::trans('actions.revoke.success'))
                                ->success()
                                ->send();
                        }
                    }
                })
                ->visible(fn (mixed $record): bool => $record instanceof OauthAccessToken && ! $record->revoked),
            'delete' => DeleteAction::make(),
        ];
    }

    /**
     * @return array<string, BulkAction|ActionGroup>
     */
    public static function getTableBulkActions(): array
    {
        return [
            'revoke_all_for_user' => BulkAction::make('revoke_all_for_user')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->requiresConfirmation()
                ->action(function (Collection $records): void {
                    $users = $records->pluck('user_id')->unique();
                    $count = 0;
                    foreach ($users as $userId) {
                        if (is_string($userId) || is_int($userId)) {
                            $count += app(RevokeAllUserTokensAction::class)->execute((string) $userId);
                        }
                    }
                    Notification::make()
                        ->title(static::trans('actions.revoke_all_for_user.success'))
                        ->success()
                        ->send();
                }),
            'delete' => DeleteBulkAction::make(),
=======
     * @return array<string, \Filament\Resources\Pages\PageRegistration>
     */
    #[\Override]
    public static function getPages(): array
    {
        return [
            'index' => ListOauthAccessTokens::route('/'),
            'view' => ViewOauthAccessToken::route('/{record}'),
            'edit' => EditOauthAccessTokens::route('/{record}/edit'),
<<<<<<< HEAD
>>>>>>> 60a2c9a9 (.)
=======
=======
    /**
     * @return array<string, Column>
     */
    public function getTableColumns(): array
    {
        return [
            'id' => TextColumn::make('id')
                ->searchable()
                ->sortable()
                ->copyable(),

            'user.name' => TextColumn::make('user.name')
                ->searchable()
                ->sortable()
                ->url(function (mixed $record): ?string {
                    if (! $record instanceof OauthAccessToken) {
                        return null;
                    }
                    $user = $record->user;
                    if ($user !== null && method_exists($user, 'exists') && $user->exists) {
                        return UserResource::getUrl('view', ['record' => $user]);
                    }

                    return null;
                })
                ->openUrlInNewTab(),

            'client.name' => TextColumn::make('client.name')
                ->searchable()
                ->sortable(),

            'name' => TextColumn::make('name')
                ->searchable()
                ->sortable(),

            'scopes' => TextColumn::make('scopes')
                ->limit(30)
                ->tooltip(function (mixed $state): ?string {
                    if ($state === null) {
                        return null;
                    }
                    if (is_array($state)) {
                        /* @var array<string, mixed> $state */
                        return json_encode($state);
                    }

                    return is_string($state) ? $state : null;
                }),

            'revoked' => IconColumn::make('revoked')
                ->boolean()
                ->color(fn (bool $state): string => $state ? 'danger' : 'success'),

            'created_at' => TextColumn::make('created_at')
                ->dateTime()
                ->sortable(),

            'expires_at' => TextColumn::make('expires_at')
                ->dateTime()
                ->sortable()
                ->formatStateUsing(function (mixed $state): string {
                    if ($state instanceof Carbon) {
                        $now = Carbon::now();
                        if ($state->lt($now)) {
                            return $state->format('Y-m-d H:i:s').' (Expired)';
                        }

                        return $state->format('Y-m-d H:i:s');
                    }

                    return 'N/A';
                }),
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
        ];
    }

    /**
<<<<<<< HEAD
     * @return array<string, Component>
     */
    #[\Override]
    public static function getFormSchema(): array
    {
        return [
            'oauth_access_token_info' => Section::make('OAuth Access Token Information')
                ->schema([
                    'grid_1' => Grid::make(2)
                        ->schema([
                            'user_id' => Select::make('user_id')
                                ->relationship('user', 'name')
                                ->searchable(),
                            'client_id' => Select::make('client_id')
                                ->relationship('client', 'name')
                                ->searchable()
                                ->required(),
                        ]),

                    'grid_2' => Grid::make(2)
                        ->schema([
                            'name' => TextInput::make('name')
                                ->maxLength(255),
                            'scopes' => TextInput::make('scopes'),
                        ]),
                ]),
=======
     * @return array<string, BaseFilter>
     */
    public static function getTableFilters(): array
    {
        return [
            'revoked' => Filter::make('revoked')
                ->query(fn (Builder $query) => $query->where('revoked', true)),

            'expired' => Filter::make('expired')
                ->query(fn (Builder $query) => $query->where('expires_at', '<', now())),

            'valid' => Filter::make('valid')
                ->query(fn (Builder $query) => $query->where('revoked', false)->where('expires_at', '>', now())),
        ];
    }

    /**
     * @return array<string, Action|ActionGroup>
     */
    public static function getTableActions(): array
    {
        return [
            'revoke' => Action::make('revoke')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->requiresConfirmation()
                ->action(function (mixed $record): void {
                    if ($record instanceof Model) {
                        $key = $record->getKey();
                        if ((is_int($key) || is_string($key)) && app(RevokeTokenAction::class)->execute((string) $key)) {
                            Notification::make()
                                ->title(static::trans('actions.revoke.success'))
                                ->success()
                                ->send();
                        }
                    }
                })
                ->visible(fn (mixed $record): bool => $record instanceof OauthAccessToken && ! $record->revoked),
            'delete' => DeleteAction::make(),
        ];
    }

    /**
     * @return array<string, BulkAction|ActionGroup>
     */
    public static function getTableBulkActions(): array
    {
        return [
            'revoke_all_for_user' => BulkAction::make('revoke_all_for_user')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->requiresConfirmation()
                ->action(function (Collection $records): void {
                    $users = $records->pluck('user_id')->unique();
                    $count = 0;
                    foreach ($users as $userId) {
                        if (is_string($userId) || is_int($userId)) {
                            $count += app(RevokeAllUserTokensAction::class)->execute((string) $userId);
                        }
                    }
                    Notification::make()
                        ->title(static::trans('actions.revoke_all_for_user.success'))
                        ->success()
                        ->send();
                }),
            'delete' => DeleteBulkAction::make(),
>>>>>>> 2024e2e7 (.)
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with(['user', 'client']);
    }
}
