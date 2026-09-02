<?php

declare(strict_types=1);

namespace Modules\Blog\Actions\Article;

use Modules\Xot\Actions\Cast\SafeStringCastAction;
use Spatie\QueueableAction\QueueableAction;

final class ResolveArticleMainImageFromAttributesAction
{
    use QueueableAction;

    /**
     * @param array<string, mixed> $attributes
     */
    public function execute(array $attributes): string
    {
        $imageUpload = $attributes['main_image_upload'] ?? null;
        $imageUrl = $attributes['main_image_url'] ?? null;
        $result = $imageUpload ?? $imageUrl ?? '#';

        return SafeStringCastAction::cast($result);
    }
}
