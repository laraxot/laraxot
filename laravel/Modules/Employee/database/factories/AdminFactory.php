<?php

declare(strict_types=1);

namespace Modules\Employee\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Employee\Models\Admin;

/** @extends Factory<Admin> */
class AdminFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = Admin::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [];
    }
}
