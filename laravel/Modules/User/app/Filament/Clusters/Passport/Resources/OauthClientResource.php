<?php

declare(strict_types=1);

namespace Modules\User\Filament\Clusters\Passport\Resources;

use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\Field;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
<<<<<<< HEAD
<<<<<<< HEAD
use Illuminate\Database\Eloquent\Model;
=======
>>>>>>> 60a2c9a9 (.)
=======
=======
use Illuminate\Database\Eloquent\Model;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
use Illuminate\Support\Str;
use Laravel\Passport\Passport as LaravelPassport;
use Modules\User\Filament\Clusters\Passport;
use Modules\User\Filament\Clusters\Passport\Resources\OauthClientResource\Pages\CreateOauthClient;
use Modules\User\Filament\Clusters\Passport\Resources\OauthClientResource\Pages\EditOauthClient;
use Modules\User\Filament\Clusters\Passport\Resources\OauthClientResource\Pages\ListOauthClients;
use Modules\User\Filament\Clusters\Passport\Resources\OauthClientResource\Pages\ViewOauthClient;
<<<<<<< HEAD
<<<<<<< HEAD
use Modules\User\Models\OauthClient;
=======
>>>>>>> 60a2c9a9 (.)
=======
=======
use Modules\User\Models\OauthClient;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
use Modules\Xot\Filament\Resources\XotBaseResource;
use Webmozart\Assert\Assert;

class OauthClientResource extends XotBaseResource
{
    protected static ?string $cluster = Passport::class;

<<<<<<< HEAD
    // use HasResourceFormComponents;

    /**
     * Get the form schema for the resource (XotBaseResource pattern).
     *
     * @return array<string, Field>
     */
    /**
     * @return array<string, Field>
     */
    public static function getFormSchema(): array
    {
        return [
            'name' => TextInput::make('name')
                ->unique('oauth_clients', 'name')
                ->required()
                ->maxLength(255),
            'user_id' => Select::make('user_id')
                ->relationship('user', 'name')
                ->searchable(),
            'redirect' => TextInput::make('redirect')
                ->url()
                ->maxLength(2000),
            'provider' => TextInput::make('provider')
                ->maxLength(255),
        ];
    }

    /**
     * Build the table for the resource.
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->formatStateUsing(fn (string $state): string => Str::headline($state))
                    ->searchable(),
                TextColumn::make('owner.name')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime(),
                TextColumn::make('updated_at')
                    ->dateTime(),
            ])
            ->toolbarActions([
                DeleteBulkAction::make(),
            ]);
    }

    /**
     * Get the model class for the resource from Passport.
     *
<<<<<<< HEAD
     * @return class-string<Model>
=======
     * @return class-string<\Illuminate\Database\Eloquent\Model>
<<<<<<< HEAD
>>>>>>> 60a2c9a9 (.)
=======
=======
    /**
     * Get the model class for the resource from Passport.
     *
     * @return class-string<Model>
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
     */
    public static function getModel(): string
    {
        $model = LaravelPassport::clientModel();
<<<<<<< HEAD
<<<<<<< HEAD
        if (! class_exists($model)) {
            return OauthClient::class;
        }

        Assert::subclassOf($model, Model::class);

        /* @var class-string<Model> $model */
=======
=======
>>>>>>> 87273113 (.)
        // @phpstan-ignore-next-line
        if (! class_exists($model)) {
            return \Modules\User\Models\OauthClient::class;
        }

        Assert::subclassOf($model, \Illuminate\Database\Eloquent\Model::class);

        /* @var class-string<\Illuminate\Database\Eloquent\Model> $model */
<<<<<<< HEAD
>>>>>>> 60a2c9a9 (.)
=======
=======
        if (! class_exists($model)) {
            return OauthClient::class;
        }

        Assert::subclassOf($model, Model::class);

        /* @var class-string<Model> $model */
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
        return $model;
    }

    public static function getPages(): array
    {
        return [
            'index' => ListOauthClients::route('/'),
            'view' => ViewOauthClient::route('/{record}'),
            'edit' => EditOauthClient::route('/{record}/edit'),
            'create' => CreateOauthClient::route('/create'),
        ];
    }

    /**
     * Check if resource form components are enabled.
     */
    protected static function isResourceFormComponentsEnabled(): bool
    {
        return false;
    }

    /**
     * Get resource form components.
<<<<<<< HEAD
<<<<<<< HEAD
     *
     * @return array<int, never>
=======
>>>>>>> 60a2c9a9 (.)
=======
=======
     *
     * @return array<int, never>
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
     */
    protected static function getResourceFormComponents(): array
    {
        return [];
    }
}
