<?php

declare(strict_types=1);

namespace Modules\WorkOrder\Models;

use Modules\User\Models\BaseProfile;
use Modules\WorkOrder\Database\Factories\ProfileFactory;

/**
 * Profilo dominio WorkOrder — owner schema: create_profiles_table in questo modulo.
 */
class Profile extends BaseProfile
{
    protected $table = 'profiles';

    protected static function newFactory(): ProfileFactory
    {
        return ProfileFactory::new();
    }
}
