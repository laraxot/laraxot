<?php

declare(strict_types=1);

namespace Modules\AI\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\AI\Models\AiMessage;

/**
 * @extends Factory<AiMessage>
 */
class AiMessageFactory extends Factory
{
    /** @var class-string<AiMessage> */
    protected $model = AiMessage::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $role = $this->faker->randomElement([
            AiMessage::ROLE_USER,
            AiMessage::ROLE_ASSISTANT,
            AiMessage::ROLE_TOOL,
            AiMessage::ROLE_SYSTEM,
        ]);

        return [
            'ai_thread_id' => AiThreadFactory::new()->createOne()->id,
            'user_id' => $role === AiMessage::ROLE_USER ? $this->faker->numberBetween(1, 50) : null,
            'role' => $role,
            'content' => $this->faker->sentence(),
            'payload' => null,
        ];
    }
}
