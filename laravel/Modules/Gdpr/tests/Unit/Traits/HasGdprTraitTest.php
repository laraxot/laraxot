<?php

declare(strict_types=1);

namespace Modules\Gdpr\Tests\Unit\Traits;

use Modules\Gdpr\Models\Traits\HasGdpr;
use Modules\Gdpr\Tests\TestCase;

uses(TestCase::class);

test('has_gdpr_trait_is_trait', function (): void {
    expect(trait_exists(HasGdpr::class))->toBeTrue();
});

test('has_gdpr_trait_has_required_methods', function (): void {
    $methods = get_class_methods(HasGdpr::class);

    expect($methods)->toContain(
        'consents',
        'activeConsents',
        'treatments',
        'hasGivenConsent',
        'giveConsent',
        'revokeConsent',
        'getMissingRequiredConsents',
        'hasAllRequiredConsents',
    );
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
        expect($reflection->hasMethod($method))->toBeTrue();
        expect($reflection->getMethod($method)->isPublic())->toBeTrue();
    }
});
