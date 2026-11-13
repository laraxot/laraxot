<?php

declare(strict_types=1);

namespace Themes\Sixteen\Presentation\Search;

use Themes\Sixteen\Infrastructure\Url\FrontofficeRoutes;

/**
 * Suggerimenti default del search-modal FO (Design Comuni).
 *
 * Il Blade riceve già `links` via @props; se vuoti usa questi default data-driven.
 * Label solo via chiavi i18n (`pub_theme` / owner) — mai stringhe hardcoded.
 */
final class FrontofficeSearchSuggestions
{
    /**
     * @return list<array{label: string, url: string}>
     */
    public static function defaults(): array
    {
        return [
            [
                'label' => (string) __('pub_theme::navigation.homepage.report_issue'),
                'url' => FrontofficeRoutes::ticketsCreate(),
            ],
            [
                'label' => (string) __('fixcity::ticket_list.heading.title'),
                'url' => FrontofficeRoutes::ticketsIndex(),
            ],
            [
                'label' => (string) __('fixcity::ticket_list.contacts.track'),
                'url' => FrontofficeRoutes::ticketsTrack(),
            ],
            [
                'label' => (string) __('pub_theme::navigation.homepage.faq'),
                'url' => FrontofficeRoutes::faqIndex(),
            ],
            [
                'label' => (string) __('pub_theme::navigation.homepage.news'),
                'url' => FrontofficeRoutes::blogIndex(),
            ],
            [
                'label' => (string) __('pub_theme::navigation.homepage.events'),
                'url' => FrontofficeRoutes::eventsIndex(),
            ],
        ];
    }
}
