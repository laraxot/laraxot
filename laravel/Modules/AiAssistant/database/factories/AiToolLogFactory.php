<?php

declare(strict_types=1);

namespace Modules\AiAssistant\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\AiAssistant\Models\AiThread;
use Modules\AiAssistant\Models\AiToolLog;

/**
 * @extends Factory<AiToolLog>
 */
class AiToolLogFactory extends Factory
{
    /** @var class-string<AiToolLog> */
    protected $model = AiToolLog::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'ai_thread_id' => AiThread::factory(),
            'ai_action_proposal_id' => null,
            'user_id' => null,
            'tool_name' => 'echo',
            'arguments' => [],
            'response' => [],
            'status' => 'ok',
            'error' => null,
        ];
    }
}
