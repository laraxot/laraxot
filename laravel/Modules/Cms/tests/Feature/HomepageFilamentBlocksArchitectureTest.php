<?php

declare(strict_types=1);

namespace Modules\Cms\Tests\Feature;

use Modules\Cms\Tests\TestCase;
use Modules\UI\Actions\Block\GetAllBlocksAction;

use function Pest\Laravel\get;

use PHPUnit\Framework\Assert;

/*
 * Homepage JSON: solo TestCase::homepageJsonForBlocksArchitecture() (PSR-4).
 * Vietato `use function Modules\Cms\Tests\loadHomepage...` — helper globale, senza namespace.
 * Dopo markTestSkipped non mettere return: PHPStan lo segnala come deadCode.unreachable.
 * Story ROOT-17.10.
 */

it('discovers and validates cms and ui blocks', function () {
    // `GetAllBlocksAction` compone QueueableAction: `execute()` e' un metodo di
    // istanza, la chiamata statica funzionava solo per il magic dispatch.
    $allBlocks = app(GetAllBlocksAction::class)->execute();

    $homepageJsonPath = config_path('local/fixcity/database/content/pages/home.json');
    expect(file_exists($homepageJsonPath))->toBeTrue();

    $homepageData = TestCase::homepageJsonForBlocksArchitecture();

    expect($homepageData)->toHaveKeys(['id', 'slug', 'content_blocks']);
    expect($homepageData['slug'])->toBe('home');
    expect($allBlocks->count())->toBeGreaterThan(0);

    $cmsBlocks = $allBlocks->toCollection()->filter(fn (mixed $block) => 'Cms' === $block->module);
    if ($cmsBlocks->count() > 0) {
        $cmsBlocks->each(function (mixed $block) {
            expect($block->toArray())->toHaveKeys(['name', 'class', 'module', 'path']);
            expect(class_exists($block->class))->toBeTrue();
        });
    }
});

test('homepage content management through cms works correctly', function () {
    /** @var TestCase $this */
    $homepageData = TestCase::homepageJsonForBlocksArchitecture();

    /** @var array<string, mixed> $contentBlocks */
    $contentBlocks = $homepageData['content_blocks'];

    if (! isset($contentBlocks[$this->lang])) {
        $this->markTestSkipped('No content blocks for language '.$this->lang);
    }

    $response = get('/'.$this->lang);
    $response->assertOk();

    $content = $response->getContent();

    /** @var array<int, array<string, mixed>> $blocks */
    $blocks = $contentBlocks[$this->lang];

    foreach ($blocks as $block) {
        /** @var array<string, mixed> $blockData */
        $blockData = $block['data'];
        if (isset($blockData['title'])) {
            expect($content)->toContain($blockData['title']);
        }
        if (isset($blockData['subtitle'])) {
            expect($content)->toContain($blockData['subtitle']);
        }
    }
});

test('cms theme integration renders blocks correctly', function () {
    /** @var TestCase $this */
    $homepageData = TestCase::homepageJsonForBlocksArchitecture();

    /** @var array<string, mixed> $contentBlocks */
    $contentBlocks = $homepageData['content_blocks'];

    if (! isset($contentBlocks[$this->lang])) {
        $this->markTestSkipped('No content blocks for language '.$this->lang);
    }

    $response = get('/'.$this->lang);
    $response->assertOk();

    $content = $response->getContent();

    expect($content)->toContain('pub_theme::');

    /** @var array<int, array<string, mixed>> $blocks */
    $blocks = $contentBlocks[$this->lang];

    foreach ($blocks as $block) {
        /** @var array<string, mixed> $blockData */
        $blockData = $block['data'];
        if (! isset($blockData['view'])) {
            continue;
        }
        $view = $blockData['view'];
        expect($view)->toStartWith('pub_theme::');
        expect($view)->toContain('components.blocks');
    }
});

