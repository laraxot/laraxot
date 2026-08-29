<?php

declare(strict_types=1);

uses(\Modules\Timber\Tests\TestCase::class);
use Modules\Timber\Actions\GenerateTimberCustomerLabelPdfAction;
use Modules\Timber\Actions\GenerateTimberCustomerSheetPdfAction;
use Modules\Timber\Models\TimberCustomer;

it('generates the customer sheet PDF', function (): void {
    $customer = TimberCustomer::factory()->createOne(['business_name' => 'Falegnameria Rossi Srl']);

    $pdf = app(GenerateTimberCustomerSheetPdfAction::class)->execute($customer);

    expect($pdf)->toStartWith('%PDF-')
        ->and(strlen($pdf))->toBeGreaterThan(800);
});

it('generates the customer address label PDF', function (): void {
    $customer = TimberCustomer::factory()->createOne();

    $pdf = app(GenerateTimberCustomerLabelPdfAction::class)->execute($customer);

    expect($pdf)->toStartWith('%PDF-');
});
