<?php

declare(strict_types=1);

namespace Modules\Notify\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use Modules\Notify\Models\Contact;

/**
 * @extends Factory<Contact>
 */
class ContactFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<Contact>
     */
    protected $model = Contact::class;

    /**
     * Define the model's default state.
     */
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'model_id' => (string) fake()->uuid(),
            'model_type' => fake()->word,
            'contact_type' => fake()->word,
            'value' => fake()->word,
            'verified_at' => fake()->dateTime,
            'updated_at' => fake()->dateTime,
            'created_at' => fake()->dateTime,
            'updated_by' => fake()->word,
            'created_by' => fake()->word,
            // 'user_id' => $this->faker->randomNumber(5, false),
<<<<<<< HEAD
<<<<<<< HEAD
            'token' => fake()->word];
=======
            'token' => fake()->word,
        ];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'token' => fake()->word];
>>>>>>> a988596b (first)
    }
}
