<?php

declare(strict_types=1);

namespace Modules\Comment\Support;

use Modules\Comment\Actions\ApproveCommentAction;
use Modules\Comment\Actions\ProcessCommentAction;
use Modules\Comment\Actions\RejectCommentAction;
use Modules\Comment\Policies\CommentPolicy;
use Modules\Comment\Policies\ReactionPolicy;
use Webmozart\Assert\Assert;

class CommentConfigActions
{
    public static function processCommentAction(): ProcessCommentAction
    {
        $action = CommentConfig::resolveAction('comments.actions.process_comment');
        Assert::isInstanceOf($action, ProcessCommentAction::class);

        return $action;
    }

    public static function approveCommentAction(): ApproveCommentAction
    {
        $action = CommentConfig::resolveAction('comments.actions.approve_comment');
        Assert::isInstanceOf($action, ApproveCommentAction::class);

        return $action;
    }

    public static function rejectCommentAction(): RejectCommentAction
    {
        $action = CommentConfig::resolveAction('comments.actions.reject_comment');
        Assert::isInstanceOf($action, RejectCommentAction::class);

        return $action;
    }

    public static function resolveMentionsAutocompleteAction(): object
    {
        return CommentConfig::resolveAction('comments.actions.resolve_mentions_autocomplete');
    }

    /** @return class-string */
    public static function commentPolicyClass(): string
    {
        return CommentConfig::resolveClassString(
            'comments.policies.comment',
            CommentPolicy::class,
        );
    }

    /** @return class-string */
    public static function reactionPolicyClass(): string
    {
        return CommentConfig::resolveClassString(
            'comments.policies.reaction',
            ReactionPolicy::class,
        );
    }
}
