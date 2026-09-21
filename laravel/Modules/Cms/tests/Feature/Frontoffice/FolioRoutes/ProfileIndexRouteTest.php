<?php

declare(strict_types=1);

it('GET /it/profile acceptable (likely auth required)', function (): void {
    $res = cmsGet('/it/profile');
});
