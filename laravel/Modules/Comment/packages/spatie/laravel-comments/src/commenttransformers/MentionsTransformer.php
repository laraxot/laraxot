<?php

namespace Spatie\Comments\CommentTransformers;

use Illuminate\Support\Facades\Blade;
use Spatie\Comments\Models\Comment;
use Spatie\Comments\Models\Concerns\Interfaces\CanComment;
use Spatie\Comments\Support\Config;
use Spatie\Comments\View\Components\Mentions\Mention;

class MentionsTransformer implements CommentTransformer
{
    public function handle(Comment $comment): void
    {
        $commentatorClass = Config::commentatorModelClass();

        if (! $commentatorClass) {
            return;
        }

        $text = $comment->text;

        $pattern = '/<(\w+)\s+[^>]*data-mention="([^"]+)"[^>]*>(.*?)<\/\1>/s';

        preg_match_all($pattern, $text, $matches, PREG_SET_ORDER);

        foreach ($matches as $match) {
            $fullMatch = $match[0];
            $mentioneeId = $match[2];

            $mentionee = $commentatorClass::find($mentioneeId);

            $replacement = $this->renderMention($mentionee);
            $text = str_replace($fullMatch, $replacement, $text);
        }

        $comment->text = $text;
    }

    public function renderMention(?CanComment $mentionee): string
    {
        return Blade::renderComponent(new Mention($mentionee));
    }
}
