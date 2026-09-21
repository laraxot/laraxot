<?php

declare(strict_types=1);

it('GET /it/patient/referto acceptable', function (): void {
    $res = cmsGet('/it/patient/referto');
});
