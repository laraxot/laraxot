<?php

declare(strict_types=1);

namespace Modules\User\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
<<<<<<< HEAD
<<<<<<< HEAD
use Modules\User\Models\ModelHasPermission;

/**
 * @extends Factory<ModelHasPermission>
 */
=======
use Illuminate\Database\Eloquent\Model;
use Modules\User\Models\ModelHasPermission;

>>>>>>> f548be94 (.)
=======
use Illuminate\Database\Eloquent\Model;
use Modules\User\Models\ModelHasPermission;

=======
use Modules\User\Models\ModelHasPermission;

/**
 * @extends Factory<ModelHasPermission>
 */
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
class ModelHasPermissionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
<<<<<<< HEAD
<<<<<<< HEAD
=======
     *
     * @var class-string<Model>
>>>>>>> f548be94 (.)
=======
     *
     * @var class-string<Model>
=======
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
     */
    protected $model = ModelHasPermission::class;

    /**
     * Define the model's default state.
<<<<<<< HEAD
<<<<<<< HEAD
     */
    /**
     * @return array<string, mixed>
=======
     *
     * @psalm-return array<never, never>
>>>>>>> f548be94 (.)
=======
     *
     * @psalm-return array<never, never>
=======
     */
    /**
     * @return array<string, mixed>
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
     */
    public function definition(): array
    {
        return [];
    }
}
