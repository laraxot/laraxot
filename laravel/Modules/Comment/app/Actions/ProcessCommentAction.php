<?php

declare(strict_types=1);

namespace Modules\Comment\Actions;

use Illuminate\Support\Collection;
use Modules\Comment\Datas\CommentConfigData;
use Modules\Comment\Models\Comment;
use Modules\Comment\Transformers\CommentTransformer;
use Spatie\QueueableAction\QueueableAction;

class ProcessCommentAction
{
    use QueueableAction;

    public function execute(Comment $comment): void
    {
        $this->handle($comment);
    }

    public function handle(Comment $comment): void
    {
        $config = CommentConfigData::make();
        $hasTransformer = false;

        foreach ($config->commentTransformers() as $transformer) {
            $transformer->handle($comment);
            $hasTransformer = true;
        }

        if (! $hasTransformer) {
            $comment->text = $comment->original_text;
        }

        $comment->text = $config->sanitizeCommentHtml($comment->text ?? '');
    }
}
