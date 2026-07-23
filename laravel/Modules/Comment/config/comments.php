<?php

declare(strict_types=1);

use Modules\Comment\Actions\Comment\ApproveCommentAction;
use Modules\Comment\Actions\Comment\ProcessCommentAction;
use Modules\Comment\Actions\Comment\RejectCommentAction;
use Modules\Comment\Actions\Mention\ResolveMentionsAutocompleteAction;
use Modules\Comment\Actions\Notification\NotifyApprovedCommentAction;
use Modules\Comment\Actions\Notification\SendNotificationsForPendingCommentAction;
use Modules\Comment\Models\Comment;
use Modules\Comment\Models\CommentNotificationSubscription;
use Modules\Comment\Models\Reaction;
use Modules\Comment\Notifications\ApprovedCommentNotification;
use Modules\Comment\Notifications\PendingCommentNotification;
use Modules\Comment\Policies\CommentPolicy;
use Modules\Comment\Policies\ReactionPolicy;
use Modules\Comment\Actions\Comment\SanitizeCommentTextAction;
use Modules\Comment\Transformers\MarkdownToHtmlTransformer;
use Modules\Comment\Transformers\MentionsTransformer;

return [

    'models' => [
        'comment' => Comment::class,
        'reaction' => Reaction::class,
        'comment_notification_subscription' => CommentNotificationSubscription::class,
        'commentator' => null,
        'name' => 'name',
        'avatar' => 'avatar',
    ],

    'policies' => [
        'comment' => CommentPolicy::class,
        'reaction' => ReactionPolicy::class,
    ],

    'actions' => [
        'process_comment' => ProcessCommentAction::class,
        'approve_comment' => ApproveCommentAction::class,
        'reject_comment' => RejectCommentAction::class,
        'send_notifications_for_pending_comment' => SendNotificationsForPendingCommentAction::class,
        'send_notifications_for_approved_comment' => NotifyApprovedCommentAction::class,
        'resolve_mentions_autocomplete' => ResolveMentionsAutocompleteAction::class,
    ],

    'notifications' => [
        'enabled' => true,
        'mail' => [
            'from' => [
                'address' => null,
                'name' => null,
            ],
        ],
        'notifications' => [
            'pending_comment' => PendingCommentNotification::class,
            'approved_comment' => ApprovedCommentNotification::class,
        ],
    ],

    'comment_transformers' => [
        MarkdownToHtmlTransformer::class,
        MentionsTransformer::class,
    ],

    'comment_sanitizer' => SanitizeCommentTextAction::class,

    'allowed_reactions' => [
        '+1',
        '-1',
        '❤️',
        '🔥',
        '🎉',
    ],

    'allowed_attributes' => [
        'a' => ['href', 'target', 'rel'],
        'ul' => [],
        'ol' => [],
        'li' => [],
        'p' => [],
        'pre' => [],
        'code' => [],
        'blockquote' => [],
        'strong' => [],
        'em' => [],
    ],

    'allow_anonymous_comments' => false,

    'automatically_approve_all_comments' => true,

    'gravatar' => [
        'default_image' => 'mp',
    ],

    'mentions' => [
        'enabled' => false,
    ],

    'ui' => [
        'show_avatars' => true,
        'autoload_fontawesome' => true,
        'show_avatars_in_mentions_autocomplete' => true,
        'editor' => 'comment::editors.textarea',
    ],

    'pagination' => [
        'results' => 10_000,
        'page_name' => 'page',
        'theme' => 'tailwind',
    ],
];
