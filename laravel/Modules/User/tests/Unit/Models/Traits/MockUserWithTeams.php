<?php

declare(strict_types=1);

namespace Modules\User\Tests\Unit\Models\Traits;

use Illuminate\Database\Eloquent\Model;
use Modules\User\Models\Traits\HasTeams;
<<<<<<< HEAD
<<<<<<< HEAD
use Modules\Xot\Models\Traits\RelationX;
use Modules\User\Models\User;
=======
>>>>>>> 60a2c9a9 (.)
=======
=======
use Modules\Xot\Models\Traits\RelationX;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)

/**
 * Modello di supporto per i test del trait HasTeams.
 */
class MockUserWithTeams extends Model
{
    use HasTeams;
<<<<<<< HEAD
<<<<<<< HEAD
    use RelationX;
=======
>>>>>>> 60a2c9a9 (.)
=======
=======
    use RelationX;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)

    protected $table = 'users';

    protected $fillable = ['name', 'email'];

    public function getKey(): int
    {
        return 1;
    }
}
