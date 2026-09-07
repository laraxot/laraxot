<?php

declare(strict_types=1);

namespace Modules\Employee\Filament\Resources;

use Filament\Widgets\Widget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Modules\Employee\Filament\Resources\AbsenceRequestResource\Pages;
use Modules\Employee\Filament\Resources\AbsenceRequestResource\Schemas\AbsenceRequestForm;
use Modules\Employee\Models\AbsenceRequest;
use Modules\Xot\Filament\Resources\XotBaseResource;
use Override;

class AbsenceRequestResource extends XotBaseResource
{
    protected static ?string $model = AbsenceRequest::class;

    

    /**
     * Scope the query: non-admin panels only see their own absence requests.
     */
    #[Override]
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if (! request()->is('admin/*')) {
            $userId = Auth::id();
            if ($userId !== null) {
                $query->where('user_id', $userId);
            }
        }

        return $query;
    }

    
}
