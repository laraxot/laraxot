<?php

declare(strict_types=1);

namespace Modules\Blog\Support;

use Illuminate\Support\Str;
use Modules\Xot\Actions\Cast\SafeStringCastAction;

final class ArticleReadTimeFormatter
{
    /**
     * @param array<string, mixed> $attributes
     */
    public function fromAttributes(array $attributes): string
    {
        $words = Str::wordCount(strip_tags(SafeStringCastAction::cast($attributes['body'] ?? '')));
        $minutes = ceil($words / 200);

        return $minutes.' '.str('min')->plural((int) $minutes).', '
            .$words.' '.str('word')->plural($words);
    }
}
