<?php

declare(strict_types=1);

namespace Modules\PublicProcurement\Database\Seeders;

use Modules\PublicProcurement\Models\PublicProcurementContact;

class ZZZScratchDebug
{
    public function run(): void
    {
        $f = PublicProcurementContact::factory();
        \PHPStan\dumpType($f);
        $f2 = $f->count(3);
        \PHPStan\dumpType($f2);
        $f3 = $f2->create();
        \PHPStan\dumpType($f3);
    }
}
