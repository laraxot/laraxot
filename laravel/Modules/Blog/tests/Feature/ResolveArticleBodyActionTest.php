<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Blog;

use Modules\Blog\Actions\Article\ResolveArticleBodyAction;
use Modules\Blog\Models\Article;
use Tests\TestCase;

/**
 * The public article body keeps every editor block (title, paragraph, image, image_spatie, ...),
 * not only paragraphs. One image becomes the lead figure; the rest stay in the body.
 */
final class ResolveArticleBodyActionTest extends TestCase
{
    /** Pure mapping over unsaved models: no schema work needed. */
    protected function runTestingMigrations(): void {}

    public function test_keeps_every_block_and_uses_the_first_image_as_figure_when_no_main_image(): void
    {
        $article = $this->article('', [
            ['type' => 'title', 'data' => ['text' => 'Body heading', 'level' => 'h3']],
            ['type' => 'paragraph', 'data' => ['text' => '<p>First paragraph</p>']],
            ['type' => 'image_spatie', 'data' => ['img_uuid' => 'abc', 'url' => '/img/one.png', 'caption' => 'First caption']],
            ['type' => 'paragraph', 'data' => ['text' => '<p>Second paragraph</p>']],
            ['type' => 'image', 'data' => ['url' => '/img/two.png', 'alt' => 'Second alt', 'caption' => 'Second caption']],
        ]);

        $body = app(ResolveArticleBodyAction::class)->execute($article);

        $this->assertSame(['src' => '/img/one.png', 'alt' => 'First caption', 'caption' => 'First caption', 'credit' => ''], $body['figure']);
        $this->assertSame(['title', 'paragraph', 'paragraph', 'image'], array_column($body['blocks'], 'type'));
        $this->assertSame('/img/two.png', $body['blocks'][3]['data']['src']);
        $this->assertSame('Second alt', $body['blocks'][3]['data']['alt']);
        $this->assertSame('h3', $body['blocks'][0]['data']['level']);
    }

    public function test_main_image_takes_metadata_only_from_a_matching_block(): void
    {
        $article = $this->article('/img/main.png', [
            ['type' => 'image', 'data' => ['url' => '/img/main.png', 'alt' => 'Main alt', 'caption' => 'Main caption', 'credit' => 'Main credit']],
            ['type' => 'image', 'data' => ['url' => '/img/other.png', 'alt' => 'Other alt']],
        ]);

        $body = app(ResolveArticleBodyAction::class)->execute($article);

        $this->assertSame(['src' => '/img/main.png', 'alt' => 'Main alt', 'caption' => 'Main caption', 'credit' => 'Main credit'], $body['figure']);
        $this->assertSame(['image'], array_column($body['blocks'], 'type'));
        $this->assertSame('/img/other.png', $body['blocks'][0]['data']['src']);
    }

    public function test_drops_a_heading_repeating_the_article_title_and_never_emits_h1(): void
    {
        $article = $this->article('', [
            ['type' => 'title', 'data' => ['text' => 'Article title', 'level' => 'h2']],
            ['type' => 'title', 'data' => ['text' => 'Section', 'level' => 'h1']],
            ['type' => 'title', 'data' => ['text' => 'No level']],
            ['type' => 'paragraph', 'data' => ['text' => '']],
            ['type' => 'image_spatie', 'data' => ['img_uuid' => 'missing']],
            'not a block',
        ]);

        $body = app(ResolveArticleBodyAction::class)->execute($article);

        $this->assertNull($body['figure']);
        $this->assertSame(['Section', 'No level'], array_map(static fn (array $block): mixed => $block['data']['text'] ?? null, $body['blocks']));
        $this->assertSame(['h2', 'h2'], array_map(static fn (array $block): mixed => $block['data']['level'] ?? null, $body['blocks']));
    }

    /**
     * @param  list<mixed>  $blocks
     */
    private function article(string $mainImage, array $blocks): Article
    {
        $article = new Article;
        $article->setAttribute('title', 'Article title');
        $article->setAttribute('main_image_url', $mainImage);
        $article->setAttribute('content_blocks', $blocks);

        return $article;
    }
}
