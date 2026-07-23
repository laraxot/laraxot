<?php

declare(strict_types=1);

namespace Modules\Comment\Datas;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Modules\Comment\Actions\Comment\SanitizeCommentTextAction;
use Modules\Comment\Models\Comment;
use Modules\Comment\Models\CommentNotificationSubscription;
use Modules\Comment\Models\Contracts\CanComment;
use Modules\Comment\Models\Reaction;
use Modules\Comment\Notifications\ApprovedCommentNotification;
use Modules\Comment\Notifications\PendingCommentNotification;
use Modules\Comment\Policies\CommentPolicy;
use Modules\Comment\Policies\ReactionPolicy;
use Modules\Comment\Transformers\CommentTransformer;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Webmozart\Assert\Assert;

/**
 * SSOT tipizzato per config/comments.php — un file, come CloudFrontData.
 */
#[MapInputName(SnakeCaseMapper::class)]
#[MapOutputName(SnakeCaseMapper::class)]
class CommentConfigData extends Data
{
    /** @var array<string, string|null> */
    public array $models = [
        'comment' => Comment::class,
        'reaction' => Reaction::class,
        'comment_notification_subscription' => CommentNotificationSubscription::class,
        'commentator' => null,
        'name' => 'name',
        'avatar' => 'avatar',
    ];

    /** @var array<string, string> */
    public array $policies = [
        'comment' => CommentPolicy::class,
        'reaction' => ReactionPolicy::class,
    ];

    /** @var array<string, string> */
    public array $actions = [];

    /** @var array<string, mixed> */
    public array $notifications = [
        'enabled' => true,
        'mail' => ['from' => ['address' => null, 'name' => null]],
        'notifications' => [
            'pending_comment' => PendingCommentNotification::class,
            'approved_comment' => ApprovedCommentNotification::class,
        ],
    ];

    /** @var list<class-string<CommentTransformer>> */
    public array $commentTransformers = [];

    public string $commentSanitizer = SanitizeCommentTextAction::class;

    /** @var list<string> */
    public array $allowedReactions = [];

    /** @var array<string, list<string>> */
    public array $allowedAttributes = [];

    #[MapInputName('allow_anonymous_comments')]
    public bool $allowAnonymous = false;

    #[MapInputName('automatically_approve_all_comments')]
    public bool $autoApproveAll = true;

    /** @var array<string, string> */
    public array $gravatar = ['default_image' => 'mp'];

    /** @var array<string, bool> */
    public array $mentions = ['enabled' => false];

    /** @var array<string, mixed> */
    #[MapInputName('ui')]
    public array $uiSettings = [
        'show_avatars' => true,
        'autoload_fontawesome' => true,
        'show_avatars_in_mentions_autocomplete' => true,
        'editor' => 'comment::editors.textarea',
    ];

    /** @var array<string, int|string> */
    public array $pagination = [
        'results' => 10_000,
        'page_name' => 'page',
        'theme' => 'tailwind',
    ];

    public static function make(): self
    {
        /** @var array<string, mixed> $data */
        $data = Config::array('comments');

        return self::from($data);
    }

    /** @return Collection<int, CommentTransformer> */
    public function commentTransformers(): Collection
    {
        $resolved = [];
        foreach ($this->commentTransformers as $class) {
            if (! is_string($class)) {
                continue;
            }
            $instance = app($class);
            Assert::isInstanceOf($instance, CommentTransformer::class);
            $resolved[] = $instance;
        }

        return new Collection($resolved);
    }

    public function commentSanitizer(): SanitizeCommentTextAction
    {
        $sanitizer = app($this->commentSanitizer);
        Assert::isInstanceOf($sanitizer, SanitizeCommentTextAction::class);

        return $sanitizer;
    }

    public function showAvatars(): bool
    {
        $value = $this->uiSettings['show_avatars'] ?? true;

        return (bool) $value;
    }

    public function paginationPageName(): string
    {
        $value = $this->pagination['page_name'] ?? 'page';

        return is_string($value) ? $value : 'page';
    }

    public function paginationCount(): int
    {
        $value = $this->pagination['results'] ?? 10_000;

        return is_int($value) ? $value : 10_000;
    }

    public function commentatorModelNameField(): string
    {
        $value = $this->models['name'] ?? 'name';

        return is_string($value) && $value !== '' ? $value : 'name';
    }

    /** @return list<string> */
    public function allowedReactions(): array
    {
        return array_values(array_filter($this->allowedReactions, is_string(...)));
    }

    /** @return class-string<CanComment>|null */
    public function commentatorModelClass(): ?string
    {
        $class = $this->models['commentator'] ?? null;
        if (! is_string($class) || $class === '') {
            return null;
        }
        Assert::classExists($class);
        Assert::subclassOf($class, CanComment::class);

        return $class;
    }

    public function pendingCommentNotification(Comment $comment): Notification
    {
        $notifications = $this->notifications['notifications'] ?? [];
        $class = is_array($notifications) && isset($notifications['pending_comment']) && is_string($notifications['pending_comment'])
            ? $notifications['pending_comment']
            : PendingCommentNotification::class;

        $notification = app($class, ['comment' => $comment]);
        Assert::isInstanceOf($notification, Notification::class);

        return $notification;
    }

    public function approvedCommentNotification(Comment $comment, CanComment $commentator): Notification
    {
        $notifications = $this->notifications['notifications'] ?? [];
        $class = is_array($notifications) && isset($notifications['approved_comment']) && is_string($notifications['approved_comment'])
            ? $notifications['approved_comment']
            : ApprovedCommentNotification::class;

        $notification = app($class, compact('comment', 'commentator'));
        Assert::isInstanceOf($notification, Notification::class);

        return $notification;
    }

    public function notificationMailFromAddress(): string
    {
        $mail = $this->notifications['mail'] ?? [];
        $from = is_array($mail) && isset($mail['from']) && is_array($mail['from'])
            ? $mail['from']
            : [];
        $address = isset($from['address']) && is_string($from['address'])
            ? $from['address']
            : null;

        if (is_string($address) && $address !== '') {
            return $address;
        }

        $fallback = config('mail.from.address');
        if (! is_string($fallback)) {
            return '';
        }

        return $fallback;
    }

    public function notificationMailFromName(): string
    {
        $mail = $this->notifications['mail'] ?? [];
        $from = is_array($mail) && isset($mail['from']) && is_array($mail['from'])
            ? $mail['from']
            : [];
        $name = isset($from['name']) && is_string($from['name'])
            ? $from['name']
            : null;

        if (is_string($name) && $name !== '') {
            return $name;
        }

        $fallback = config('mail.from.name', '');

        return is_string($fallback) ? $fallback : '';
    }
}
