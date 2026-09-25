<?php

declare(strict_types=1);

namespace Themes\Sixteen\Support;

use function Safe\scandir;

/**
 * SSoT: nomi ammessi per le sottocartelle di resources/views/components/blocks/.
 *
 * Ogni slug deve derivare da una categoria documentata su:
 * - https://flowbite.com/blocks/
 * - https://tailwindcss.com/plus/ui-blocks
 *
 * @see laravel/Themes/Sixteen/docs/blocks/folder-vocabulary.md
 */
final class BlockCategoryRegistry
{
    /**
     * Cartelle legacy ancora referenziate da CMS/JSON — non crearne di nuove.
     *
     * @var list<string>
     */
    public const LEGACY_FOLDERS = [
        'administration',
        'application',
        'booking',
        'button',
        'cards',
        'categories',
        'category',
        'confirmation',
        'contacts',
        'dashboard',
        'design-comuni',
        'details',
        'event',
        'events',
        'feature_sections',
        'flow',
        'forms',
        'governance',
        'info',
        'listing',
        'marketing',
        'news',
        'paragraph',
        'quick-links',
        'resources',
        'review',
        'sections',
        'segnalazioni',
        'service',
        'services',
        'test',
        'tests',
        'thematic',
        'ticket',
        'ticket-layout',
        'ticket-list',
        'timeline-block',
        'topics',
        'topics-grid',
        'utilities',
        'widget',
        'wizard',
    ];

    /**
     * Slug canonici (kebab-case) allineati a Flowbite Blocks e Tailwind Plus UI Blocks.
     *
     * @var list<string>
     */
    public const CANONICAL_FOLDERS = [
        'accordion',
        'alert',
        'alerts',
        'application-shell',
        'avatar',
        'badge',
        'banner',
        'blog',
        'breadcrumb',
        'buttons',
        'calendar',
        'card',
        'checkout',
        'comments',
        'contact',
        'content',
        'cta',
        'description-list',
        'drawer',
        'dropdown',
        'empty-state',
        'error',
        'faq',
        'feature',
        'features',
        'grid',
        'feed',
        'feedback',
        'filters',
        'flyout-menu',
        'footer',
        'form',
        'grid',
        'header',
        'heading',
        'hero',
        'layout',
        'list',
        'login',
        'modal',
        'navbar',
        'navigation',
        'newsletter',
        'notification',
        'pagination',
        'popup',
        'pricing',
        'product',
        'progress',
        'rating',
        'reviews',
        'search',
        'sidebar',
        'stats',
        'steps',
        'table',
        'tabs',
        'team',
        'testimonials',
        'timeline',
        'vertical-navigation',
    ];

    /**
     * @return list<string>
     */
    public static function allowedFolders(): array
    {
        return array_values(array_unique(array_merge(self::CANONICAL_FOLDERS, self::LEGACY_FOLDERS)));
    }

    /**
     * @return list<string>
     */
    public static function canonicalFolders(): array
    {
        return self::CANONICAL_FOLDERS;
    }

    public static function isAllowed(string $folder): bool
    {
        return in_array($folder, self::allowedFolders(), true);
    }

    public static function isCanonical(string $folder): bool
    {
        return in_array($folder, self::CANONICAL_FOLDERS, true);
    }

    public static function isLegacy(string $folder): bool
    {
        return in_array($folder, self::LEGACY_FOLDERS, true);
    }

    /**
     * @return list<string>
     */
    public static function invalidFoldersIn(string $blocksRoot): array
    {
        if (! is_dir($blocksRoot)) {
            return [];
        }

        $invalid = [];

        /** @var list<string> $entries */
        $entries = scandir($blocksRoot);

        foreach ($entries as $entry) {
            if ($entry === '.' || $entry === '..' || str_ends_with($entry, '.old')) {
                continue;
            }

            $path = $blocksRoot.DIRECTORY_SEPARATOR.$entry;

            if (! is_dir($path)) {
                continue;
            }

            if (! self::isAllowed($entry)) {
                $invalid[] = $entry;
            }
        }

        sort($invalid);

        return $invalid;
    }

    /**
     * @return list<string>
     */
    public static function legacyFoldersPresentIn(string $blocksRoot): array
    {
        if (! is_dir($blocksRoot)) {
            return [];
        }

        $present = [];

        /** @var list<string> $entries */
        $entries = scandir($blocksRoot);

        foreach ($entries as $entry) {
            if ($entry === '.' || $entry === '..' || str_ends_with($entry, '.old')) {
                continue;
            }

            $path = $blocksRoot.DIRECTORY_SEPARATOR.$entry;

            if (is_dir($path) && self::isLegacy($entry)) {
                $present[] = $entry;
            }
        }

        sort($present);

        return $present;
    }
}
