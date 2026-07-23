<?php

declare(strict_types=1);

namespace Modules\Intervention\Filament\Resources\DailyNoteResource\Pages;

use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;
use Modules\Intervention\Actions\SyncDailyNotePhotosAction;
use Modules\Intervention\Support\DailyNoteHtml;
use Modules\Intervention\Filament\Resources\DailyNoteResource;
use Modules\Intervention\Models\DailyNote;

final class EditDailyNote extends EditRecord
{
    protected static string $resource = DailyNoteResource::class;

    /** @var array<int, string> */
    private array $photoPaths = [];

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeFill(array $data): array
    {
        if ($this->record instanceof DailyNote) {
            $data['body_html'] = $this->record->body_html ?: DailyNoteHtml::legacyToHtml($this->record->body);
            $data['photo_paths'] = $this->record->media()
                ->where('kind', 'photo')
                ->orderBy('id')
                ->pluck('path')
                ->all();
        }

        return $data;
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $this->photoPaths = array_values(array_map('strval', $data['photo_paths'] ?? []));

        $bodyHtml = DailyNoteHtml::sanitize((string) ($data['body_html'] ?? ''));
        $body = DailyNoteHtml::toPlainText($bodyHtml);

        return [
            'user_id' => (int) $data['user_id'],
            'note_date' => (string) $data['note_date'],
            'body' => $body,
            'body_html' => $bodyHtml,
        ];
    }

    protected function afterSave(): void
    {
        if (! $this->record instanceof DailyNote) {
            return;
        }

        app(SyncDailyNotePhotosAction::class)->execute(
            dailyNote: $this->record,
            paths: $this->photoPaths,
            uploadedByUserId: Auth::id(),
        );
    }
}
