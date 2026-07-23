<?php

declare(strict_types=1);

namespace Modules\HR\Filament\Resources\AbsenceRequestResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Modules\HR\Filament\Resources\AbsenceRequestResource;

final class CreateAbsenceRequest extends CreateRecord
{
    protected static string $resource = AbsenceRequestResource::class;

    /**
     * Ensure operator requests are always owned by the logged-in user.
     *
     * @param  array<string, mixed>  $data
     */
    protected function handleRecordCreation(array $data): Model
    {
        if (request()->is('operator/*')) {
            $data['user_id'] = (int) Auth::id();
            $data['status'] = 'pending';
        }

        return parent::handleRecordCreation($data);
    }
}

