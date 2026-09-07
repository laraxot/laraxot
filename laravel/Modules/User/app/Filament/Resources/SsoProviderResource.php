<?php

declare(strict_types=1);

namespace Modules\User\Filament\Resources;

use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Pages\PageRegistration;
<<<<<<< HEAD
<<<<<<< HEAD
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Components\Component;
=======
>>>>>>> 60a2c9a9 (.)
=======
=======
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Components\Component;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
use Modules\User\Filament\Resources\SsoProviderResource\Pages;
use Modules\User\Filament\Resources\SsoProviderResource\RelationManagers\UsersRelationManager;
use Modules\User\Models\SsoProvider;
use Modules\Xot\Filament\Resources\XotBaseResource;

class SsoProviderResource extends XotBaseResource
{
    protected static ?string $model = SsoProvider::class;

<<<<<<< HEAD
    /**
<<<<<<< HEAD
     * @return array<string, Component>
=======
     * @return array<string, \Filament\Support\Components\Component>
>>>>>>> 60a2c9a9 (.)
     */
    #[\Override]
    public static function getFormSchema(): array
    {
        return [
            'name' => TextInput::make('name')
                ->required()
                ->maxLength(255),
            'display_name' => TextInput::make('display_name')
                ->required()
                ->maxLength(255),
            'type' => Select::make('type')
                ->options([
                    'saml' => 'SAML',
                    'oidc' => 'OIDC',
                    'oauth' => 'OAuth',
                ])
                ->required(),
            'entity_id' => TextInput::make('entity_id')->maxLength(255),
            'client_id' => TextInput::make('client_id')->maxLength(255),
            'client_secret' => TextInput::make('client_secret')
                ->password()
                ->maxLength(255),
            'redirect_url' => TextInput::make('redirect_url')
                ->url()
                ->maxLength(255),
            'metadata_url' => TextInput::make('metadata_url')
                ->url()
                ->maxLength(255),
            'scopes' => Textarea::make('scopes')->rows(2),
            'settings' => KeyValue::make('settings'),
            'domain_whitelist' => KeyValue::make('domain_whitelist'),
            'role_mapping' => KeyValue::make('role_mapping'),
            'is_active' => Toggle::make('is_active'),
        ];
    }

    /**
<<<<<<< HEAD
     * @return array<string, class-string<RelationManager>>
=======
     * @return array<string, class-string<\Filament\Resources\RelationManagers\RelationManager>>
<<<<<<< HEAD
>>>>>>> 60a2c9a9 (.)
=======
=======
    

    /**
     * @return array<string, class-string<RelationManager>>
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
     */
    #[\Override]
    public static function getRelations(): array
    {
        return [
            'users' => UsersRelationManager::class,
        ];
    }

    /**
     * @return array<string, PageRegistration>
     */
    #[\Override]
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSsoProviders::route('/'),
            'create' => Pages\CreateSsoProvider::route('/create'),
            'view' => Pages\ViewSsoProvider::route('/{record}'),
            'edit' => Pages\EditSsoProvider::route('/{record}/edit'),
        ];
    }
}
