<?php

declare(strict_types=1);

namespace Modules\Notify\Tests\Unit\Models;

use Modules\Notify\Enums\NotificationTypeEnum;
use Modules\Notify\Models\NotificationTemplate;
<<<<<<< HEAD
<<<<<<< HEAD
use PHPUnit\Framework\Assert;

=======
use Modules\Notify\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class)->group('no-notify-db');

>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
use PHPUnit\Framework\Assert;

>>>>>>> a988596b (first)
/**
 * Unit tests must not bootstrap the application container.
 */
it('has correct fillable fields', function (): void {
    $reflection = new \ReflectionClass(NotificationTemplate::class);
    $instance = $reflection->newInstanceWithoutConstructor();

    $fillableProperty = $reflection->getProperty('fillable');
    $fillableProperty->setAccessible(true);

    $fillable = $fillableProperty->getValue($instance);

    $expectedFillable = [
        'name',
        'code',
        'description',
        'subject',
        'body_html',
        'body_text',
        'channels',
        'variables',
        'conditions',
        'preview_data',
        'metadata',
        'category',
        'is_active',
        'version',
        'tenant_id',
        'grapesjs_data',
<<<<<<< HEAD
<<<<<<< HEAD
        'type'];
=======
        'type',
    ];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
        'type'];
>>>>>>> a988596b (first)

    Assert::assertSame($expectedFillable, $fillable);
});

it('has correct casts', function (): void {
    $reflection = new \ReflectionClass(NotificationTemplate::class);
    $instance = $reflection->newInstanceWithoutConstructor();

    $castsMethod = $reflection->getMethod('casts');
    $castsMethod->setAccessible(true);

    $casts = $castsMethod->invoke($instance);

    $expectedCasts = [
        'type' => NotificationTypeEnum::class,
        'preview_data' => 'array',
        'body_html' => 'string',
        'body_text' => 'string',
        'channels' => 'array',
        'variables' => 'array',
        'conditions' => 'array',
        'metadata' => 'array',
        'is_active' => 'boolean',
<<<<<<< HEAD
<<<<<<< HEAD
        'grapesjs_data' => 'array'];
=======
        'grapesjs_data' => 'array',
    ];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
        'grapesjs_data' => 'array'];
>>>>>>> a988596b (first)

    Assert::assertSame($expectedCasts, $casts);
});

it('has translatable fields', function (): void {
    $reflection = new \ReflectionClass(NotificationTemplate::class);
    $instance = $reflection->newInstanceWithoutConstructor();

    $translatableProperty = $reflection->getProperty('translatable');
    $translatableProperty->setAccessible(true);

    $translatable = $translatableProperty->getValue($instance);

    $expectedTranslatable = [
        'subject',
        'body_text',
<<<<<<< HEAD
<<<<<<< HEAD
        'body_html'];
=======
        'body_html',
    ];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
        'body_html'];
>>>>>>> a988596b (first)

    Assert::assertSame($expectedTranslatable, $translatable);
});
