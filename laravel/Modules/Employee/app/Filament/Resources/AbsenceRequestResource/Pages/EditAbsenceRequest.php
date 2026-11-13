<?php

declare(strict_types=1);

namespace Modules\Employee\Filament\Resources\AbsenceRequestResource\Pages;

use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Modules\Employee\Filament\Resources\AbsenceRequestResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseEditRecord;

class EditAbsenceRequest extends XotBaseEditRecord
{
    protected static string $resource = AbsenceRequestResource::class;

    /**
     * @return array<string, Action>
     */
    protected function getHeaderActions(): array
    {
        return [
            'delete' => DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getRecordReturnUrl($this->getResourceUrl('index'));
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Il form non e' una fonte fidata: chi non gestisce le richieste di tutti non cambia ne' lo stato ne' il dipendente.
        if (! AbsenceRequestResource::canManageAll()) {
            unset($data['status'], $data['user_id']);
        }

        return $data;
    }
}
