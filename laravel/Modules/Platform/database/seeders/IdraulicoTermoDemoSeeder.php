<?php

declare(strict_types=1);

namespace Modules\Platform\Database\Seeders;

use Modules\Platform\Database\Seeders\Support\TradeProfile;

/**
 * Demo dataset themed for a plumber / heating engineer (idraulico / termoidraulico).
 * Same engine as SyntheticDataSeeder, plumbing-coherent content. Run before a demo
 * to a plumbing client: `php artisan db:seed --class="Modules\Platform\Database\Seeders\IdraulicoTermoDemoSeeder"`.
 */
final class IdraulicoTermoDemoSeeder extends SyntheticDataSeeder
{
    protected function profile(): TradeProfile
    {
        return TradeProfile::idraulicoTermo();
    }
}
