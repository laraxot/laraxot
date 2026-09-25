<?php

declare(strict_types=1);

namespace Modules\Comment\Support;

use Illuminate\Support\Collection;
use Modules\Comment\Transformers\CommentTransformer;
use Webmozart\Assert\Assert;

class CommentConfigContent
{
    /** @return Collection<int, CommentTransformer> */
    public static function commentTransformers(): Collection
    {
        $transformers = config('comments.comment_transformers', []);
        if (! is_array($transformers)) {
            return new Collection;
        }

        $resolved = [];
        foreach ($transformers as $class) {
            if (! is_string($class)) {
                continue;
            }
            $instance = app($class);
            Assert::isInstanceOf($instance, CommentTransformer::class);
            $resolved[] = $instance;
        }

        return new Collection($resolved);
    }

    public static function commentSanitizer(): CommentSanitizer
    {
        $className = config('comments.comment_sanitizer', CommentSanitizer::class);
        $resolved = is_string($className) ? $className : CommentSanitizer::class;
        $sanitizer = app($resolved);
        Assert::isInstanceOf($sanitizer, CommentSanitizer::class);

        return $sanitizer;
    }

    /** @return array<string, list<string>> */
    public static function allowedAttributes(): array
    {
        $attributes = config('comments.allowed_attributes', []);
        if (! is_array($attributes)) {
            return [];
        }

        $normalized = [];
        foreach ($attributes as $attribute => $elements) {
            if (! is_string($attribute) || ! is_array($elements)) {
                continue;
            }
            $normalized[$attribute] = array_values(array_filter($elements, is_string(...)));
        }

        return $normalized;
    }
}
