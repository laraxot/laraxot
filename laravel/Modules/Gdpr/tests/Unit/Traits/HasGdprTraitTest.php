<?php

declare(strict_types=1);

namespace Modules\Gdpr\Tests\Unit\Traits;

use Modules\Gdpr\Models\Traits\HasGdpr;
use Modules\Gdpr\Tests\Fixtures\HasGdprDummy;
use Modules\Gdpr\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

test('has_gdpr_trait_is_trait', function (): void {
    Assert::assertTrue(trait_exists(HasGdpr::class));
});

test('has_gdpr_trait_has_required_methods', function (): void {
    $methods = get_class_methods(HasGdpr::class);

    Assert::assertContains('consents', $methods);
    Assert::assertContains('activeConsents', $methods);
    Assert::assertContains('treatments', $methods);
    Assert::assertContains('hasGivenConsent', $methods);
    Assert::assertContains('giveConsent', $methods);
    Assert::assertContains('revokeConsent', $methods);
    Assert::assertContains('getMissingRequiredConsents', $methods);
    Assert::assertContains('hasAllRequiredConsents', $methods);
});

test('has_gdpr_trait_methods_are_public', function (): void {
    $reflection = new \ReflectionClass(HasGdpr::class);

    foreach ([
        'consents',
        'activeConsents',
        'treatments',
        'hasGivenConsent',
        'giveConsent',
        'revokeConsent',
        'getMissingRequiredConsents',
        'hasAllRequiredConsents',
    ] as $method) {
        Assert::assertTrue($reflection->hasMethod($method));
        Assert::assertTrue($reflection->getMethod($method)->isPublic());
    }
});

test('has_gdpr_trait_builds_consent_cache_key_from_string_id', function (): void {
    // Eloquent auto-casts the primary key to `getKeyType()` while `incrementing`
    // is true (default `int`), which would silently coerce a string id to 0.
    // Disable it here to simulate a string-keyed model (e.g. UUID primary key).
    $model = new HasGdprDummy();
    $model->incrementing = false;
    $model->setKeyType('string');
    $model->setAttribute('id', 'abc-123');

    $method = new \ReflectionMethod($model, 'gdprConsentCacheKey');

    Assert::assertSame('user_abc-123_consent_marketing', $method->invoke($model, 'marketing'));
});

test('has_gdpr_trait_builds_consent_cache_key_from_int_id', function (): void {
    $model = new HasGdprDummy();
    $model->setAttribute('id', 42);

    $method = new \ReflectionMethod($model, 'gdprConsentCacheKey');

    Assert::assertSame('user_42_consent_marketing', $method->invoke($model, 'marketing'));
});

test('has_gdpr_trait_throws_for_unsupported_primary_key_type', function (): void {
    // Same reasoning as above: disable the auto int-cast so the raw (unsupported)
    // attribute value reaches gdprKeyAsString() instead of being coerced to int.
    $model = new HasGdprDummy();
    $model->incrementing = false;
    $model->setAttribute('id', ['not', 'scalar']);

    $method = new \ReflectionMethod($model, 'gdprKeyAsString');

    expect(fn () => $method->invoke($model))->toThrow(\RuntimeException::class);
});
