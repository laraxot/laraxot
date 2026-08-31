<?php

declare(strict_types=1);

use PHPUnit\Framework\Assert;

it('does not contain the legacy notification tracking controller', function (): void {
    $controllerPath = dirname(__DIR__, 4)
        .'/app/Http/Controllers/NotificationTrackingController.php';

    Assert::assertFileDoesNotExist($controllerPath);
});
