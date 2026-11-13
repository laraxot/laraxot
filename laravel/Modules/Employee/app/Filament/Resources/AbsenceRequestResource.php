<?php

declare(strict_types=1);

namespace Modules\Employee\Filament\Resources;

use Filament\Widgets\Widget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\Employee\Filament\Resources\AbsenceRequestResource\Pages;
use Modules\Employee\Filament\Resources\AbsenceRequestResource\Schemas\AbsenceRequestForm;
use Modules\Employee\Models\AbsenceRequest;
use Modules\Xot\Filament\Resources\XotBaseResource;
use Override;

class AbsenceRequestResource extends XotBaseResource
{
    protected static ?string $model = AbsenceRequest::class;

    

    /**
     * Chi non gestisce le richieste di tutti (vedi `AbsenceRequestPolicy::manage()`) vede solo le proprie, in qualunque pannello.
     * Il vecchio controllo sul percorso `admin/*` non reggeva le richieste Livewire, che arrivano da `livewire/update`.
     */
    #[Override]
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if (! static::canManageAll()) {
            $query->where('user_id', (string) Auth::id());
        }

        return $query;
    }

    public static function canManageAll(): bool
    {
        return Gate::allows('manage', AbsenceRequest::class);
    }

    
}
