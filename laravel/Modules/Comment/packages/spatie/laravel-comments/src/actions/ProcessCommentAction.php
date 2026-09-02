<?php

namespace Spatie\Comments\Actions;

use Spatie\Comments\CommentTransformers\CommentTransformer;
use Spatie\Comments\Models\Comment;
use Spatie\Comments\Support\Config;

class ProcessCommentAction
{
    public function execute(Comment $comment): void
    {
        Config::commentTransformers()->each(
            fn (CommentTransformer $commentProcessor) => $commentProcessor->handle($comment)
        );

        if (Config::commentTransformers()->isEmpty()) {
            $comment->text = $comment->original_text;
        }

        $comment->text = $this->sanitize($comment->text);
    }

    protected function sanitize(?string $text = ''): string
    {
        return Config::commentSanitizer()->sanitize($text ?? '');
    }
}
