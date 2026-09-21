<?php

declare(strict_types=1);

it('GET /it/auth/logout acceptable (may redirect)', function (): void {
    $res = cmsGet('/it/auth/logout');
});
