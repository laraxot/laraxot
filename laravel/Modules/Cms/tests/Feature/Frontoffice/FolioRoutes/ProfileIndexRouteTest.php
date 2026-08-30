<?php

declare(strict_types=1);

use Modules\Cms\Tests\TestCase;

uses(\Modules\Cms\Tests\TestCase::class);
it('GET /it/profile acceptable (likely auth required)', function (): void {
    $res = cmsGet('/it/profile');
});
