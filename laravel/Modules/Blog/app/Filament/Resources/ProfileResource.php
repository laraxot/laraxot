<?php

declare(strict_types=1);

namespace Modules\Blog\Filament\Resources;

use Filament\Resources\Pages\PageRegistration;
// use LaraZeus\SpatieTranslatable\Resources\Concerns\Translatable;
use Modules\Blog\Filament\Resources\ProfileResource\Pages\CreateProfile;
use Modules\Blog\Filament\Resources\ProfileResource\Pages\EditProfile;
use Modules\Blog\Filament\Resources\ProfileResource\Pages\ListProfiles;
use Modules\Blog\Filament\Resources\ProfileResource\Pages\ViewProfile;
use Modules\Blog\Filament\Resources\ProfileResource\RelationManagers\RatingMorphsRelationManager;
use Modules\Blog\Models\Profile;
use Modules\User\Filament\Resources\BaseProfileResource;

class ProfileResource extends BaseProfileResource
{
    // use Translatable; // Temporarily disabled until lara-zeus package is working

    protected static ?string $model = Profile::class;

    /**
     * @return array<int, class-string>
     */
    public static function getRelations(): array
    {
        return [
            RatingMorphsRelationManager::class,
        ];
    }

    /**
     * @return array<string, PageRegistration>
     */
    public static function getPages(): array
    {
        return [
            'index' => ListProfiles::route('/'),
            'create' => CreateProfile::route('/create'),
            'edit' => EditProfile::route('/{record}/edit'),
            'view' => ViewProfile::route('/{record}'),
            // 'getcredits' => Pages\GetCreditProfile::route('/{record}/getcredits'),
        ];
    }
}
