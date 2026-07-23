<?php

declare(strict_types=1);

namespace Modules\Comment\Filament\Widgets\Commentable;

use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;
use Modules\Comment\Models\Contracts\Commentable;

class CommentsWidgetCommentableResolver
{
    public static function assignModel(
        CommentsWidget $widget,
        ?Model $model,
        ?string $commentableType,
        int|string|null $commentableKey,
    ): void {
        if (is_string($commentableType) && is_a($commentableType, Model::class, true)) {
            $widget->commentableType = $commentableType;
        }
        if ($commentableKey !== null && $commentableKey !== '') {
            $widget->commentableKey = (string) $commentableKey;
        }

        if ($model instanceof Commentable) {
            $widget->model = $model;
            $widget->commentableType ??= $model::class;
            $key = $model->getKey();
            if ($key !== null) {
                $widget->commentableKey ??= is_scalar($key) ? (string) $key : '';
            }

            return;
        }

        $resolved = self::resolveFromKeys($widget);
        if ($resolved instanceof Commentable) {
            $widget->model = $resolved;
        }
    }

    public static function resolve(CommentsWidget $widget): Commentable
    {
        if (! $widget->model instanceof Commentable) {
            $resolved = self::resolveFromKeys($widget);
            if ($resolved instanceof Commentable) {
                $widget->model = $resolved;
            }
        }

        if (! $widget->model instanceof Commentable) {
            throw new InvalidArgumentException('CommentsWidget requires a Commentable model.');
        }

        return $widget->model;
    }

    public static function resolveFromKeys(CommentsWidget $widget): ?Model
    {
        $type = $widget->commentableType;
        $key = $widget->commentableKey;

        if ($type === null || $type === '' || $key === null || $key === '') {
            return null;
        }

        if (! is_subclass_of($type, Model::class)) {
            return null;
        }

        /** @var class-string<Model> $type */
        $found = $type::query()->whereKey($key)->first();

        return $found instanceof Model ? $found : null;
    }
}
