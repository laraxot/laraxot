<?php

declare(strict_types=1);

namespace Modules\Comment\Support;

class CommentConfigUi
{
    public static function mentionsEnabled(): bool
    {
        return (bool) config('comments.mentions.enabled');
    }

    public static function autoloadFontAwesome(): bool
    {
        return (bool) config('comments.ui.autoload_fontawesome', true);
    }

    public static function showAvatarsInMentionsAutocomplete(): bool
    {
        return (bool) config('comments.ui.show_avatars_in_mentions_autocomplete', true);
    }

    public static function editor(): string
    {
        return CommentConfig::configString('comments.ui.editor', 'comment::editors.textarea');
    }

    public static function showAvatars(): bool
    {
        return (bool) config('comments.ui.show_avatars', true);
    }

    public static function paginationCount(): int
    {
        $value = config('comments.pagination.results', 10_000);

        return is_int($value) ? $value : 10_000;
    }

    public static function paginationPageName(): string
    {
        return CommentConfig::configString('comments.pagination.page_name', 'page');
    }

    public static function paginationTheme(): string
    {
        return CommentConfig::configString('comments.pagination.theme', 'tailwind');
    }
}
