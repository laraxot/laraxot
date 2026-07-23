<?php

declare(strict_types=1);

namespace Modules\Platform\Database\Seeders;

use Modules\Platform\Database\Seeders\Support\TradeProfile;

/**
 * Demo dataset themed for a metalworker (carpenteria metallica) — the default
 * theme, exposed as an explicit named seeder for consistency with the other trades.
 */
final class CarpenteriaDemoSeeder extends SyntheticDataSeeder
{
    protected function profile(): TradeProfile
    {
        return TradeProfile::carpenteria();
    }
}
