<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

test('skiplinks component renders correctly', function (): void {
    /** @var TestCase $this */
    $view = $this->view('pub_theme::bootstrap-italia.skiplinks', [
        'links' => [
            ['label' => 'Vai al contenuto', 'href' => '#content'],
            ['label' => 'Vai al menu', 'href' => '#navigation'],
        ],
    ]);

    $view->assertSee('skiplinks');
    $view->assertSee('Vai al contenuto');
    $view->assertSee('#content');
    $view->assertSee('screen reader');
});

test('cookiebar component has gdpr features', function (): void {
    /** @var TestCase $this */
    $view = $this->view('pub_theme::bootstrap-italia.cookiebar', [
        'acceptText' => 'Accetta tutti',
        'rejectText' => 'Rifiuta tutti',
        'customizeText' => 'Personalizza',
    ]);

    $view->assertSee('cookiebar');
    $view->assertSee('Accetta tutti');
    $view->assertSee('Rifiuta tutti');
    $view->assertSee('Personalizza');
    $view->assertSee('role="alert"');
});

test('hero component variants', function (): void {
    /** @var TestCase $this */
    // Test text variant
    $textHero = $this->view('pub_theme::bootstrap-italia.hero', [
        'type' => 'text',
        'title' => 'Hero Title',
        'subtitle' => 'Hero Subtitle',
    ]);

    $textHero->assertSee('hero-text');
    $textHero->assertSee('Hero Title');
    $textHero->assertSee('Hero Subtitle');

    // Test image variant
    $imageHero = $this->view('pub_theme::bootstrap-italia.hero', [
        'type' => 'image',
        'image' => '/images/hero.jpg',
        'imageAlt' => 'Hero Image',
    ]);

    $imageHero->assertSee('hero-image');
    $imageHero->assertSee('/images/hero.jpg');
    $imageHero->assertSee('Hero Image');
});

test('badge component variants', function (): void {
    /** @var TestCase $this */
    $view = $this->view('pub_theme::bootstrap-italia.badge', [
        'variant' => 'primary',
        'text' => 'Badge Text',
        'pill' => false,
    ]);

    $view->assertSee('badge');
    $view->assertSee('badge-primary');
    $view->assertSee('Badge Text');
    $view->assertDontSee('rounded-pill');
});

test('accordion component accessibility', function (): void {
    /** @var TestCase $this */
    $items = [
        [
            'id' => 'accordion-1',
            'title' => 'First Item',
            'content' => 'First content',
        ],
        [
            'id' => 'accordion-2',
            'title' => 'Second Item',
            'content' => 'Second content',
        ],
    ];

    $view = $this->view('pub_theme::bootstrap-italia.accordion', [
        'items' => $items,
    ]);

    $view->assertSee('accordion');
    $view->assertSee('First Item');
    $view->assertSee('Second Item');
    $view->assertSee('aria-expanded');
    $view->assertSee('role="button"');
});

test('select component options', function (): void {
    /** @var TestCase $this */
    $options = [
        'option1' => 'Option 1',
        'option2' => 'Option 2',
        'option3' => 'Option 3',
    ];

    $view = $this->view('pub_theme::bootstrap-italia.select', [
        'name' => 'test_select',
        'options' => $options,
        'label' => 'Select Label',
        'placeholder' => 'Choose an option',
    ]);

    $view->assertSee('form-select');
    $view->assertSee('Select Label');
    $view->assertSee('Choose an option');
    $view->assertSee('Option 1');
    $view->assertSee('Option 2');
    $view->assertSee('Option 3');
});

test('radio component grouping', function (): void {
    /** @var TestCase $this */
    $radios = [
        [
            'id' => 'radio1',
            'value' => 'value1',
            'label' => 'Radio 1',
        ],
        [
            'id' => 'radio2',
            'value' => 'value2',
            'label' => 'Radio 2',
        ],
    ];

    $view = $this->view('pub_theme::bootstrap-italia.radio', [
        'radios' => $radios,
        'name' => 'test_radio',
        'legend' => 'Radio Group',
    ]);

    $view->assertSee('fieldset');
    $view->assertSee('Radio Group');
    $view->assertSee('Radio 1');
    $view->assertSee('Radio 2');
    $view->assertSee('type="radio"');
});

