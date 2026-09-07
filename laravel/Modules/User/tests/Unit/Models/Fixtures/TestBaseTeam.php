<?php

declare(strict_types=1);

namespace Modules\User\Tests\Unit\Models\Fixtures;

use Modules\User\Models\Team;
<<<<<<< HEAD
use Modules\User\Models\User;
=======
>>>>>>> 87273113 (.)

/**
 * Team concreto per test BaseTeam in memoria (no DB).
 */
final class TestBaseTeam extends Team
{
    protected $table = 'test_teams';
}
