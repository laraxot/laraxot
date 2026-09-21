<?php

declare(strict_types=1);

it('GET /it/auth/register is reachable', function (): void {
    $res = cmsGet('/it/auth/register');
});
