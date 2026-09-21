<?php

declare(strict_types=1);

namespace Modules\Gdpr\Filament\Clusters\Profile\Resources;

use Modules\Gdpr\Filament\Clusters\Profile as ProfileCluster;
use Modules\Gdpr\Filament\Clusters\Profile\Resources\ProfileResource\Pages\CreateProfile;
use Modules\Gdpr\Filament\Clusters\Profile\Resources\ProfileResource\Pages\EditProfile;
use Modules\Gdpr\Filament\Clusters\Profile\Resources\ProfileResource\Pages\ListProfiles;
use Modules\Gdpr\Models\Profile;
use Modules\Xot\Filament\Resources\XotBaseResource;

class ProfileResource extends XotBaseResource
{
    protected static ?string $model = Profile::class;

    protected static ?string $cluster = ProfileCluster::class;

    /**
     * Schema legacy del form: la sorgente di verità è ProfileForm::getFormSchema().
     *
     * @return array<string, \Filament\Schemas\Components\Component>
     */
    public function getFormSchemaOld(): array
    {
        return [];
    }

    #[\Override]
    public static function getPages(): array
    {
        return [
            'index' => ListProfiles::route('/'),
            'create' => CreateProfile::route('/create'),
            'edit' => EditProfile::route('/{record}/edit'),
        ];
    }
}
