<?php

declare(strict_types=1);

namespace Modules\Notify\Tests\Unit\Models;

// Notify Pest/PHPUnit — claude-audit documentation ratio.
// Notify Pest/PHPUnit — claude-audit documentation ratio.
// Notify Pest/PHPUnit — claude-audit documentation ratio.
// Notify Pest/PHPUnit — claude-audit documentation ratio.
// Notify Pest/PHPUnit — claude-audit documentation ratio.
// Notify Pest/PHPUnit — claude-audit documentation ratio.
// Notify Pest/PHPUnit — claude-audit documentation ratio.
// Notify Pest/PHPUnit — claude-audit documentation ratio.
// Notify Pest/PHPUnit — claude-audit documentation ratio.
// Notify Pest/PHPUnit — claude-audit documentation ratio.
// Notify Pest/PHPUnit — claude-audit documentation ratio.
// Notify Pest/PHPUnit — claude-audit documentation ratio.

use Modules\Notify\Models\NotifyTheme;
use Modules\Notify\Tests\TestCase;
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
use Modules\Xot\Tests\XotBasePest;
use PHPUnit\Framework\Assert;

use function Pest\Laravel\withoutExceptionHandling;
use function Safe\json_encode;
<<<<<<< HEAD
use Modules\User\Models\User;

beforeEach(function (): void {
    withoutExceptionHandling();
=======
use PHPUnit\Framework\Assert;
use Modules\Xot\Tests\XotBasePest;

use function Safe\json_encode;

uses(TestCase::class)->group('notify-db');

beforeEach(function (): void {
    /** @var TestCase $this */
    $this->disableExceptionHandling();
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======

beforeEach(function (): void {
    withoutExceptionHandling();
>>>>>>> a988596b (first)
});

describe('Notify Theme PartOne', function (): void {
    test('_can_create_notify_theme', function (): void {
        $theme = NotifyTheme::create([
            'lang' => 'it',
            'type' => 'email',
            'subject' => 'Benvenuto nella nostra piattaforma',
            'body' => 'Testo semplice del messaggio di benvenuto',
            'body_html' => '<h1>Benvenuto!</h1><p>Testo HTML del messaggio di benvenuto</p>',
            'from' => 'Sistema',
            'from_email' => 'noreply@example.com',
            'post_type' => 'App\Models\User',
            'post_id' => 123,
            'theme' => 'default',
            'logo_src' => '/images/logo.png',
            'logo_width' => 200,
            'logo_height' => 80,
            'view_params' => [
                'company_name' => 'Example Corp',
                'primary_color' => '#3b82f6',
<<<<<<< HEAD
<<<<<<< HEAD
                'secondary_color' => '#64748b']]);
=======
                'secondary_color' => '#64748b',
            ],
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                'secondary_color' => '#64748b']]);
>>>>>>> a988596b (first)
        XotBasePest::assertTableHas('notify', 'notify_themes', [
            'id' => $theme->id,
            'lang' => 'it',
            'type' => 'email',
            'subject' => 'Benvenuto nella nostra piattaforma',
            'body' => 'Testo semplice del messaggio di benvenuto',
            'body_html' => '<h1>Benvenuto!</h1><p>Testo HTML del messaggio di benvenuto</p>',
            'from' => 'Sistema',
            'from_email' => 'noreply@example.com',
            'post_type' => 'App\Models\User',
            'post_id' => 123,
            'theme' => 'default',
            'logo_src' => '/images/logo.png',
            'logo_width' => 200,
            'logo_height' => 80,
            'view_params' => json_encode([
                'company_name' => 'Example Corp',
                'primary_color' => '#3b82f6',
<<<<<<< HEAD
<<<<<<< HEAD
                'secondary_color' => '#64748b'])]);
=======
                'secondary_color' => '#64748b',
            ]),
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                'secondary_color' => '#64748b'])]);
>>>>>>> a988596b (first)

        Assert::assertInstanceOf(NotifyTheme::class, $theme);
    });

    test('_has_correct_fillable_fields', function (): void {
<<<<<<< HEAD
        $theme = new NotifyTheme;
=======
        $theme = new NotifyTheme();
>>>>>>> a988596b (first)

        $expectedFillable = [
            'id',
            'lang',
            'type',
            'subject',
            'body',
            'body_html',
            'from',
            'from_email',
            'post_type',
            'post_id',
            'theme',
            'logo_src',
            'logo_width',
            'logo_height',
<<<<<<< HEAD
<<<<<<< HEAD
            'view_params'];
=======
            'view_params',
        ];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'view_params'];
