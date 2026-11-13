<?php

declare(strict_types=1);

return [
    'meta' => ['title' => 'News and press releases', 'description' => 'Municipal news, press releases and notices.', 'breadcrumb' => 'News'],
    'hero' => ['title' => 'News and press releases', 'subtitle' => 'Official updates and useful information from the city.'],
    'filters' => ['aria' => 'News filters', 'all' => 'All news', 'press' => 'Press releases', 'notices' => 'Notices'],
    'latest' => ['title' => 'Latest news', 'subtitle' => 'Keep up with municipal updates.'],
    'search' => ['label' => 'Search news', 'placeholder' => 'Search news'],
    'read_more' => 'Read more',
    'index' => [
        'default_category' => 'News',
        'default_event_category' => 'Events',
        'search_label' => 'Search',
        'search_placeholder' => 'Search by keyword',
        'search_submit' => 'Submit',
        'result_one' => '1 news item found',
        'result_many' => ':count news items found',
        'feedback' => [
            'title' => 'How clear is the information on this page?',
            'legend' => 'Rate this page from 1 to 5 stars',
            'star_label' => 'Rate %d out of 5 stars',
            'thanks' => 'Thank you. Your feedback will help us improve this service.',
            'positive' => [
                'question' => 'What did you like most?',
                'answers' => [
                    'clear' => 'The instructions were clear',
                    'complete' => 'The instructions were complete',
                    'progress' => 'I always knew I was following the right steps',
                    'technical' => 'I did not have any technical issues',
                    'other' => 'Other',
                ],
            ],
            'negative' => [
                'question' => 'Where did you have the most difficulty?',
                'answers' => [
                    'clear' => 'The instructions were sometimes unclear',
                    'complete' => 'The instructions were sometimes incomplete',
                    'progress' => 'I was sometimes unsure whether I was following the right steps',
                    'technical' => 'I had technical issues',
                    'other' => 'Other',
                ],
            ],
            'detail' => [
                'question' => 'Would you like to add more details?',
                'label' => 'Details',
                'help' => 'Enter up to 200 characters',
            ],
            'back' => 'Back',
            'next' => 'Next',
        ],
    ],
    'items' => [
        'one' => ['category' => 'Press release', 'date_iso' => '2026-09-01', 'date' => '1 September 2026', 'title' => 'New digital services for citizens', 'body' => 'New online journeys are available for municipal services.'],
        'two' => ['category' => 'Notice', 'date_iso' => '2026-08-20', 'date' => '20 August 2026', 'title' => 'Service updates across the city', 'body' => 'Read operational updates and useful community information.'],
        'three' => ['category' => 'Event', 'date_iso' => '2026-08-05', 'date' => '5 August 2026', 'title' => 'Open events for the community', 'body' => 'Discover upcoming initiatives and take part in city life.'],
    ],
];
