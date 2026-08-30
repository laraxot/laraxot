<?php

declare(strict_types=1);

uses(\Modules\Costing\Tests\TestCase::class);

use Modules\Costing\Database\Factories\WorkCenterFactory;
use Modules\Costing\Models\WorkCenter;
use Modules\Costing\Tests\TestCase;

it('keeps one shop floor out of the other', function (): void {
    WorkCenterFactory::new()->createOne(['context_code' => 'signage', 'code' => 'plotter']);
    WorkCenterFactory::new()->createOne(['context_code' => 'metalwork', 'code' => 'taglio-laser']);

    $signage = WorkCenter::query()->forContext('signage')->pluck('code')->all();

    expect($signage)->toContain('plotter')
        ->and($signage)->not->toContain('taglio-laser');
});

it('costs an hour of the centre at its own rate', function (): void {
    $center = WorkCenterFactory::new()->createOne(['hourly_rate' => 45.00]);

    expect($center->costOfMinutes(60))->toBe(45.0)
        ->and($center->costOfMinutes(90))->toBe(67.5)
        ->and($center->costOfMinutes(0))->toBe(0.0);
});

it('lets the same code exist in two different contexts', function (): void {
    WorkCenterFactory::new()->createOne(['context_code' => 'signage', 'code' => 'montaggio']);
    $second = WorkCenterFactory::new()->createOne(['context_code' => 'metalwork', 'code' => 'montaggio']);

    expect($second->exists)->toBeTrue()
        ->and(WorkCenter::query()->where('code', 'montaggio')->count())->toBe(2);
});
