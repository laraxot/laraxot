<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Tests\Feature;

use Modules\TimberBilling\Tests\TestCase;

uses(TestCase::class);

use Modules\TimberBilling\Actions\GenerateTimberCustomerLabelPdfAction;
use Modules\TimberBilling\Actions\GenerateTimberCustomerSheetPdfAction;
use Modules\TimberBilling\Models\TimberCustomer;

it('generates the customer sheet PDF', function (): void {
    $customer = TimberCustomer::factory()->create(['business_name' => 'Falegnameria Rossi Srl']);

    $pdf = app(GenerateTimberCustomerSheetPdfAction::class)->execute($customer);

    expect($pdf)->toStartWith('%PDF-')
        ->and(strlen($pdf))->toBeGreaterThan(800);
});

it('generates the customer address label PDF', function (): void {
    $customer = TimberCustomer::factory()->create();

    $pdf = app(GenerateTimberCustomerLabelPdfAction::class)->execute($customer);

    expect($pdf)->toStartWith('%PDF-');
});
