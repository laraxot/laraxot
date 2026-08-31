<?php

declare(strict_types=1);

namespace Modules\Intervention\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Carbon;
use Modules\Document\Models\Media;
use Modules\Intervention\Database\Factories\DailyNoteFactory;
use Modules\Intervention\Support\DailyNoteHtml;
use Modules\User\Models\User;

/**
 * @property int $id
 * @property int $user_id
 * @property Carbon $note_date
 * @property string $body
 * @property string|null $body_html
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read User|null $user
 * @property-read Collection<int, Media> $media
 *
 * @method static DailyNoteFactory factory($count = null, $state = [])
 */
final class DailyNote extends BaseModel
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
