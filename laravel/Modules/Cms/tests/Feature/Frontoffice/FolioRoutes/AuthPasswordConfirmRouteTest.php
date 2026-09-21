<?php

declare(strict_types=1);

it('GET /it/auth/password/confirm acceptable', function (): void {
    $res = cmsGet('/it/auth/password/confirm');
});
