<?php

declare(strict_types=1);

use Illuminate\Support\Facades\File;
use Tests\TestCase;

uses(TestCase::class);

test('page-header renders breadcrumbs component', function () {
    $breadcrumbs = [
        ['label' => 'Home', 'url' => '/'],
        ['label' => 'Novità', 'url' => '/novita'],
    ];

    $html = view('pub_theme::components.blocks.listing.page-header', [
        'breadcrumbs' => $breadcrumbs,
        'eyebrow' => 'Category',
        'title' => 'Tutte le novità',
        'summary' => 'Rimani aggiornato sulle ultime notizie.',
    ])->render();

    expect($html)
        ->toContain('Home')
        ->toContain('Novità')
        ->toContain('Tutte le novità')
        ->toContain('Rimani aggiornato')
        ->toContain('cmp-breadcrumbs');
})->skip('Skipping DatabaseTransactions issue');

test('featured-cards renders correct grid layout', function () {
    $items = [
        [
            'image' => '/images/news1.jpg',
            'category' => 'Novità',
            'date' => '15 Aprile 2026',
            'title' => 'Titolo notizia principale',
            'description' => 'Descrizione breve della notizia principale.',
            'url' => '/novita/1',
        ],
        [
            'image' => '/images/news2.jpg',
            'category' => 'Avviso',
            'date' => '14 Aprile 2026',
            'title' => 'Titolo avviso importante',
            'description' => 'Descrizione breve dell\'avviso importante.',
            'url' => '/novita/2',
        ],
        [
            'image' => '/images/news3.jpg',
            'category' => 'Comunicato',
            'date' => '13 Aprile 2026',
            'title' => 'Titolo comunicato ufficiale',
            'description' => 'Descrizione breve del comunicato ufficiale.',
            'url' => '/novita/3',
        ],
    ];

    $html = view('pub_theme::components.blocks.listing.featured-cards', [
        'title' => 'In evidenza',
        'items' => $items,
    ])->render();

    expect($html)
        ->toContain('row')
        ->toContain('col-12')
        ->toContain('col-md-6')
        ->toContain('col-lg-4')
        ->toContain('Titolo notizia principale')
        ->toContain('Titolo avviso importante')
        ->toContain('Titolo comunicato ufficiale')
        ->toContain('section');
})->skip('Skipping DatabaseTransactions issue');

test('search-results renders items and summary', function () {
    $items = [
        [
            'image' => '/images/news1.jpg',
            'category' => 'Novità',
            'date' => '15 Aprile 2026',
            'title' => 'Notizia importante',
            'description' => 'Descrizione della notizia importante.',
            'url' => '/novita/1',
        ],
        [
            'image' => '/images/news2.jpg',
            'category' => 'Avviso',
            'date' => '14 Aprile 2026',
            'title' => 'Avviso ai cittadini',
            'description' => 'Descrizione dell\'avviso ai cittadini.',
            'url' => '/novita/2',
        ],
    ];

    $html = view('pub_theme::components.blocks.listing.search-results', [
        'title' => 'Risultati ricerca',
        'summary' => '62 notizie trovate',
        'items' => $items,
        'button_label' => 'Cerca',
    ])->render();

    expect($html)
        ->toContain('Notizia importante')
        ->toContain('Avviso ai cittadini')
        ->toContain('62 notizie trovate')
        ->toContain('row');
})->skip('Skipping DatabaseTransactions issue');

test('pagination renders correctly', function () {
    $html = view('pub_theme::components.blocks.listing.pagination', [
        'current_page' => 2,
        'pages' => [
            ['number' => 1, 'url' => '/novita?page=1'],
            ['number' => 2, 'url' => '/novita?page=2'],
            ['number' => 3, 'url' => '/novita?page=3'],
        ],
        'previous_label' => 'Precedente',
        'next_label' => 'Successivo',
    ])->render();

    expect($html)
        ->toContain('pagination')
        ->toContain('1')
        ->toContain('2')
        ->toContain('3');
})->skip('Skipping DatabaseTransactions issue');

/*
 * Le pagine dimostrative stanno in content/pages/tests/ e la lista risorse non e' piu' una sequenza di blocchi `listing_*`:
 * e' un solo blocco `list-page` (vista pub_theme::components.blocks.list-page.design-comuni) con titolo e sezioni.
 */
test('lista-risorse config loads correctly', function () {
    $configPath = base_path('config/local/fixcity/database/content/pages/tests/tests.lista-risorse.json');

    expect(File::exists($configPath))->toBeTrue();

    $config = json_decode(File::get($configPath), true);

    expect($config)
        ->toBeArray()
        ->toHaveKey('slug', 'lista-risorse')
        ->toHaveKey('content_blocks');

    expect($config['content_blocks'])
        ->toBeArray()
        ->toHaveKeys(['it', 'en']);

    $blocks = $config['content_blocks']['it'];
    expect(collect($blocks)->pluck('type')->all())->toBe(['list-page']);

    foreach ($blocks as $block) {
        expect($block)
            ->toHaveKey('type')
            ->toHaveKey('data');
    }

    $data = $blocks[0]['data'];
    expect($data)
        ->toHaveKey('view', 'pub_theme::components.blocks.list-page.design-comuni')
        ->toHaveKey('title')
        ->toHaveKey('sections');
    expect($data['sections'])->toBeArray()->not->toBeEmpty();
    expect(File::exists(base_path('Themes/Sixteen/resources/views/components/blocks/list-page/design-comuni.blade.php')))->toBeTrue();
});
