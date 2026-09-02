<?php

declare(strict_types=1);

use Modules\Comment\Actions\Comment\ApproveCommentAction;
use Modules\Comment\Models\Comment;
use function Laravel\Folio\middleware;
use function Laravel\Folio\name;
use function Laravel\Folio\render;

name('comment::comment.approve');
middleware(['signed']);

render(function (Comment $comment) {
    if (request()->isMethod('post')) {
        app(ApproveCommentAction::class)->execute($comment);

        return view('comment::signed.approval.approve-comment', [
            'comment' => $comment,
        ]);
    }

    return view('comment::signed.approval.approve-comment-confirmation', [
        'comment' => $comment,
    ]);
});
