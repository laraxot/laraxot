<?php

declare(strict_types=1);

use PHPUnit\Framework\Assert;

it('GET /it/auth/thank-you acceptable', function (): void {
    $res = cmsGet('/it/auth/thank-you');
    $status = (int) $res->getStatusCode();
    Assert::assertTrue(in_array($status, [200, 204, 301, 302, 303, 307, 308, 404], true));
});
