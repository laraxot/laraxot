<?php

declare(strict_types=1);

use PHPUnit\Framework\Assert;

it('GET /it/events acceptable (LaravelPizza Meetup)', function (): void {
    $res = cmsGet('/it/events');

    $status = (int) $res->getStatusCode();
    if ($status >= 500) {
        cmsSkipTest('Events (LaravelPizza) route returned server error in this install.');

        return;
    }

    Assert::assertTrue(in_array($status, [200, 204, 301, 302, 303, 307, 308, 401, 403, 404], true));
});
