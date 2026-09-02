<?php

declare(strict_types=1);

namespace Modules\Comment\Transformers;

use League\CommonMark\Extension\DisallowedRawHtml\DisallowedRawHtmlExtension;
use Modules\Comment\Models\Comment;
use Spatie\LaravelMarkdown\MarkdownRenderer;

class MarkdownToHtmlTransformer implements CommentTransformer
{
    public function __construct(protected MarkdownRenderer $markdownRenderer) {}

    public function handle(Comment $comment): void
    {
        $this->markdownRenderer->addExtension(new DisallowedRawHtmlExtension);

        $comment->text = $this->markdownRenderer->toHtml($comment->original_text);
    }
}
