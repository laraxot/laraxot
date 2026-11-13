<?php

declare(strict_types=1);

namespace Modules\Comment\Models\Contracts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Modules\Comment\Models\Comment;

/**
 * Modello che accetta commenti (commentable).
 */
interface Commentable
{
    /**
     * Relazione morph dei commenti sul commentable.
     * `covariant Model` rende MorphMany&lt;Comment, $this&gt; del trait compatibile col contratto.
     *
     * @return MorphMany<Comment, covariant Model>
     */
    public function comments(): MorphMany;

    public function comment(string $text, ?CanComment $commentator = null): Comment;
}
