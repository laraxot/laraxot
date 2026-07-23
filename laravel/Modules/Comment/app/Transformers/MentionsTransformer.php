<?php

declare(strict_types=1);

namespace Modules\Comment\Transformers;

use Modules\Comment\Datas\CommentConfigData;
use Modules\Comment\Models\Comment;
use function Safe\preg_replace_callback;

class MentionsTransformer implements CommentTransformer
{
    public function handle(Comment $comment): void
    {
        if (! (CommentConfigData::make()->mentions['enabled'] ?? false)) {
            return;
        }

        $comment->original_text = $this->expandMentionSyntax($comment->original_text);
        $comment->text = $this->expandMentionSyntax($comment->text ?? $comment->original_text);
    }

    private function expandMentionSyntax(string $text): string
    {
        $result = preg_replace_callback(
            '/@\{(\d+)\|([^}]+)\}/',
            static function (array $matches): string {
                $id = is_string($matches[1]) ? $matches[1] : '';
                $name = is_string($matches[2]) ? $matches[2] : '';

                return '<span class="comment-mention" data-mention="'.e($id).'">@'.e($name).'</span>';
            },
            $text,
        );
        $text = $result ?? $text;

        if (str_contains($text, 'data-mention=')) {
            return $text;
        }

        $result = preg_replace_callback(
            '/@(\w+)/',
            static function (array $matches): string {
                $word = is_string($matches[1]) ? $matches[1] : '';
                $mention = e($word);

                return '<span class="comment-mention" data-mention="'.e($word).'">@'.$mention.'</span>';
            },
            $text,
        );

        return $result ?? $text;
    }
}
