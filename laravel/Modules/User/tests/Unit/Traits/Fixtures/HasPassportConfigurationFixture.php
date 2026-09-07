<?php

declare(strict_types=1);

namespace Modules\User\Tests\Unit\Traits\Fixtures;

use Illuminate\Support\ServiceProvider;
use Modules\User\Providers\Traits\HasPassportConfiguration;
<<<<<<< HEAD
use Modules\User\Models\User;
=======
>>>>>>> 87273113 (.)

/** PHPStan fixture: keeps HasPassportConfiguration trait in analysed graph. */
final class HasPassportConfigurationFixture extends ServiceProvider
{
    use HasPassportConfiguration;
}
