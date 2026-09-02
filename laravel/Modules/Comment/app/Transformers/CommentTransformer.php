<?php

declare(strict_types=1);

namespace Modules\Comment\Transformers;

use Modules\Comment\Models\Comment;

interface CommentTransformer
{
    public function handle(Comment $comment): void;
}
