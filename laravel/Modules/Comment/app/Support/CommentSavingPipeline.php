<?php

declare(strict_types=1);

namespace Modules\Comment\Support;

use Illuminate\Support\Facades\Schema;
use Modules\Comment\Actions\Comment\ProcessCommentAction;
use Modules\Comment\Models\Comment;

class CommentSavingPipeline
{
    public static function handle(Comment $comment): void
    {
        app(ProcessCommentAction::class)->execute($comment);

        $connection = $comment->getConnectionName();
        if (! is_string($connection) || $connection === '') {
            $default = config('database.default');
            $connection = is_string($default) ? $default : 'sqlite';
        }
        $table = $comment->getTable();

        if (Schema::connection($connection)->hasColumn($table, 'comment')) {
            $comment->setAttribute(
                'comment',
                $comment->text ?? $comment->original_text,
            );
        }

        if (Schema::connection($connection)->hasColumn($table, 'post_id') && $comment->getAttribute('post_id') === null) {
            $comment->setAttribute('post_id', 0);
        }

        if (Schema::connection($connection)->hasColumn($table, 'user_id') && $comment->getAttribute('user_id') === null) {
            $comment->setAttribute('user_id', 0);
        }
    }
}
