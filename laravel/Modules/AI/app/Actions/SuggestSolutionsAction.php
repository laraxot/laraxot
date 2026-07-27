<?php

declare(strict_types=1);

namespace Modules\AI\Actions;

use Illuminate\Support\Facades\Cache;
use Modules\AI\Actions\Prompt\BuildAIPromptAction;
use Modules\AI\Actions\Support\MakeAIRequestAction;
use Spatie\QueueableAction\QueueableAction;
use Webmozart\Assert\Assert;

class SuggestSolutionsAction
{
    use QueueableAction;

    public function __construct(
        private readonly ?string $title = null,
        private readonly ?string $description = null,
        private readonly ?string $category = null,
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function execute(?string $title = null, ?string $description = null, ?string $category = null): array
    {
        $title = $title ?? $this->title ?? '';
        $description = $description ?? $this->description ?? '';
        $category = $category ?? $this->category ?? '';
        $cacheKey = 'ai:solutions:'.md5($title.$description.$category);

        $result = Cache::remember($cacheKey, 1800, function () use ($title, $description, $category): string {
            $prompt = app(BuildAIPromptAction::class)->execute('solutions', [
                'title' => $title,
                'description' => $description,
                'category' => $category,
            ]);

            return app(MakeAIRequestAction::class, [
                'prompt' => $prompt,
                'type' => 'solutions',
            ])->execute();
        });

        Assert::string($result, 'Solutions result must be a JSON string');

        return app(AiJsonResponseDecoderAction::class)->execute($result);
    }

    /**
     * @return array<string, mixed>
     */
    public function handle(): array
    {
        return $this->execute();
    }
}
