<?php

declare(strict_types=1);

use Modules\Geo\Actions\Here\GetHereRouteDurationAndLengthAction;
use Modules\Geo\Adapters\HereClient;
use PHPUnit\Framework\Assert;

test('here client can be instantiated', function (): void {
    Assert::assertInstanceOf(HereClient::class, new HereClient());
});

test('here client delegates to GetHereRouteDurationAndLengthAction', function (): void {
    Assert::assertContains('getDurationAndLength', get_class_methods(HereClient::class));
    Assert::assertContains('execute', get_class_methods(GetHereRouteDurationAndLengthAction::class));
});
