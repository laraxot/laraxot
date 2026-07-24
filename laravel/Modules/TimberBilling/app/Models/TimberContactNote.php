<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;
use Modules\TimberBilling\Database\Factories\TimberContactNoteFactory;
use Modules\User\Models\User;

/**
 * @property string $body
 * @property int|null $created_by_user_id
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
final class TimberContactNote extends Model
{
    /** @use HasFactory<TimberContactNoteFactory> */
    use HasFactory;

    protected $table = 'timber_contact_notes';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return MorphTo<Model, $this> */
    public function noteable(): MorphTo
    {
        return $this->morphTo();
    }

    /** @return BelongsTo<User, $this> */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    protected static function newFactory(): TimberContactNoteFactory
    {
        return TimberContactNoteFactory::new();
    }
}
