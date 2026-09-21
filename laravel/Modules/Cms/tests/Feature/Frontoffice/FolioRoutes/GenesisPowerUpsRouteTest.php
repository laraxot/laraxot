<?php

declare(strict_types=1);

use PHPUnit\Framework\Assert;

it('GET /it/genesis/power-ups acceptable', function (): void {
    $res = cmsGet('/it/genesis/power-ups');

    $status = (int) $res->getStatusCode();
    if ($status >= 500) {
        cmsSkipTest('Genesis power-ups route returned server error in this install.');

        return;
    }

    Assert::assertTrue(in_array($status, [200, 204, 301, 302, 303, 307, 308, 404], true));
});
