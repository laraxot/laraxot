<?php

declare(strict_types=1);

use Illuminate\Support\Str;

if (! function_exists('trimPath')) {
    function trimPath(string $path): string
    {
        return trim($path, '/');
    }
}

$moduleName = 'Cms';

return [
    'baseUrl' => '',
    'production' => false,
    'siteName' => 'Modulo '.$moduleName,
    'siteDescription' => 'Modulo '.$moduleName,
    'lang' => 'it',

    'collections' => [
        'posts' => [
            /* @param object{getFilename(): string} $page */
            'path' => function ($page) {
                // return $page->lang.'/posts/'.Str::slug($page->getFilename());
                // return 'posts/' . ($page->featured ? 'featured/' : '') . Str::slug($page->getFilename());

                return 'posts/'.Str::slug($page->getFilename());
            },
        ],
        'docs' => [
            /* @param object{getFilename(): string} $page */
            'path' => function ($page) {
                // return $page->lang.'/docs/'.Str::slug($page->getFilename());
                return 'docs/'.Str::slug($page->getFilename());
            },
        ],
    ],

    // Algolia DocSearch credentials
    'docsearchApiKey' => env('DOCSEARCH_KEY'),
    'docsearchIndexName' => env('DOCSEARCH_INDEX'),

    // navigation menu
    'navigation' => file_exists(__DIR__.'/navigation.php') ? require __DIR__.'/navigation.php' : [],

    // helpers
    /* @param object{getPath(): string} $page */
    'isActive' => function ($page, $path) {
        return Str::endsWith(trimPath($page->getPath()), trimPath($path));
    },
    /* @param object{getPath(): string} $page */
    'isItemActive' => function ($page, $item) {
        return Str::endsWith(trimPath($page->getPath()), trimPath($item->getPath()));
    },
    /* @param object{getPath(): string, children: \Illuminate\Support\Collection} $page */
    'isActiveParent' => function ($page, $menuItem) {
        if (is_object($menuItem) && property_exists($menuItem, 'children') && $menuItem->children instanceof Illuminate\Support\Collection) {
            return $menuItem->children->contains(function ($child) use ($page) {
                return trimPath($page->getPath()) == trimPath($child);
            });
        }

        return false;
    }, /*
    'url' => function ($page, $path) {
        return Str::startsWith($path, 'http') ? $path : '/' . trimPath($path);
    },
    */
    'url' => function ($page, $path) {
        if (Str::startsWith($path, 'http')) {
            return $path;
        }

        // return url('/'.$page->lang.'/'.trimPath($path));
        return url('/'.trimPath($path));
    },

    /* @param object{id: mixed} $page */
    'children' => function ($page, $docs) {
        if ($docs instanceof Illuminate\Support\Collection) {
            return $docs->where('parent_id', $page->id);
        }

        return collect();
    },
];
