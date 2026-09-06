<?php

declare(strict_types=1);

namespace Modules\Intervention\Filament\Resources\DailyNoteResource\Pages;

use Illuminate\Support\Facades\Auth;
use Modules\Intervention\Actions\SyncDailyNotePhotosAction;
use Modules\Intervention\Filament\Resources\DailyNoteResource;
use Modules\Intervention\Models\DailyNote;
use Modules\Intervention\Support\DailyNoteHtml;
use Modules\Xot\Filament\Resources\Pages\XotBaseEditRecord;

final class EditDailyNote extends XotBaseEditRecord
{
    protected static string $resource = DailyNoteResource::class;

    /** @var array<int, string> */
    private array $photoPaths = [];

    private static function ensureString(mixed $value): string
    {
        if (is_string($value)) {
            return $value;
        }

        if (is_numeric($value) || is_bool($value) || $value === null) {
            return (string) $value;
        }

        return '';
    }

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
        $photoPaths = is_array($data['photo_paths'] ?? null) ? $data['photo_paths'] : [];
        $this->photoPaths = array_values(array_map(static function (mixed $path): string {
            if (is_string($path)) {
                return $path;
            }

            return self::ensureString($path);
        }, $photoPaths));

        $bodyHtmlValue = $data['body_html'] ?? '';
        $bodyHtmlStr = self::ensureString($bodyHtmlValue);
        $bodyHtml = DailyNoteHtml::sanitize($bodyHtmlStr);
        $body = DailyNoteHtml::toPlainText($bodyHtml);

        $userId = $data['user_id'] ?? null;
        $userIdInt = 0;
        if (is_numeric($userId)) {
            $userIdInt = (int) $userId;
        }

        $noteDate = $data['note_date'] ?? null;
        $noteDateStr = self::ensureString($noteDate);

        return [
            'user_id' => $userIdInt,
            'note_date' => $noteDateStr,
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
            uploadedByUserId: Auth::id() !== null ? (int) Auth::id() : null,
        );
    }
}
