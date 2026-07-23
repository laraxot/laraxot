<?php

declare(strict_types=1);

namespace Tests\Unit;

test('HasBlocks getBlocksBySlug handles MultipleRecordsFoundException', function (): void {
    $reflectionClass = new \ReflectionClass(\Modules\Cms\Models\Traits\HasBlocks::class);
    $method = $reflectionClass->getMethod('getBlocksBySlug');
    expect($method)->not->toBeNull();
    expect($method->isStatic())->toBeTrue();
    expect($method->getReturnType()?->getName())->toBe('array');
});

test('getBlocksBySlug method signature is correct', function (): void {
    $reflectionClass = new \ReflectionClass(\Modules\Cms\Models\Traits\HasBlocks::class);
    $method = $reflectionClass->getMethod('getBlocksBySlug');
    $params = $method->getParameters();
    expect(count($params))->toBe(2);
    expect($params[0]->getName())->toBe('slug');
    expect($params[0]->getType()->getName())->toBe('string');
});
