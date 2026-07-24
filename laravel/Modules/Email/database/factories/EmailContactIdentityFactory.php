<?php

declare(strict_types=1);

namespace Modules\Email\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Email\Models\EmailContactIdentity;

/**
 * @extends Factory<EmailContactIdentity>
 */
class EmailContactIdentityFactory extends Factory
{
    /** @var class-string<EmailContactIdentity> */
    protected $model = EmailContactIdentity::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'email' => $this->faker->unique()->safeEmail(),
            'meta' => [],
        ];
    }
}
