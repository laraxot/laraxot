<?php

declare(strict_types=1);

namespace Modules\User\Filament\Resources;

<<<<<<< HEAD
<<<<<<< HEAD
=======
=======
>>>>>>> 87273113 (.)
use Filament\Schemas\Components\Component;
use Override;
use Modules\User\Filament\Resources\SocialProviderResource\Pages\ListSocialProviders;
use Modules\User\Filament\Resources\SocialProviderResource\Pages\CreateSocialProvider;
use Modules\User\Filament\Resources\SocialProviderResource\Pages\ViewSocialProvider;
use Modules\User\Filament\Resources\SocialProviderResource\Pages\EditSocialProvider;
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
<<<<<<< HEAD
<<<<<<< HEAD
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
use Modules\User\Filament\Resources\SocialProviderResource\Pages\CreateSocialProvider;
use Modules\User\Filament\Resources\SocialProviderResource\Pages\EditSocialProvider;
use Modules\User\Filament\Resources\SocialProviderResource\Pages\ListSocialProviders;
use Modules\User\Filament\Resources\SocialProviderResource\Pages\ViewSocialProvider;
=======
use Modules\User\Filament\Resources\SocialProviderResource\Pages;
>>>>>>> f548be94 (.)
=======
use Modules\User\Filament\Resources\SocialProviderResource\Pages;
=======
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
use Modules\User\Filament\Resources\SocialProviderResource\Pages\CreateSocialProvider;
use Modules\User\Filament\Resources\SocialProviderResource\Pages\EditSocialProvider;
use Modules\User\Filament\Resources\SocialProviderResource\Pages\ListSocialProviders;
use Modules\User\Filament\Resources\SocialProviderResource\Pages\ViewSocialProvider;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
use Modules\User\Models\SocialProvider;
use Modules\Xot\Filament\Resources\XotBaseResource;

/**
 * @property SocialProvider $record
 *                                  -------
 */
class SocialProviderResource extends XotBaseResource
{
<<<<<<< HEAD
<<<<<<< HEAD
    protected static ?string $model = SocialProvider::class;
=======
    protected static null|string $model = SocialProvider::class;
>>>>>>> f548be94 (.)
=======
    protected static null|string $model = SocialProvider::class;
=======
    protected static ?string $model = SocialProvider::class;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)

    /**
     * @return array<string, Component>
     */
<<<<<<< HEAD
<<<<<<< HEAD
    #[\Override]
    public static function getFormSchema(): array
    {
        return [
            'env_guide' => TextEntry::make('env_guide')
                ->hiddenLabel()
                ->state(__('fields.env_guide.content'))
                ->columnSpanFull(),
            'name' => TextInput::make('name')
                ->required()
                ->maxLength(255),
            'scopes' => KeyValue::make('scopes')
                // ->placeholder(static::trans('fields.scopes.placeholder'))
                ->helperText(__('fields.scopes.helper_text')),
            'client_id' => TextInput::make('client_id')
                ->maxLength(255)
                ->placeholder(__('fields.client_id.placeholder'))
                ->helperText(__('fields.client_id.helper_text')),
            'client_secret' => TextInput::make('client_secret')
                ->maxLength(1024)
                ->placeholder(__('fields.client_secret.placeholder'))
                ->helperText(__('fields.client_secret.helper_text')),
            'redirect' => TextInput::make('redirect')
                ->required()
                ->maxLength(255)
                ->placeholder(__('fields.redirect.placeholder'))
                ->helperText(__('fields.redirect.helper_text')),
            'parameters' => KeyValue::make('parameters')
                // ->placeholder(static::trans('fields.parameters.placeholder'))
                ->helperText(__('fields.parameters.helper_text')),
            'additional_params' => Textarea::make('additional_params'),
            'stateless' => Toggle::make('stateless')->helperText(__('fields.stateless.helper_text')),
            'active' => Toggle::make('active')->helperText(__('fields.active.helper_text')),
            'socialite' => Toggle::make('socialite')->helperText(__('fields.socialite.helper_text')),
            'enabled' => Toggle::make('enabled'),
            'svg' => Textarea::make('svg')
                ->columnSpanFull()
                ->placeholder(__('fields.svg.placeholder'))
                ->helperText(__('fields.svg.helper_text')),
        ];
    }

    #[\Override]
=======
=======
>>>>>>> 87273113 (.)
    #[Override]
    public static function getFormSchema(): array
    {
        return [
            'name' => TextInput::make('name')
                ->required()
                ->maxLength(255)
                ->placeholder(static::trans('fields.name.placeholder'))
                ->helperText(static::trans('fields.name.helper_text')),
            'scopes' => KeyValue::make('scopes')
                // ->placeholder(static::trans('fields.scopes.placeholder'))
                ->helperText(static::trans('fields.scopes.helper_text')),
            'client_id' => TextInput::make('client_id')
                ->required()
                ->maxLength(255)
                ->placeholder(static::trans('fields.client_id.placeholder'))
                ->helperText(static::trans('fields.client_id.helper_text')),
            'client_secret' => TextInput::make('client_secret')
                ->required()
                ->maxLength(1024)
                ->placeholder(static::trans('fields.client_secret.placeholder'))
                ->helperText(static::trans('fields.client_secret.helper_text')),
            'redirect' => TextInput::make('redirect')
                ->required()
                ->maxLength(255)
                ->placeholder(static::trans('fields.redirect.placeholder'))
                ->helperText(static::trans('fields.redirect.helper_text')),
            'parameters' => KeyValue::make('parameters')
                // ->placeholder(static::trans('fields.parameters.placeholder'))
                ->helperText(static::trans('fields.parameters.helper_text')),
            'additional_params' => Textarea::make('additional_params'),
            'stateless' => Toggle::make('stateless')->helperText(static::trans('fields.stateless.helper_text')),
            'active' => Toggle::make('active')->helperText(static::trans('fields.active.helper_text')),
            'socialite' => Toggle::make('socialite')->helperText(static::trans('fields.socialite.helper_text')),
            'enabled' => Toggle::make('enabled'),
            'svg' => Textarea::make('svg')
                ->columnSpanFull()
                ->placeholder(static::trans('fields.svg.placeholder'))
                ->helperText(static::trans('fields.svg.helper_text')),
        ];
    }

    #[Override]
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
    #[\Override]
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    public static function getRelations(): array
    {
        return [];
    }

<<<<<<< HEAD
<<<<<<< HEAD
    #[\Override]
=======
    #[Override]
>>>>>>> f548be94 (.)
=======
    #[Override]
=======
    #[\Override]
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    public static function getPages(): array
    {
        return [
            'index' => ListSocialProviders::route('/'),
            'create' => CreateSocialProvider::route('/create'),
            'view' => ViewSocialProvider::route('/{record}'),
            'edit' => EditSocialProvider::route('/{record}/edit'),
        ];
    }
}
