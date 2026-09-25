<?php

declare(strict_types=1);

namespace Modules\Blog\Support;

use Modules\Xot\Actions\Cast\SafeStringCastAction;

final class ArticleMainImageResolver
{
    /**
     * @param array<string, mixed> $attributes
     */
    public function fromAttributes(array $attributes): string
    {
        $imageUpload = $attributes['main_image_upload'] ?? null;
        $imageUrl = $attributes['main_image_url'] ?? null;
        $result = $imageUpload ?? $imageUrl ?? '#';

        return SafeStringCastAction::cast($result);
    }
}
