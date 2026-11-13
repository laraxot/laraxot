<?php

declare(strict_types=1);

use Modules\Blog\Models\Article;
use PHPUnit\Framework\Assert;
use Tests\TestCase;
use Themes\Sixteen\Infrastructure\Url\FrontofficeRoutes;

use function Safe\file_get_contents;

uses(TestCase::class);

$views = dirname(__DIR__, 3).'/resources/views/components';

test('article body renders every block type through the UI renderer, sanitised', function (): void {
    $article = new Article;
    $article->setAttribute('title', 'Article title');
    $article->setAttribute('main_image_url', '');
    $article->setAttribute('content_blocks', [
        ['type' => 'title', 'data' => ['text' => 'Body heading', 'level' => 'h3']],
        ['type' => 'paragraph', 'data' => ['text' => '<p>First paragraph</p><script>alert(1)</script>']],
        ['type' => 'image_spatie', 'data' => ['img_uuid' => 'abc', 'url' => '/img/lead.png', 'caption' => 'Lead caption']],
        ['type' => 'image', 'data' => ['url' => '/img/inline.png', 'alt' => 'Inline alt', 'caption' => 'Inline caption', 'credit' => 'Inline credit']],
    ]);

    $html = view('pub_theme::components.blocks.partials.article-body', ['article' => $article])->render();

    Assert::assertStringContainsString('<h3>Body heading</h3>', $html);
    Assert::assertStringContainsString('<p>First paragraph</p>', $html);
    Assert::assertStringNotContainsString('<script>', $html);
    Assert::assertStringContainsString('src="/img/inline.png"', $html);
    Assert::assertStringContainsString('alt="Inline alt"', $html);
    Assert::assertStringContainsString('Inline credit', $html);
    Assert::assertStringContainsString('src="/img/lead.png"', $html);
    Assert::assertStringContainsString('Lead caption', $html);
});

test('article body shows the empty status only when asked and there is nothing to show', function (): void {
    $article = new Article;
    $article->setAttribute('title', 'Empty');
    $article->setAttribute('main_image_url', '');
    $article->setAttribute('content_blocks', []);

    $html = view('pub_theme::components.blocks.partials.article-body', ['article' => $article, 'emptyMessage' => 'Nothing yet'])->render();
    Assert::assertStringContainsString('role="status"', $html);
    Assert::assertStringContainsString('Nothing yet', $html);

    $silent = view('pub_theme::components.blocks.partials.article-body', ['article' => $article])->render();
    Assert::assertStringNotContainsString('role="status"', $silent);
});

test('blog and event detail bodies use the shared article body partial, not a paragraph filter', function () use ($views): void {
    foreach (['blog/detail.blade.php', 'events/detail.blade.php'] as $file) {
        $source = file_get_contents($views.'/blocks/'.$file);
        Assert::assertStringContainsString('pub_theme::components.blocks.partials.article-body', $source, $file);
        Assert::assertStringNotContainsString("where('type', 'paragraph')", $source, $file);
        Assert::assertStringNotContainsString("firstWhere('type', 'image')", $source, $file);
    }
});

test('design comuni search modal is a GET form with a labelled q field and a submit button', function (): void {
    $html = view('pub_theme::components.sections.search-modal.design-comuni', ['blocks' => [
        ['data' => ['title' => 'Cerca', 'label' => 'Cerca nel sito', 'placeholder' => 'Cerca nel sito', 'button' => 'Cerca', 'suggestions' => []]],
    ]])->render();

    // The modal searches the whole site (site-wide-front-office-search story), not only the tickets list.
    $searchUrl = FrontofficeRoutes::searchIndex();
    Assert::assertMatchesRegularExpression('/<form[^>]*method="get"[^>]*action="'.preg_quote($searchUrl, '/').'"[^>]*role="search"/', $html);
    Assert::assertMatchesRegularExpression('/<input[^>]*type="search"[^>]*id="autocomplete-two"[^>]*name="q"/', $html);
    Assert::assertStringContainsString('<label for="autocomplete-two"', $html);
    Assert::assertMatchesRegularExpression('/<button type="submit" class="btn btn-primary"/', $html);
});
