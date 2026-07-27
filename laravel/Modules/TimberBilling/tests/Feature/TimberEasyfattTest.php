<?php

declare(strict_types=1);

use Modules\Catalog\Models\Product;
use Modules\Customer\Models\Customer;
use Modules\TimberBilling\Actions\SyncEasyfattCustomersAction;
use Modules\TimberBilling\Actions\SyncEasyfattProductsAction;
use Modules\TimberBilling\Legacy\ConfiguredEasyfattGateway;
use Modules\TimberBilling\Tests\TestCase;

uses(TestCase::class);

it('falls back to the demo dataset when the easyfatt connection is disabled', function (): void {
    $gateway = app(ConfiguredEasyfattGateway::class);

    $customers = $gateway->fetchCustomers();
    $products = $gateway->fetchProducts();

    expect($customers)->not->toBeEmpty()
        ->and($customers[0]->customerName)->not->toBe('')
        ->and($products)->not->toBeEmpty()
        ->and($products[0]->productName)->not->toBe('');
});

it('syncs demo customers into the Customers module, creating then updating idempotently', function (): void {
    $firstRun = app(SyncEasyfattCustomersAction::class)->execute();

    expect($firstRun['created'])->toBeGreaterThan(0)
        ->and(Customer::query()->count())->toBe($firstRun['created']);

    $secondRun = app(SyncEasyfattCustomersAction::class)->execute();

    expect($secondRun['created'])->toBe(0)
        ->and($secondRun['updated'])->toBeGreaterThan(0)
        ->and(Customer::query()->count())->toBe($firstRun['created']);
});

it('syncs demo products into the Catalog module, creating then updating idempotently', function (): void {
    $firstRun = app(SyncEasyfattProductsAction::class)->execute();

    expect($firstRun['created'])->toBeGreaterThan(0)
        ->and(Product::query()->count())->toBe($firstRun['created']);

    $secondRun = app(SyncEasyfattProductsAction::class)->execute();

    expect($secondRun['created'])->toBe(0)
        ->and($secondRun['updated'])->toBeGreaterThan(0)
        ->and(Product::query()->count())->toBe($firstRun['created']);
});
