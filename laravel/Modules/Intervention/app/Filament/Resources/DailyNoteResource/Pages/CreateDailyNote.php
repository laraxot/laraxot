<?php

declare(strict_types=1);

namespace Modules\Intervention\Filament\Resources\DailyNoteResource\Pages;

use Carbon\CarbonImmutable;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Modules\Intervention\Actions\SyncDailyNotePhotosAction;
use Modules\Intervention\Actions\UpsertDailyNoteForUserAction;
use Modules\Intervention\Support\DailyNoteHtml;
use Modules\Intervention\Filament\Resources\DailyNoteResource;
use Modules\Intervention\Models\DailyNote;

final class CreateDailyNote extends CreateRecord
{
    protected static string $resource = DailyNoteResource::class;

    /** @var array<int, string> */
    private array $photoPaths = [];

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeCreate(array $data): array
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

    /**
     * Legacy parity: upsert by (user_id, note_date).
     *
     * @param  array<string, mixed>  $data
     */
    protected function handleRecordCreation(array $data): Model
    {
        $userId = (int) $data['user_id'];
        $noteDate = CarbonImmutable::parse((string) $data['note_date']);
        $body = (string) $data['body'];
        $bodyHtml = (string) ($data['body_html'] ?? '');

        return app(UpsertDailyNoteForUserAction::class)->execute($userId, $noteDate, $body, $bodyHtml);
    }

    protected function afterCreate(): void
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
