<?php

declare(strict_types=1);

namespace Modules\Platform\Database\Seeders;

use Modules\Platform\Database\Seeders\Support\TradeProfile;

/**
 * Demo dataset themed for an electrician (elettricista). Same engine as
 * SyntheticDataSeeder, electrical-coherent content. Run before a demo to an
 * electrical client: `php artisan db:seed --class="Modules\Platform\Database\Seeders\ElettricistaDemoSeeder"`.
 */
final class ElettricistaDemoSeeder extends SyntheticDataSeeder
{
    protected function profile(): TradeProfile
    {
        return TradeProfile::elettricista();
    }
}
