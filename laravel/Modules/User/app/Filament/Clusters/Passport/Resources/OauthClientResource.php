<?php

declare(strict_types=1);

namespace Modules\User\Filament\Clusters\Passport\Resources;

use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\Field;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Laravel\Passport\Passport as LaravelPassport;
use Modules\User\Filament\Clusters\Passport;
use Modules\User\Filament\Clusters\Passport\Resources\OauthClientResource\Pages\CreateOauthClient;
use Modules\User\Filament\Clusters\Passport\Resources\OauthClientResource\Pages\EditOauthClient;
use Modules\User\Filament\Clusters\Passport\Resources\OauthClientResource\Pages\ListOauthClients;
use Modules\User\Filament\Clusters\Passport\Resources\OauthClientResource\Pages\ViewOauthClient;
use Modules\User\Models\OauthClient;
use Modules\Xot\Filament\Resources\XotBaseResource;
use Webmozart\Assert\Assert;

class OauthClientResource extends XotBaseResource
{
    protected static ?string $cluster = Passport::class;

    /**
     * Get the model class for the resource from Passport.
     *
     * @return class-string<Model>
     */
    public static function getModel(): string
    {
        $model = LaravelPassport::clientModel();
        if (! class_exists($model)) {
            return OauthClient::class;
        }

        Assert::subclassOf($model, Model::class);

        /* @var class-string<Model> $model */
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
     *
     * @return array<int, never>
     */
    protected static function getResourceFormComponents(): array
    {
        return [];
    }
}
