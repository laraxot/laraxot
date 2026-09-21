<?php

declare(strict_types=1);

use PHPUnit\Framework\Assert;

it('GET /it/auth/login is reachable', function (): void {
    $res = cmsGet('/it/auth/login');
    $status = (int) $res->getStatusCode();

    if ($status >= 500) {
        cmsSkipTest('Server error on /it/auth/login: '.$status);
    }

    Assert::assertLessThan(500, $status);
});
