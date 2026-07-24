<?php

declare(strict_types=1);

namespace Modules\Email\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Email\Models\EmailConversation;

/**
 * @extends Factory<EmailConversation>
 */
class EmailConversationFactory extends Factory
{
    /** @var class-string<EmailConversation> */
    protected $model = EmailConversation::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'email' => $this->faker->unique()->safeEmail(),
            'display_name' => $this->faker->name(),
            'status' => 'open',
            'meta' => [],
        ];
    }
}
