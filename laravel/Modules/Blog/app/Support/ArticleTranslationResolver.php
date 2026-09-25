<?php

declare(strict_types=1);

namespace Modules\Blog\Support;

use Modules\Blog\Models\Article;
use Modules\Xot\Actions\Cast\SafeStringCastAction;

final class ArticleTranslationResolver
{
    /**
     * @return array<int|string, mixed>|string|int|null
     */
    public function resolve(Article $article, string $key, string $locale, bool $useFallbackLocale): array|string|int|null
    {
        if (! $article->isTranslatableAttribute($key)) {
            $value = $article->getAttribute($key);

            return null !== $value ? SafeStringCastAction::cast($value) : null;
        }

        $translations = $article->getTranslations($key);
        $translation = $translations[$locale] ?? '';

        if ('' !== $translation || ! $useFallbackLocale) {
            return $this->castTranslationValue($translation);
        }

        $fallbackLocale = config('app.fallback_locale');
        $fallbackKey = is_string($fallbackLocale) ? $fallbackLocale : 'en';
        $value = $translations[$fallbackKey] ?? '';

        return $this->castTranslationValue($value);
    }

    /**
     * @return array<int|string, mixed>|string|int|null
     */
    private function castTranslationValue(mixed $value): array|string|int|null
    {
        return match (true) {
            is_string($value) => $value,
            is_array($value) => $value,
            is_int($value) => $value,
            default => null,
        };
    }
}