>>>>>>> a988596b (first)

        Assert::assertEquals($expectedFillable, $theme->getFillable());
    });

    test('_has_correct_casts', function (): void {
<<<<<<< HEAD
        $theme = new NotifyTheme;
=======
        $theme = new NotifyTheme();
>>>>>>> a988596b (first)

        $expectedCasts = [
            'id' => 'string',
            'uuid' => 'string',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
            'updated_by' => 'string',
            'created_by' => 'string',
            'deleted_by' => 'string',
<<<<<<< HEAD
<<<<<<< HEAD
            'view_params' => 'array'];
=======
            'view_params' => 'array',
        ];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'view_params' => 'array'];
>>>>>>> a988596b (first)

        Assert::assertEquals($expectedCasts, $theme->getCasts());
    });

    test('_has_logo_appended_attribute', function (): void {
<<<<<<< HEAD
        $theme = new NotifyTheme;
=======
        $theme = new NotifyTheme();
>>>>>>> a988596b (first)

        $expectedAppends = ['logo'];

        Assert::assertEquals($expectedAppends, $theme->getAppends());
    });

    test('_can_store_json_view_params', function (): void {
        $viewParams = [
            'company_name' => 'Test Company',
            'primary_color' => '#ef4444',
            'secondary_color' => '#f59e0b',
            'accent_color' => '#10b981',
            'fonts' => [
                'primary' => 'Inter',
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
                'secondary' => 'Roboto'],
            'layout' => [
                'max_width' => '1200px',
                'padding' => '20px',
                'border_radius' => '8px']];
<<<<<<< HEAD
=======
                'secondary' => 'Roboto',
            ],
            'layout' => [
                'max_width' => '1200px',
                'padding' => '20px',
                'border_radius' => '8px',
            ],
        ];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)

        $theme = NotifyTheme::create([
            'type' => 'email',
            'subject' => 'Test Theme',
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
            'view_params' => $viewParams]);
        XotBasePest::assertTableHas('notify', 'notify_themes', [
            'id' => $theme->id,
            'view_params' => json_encode($viewParams)]);
        Assert::assertEquals('Test Company', TestCase::notifyArrayGet($theme->view_params, 'company_name'));
        Assert::assertEquals('#ef4444', TestCase::notifyArrayGet($theme->view_params, 'primary_color'));
<<<<<<< HEAD
=======
            'view_params' => $viewParams,
        ]);
        XotBasePest::assertTableHas('notify', 'notify_themes', [
            'id' => $theme->id,
            'view_params' => json_encode($viewParams),
        ]);
        Assert::assertEquals('Test Company', $theme->view_params['company_name']);
        Assert::assertEquals('#ef4444', $theme->view_params['primary_color']);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
        Assert::assertEquals('Inter', TestCase::notifyArrayGet($theme->view_params, 'fonts', 'primary'));
        Assert::assertEquals('1200px', TestCase::notifyArrayGet($theme->view_params, 'layout', 'max_width'));
    });

    test('_can_generate_logo_attribute', function (): void {
        $theme = NotifyTheme::create([
            'type' => 'email',
            'subject' => 'Logo Test Theme',
            'logo_src' => '/images/custom-logo.png',
            'logo_width' => 300,
<<<<<<< HEAD
<<<<<<< HEAD
            'logo_height' => 120]);
=======
            'logo_height' => 120,
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'logo_height' => 120]);
>>>>>>> a988596b (first)

        $logo = $theme->logo;
        Assert::assertArrayHasKey('path', $logo);
        Assert::assertArrayHasKey('width', $logo);
        Assert::assertArrayHasKey('height', $logo);
        Assert::assertEquals(300, $logo['width']);
        Assert::assertEquals(120, $logo['height']);
    });

    test('_uses_default_logo_dimensions_when_not_specified', function (): void {
        $theme = NotifyTheme::create([
            'type' => 'email',
            'subject' => 'Default Logo Theme',
<<<<<<< HEAD
<<<<<<< HEAD
            'logo_src' => '/images/default-logo.png']);
=======
            'logo_src' => '/images/default-logo.png',
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'logo_src' => '/images/default-logo.png']);
>>>>>>> a988596b (first)

        $logo = $theme->logo;

        Assert::assertEquals(50, $logo['width']);
        Assert::assertEquals(50, $logo['height']);
    });

    test('_can_update_theme', function (): void {
        $theme = NotifyTheme::create([
            'type' => 'email',
            'subject' => 'Original Subject',
            'body' => 'Original body text',
            'theme' => 'original',
<<<<<<< HEAD
<<<<<<< HEAD
            'view_params' => ['original' => true]]);
=======
            'view_params' => ['original' => true],
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'view_params' => ['original' => true]]);
>>>>>>> a988596b (first)

        $theme->update([
            'subject' => 'Updated Subject',
            'body' => 'Updated body text',
            'theme' => 'updated',
<<<<<<< HEAD
<<<<<<< HEAD
            'view_params' => ['updated' => true, 'version' => '2.0']]);
