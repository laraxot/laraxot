<?php

declare(strict_types=1);

namespace Modules\Comment\Actions\Comment;

use Modules\Comment\Actions\CheckCommentiThreadedAction;
use Modules\Comment\Models\Comment;
use Spatie\QueueableAction\QueueableAction;

/**
 * Si puo' rispondere a questo commento? (CMT-022)
 *
 * Due condizioni: le risposte sono accese (`commenti.threaded`) e il commento non e' gia' all'ultimo livello
 * (`comments.ui.max_reply_depth`). La vista la usa per mostrare «Rispondi», il widget la rifa' lato server a ogni chiamata:
 * una chiamata Livewire diretta non deve poter aggirare il limite di profondita' che il browser non mostra.
 */
class CheckReplyAllowedAction
{
    use QueueableAction;

    /** Livelli di risposta consentiti se la configurazione non dice altro. */
    public const int DEFAULT_MAX_DEPTH = 3;

    public function execute(Comment $comment): bool
    {
        if (! app(CheckCommentiThreadedAction::class)->execute()) {
            return false;
        }

        $max = config('comments.ui.max_reply_depth', self::DEFAULT_MAX_DEPTH);

        return $comment->depth() < (is_int($max) ? $max : self::DEFAULT_MAX_DEPTH);
    }
}
