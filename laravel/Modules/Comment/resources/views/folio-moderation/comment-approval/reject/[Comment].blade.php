<?php

declare(strict_types=1);

use Modules\Comment\Actions\Comment\RejectCommentAction;
use Modules\Comment\Models\Comment;
use function Laravel\Folio\middleware;
use function Laravel\Folio\name;
use function Laravel\Folio\render;

name('comment::comment.reject');
middleware(['signed']);

render(function (Comment $comment) {
    if (request()->isMethod('post')) {
        app(RejectCommentAction::class)->execute($comment);

        return view('comment::signed.approval.reject-comment', [
            'comment' => $comment,
        ]);
    }

    return view('comment::signed.approval.reject-comment-approval', [
        'comment' => $comment,
    ]);
});
