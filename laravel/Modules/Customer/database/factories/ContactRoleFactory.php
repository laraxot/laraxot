<?php

declare(strict_types=1);

namespace Modules\Customer\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Customer\Models\ContactRole;

/**
 * @extends Factory<ContactRole>
 */
class ContactRoleFactory extends Factory
{
    /** @var class-string<ContactRole> */
    protected $model = ContactRole::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->randomElement(['Titolare', 'Tecnico', 'Amministrazione', 'Referente cantiere']),
        ];
    }
}
