<?php

declare(strict_types=1);

namespace Modules\Intervention\Filament\Resources\InterventionResource\Pages;

use Filament\Actions;
use Modules\Intervention\Filament\Concerns\ResolvesCalendarReturnUrl;
use Modules\Intervention\Filament\Resources\InterventionResource;
use Modules\Intervention\Models\Intervention;
use Modules\Intervention\Support\DailyNoteHtml;
use Modules\Xot\Filament\Resources\Pages\XotBaseEditRecord;

final class EditIntervention extends XotBaseEditRecord
{
    use ResolvesCalendarReturnUrl;

    protected static string $resource = InterventionResource::class;

    protected function getRedirectUrl(): ?string
    {
        return $this->getRequestedReturnUrl() ?? parent::getRedirectUrl();
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        if ($this->record instanceof Intervention) {
            $data['notes_html'] = $this->record->notes_html ?: DailyNoteHtml::legacyToHtml((string) $this->record->notes);
            $data['notes_photo_paths'] = $this->record->media()
                ->where('kind', 'photo')
                ->where('meta->scope', 'intervention_note')
                ->orderBy('id')
                ->pluck('path')
                ->all();
        }

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        return InterventionResource::extractPersistableData(
            InterventionResource::normalizeFormData($data)
        );
    }

    protected function afterSave(): void
    {
        if ($this->record instanceof Intervention) {
            InterventionResource::syncSupplementalData($this->record, is_array($this->data) ? $this->data : []);
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            'delete' => Actions\DeleteAction::make()
                ->successRedirectUrl(fn (): ?string => $this->getRequestedReturnUrl()),
        ];
    }
}
