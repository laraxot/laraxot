<?php

declare(strict_types=1);

use PHPUnit\Framework\Assert;
use Tests\TestCase;

use function Safe\file_get_contents;

uses(TestCase::class);

/**
 * @return array{view: string, layout: string, nav: array{next: string, ...}, steps: array{index: string, ...}, ...}
 */
function segnalazioneDatiPayload(): array
{
    $path = dirname(__DIR__, 4).'/Modules/Fixcity/config/database/content/pages/tests.segnalazione-02-dati.json';
    $page = json_decode(file_get_contents($path), true, 512, JSON_THROW_ON_ERROR);

    Assert::assertIsArray($page);
    Assert::assertIsArray($page['content_blocks']);
    Assert::assertIsArray($page['content_blocks']['it']);
    Assert::assertIsArray($page['content_blocks']['it'][0]);
    Assert::assertIsArray($page['content_blocks']['it'][0]['data']);

    $data = $page['content_blocks']['it'][0]['data'];
    Assert::assertIsString($data['view']);
    Assert::assertIsString($data['layout']);
    Assert::assertIsArray($data['nav']);
    Assert::assertIsString($data['nav']['next']);
    Assert::assertIsArray($data['steps']);
    Assert::assertIsString($data['steps']['index']);

    return $data;
}

test('the data step uses the shared CMS block instead of a page-specific Blade', function (): void {
    $data = segnalazioneDatiPayload();
    expect($data['view'])->toBe('pub_theme::components.blocks.report-step.design-comuni');
    expect($data['layout'])->toBe('sidebar');
    Assert::assertIsArray($data['nav']);
    Assert::assertIsString($data['nav']['next']);
    $this->view('pub_theme::components.blocks.report-step.design-comuni', ['data' => $data])
        ->assertSee('steppers-btn-confirm', false)
        ->assertSee('steppers-btn-prev', false)
        ->assertSee($data['nav']['next'])
        ->assertDontSee('confirmAndProceed()', false);
});

test('the data step exposes responsive step states from its CMS payload', function (): void {
    $data = segnalazioneDatiPayload();
    Assert::assertIsArray($data['steps']);
    Assert::assertIsString($data['steps']['index']);
    $this->view('pub_theme::components.blocks.report-step.design-comuni', ['data' => $data])
        ->assertSee('steppers-header', false)
        ->assertSee('steppers-index', false)
        ->assertSee($data['steps']['index'])
        ->assertSee('class="active"', false)
        ->assertSee('class="confirmed"', false);
});

test('the compiled CSS retains responsive step visibility without relying on source comments', function (): void {
    $root = dirname(__DIR__, 2);
    $manifest = json_decode(file_get_contents($root.'/public/manifest.json'), true, 512, JSON_THROW_ON_ERROR);
    Assert::assertIsArray($manifest);
    Assert::assertIsArray($manifest['resources/css/app.css']);
    Assert::assertIsString($manifest['resources/css/app.css']['file']);
    $css = file_get_contents($root.'/public/'.$manifest['resources/css/app.css']['file']);
    expect($css)->toContain('.steppers-header ul li:not(.active)')
        ->toContain('.steppers-index')
        ->toContain('@media');
});
