<?php

declare(strict_types=1);

namespace Modules\HR\Filament\Resources\AbsenceRequestResource\Pages;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Modules\HR\Filament\Resources\AbsenceRequestResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseCreateRecord;

final class CreateAbsenceRequest extends XotBaseCreateRecord
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
