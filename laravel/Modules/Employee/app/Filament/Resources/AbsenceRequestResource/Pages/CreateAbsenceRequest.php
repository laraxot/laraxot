<?php

declare(strict_types=1);

namespace Modules\Employee\Filament\Resources\AbsenceRequestResource\Pages;

use Illuminate\Support\Facades\Auth;
use Modules\Employee\Filament\Resources\AbsenceRequestResource;
use Modules\Employee\Models\AbsenceRequest;
use Modules\Xot\Filament\Resources\Pages\XotBaseCreateRecord;

class CreateAbsenceRequest extends XotBaseCreateRecord
{
    protected static string $resource = AbsenceRequestResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getRecordReturnUrl($this->getResourceUrl('index'));
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Il form non e' una fonte fidata: chi non gestisce le richieste di tutti ne apre solo di proprie e in attesa.
        if (! AbsenceRequestResource::canManageAll()) {
            $data['status'] = \Modules\Employee\Enums\AbsenceRequestStatusEnum::PENDING->value;
            $data['user_id'] = Auth::id();
        }

        if (! isset($data['status'])) {
            $data['status'] = \Modules\Employee\Enums\AbsenceRequestStatusEnum::PENDING->value;
        }

        if (! isset($data['user_id'])) {
            $data['user_id'] = Auth::id();
        }

        return $data;
    }
}
