<?php

declare(strict_types=1);

it('GET /it/auth/verify acceptable', function (): void {
    $res = cmsGet('/it/auth/verify');
});
