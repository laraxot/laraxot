<?php

declare(strict_types=1);

namespace Modules\User\Tests\Fixtures;

use Modules\User\Models\BaseUser;
<<<<<<< HEAD
use Modules\User\Models\User;
=======
>>>>>>> 87273113 (.)

/**
 * Named BaseUser probe for offline accessor coverage.
 */
final class UserGapBaseUserProbe extends BaseUser
{
    public function getTable(): string
    {
        return 'users';
    }
}
