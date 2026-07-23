<?php

declare(strict_types=1);

namespace Modules\Intervention\Actions;

use Carbon\CarbonImmutable;
use Modules\Intervention\Models\DailyNote;

final class UpsertDailyNoteForUserAction
{
    public function execute(
        int $userId,
        CarbonImmutable $noteDate,
        string $body,
        ?string $bodyHtml = null,
    ): DailyNote {
        /** @var DailyNote|null $existing */
        $existing = DailyNote::query()
            ->where('user_id', $userId)
            ->whereDate('note_date', $noteDate)
            ->first();

        if ($existing instanceof DailyNote) {
            $existing->body = $body;
            $existing->body_html = $bodyHtml;
            $existing->save();

            return $existing;
        }

        /** @var DailyNote $created */
        $created = DailyNote::query()->create([
            'user_id' => $userId,
            'note_date' => $noteDate->toDateString(),
            'body' => $body,
            'body_html' => $bodyHtml,
        ]);

        return $created;
    }
}
