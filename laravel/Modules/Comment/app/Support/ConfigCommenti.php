<?php

declare(strict_types=1);

namespace Modules\Comment\Support;

use Illuminate\Support\Str;

/**
 * Legacy Italian facade — delegates to {@see CommentConfig} (SSOT).
 *
 * @deprecated Use CommentConfig directly. Kept for backward compatibility only.
 */
class ConfigCommenti
{
    public static function modelloCommento(): string
    {
        return CommentConfig::commentModelClass();
    }

    public static function modelloReazione(): string
    {
        return CommentConfig::reactionModelClass();
    }

    public static function modelloSottoscrizioneNotifica(): string
    {
        return CommentConfig::commentNotificationSubscriptionModelClass();
    }

    public static function campoNomeModelloCommentatore(): string
    {
        return CommentConfig::commentatorModelNameField();
    }

    public static function campoAvatarModelloCommentatore(): string
    {
        return CommentConfig::commentatorModelAvatarField();
    }

    public static function immagineDefaultGravatar(): string
    {
        return CommentConfig::gravatarDefaultImage();
    }

    public static function markdownParser(): callable
    {
        $parser = config('commenti.markdown_parser');

        if (is_callable($parser)) {
            return $parser;
        }

        return static fn (string $text): string => Str::markdown($text);
    }

    public static function mentionsParser(): callable
    {
        $parser = config('commenti.mentions_parser');

        if (is_callable($parser)) {
            return $parser;
        }

        return static fn (string $text): string => $text;
    }

    public static function commentiThreaded(): bool
    {
        return (bool) config('commenti.threaded', true);
    }

    public static function richiedeApprovazione(): bool
    {
        return (bool) config('commenti.require_approval', false);
    }

    public static function tipoSottoscrizioneDefault(): string
    {
        $value = config('commenti.default_subscription_type', 'participating');

        return is_string($value) ? $value : 'participating';
    }
}
