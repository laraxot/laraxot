<?php

declare(strict_types=1);

namespace Modules\AiAssistant\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Modules\AiAssistant\Models\AiActionProposal;
use Modules\AiAssistant\Models\AiThread;

/**
 * @extends Factory<AiActionProposal>
 */
class AiActionProposalFactory extends Factory
{
    /** @var class-string<AiActionProposal> */
    protected $model = AiActionProposal::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'public_id' => (string) Str::uuid(),
            'ai_thread_id' => AiThread::factory(),
            'proposed_by_user_id' => 1,
            'type' => 'noop',
            'payload' => [],
            'preview' => null,
            'status' => 'pending',
        ];
    }
}