test('upload component features', function (): void {
    /** @var TestCase $this */
    $view = $this->view('pub_theme::bootstrap-italia.upload', [
        'name' => 'file_upload',
        'label' => 'Upload File',
        'multiple' => true,
        'accept' => '.pdf,.doc,.docx',
    ]);

    $view->assertSee('upload');
    $view->assertSee('Upload File');
    $view->assertSee('multiple');
    $view->assertSee('.pdf,.doc,.docx');
    $view->assertSee('drag');
});

test('toggle component states', function (): void {
    /** @var TestCase $this */
    $view = $this->view('pub_theme::bootstrap-italia.toggle', [
        'name' => 'test_toggle',
        'label' => 'Toggle Label',
        'checked' => true,
    ]);

    $view->assertSee('toggles');
    $view->assertSee('lever');
    $view->assertSee('Toggle Label');
    $view->assertSee('checked');
});

test('megamenu component structure', function (): void {
    /** @var TestCase $this */
    $columns = [
        [
            'title' => 'Category 1',
            'links' => [
                ['label' => 'Link 1', 'url' => '/link1'],
                ['label' => 'Link 2', 'url' => '/link2'],
            ],
        ],
    ];

    $view = $this->view('pub_theme::bootstrap-italia.megamenu', [
        'title' => 'Megamenu',
        'columns' => $columns,
    ]);

    $view->assertSee('megamenu');
    $view->assertSee('Category 1');
    $view->assertSee('Link 1');
    $view->assertSee('Link 2');
    $view->assertSee('dropdown-toggle');
});

test('sidebar component navigation', function (): void {
    /** @var TestCase $this */
    $links = [
        [
            'label' => 'Home',
            'url' => '/',
            'active' => true,
        ],
        [
            'label' => 'Services',
            'url' => '/services',
        ],
    ];

    $view = $this->view('pub_theme::bootstrap-italia.sidebar', [
        'title' => 'Navigation',
        'links' => $links,
    ]);

    $view->assertSee('sidebar-wrapper');
    $view->assertSee('Navigation');
    $view->assertSee('Home');
    $view->assertSee('Services');
    $view->assertSee('active');
});

test('bottom nav component mobile', function (): void {
    /** @var TestCase $this */
    $items = [
        [
            'label' => 'Home',
            'url' => '/',
            'icon' => 'it-home',
        ],
        [
            'label' => 'Settings',
            'url' => '/settings',
            'icon' => 'it-settings',
        ],
    ];

    $view = $this->view('pub_theme::bootstrap-italia.bottom-nav', [
        'items' => $items,
        'fixed' => true,
    ]);

    $view->assertSee('bottom-nav');
    $view->assertSee('fixed-bottom');
    $view->assertSee('Home');
    $view->assertSee('Settings');
    $view->assertSee('it-home');
    $view->assertSee('it-settings');
});

test('progress indicators variants', function (): void {
    /** @var TestCase $this */
    // Test spinner
    $spinner = $this->view('pub_theme::bootstrap-italia.progress-indicators', [
        'type' => 'spinner',
        'active' => true,
        'size' => 'lg',
    ]);

    $spinner->assertSee('progress-spinner');
    $spinner->assertSee('progress-spinner-active');
    $spinner->assertSee('size-lg');

    // Test progress bar
    $progressBar = $this->view('pub_theme::bootstrap-italia.progress-indicators', [
        'type' => 'bar',
        'value' => 0.75,
        'showLabel' => true,
    ]);

    $progressBar->assertSee('progress-bar');
    $progressBar->assertSee('75%');
});

test('notifiche component states', function (): void {
    /** @var TestCase $this */
    $view = $this->view('pub_theme::bootstrap-italia.notifiche', [
        'title' => 'Test Notification',
        'message' => 'This is a test message',
        'type' => 'success',
        'dismissible' => true,
    ]);

    $view->assertSee('notification');
    $view->assertSee('success');
    $view->assertSee('Test Notification');
    $view->assertSee('This is a test message');
    $view->assertSee('btn-close');
    $view->assertSee('it-check-circle');
});

