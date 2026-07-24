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
        /** @var string */
        return $this->getResource()::getUrl('index');
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (! isset($data['status'])) {
            $data['status'] = AbsenceRequest::STATUS_PENDING;
        }

        if (! isset($data['user_id'])) {
            $data['user_id'] = Auth::id();
        }

        return $data;
    }
}
