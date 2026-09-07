<?php

declare(strict_types=1);

namespace Modules\User\Filament\Resources;

// // use LaraZeus\SpatieTranslatable\Resources\Concerns\Translatable; // Temporaneamente commentato per compatibilità Filament 4.x
<<<<<<< HEAD
<<<<<<< HEAD
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Modules\User\Filament\Resources\BaseProfileResource\Pages\ListProfiles;
use Modules\User\Models\BaseProfile;
=======
=======
>>>>>>> 87273113 (.)
use Override;
use Filament\Forms\Components\TextInput;
use Modules\User\Filament\Resources\BaseProfileResource\Pages\ListProfiles;
use Filament\Forms;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Illuminate\Database\Eloquent\Collection;
use Modules\User\Filament\Resources\BaseProfileResource\Pages;
use Modules\User\Models\BaseProfile;
use Modules\Xot\Filament\Resources\RelationManagers\XotBaseRelationManager;
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Modules\User\Filament\Resources\BaseProfileResource\Pages\ListProfiles;
use Modules\User\Models\BaseProfile;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
use Modules\Xot\Filament\Resources\XotBaseResource;

abstract class BaseProfileResource extends XotBaseResource
{
    // // use Translatable; // Temporaneamente commentato per compatibilità Filament 4.x // Temporaneamente commentato per compatibilità Filament 4.x

<<<<<<< HEAD
<<<<<<< HEAD
    protected static ?string $model = BaseProfile::class;

    #[\Override]
=======
=======
>>>>>>> 87273113 (.)
    protected static null|string $model = BaseProfile::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-users';

    #[Override]
>>>>>>> f548be94 (.)
    public static function getFormSchema(): array
    {
        return [
            // Forms\Components\TextInput::make('user_id'),
            // Forms\Components\TextInput::make('user_id')->readonly(),
            'user_name' => TextInput::make('user.name'),
            'email' => TextInput::make('email'),
            'first_name' => TextInput::make('first_name'),
            'last_name' => TextInput::make('last_name'),
            'photo_profile' => SpatieMediaLibraryFileUpload::make('photo_profile')
                // ->image()
                // ->maxSize(5000)
                // ->multiple()
                // ->enableReordering()
                ->openable()
                ->downloadable()
                ->columnSpanFull()
                // ->collection('avatars')
                // ->conversion('thumbnail')
                ->disk('uploads')
                ->directory('photos')
                ->collection('photo_profile'),
        ];
    }

<<<<<<< HEAD
    #[\Override]
=======
    #[Override]
    public static function getRelations(): array
    {
        return [];
    }

    #[Override]
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
    protected static ?string $model = BaseProfile::class;

    #[\Override]
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    public static function getPages(): array
    {
        return [
            'index' => ListProfiles::route('/'),
            // 'create' => Pages\CreateProfile::route('/create'),
            // 'edit' => Pages\EditProfile::route('/{record}/edit'),
            // 'getcredits' => Pages\GetCreditProfile::route('/{record}/getcredits'),
        ];
    }
}
