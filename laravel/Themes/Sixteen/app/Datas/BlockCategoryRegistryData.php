<?php

declare(strict_types=1);

namespace Themes\Sixteen\Datas;

/**
 * SSoT: nomi ammessi per le sottocartelle di resources/views/components/blocks/.
 */
final class BlockCategoryRegistryData
{
    /**
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
}
