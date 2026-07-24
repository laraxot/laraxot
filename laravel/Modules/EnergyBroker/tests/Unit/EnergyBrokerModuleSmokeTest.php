<?php

declare(strict_types=1);

namespace Modules\EnergyBroker\Tests\Unit;

use Modules\EnergyBroker\Models\EnergyBrokerDocument;
use Modules\EnergyBroker\Models\EnergyBrokerSupplyPoint;
use Modules\EnergyBroker\Providers\EnergyBrokerServiceProvider;
use PHPUnit\Framework\Assert;

test('registers the EnergyBroker module classes', function (): void {
    Assert::assertTrue(class_exists(EnergyBrokerServiceProvider::class));
    Assert::assertTrue(class_exists(EnergyBrokerSupplyPoint::class));
    Assert::assertTrue(class_exists(EnergyBrokerDocument::class));
});
