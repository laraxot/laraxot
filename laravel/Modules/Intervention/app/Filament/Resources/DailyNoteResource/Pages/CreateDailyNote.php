<?php

declare(strict_types=1);

namespace Modules\Intervention\Filament\Resources\DailyNoteResource\Pages;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Modules\Intervention\Actions\SyncDailyNotePhotosAction;
use Modules\Intervention\Actions\UpsertDailyNoteForUserAction;
use Modules\Intervention\Filament\Resources\DailyNoteResource;
use Modules\Intervention\Models\DailyNote;
use Modules\Intervention\Support\DailyNoteHtml;
use Modules\Xot\Filament\Resources\Pages\XotBaseCreateRecord;

final class CreateDailyNote extends XotBaseCreateRecord
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
        $photoPaths = is_array($data['photo_paths'] ?? null) ? $data['photo_paths'] : [];
        $this->photoPaths = array_values(array_map(static function (mixed $path): string {
            return is_string($path) ? $path : (string) $path;
        }, $photoPaths));

        $bodyHtmlValue = $data['body_html'] ?? '';
        $bodyHtml = DailyNoteHtml::sanitize((string) $bodyHtmlValue);
        $body = DailyNoteHtml::toPlainText($bodyHtml);

        return [
            'user_id' => (int) ($data['user_id'] ?? 0),
            'note_date' => (string) ($data['note_date'] ?? ''),
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
        $userId = (int) ($data['user_id'] ?? 0);
        $noteDate = CarbonImmutable::parse((string) ($data['note_date'] ?? ''));
        $body = (string) ($data['body'] ?? '');
        $bodyHtmlValue = $data['body_html'] ?? '';
        $bodyHtml = (string) $bodyHtmlValue;

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
            uploadedByUserId: Auth::id() !== null ? (int) Auth::id() : null,
        );
    }
}
