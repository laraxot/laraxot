<?php

declare(strict_types=1);

namespace Modules\Intervention\Filament\Resources\InterventionResource\Pages;

use App\Support\AccountFeatures;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;
use Modules\Intervention\Filament\Resources\InterventionResource;

final class ListInterventions extends ListRecords
{
    protected static string $resource = InterventionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public static function canCreate(): bool
    {
        if (request()->is('operator/*')) {
            $user = Auth::user();

            return $user instanceof \App\Models\User
                && AccountFeatures::hasFeature($user, 'operator.create_intervention_from_list', true)
                && $user->can('Create:Intervention');
        }

        return parent::canCreate();
    }
}
