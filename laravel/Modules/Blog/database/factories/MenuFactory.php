<?php

declare(strict_types=1);

namespace Modules\Blog\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Blog\Models\Menu;

/**
 * @extends Factory<Menu>
 */
/** @extends Factory<Menu> */
class MenuFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = Menu::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [];
    }
}
