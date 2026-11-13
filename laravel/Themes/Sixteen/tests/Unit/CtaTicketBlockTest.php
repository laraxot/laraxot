<?php

declare(strict_types=1);
use Tests\TestCase;

use function Safe\file_get_contents;

uses(TestCase::class);

test('cta ticket block omits empty CMS payload', function (): void {
    /** @var TestCase $this */
    $view = $this->view('pub_theme::components.blocks.cta.ticket', [
        'cta' => [],
    ]);

    $view->assertDontSee('cmp-text-button', false);
    $view->assertDontSee('<h2', false);
});

test('cta ticket block renders custom payload', function (): void {
    /** @var TestCase $this */
    $view = $this->view('pub_theme::components.blocks.cta.ticket', [
        'cta' => [
            'title' => 'Custom title',
            'text' => 'Custom text',
            'button_text' => 'Custom button',
            'button_url' => '/tests/ticket-crea',
        ],
    ]);

    $view->assertSee('Custom title', false)
        ->assertSee('Custom text', false)
        ->assertSee('Custom button', false);
});

test('cta ticket block does not use frontoffice url', function (): void {
    $path = dirname(__DIR__, 2).'/resources/views/components/blocks/cta/ticket.blade.php';
    $html = file_get_contents($path);

    expect($html)->not->toContain('FrontofficeUrl');
});
