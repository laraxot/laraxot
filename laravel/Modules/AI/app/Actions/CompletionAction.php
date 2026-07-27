<?php

declare(strict_types=1);

namespace Modules\AI\Actions;

use Modules\AI\Datas\CompletionData;
use OpenAI\Laravel\Facades\OpenAI;
use Spatie\QueueableAction\QueueableAction;

class CompletionAction
{
    use QueueableAction;

    /**
     * Execute the completion action and return structured data.
     */
    public function execute(string $prompt): CompletionData
    {
        $result = OpenAI::completions()->create([
            // 'model' => 'text-davinci-003',
            'model' => 'gpt-3.5-turbo-instruct',
            'prompt' => $prompt,
            'temperature' => 0.5,
            'max_tokens' => 100,
            'top_p' => 1.0,
            'frequency_penalty' => 0.0,
            'presence_penalty' => 0.0,
        ]);

        // Map OpenAI response to Data Transfer Object
        $choice = $result->choices[0]->text;
        $usage = $result->usage;

        return new CompletionData(
            text: trim($choice),
            promptTokens: $usage->promptTokens,
            completionTokens: $usage->completionTokens ?? 0,
            totalTokens: $usage->totalTokens,
        );
    }
}
