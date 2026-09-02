<?php

declare(strict_types=1);

namespace Modules\Blog\Database\Seeders;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Seeder;
use Modules\Cms\Models\Menu;
use Modules\Cms\Models\Page;
use Webmozart\Assert\Assert;

class SiteSeeder extends Seeder
{
    public function run(): void
    {
        /** @var Factory<Page> $pageFactory */
        $pageFactory = Page::factory();
        Assert::object($pageFactory, 'Page factory must be an object');
        Assert::methodExists($pageFactory, 'create', 'Page factory must have create method');

        $pageFactory->create([
            'slug' => 'about',
            'title' => 'About Us',
        ]);

        $pageFactory->create([
            'slug' => 'terms',
            'title' => 'Terms & Conditions',
        ]);

        Menu::create([
            'name' => 'main',
            'items' => [
                [
                    'title' => 'Blog',
                    'url' => '/blog',
                    'type' => 'internal',
                ],
                [
                    'title' => 'About',
                    'url' => '/about',
                    'type' => 'internal',
                ],
                [
                    'title' => 'Contact',
                    'url' => '/contact',
                    'type' => 'internal',
                ],
            ],
        ]);

        Menu::create([
            'name' => 'footer',
            'items' => [
                [
                    'title' => 'Terms & Conditions',
                    'url' => '/terms',
                    'type' => 'internal',
                ],
            ],
        ]);
    }
}
