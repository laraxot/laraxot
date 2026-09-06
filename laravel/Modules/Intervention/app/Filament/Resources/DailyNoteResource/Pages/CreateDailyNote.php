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
    protected function mutateFormDataBeforeCreate(array $data): array
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

    /**
     * Legacy parity: upsert by (user_id, note_date).
     *
     * @param  array<string, mixed>  $data
     */
    protected function handleRecordCreation(array $data): Model
    {
        $userId = $data['user_id'] ?? null;
        $userIdInt = 0;
        if (is_numeric($userId)) {
            $userIdInt = (int) $userId;
        }

        $noteDateValue = $data['note_date'] ?? '';
        $noteDateStr = self::ensureString($noteDateValue);
        $noteDate = CarbonImmutable::parse($noteDateStr);

        $body = $data['body'] ?? '';
        $bodyStr = self::ensureString($body);

        $bodyHtmlValue = $data['body_html'] ?? '';
        $bodyHtmlStr = self::ensureString($bodyHtmlValue);

        return app(UpsertDailyNoteForUserAction::class)->execute($userIdInt, $noteDate, $bodyStr, $bodyHtmlStr);
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