test('rating component accessibility', function (): void {
    /** @var TestCase $this */
    $view = $this->view('pub_theme::bootstrap-italia.rating', [
        'name' => 'test_rating',
        'legend' => 'Rate this service',
        'value' => 4,
        'showLabel' => true,
    ]);

    $view->assertSee('rating');
    $view->assertSee('fieldset');
    $view->assertSee('Rate this service');
    $view->assertSee('type="radio"');
    $view->assertSee('it-star-full');
    $view->assertSee('4 stelle');
});

test('tab component structure', function (): void {
    /** @var TestCase $this */
    $tabs = [
        'tab1' => [
            'label' => 'Tab 1',
            'content' => 'Content 1',
        ],
        'tab2' => [
            'label' => 'Tab 2',
            'content' => 'Content 2',
            'icon' => 'it-settings',
        ],
    ];

    $view = $this->view('pub_theme::bootstrap-italia.tab', [
        'tabs' => $tabs,
        'activeTab' => 'tab1',
    ]);

    $view->assertSee('nav-tabs');
    $view->assertSee('tab-content');
    $view->assertSee('Tab 1');
    $view->assertSee('Tab 2');
    $view->assertSee('Content 1');
    $view->assertSee('Content 2');
    $view->assertSee('it-settings');
    $view->assertSee('active');
});

test('components responsiveness', function (): void {
    /** @var TestCase $this */
    // Test that components include responsive classes
    $bottomNav = $this->view('pub_theme::bootstrap-italia.bottom-nav', [
        'items' => [['label' => 'Home', 'url' => '/']],
        'hiddenOnDesktop' => true,
    ]);

    $bottomNav->assertSee('d-lg-none');

    $megamenu = $this->view('pub_theme::bootstrap-italia.megamenu', [
        'title' => 'Menu',
        'fullWidth' => true,
    ]);

    $megamenu->assertSee('full-width');
});

test('components accessibility compliance', function (): void {
    /** @var TestCase $this */
    // Test that key components have proper ARIA attributes
    $accordion = $this->view('pub_theme::bootstrap-italia.accordion', [
        'items' => [
            ['id' => 'test', 'title' => 'Test', 'content' => 'Content'],
        ],
    ]);

    $accordion->assertSee('aria-expanded');
    $accordion->assertSee('aria-controls');

    $skiplinks = $this->view('pub_theme::bootstrap-italia.skiplinks');
    $skiplinks->assertSee('Salta al contenuto');
    $skiplinks->assertSee('screen reader');
});

test('component prop defaults', function (): void {
    /** @var TestCase $this */
    // Test default props are applied correctly
    $badge = $this->view('pub_theme::bootstrap-italia.badge');
    $badge->assertSee('badge');

    $rating = $this->view('pub_theme::bootstrap-italia.rating', [
        'name' => 'default_rating',
    ]);
    $rating->assertSee('rating');
    $rating->assertSee('Rating');
});

test('complete form integration', function (): void {
    /** @var TestCase $this */
    $view = $this->view('tests.bootstrap-italia-form-integration');

    // Should contain multiple Bootstrap Italia components
    $view->assertSee('form-select'); // Select component
    $view->assertSee('type="radio"'); // Radio component
    $view->assertSee('toggles'); // Toggle component
    $view->assertSee('upload'); // Upload component
    $view->assertSee('rating'); // Rating component
});

test('component rendering performance', function (): void {
    /** @var TestCase $this */
    $startTime = microtime(true);

    // Render multiple components
    $this->view('pub_theme::bootstrap-italia.hero', [
        'type' => 'text',
        'title' => 'Performance Test',
    ]);

    $this->view('pub_theme::bootstrap-italia.accordion', [
        'items' => array_fill(0, 10, [
            'id' => 'perf-test',
            'title' => 'Performance Item',
            'content' => 'Performance content',
        ]),
    ]);

    $endTime = microtime(true);
    $renderTime = $endTime - $startTime;

    // Should render in reasonable time (< 1 second)
    $this->assertLessThan(1.0, $renderTime, 'Component rendering took too long');
});
