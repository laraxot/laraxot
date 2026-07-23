<?php

declare(strict_types=1);

namespace Modules\Intervention\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Modules\Document\Models\Media;
use Modules\Intervention\Support\DailyNoteHtml;

final class DailyNote extends Model
{
    protected $table = 'daily_notes';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'note_date' => 'date',
        ];
    }

    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** @return MorphMany<Media, $this> */
    public function media(): MorphMany
    {
        return $this->morphMany(Media::class, 'mediable');
    }

    public function getRenderedBodyHtml(): string
    {
        return $this->body_html
            ? DailyNoteHtml::sanitize((string) $this->body_html)
            : DailyNoteHtml::legacyToHtml((string) $this->body);
    }
}
