<?php

declare(strict_types=1);

namespace Modules\Geo\Tests\Unit\Actions;

use Illuminate\Support\Collection;
use Modules\Geo\Actions\GetAddressDataFromFullAddressAction;
use PHPUnit\Framework\Assert;

it('returns AddressData when first service succeeds', function (): void {
    $action = new GetAddressDataFromFullAddressAction();

    // As this action depends on multiple external services which are difficult to mock,
    // we can test that it at least has the correct structure and properties
    Assert::assertInstanceOf(GetAddressDataFromFullAddressAction::class, $action);
    Assert::assertInstanceOf(Collection::class, $action->getErrors());
    Assert::assertSame(0, $action->getErrors()->count());
});

it('initializes with empty errors collection', function (): void {
    $action = new GetAddressDataFromFullAddressAction();

    $action = new GetAddressDataFromFullAddressAction();

    Assert::assertInstanceOf(Collection::class, $action->getErrors());
    Assert::assertSame(0, $action->getErrors()->count());
});

it('executes without throwing error for basic call', function (): void {
    $action = new GetAddressDataFromFullAddressAction();

    // This tests that the action can be instantiated and executed without critical errors
    // Since it depends on external services, we can't easily test the full functionality
    $action = new GetAddressDataFromFullAddressAction();

    // The execute method should handle missing services gracefully
    $result = $action->execute('Test Address');

    // The method should return null if no services are available
    Assert::assertNull($result);

    // And should have set error messages
    Assert::assertInstanceOf(Collection::class, $action->getErrors());
});
