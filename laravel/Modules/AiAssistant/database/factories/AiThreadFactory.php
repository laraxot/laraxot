<?php

declare(strict_types=1);

namespace Modules\AiAssistant\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Modules\AiAssistant\Models\AiThread;

/**
 * @extends Factory<AiThread>
 */
class AiThreadFactory extends Factory
{
    /** @var class-string<AiThread> */
    protected $model = AiThread::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'public_id' => (string) Str::uuid(),
            'created_by_user_id' => 1,
            'panel_id' => 'operator',
            'last_message_at' => null,
            'meta' => [],
        ];
    }
}
