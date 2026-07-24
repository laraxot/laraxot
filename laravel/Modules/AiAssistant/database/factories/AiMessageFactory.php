<?php

declare(strict_types=1);

namespace Modules\AiAssistant\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\AiAssistant\Models\AiMessage;
use Modules\AiAssistant\Models\AiThread;

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
        return [
            'ai_thread_id' => AiThread::factory(),
            'user_id' => null,
            'role' => 'user',
            'content' => $this->faker->paragraph(),
            'payload' => null,
        ];
    }
}
