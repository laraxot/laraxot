<?php

declare(strict_types=1);

namespace Modules\User\Filament\Resources\UserResource\RelationManagers;

<<<<<<< HEAD
<<<<<<< HEAD
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\Column;
use Filament\Tables\Columns\TextColumn;
use Modules\Xot\Filament\Resources\RelationManagers\XotBaseRelationManager;

/**
 * Class SocialiteUsersRelationManager.
 */
class SocialiteUsersRelationManager extends XotBaseRelationManager
{
    protected static string $relationship = 'socialiteUsers';

    protected static ?string $recordTitleAttribute = 'provider';

    /**
     * @return array<string, Column>
     */
    #[\Override]
    public function getTableColumns(): array
    {
        return [
            'provider' => TextColumn::make('provider')
                ->sortable()
                ->searchable(),
            'provider_id' => TextColumn::make('provider_id')
                ->searchable(),
            'provider_avatar' => TextColumn::make('provider_avatar')
                ->formatStateUsing(function (mixed $state): string {
                    if (\is_scalar($state) && $state) {
                        /** @phpstan-var view-string $viewString */
                        $viewString = 'filament.components.avatar';

                        return view($viewString, ['url' => (string) $state])->render();
                    }

                    return 'No Avatar';
                })
                ->html(),
            'created_at' => TextColumn::make('created_at')
                ->dateTime()
                ->sortable(),
        ];
    }

    /**
     * @return array<string, Action>
     */
    #[\Override]
    public function getTableActions(): array
    {
        return [
            'edit' => EditAction::make(),
            'delete' => DeleteAction::make(),
        ];
    }
=======
=======
>>>>>>> 87273113 (.)
use Filament\Schemas\Components\Component;
use Override;
use Filament\Forms\Components\TextInput;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Modules\User\Models\SocialiteUser;
use Modules\Xot\Filament\Resources\RelationManagers\XotBaseRelationManager;
use Modules\Xot\Filament\Traits\HasXotTable;

/**
 * Class Modules\User\Filament\Resources\UserResource\RelationManagers\SocialiteUsersRelationManager.
 */
class SocialiteUsersRelationManager extends XotBaseRelationManager
{
    use HasXotTable;

    protected static string $relationship = 'socialiteUsers';

    /**
     * Configure the form schema for managing Socialite User data.
     */
    /**
     * Define form fields in a dedicated method for reusability.
     *
     * @return array<Component>
     */
    #[Override]
    public function getFormSchema(): array
    {
        return [
            TextInput::make('provider')
                ->required()
                ->maxLength(255)
                ->placeholder(__('Enter provider name, e.g., Google, Facebook')),
            TextInput::make('provider_id')
                ->required()
                ->maxLength(255)
                ->placeholder(__('Enter the provider ID for the user')),
            TextInput::make('name')
                ->maxLength(255)
                ->placeholder(__('User’s name associated with the provider')),
            TextInput::make('email')
                ->email()
                ->maxLength(255)
                ->placeholder(__('User’s email associated with the provider')),
            TextInput::make('avatar')
                ->url()
                ->maxLength(512)
                ->placeholder(__('URL of the user’s avatar image')),
        ];
    }

    /**
     * Define table columns in a separate, strongly-typed method.
     *
     * @return array<TextColumn|ImageColumn>
     */
    #[Override]
    public function getTableColumns(): array
    {
        return [
            TextColumn::make('provider')->searchable(),
            TextColumn::make('provider_id')->searchable(),
            TextColumn::make('name')->searchable(),
            TextColumn::make('email')->searchable(),
            ImageColumn::make('avatar')->size(40),
        ];
    }

    //  * Query scope to apply conditions to the relation manager.
    // protected function applyTableQueryScope(Builder $query): Builder
    // {
    //     return $query->when(
    //         in_array(SoftDeletingScope::class, class_uses_recursive(SocialiteUser::class)),
    //         fn (Builder $query) => $query->withTrashed()
    //     );
    // }
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\Column;
use Filament\Tables\Columns\TextColumn;
use Modules\Xot\Filament\Resources\RelationManagers\XotBaseRelationManager;

/**
 * Class SocialiteUsersRelationManager.
 */
class SocialiteUsersRelationManager extends XotBaseRelationManager
{
    protected static string $relationship = 'socialiteUsers';

    protected static ?string $recordTitleAttribute = 'provider';

    /**
     * @return array<string, Column>
     */
    #[\Override]
    public function getTableColumns(): array
    {
        return [
            'provider' => TextColumn::make('provider')
                ->sortable()
                ->searchable(),
            'provider_id' => TextColumn::make('provider_id')
                ->searchable(),
            'provider_avatar' => TextColumn::make('provider_avatar')
                ->formatStateUsing(function (mixed $state): string {
                    if (\is_scalar($state) && $state) {
                        /** @phpstan-var view-string $viewString */
                        $viewString = 'filament.components.avatar';

                        return view($viewString, ['url' => (string) $state])->render();
                    }

                    return 'No Avatar';
                })
                ->html(),
            'created_at' => TextColumn::make('created_at')
                ->dateTime()
                ->sortable(),
        ];
    }

    /**
     * @return array<string, Action>
     */
    #[\Override]
    public function getTableActions(): array
    {
        return [
            'edit' => EditAction::make(),
            'delete' => DeleteAction::make(),
        ];
    }
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
}
