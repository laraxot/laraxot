<?php

declare(strict_types=1);

uses(\Modules\Costing\Tests\TestCase::class);

use Modules\Costing\Support\MarkupResolver;
use Modules\Costing\Tests\TestCase;

it('picks the most specific markup available', function (): void {
    $decision = MarkupResolver::firstOf([
        'item' => null,
        'work_center' => 40.0,
        'price_list' => 25.0,
    ]);

    expect($decision->percent)->toBe(40.0)
        ->and($decision->sourceCode)->toBe('work_center');
});

it('falls through every null candidate', function (): void {
    $decision = MarkupResolver::firstOf([
        'item' => null,
        'work_center' => null,
        'config' => 15.0,
    ]);

    expect($decision->sourceCode)->toBe('config');
});

it('says nobody decided instead of pretending zero was a choice', function (): void {
    $decision = MarkupResolver::firstOf(['item' => null, 'work_center' => null]);

    expect($decision->percent)->toBe(0.0)
        ->and($decision->sourceCode)->toBe('none');
});

it('keeps a zero markup that was actually set', function (): void {
    $decision = MarkupResolver::firstOf(['item' => 0.0, 'work_center' => 40.0]);

    expect($decision->percent)->toBe(0.0)
        ->and($decision->sourceCode)->toBe('item');
});

it('reads a numeric config default', function (): void {
    config(['costing_test.markup' => '12.5']);

    expect(MarkupResolver::configDefault('costing_test.markup'))->toBe(12.5);
});

it('treats a missing or nonsense config default as no markup', function (): void {
    expect(MarkupResolver::configDefault('costing_test.absent'))->toBe(0.0);

    config(['costing_test.rubbish' => 'quaranta']);
    expect(MarkupResolver::configDefault('costing_test.rubbish'))->toBe(0.0);
});

it('applies the markup to a cost', function (): void {
    $decision = MarkupResolver::firstOf(['work_center' => 40.0]);

    expect($decision->priceOf(100.0))->toBe(140.0)
        ->and($decision->priceOf(33.33))->toBe(46.66);
});
