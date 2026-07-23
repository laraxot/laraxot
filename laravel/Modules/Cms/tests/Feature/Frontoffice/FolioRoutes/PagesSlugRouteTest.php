<?php

declare(strict_types=1);

use Modules\Cms\Tests\TestCase;

uses(TestCase::class);
it('SKIP dynamic /it/{slug}', function (): void {
    cmsSkipTest('Dynamic pages slug requires fixture.');
});
