<?php

declare(strict_types=1);

namespace Modules\Comment\Tests\Support;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Modules\Comment\Models\Comment;
use Modules\Comment\Models\Contracts\CanComment;
use Modules\Comment\Models\Contracts\Commentable;

/**
 * Commentable stub per test unitari — nessuna dipendenza da Fixcity.
 */
final class ParityCommentableStub extends Model implements Commentable
{
    protected $table = 'parity_commentables';

    protected $guarded = [];

    public function getMorphClass(): string
    {
        return 'parity-commentable';
    }

    public function comments(): MorphMany
    {
        /** @var MorphMany<Comment, Model> $relation */
        $relation = $this->morphMany(Comment::class, 'commentable');

        return $relation;
    }

    public function comment(string $text, ?CanComment $commentator = null): Comment
    {
        return $this->comments()->create([
            'original_text' => $text,
            'text' => $text,
            'commentator_id' => $commentator?->getKey(),
            'commentator_type' => $commentator?->getMorphClass(),
        ]);
    }
}