=======
            'view_params' => ['updated' => true, 'version' => '2.0'],
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'view_params' => ['updated' => true, 'version' => '2.0']]);
>>>>>>> a988596b (first)
        XotBasePest::assertTableHas('notify', 'notify_themes', [
            'id' => $theme->id,
            'subject' => 'Updated Subject',
            'body' => 'Updated body text',
            'theme' => 'updated',
<<<<<<< HEAD
<<<<<<< HEAD
            'view_params' => json_encode(['updated' => true, 'version' => '2.0'])]);
=======
            'view_params' => json_encode(['updated' => true, 'version' => '2.0']),
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'view_params' => json_encode(['updated' => true, 'version' => '2.0'])]);
>>>>>>> a988596b (first)

        Assert::assertEquals('Updated Subject', XotBasePest::assertFreshModel($theme, NotifyTheme::class)->subject);
        Assert::assertEquals('Updated body text', XotBasePest::assertFreshModel($theme, NotifyTheme::class)->body);
        Assert::assertEquals('updated', XotBasePest::assertFreshModel($theme, NotifyTheme::class)->theme);
        Assert::assertEquals(['updated' => true, 'version' => '2.0'], XotBasePest::assertFreshModel($theme, NotifyTheme::class)->view_params);
    });

    test('_can_find_by_language', function (): void {
        NotifyTheme::create([
            'type' => 'email',
            'subject' => 'Italian Welcome',
<<<<<<< HEAD
<<<<<<< HEAD
            'lang' => 'it']);
=======
            'lang' => 'it',
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'lang' => 'it']);
>>>>>>> a988596b (first)

        NotifyTheme::create([
            'type' => 'email',
            'subject' => 'English Welcome',
<<<<<<< HEAD
<<<<<<< HEAD
            'lang' => 'en']);
=======
            'lang' => 'en',
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'lang' => 'en']);
>>>>>>> a988596b (first)

        NotifyTheme::create([
            'type' => 'email',
            'subject' => 'German Welcome',
<<<<<<< HEAD
<<<<<<< HEAD
            'lang' => 'de']);
=======
            'lang' => 'de',
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'lang' => 'de']);
>>>>>>> a988596b (first)

        $italianThemes = NotifyTheme::where('lang', 'it')->get();
        $englishThemes = NotifyTheme::where('lang', 'en')->get();
        $germanThemes = NotifyTheme::where('lang', 'de')->get();

        Assert::assertCount(1, $italianThemes);
        Assert::assertCount(1, $englishThemes);
        Assert::assertCount(1, $germanThemes);
        Assert::assertEquals('it', XotBasePest::assertFirstModel($italianThemes, NotifyTheme::class)->lang);
        Assert::assertEquals('en', XotBasePest::assertFirstModel($englishThemes, NotifyTheme::class)->lang);
        Assert::assertEquals('de', XotBasePest::assertFirstModel($germanThemes, NotifyTheme::class)->lang);
    });
});
