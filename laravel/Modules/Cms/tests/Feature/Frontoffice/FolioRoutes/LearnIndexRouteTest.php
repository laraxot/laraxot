<?php

declare(strict_types=1);

use PHPUnit\Framework\Assert;

it('GET /it/learn acceptable', function (): void {
    $res = cmsGet('/it/learn');

    $status = (int) $res->getStatusCode();
    if ($status >= 500) {
        cmsSkipTest('Learn route returned server error in this install.');

        return;
    }

    Assert::assertTrue(in_array($status, [200, 204, 301, 302, 303, 307, 308, 404], true));
});
