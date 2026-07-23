<?php

declare(strict_types=1);

namespace Modules\Blog\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Modules\Blog\Models\Article;
use Modules\Blog\Models\Category;
use Webmozart\Assert\Assert;

class ArticleSeeder extends Seeder
{
    /** @var list<array{name: string, image: string}> */
    /** @var array<int, array<string, mixed>> */
    private array $categories = [
        ['name' => 'Animals', 'image' => 'https://picsum.photos/id/219/800/600'],
        ['name' => 'Mountains', 'image' => 'https://picsum.photos/id/353/800/600'],
        ['name' => 'People', 'image' => 'https://picsum.photos/id/342/800/600'],
        ['name' => 'Things', 'image' => 'https://picsum.photos/id/252/800/600'],
    ];

    private Carbon $date;

    public function run(): void
    {
        $this->date = Carbon::now();

        foreach ($this->categories as $category) {
            Assert::isArray($category);
            Assert::string($category['name']);
            Category::create([
                'title' => $category['name'],
                'slug' => Str::slug($category['name']),
            ]);
        }

        // Featured posts
        for ($i = 0; $i < 2; ++$i) {
            $this->createArticle(['is_featured' => 1]);
        }

        // Published posts
        for ($i = 0; $i < 26; ++$i) {
            $this->createArticle();
        }

        // Draft posts
        for ($i = 0; $i < 2; ++$i) {
            $this->createArticle(['published_at' => null]);
        }
    }

    /**
     * @param array<string, mixed> $data
     *
     * @return Collection<int, Article>
     */
    private function createArticle(array $data = []): Collection
    {
        $date = $this->date->subDay();

        /* @phpstan-ignore-next-line argument.type */
        $categoryKey = array_rand($this->categories);
        Assert::keyExists($this->categories, $categoryKey, 'Category key must exist');

        $category = $this->categories[$categoryKey];
        Assert::isArray($category, 'Category must be an array');
        Assert::keyExists($category, 'image', 'Category must have image key');

        $defaults = [
            'created_at' => $date,
            'updated_at' => $date,
            'published_at' => $date,
            // 'category_id' => $category_key + 1,
            'main_image_url' => $category['image'],
        ];

        /** @var array<string, mixed> $mergedData */
        $mergedData = array_merge($defaults, $data);

        /** @var Factory<Article> $factory */
        $factory = Article::factory();
        Assert::object($factory, 'Factory must be an object');
        Assert::methodExists($factory, 'create', 'Factory must have create method');

        $result = $factory->create($mergedData);
        if ($result instanceof Collection) {
            /* @var \Illuminate\Database\Eloquent\Collection<int, Article> */
            return $result;
        }

        return new Collection();
    }
}
