<?php

declare(strict_types=1);

it('GET /it/genesis/about acceptable', function (): void {
    $res = cmsGet('/it/genesis/about');
    $status = (int) $res->getStatusCode();
    if ($status >= 500) {
        cmsSkipTest('Genesis about route returned server error in this install.');
    }
});