test('cms handles multilingual content correctly', function () {
    /** @var TestCase $this */
    $homepageData = TestCase::homepageJsonForBlocksArchitecture();

    expect($homepageData['content_blocks'])->toBeArray();
    expect($homepageData['title'])->toBeArray();

    /** @var array<string, mixed> $contentBlocks */
    $contentBlocks = $homepageData['content_blocks'];
    /** @var array<string, mixed> $title */
    $title = $homepageData['title'];

    if (! isset($contentBlocks[$this->lang]) || ! isset($title[$this->lang])) {
        $this->markTestSkipped('Missing multilingual content for language '.$this->lang);
    }

    $response = get('/'.$this->lang);
    $response->assertOk();

    $content = $response->getContent();
    expect($content)->toContain($title[$this->lang]);
});

test('cms page component passes correct data to blocks', function () {
    /** @var TestCase $this */
    $response = get('/'.$this->lang);
    $response->assertOk();

    $content = $response->getContent();

    expect($content)->toContain('side="content"');
    expect($content)->toContain('slug="home"');

    if (auth()->check()) {
        expect($content)->toContain('type=');
    }
});

test('cms json storage pattern is consistent', function () {
    $pagesPath = config_path('local/fixcity/database/content/pages/');
    expect(file_exists($pagesPath))->toBeTrue();

    $homepageJsonPath = $pagesPath.'home.json';
    expect(file_exists($homepageJsonPath))->toBeTrue();

    $homepageData = TestCase::homepageJsonForBlocksArchitecture();

    expect($homepageData)->toHaveKeys(['id', 'slug', 'content_blocks']);
    expect($homepageData['slug'])->toBe('home');

    /** @var array<string, mixed> $contentBlocks */
    $contentBlocks = $homepageData['content_blocks'];

    foreach ($contentBlocks as $locale => $blocks) {
        expect($locale)->not->toBeEmpty();
        expect($blocks)->toBeArray();

        /** @var array<int, array<string, mixed>> $blocks */
        foreach ($blocks as $block) {
            expect($block)->toHaveKeys(['type', 'data']);
            expect($block['type'])->toBeString();
            expect($block['data'])->toBeArray();
            expect($block['data'])->toHaveKey('view');
        }
    }
});

test('cms blade syntax processing works in json', function () {
    /** @var TestCase $this */
    $homepageData = TestCase::homepageJsonForBlocksArchitecture();

    /** @var array<string, mixed> $contentBlocks */
    $contentBlocks = $homepageData['content_blocks'];

    if (! isset($contentBlocks[$this->lang])) {
        $this->markTestSkipped('No content blocks for language '.$this->lang);
    }

    /** @var array<int, array<string, mixed>> $blocks */
    $blocks = $contentBlocks[$this->lang];
    $landingBlock = collect($blocks)->firstWhere('type', 'landing-page');

    if (null !== $landingBlock) {
        /** @var array<string, mixed> $landingBlock */
        $landingBlockData = $landingBlock['data'];
        Assert::assertIsArray($landingBlockData);

        Assert::assertArrayHasKey('cta_link', $landingBlockData);
        Assert::assertIsString($landingBlockData['cta_link']);

        expect($landingBlockData['cta_link'])->toContain("{{ route('register') }}");

        $response = get('/'.$this->lang);
        $content = $response->getContent();

        $expectedUrl = route('register');
        expect($content)->toContain($expectedUrl);
    }
});

test('cms renders valid html structure', function () {
    /** @var TestCase $this */
    $response = get('/'.$this->lang);
    $response->assertOk();

    $content = $response->getContent();

    expect($content)->toContain('<!DOCTYPE html>');
    expect($content)->toContain('<html');
    expect($content)->toContain('<head>');
    expect($content)->toContain('<body>');
    expect($content)->toContain('<title>');

    expect($content)->toContain('<meta name="viewport"');
    expect($content)->toContain('<meta name="description"');
});

test('cms performance for block rendering is acceptable', function () {
    /** @var TestCase $this */
    $startTime = microtime(true);

    $response = get('/'.$this->lang);
    $response->assertOk();

    $renderTime = microtime(true) - $startTime;

    expect($renderTime)->toBeLessThan(2.0);
});
