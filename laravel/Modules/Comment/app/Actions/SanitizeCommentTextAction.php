<?php

declare(strict_types=1);

namespace Modules\Comment\Actions;

use Spatie\Comments\Support\CommentSanitizer;
use Spatie\QueueableAction\QueueableAction;

/**
 * Action per sanitizzare il testo dei commenti.
 *
 * Pattern: Spatie Queueable Action per operazioni di business
 * Architettura: SINGLE RESPONSIBILITY - sanitizzazione testo
 *
 * @see https://github.com/spatie/laravel-queueable-action
 */
final class SanitizeCommentTextAction
{
    use QueueableAction;

    public function execute(string $text): string
    {
        /** @var string $sanitized */
        $sanitized = app(CommentSanitizer::class)->sanitize($text);

        return $sanitized;
    }
}
