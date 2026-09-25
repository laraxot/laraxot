<?php

declare(strict_types=1);

namespace Modules\Comment\Support;

use Illuminate\Support\Collection;
use InvalidArgumentException;
use Modules\Comment\Models\Comment;
use Modules\Comment\Models\CommentNotificationSubscription;
use Modules\Comment\Models\Contracts\CanComment;
use Modules\Comment\Models\Reaction;
use Modules\Comment\Transformers\CommentTransformer;
use Webmozart\Assert\Assert;

/**
 * SSOT configurazione motore commenti (core + livewire UI).
 *
 * Durante la migrazione le action/policy possono ancora risolvere classi Spatie via config.
 */
class CommentConfig
{
    /** @return list<string> */
    public static function allowedReactions(): array
    {
        $reactions = config('comments.allowed_reactions', []);
        if (! is_array($reactions)) {
            return [];
        }

        return array_values(array_filter($reactions, is_string(...)));
    }

    public static function allowAnonymousComments(): bool
    {
        return (bool) config('comments.allow_anonymous_comments', false);
    }

    public static function automaticallyApproveAllComments(): bool
    {
        return (bool) config('comments.automatically_approve_all_comments');
    }

    /** @return class-string<CanComment>|null */
    /** @phpstan-return class-string<CanComment>|null */
    public static function commentatorModelClass(): ?string
    {
        $class = config('comments.models.commentator');
        if (! is_string($class) || $class === '') {
            return null;
        }
        Assert::classExists($class);
        Assert::subclassOf($class, CanComment::class);

        return $class;
    }

    public static function commentatorModelNameField(): string
    {
        return self::configString('comments.models.name', 'name');
    }

    public static function commentatorModelAvatarField(): string
    {
        return self::configString('comments.models.avatar', 'avatar');
    }

    /** @return class-string<Comment> */
    public static function commentModelClass(): string
    {
        $class = self::resolveClassString('comments.models.comment', Comment::class);
        Assert::true(is_a($class, Comment::class, true));

        return $class;
    }

    /** @return class-string<CommentNotificationSubscription> */
    public static function commentNotificationSubscriptionModelClass(): string
    {
        $class = self::resolveClassString(
            'comments.models.comment_notification_subscription',
            CommentNotificationSubscription::class,
        );
        Assert::true(is_a($class, CommentNotificationSubscription::class, true));

        return $class;
    }

    public static function gravatarDefaultImage(): string
    {
        return self::configString('comments.gravatar.default_image', 'mp');
    }

    /** @return class-string<Reaction> */
    public static function reactionModelClass(): string
    {
        $class = self::resolveClassString('comments.models.reaction', Reaction::class);
        Assert::true(is_a($class, Reaction::class, true));

        return $class;
    }

    /** @return Collection<int, CommentTransformer> */
    public static function commentTransformers(): Collection
    {
        return CommentConfigContent::commentTransformers();
    }

    public static function commentSanitizer(): CommentSanitizer
    {
        return CommentConfigContent::commentSanitizer();
    }

    /** @return array<string, list<string>> */
    public static function allowedAttributes(): array
    {
        return CommentConfigContent::allowedAttributes();
    }

    public static function configString(string $key, string $default = ''): string
    {
        $value = config($key, $default);

        return is_string($value) ? $value : $default;
    }

    /**
     * @param  class-string  $default
     * @return class-string
     */
    public static function resolveClassString(string $key, string $default): string
    {
        $class = config($key, $default);
        if (! is_string($class) || $class === '') {
            return $default;
        }
        Assert::classExists($class);

        return $class;
    }

    public static function resolveAction(string $key): object
    {
        $class = config($key);
        if (! is_string($class) || $class === '') {
            throw new InvalidArgumentException("Missing config key: {$key}");
        }
        Assert::classExists($class);
        $action = app($class);
        Assert::object($action);

        return $action;
    }
}
