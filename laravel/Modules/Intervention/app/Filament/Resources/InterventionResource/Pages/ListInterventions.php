<?php

declare(strict_types=1);

namespace Modules\Intervention\Filament\Resources\InterventionResource\Pages;

use App\Support\AccountFeatures;
use Filament\Actions;
use Illuminate\Support\Facades\Auth;
use Modules\Intervention\Filament\Resources\InterventionResource;
use Modules\User\Models\User;
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;

final class ListInterventions extends XotBaseListRecords
{
    protected static string $resource = InterventionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            'create' => Actions\CreateAction::make(),
        ];
    }

    public static function canCreate(): bool
    {
        if (request()->is('operator/*')) {
            $user = Auth::user();

            return $user instanceof User
                && AccountFeatures::hasFeature($user, 'operator.create_intervention_from_list', true)
                && $user->can('Create:Intervention');
        }

        return InterventionResource::canCreate();
    }
}
