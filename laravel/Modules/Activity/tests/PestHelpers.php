<?php

declare(strict_types=1);

use Modules\Activity\Database\Factories\ActivityFactory;
use Modules\Activity\Models\Activity;
use Modules\User\Database\Factories\UserFactory;
use Modules\User\Models\User;

/**
 * Helper Pest/PHPStan — modulo Activity.
 *
 * @see Modules/Platform/tests/PestHelpers.php
 */

/**
 * @param  array<string, mixed>  $attributes
 */
function activityCreateUser(array $attributes = []): User
{
    $user = UserFactory::new()->createOne($attributes);
    assert($user instanceof User);

    return $user;
}

/**
 * @param  array<string, mixed>  $attributes
 */
function activityCreateActivity(array $attributes = []): Activity
{
    $activity = ActivityFactory::new()->createOne($attributes);
    assert($activity instanceof Activity);

    return $activity;
}
