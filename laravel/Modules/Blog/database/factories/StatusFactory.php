<?php

declare(strict_types=1);

namespace Modules\Blog\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Blog\Models\Status;

/**
 * @extends Factory<Status>
 */
class StatusFactory extends Factory
{
    /** @var class-string<Status> */
    protected $model = Status::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
        ];
    }
}
