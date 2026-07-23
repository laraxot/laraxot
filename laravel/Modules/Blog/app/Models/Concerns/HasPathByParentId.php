<?php

declare(strict_types=1);

namespace Modules\Blog\Models\Concerns;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use Webmozart\Assert\Assert;

/**
 * @property string $parent_id
 * @property string $path
 * @property string $name
 */
trait HasPathByParentId
{
    /**
     * @see https://github.com/vicklr/materialized-model/blob/main/src/Traits/HasMaterializedPaths.php
     * non più necessario
     * UPDATE assets SET depth=LENGTH(`path`) - LENGTH(REPLACE(`path`, '/', ''))-1
     */
    public function getPathAttribute(?string $value): string
    {
        if (null !== $value) {
            return $value;
        }
        $pathValue = $this->getPath();
        if (null !== $this->getKey()) {
            $this->update(['path' => $pathValue]);
        }

        return $pathValue;
    }

    public function getPath(): string
    {
        return ($this->parent ? $this->parent->path : '').$this->parent_id.'/';
    }

    public function getBreads(): string
    {
        $delim = ' > ';
        if (null === $this->parent) {
            return '';
        }

        $str = $this->parent->breads.$delim.$this->name;

        if (Str::startsWith($str, $delim)) {
            return Str::after($str, $delim);
        }

        return $str;
    }

    public function getRootIdAttribute(?string $value): ?string
    {
        if (null !== $value || null === $this->getKey()) {
            return $value;
        }
        $root = $this->rootAncestor()->first();
        if (null === $root) {
            $this->update(['root_id' => null]);

            return null;
        }
        Assert::isInstanceOf($root, static::class, '['.__LINE__.']['.__FILE__.']');
        Assert::nullOrString($value = $root->getKey());

        $this->update(['root_id' => $value]);

        return $value;
    }

    /** @return BelongsTo<static, $this> */
    public function root(): BelongsTo
    {
        return $this->belongsTo(
            static::class,
            'root_id',
            'id',
        );
    }

    public function getRootNameAttribute(?string $value): ?string
    {
        if (null !== $value || null === $this->getKey()) {
            return $value;
        }
        $root = $this->rootAncestor()->first();
        if (null === $root) {
            $this->update(['root_name' => null]);

            return null;
        }
        Assert::isInstanceOf($root, static::class, '['.__LINE__.']['.__FILE__.']');
        $value = $root->name;
        $this->update(['root_name' => $value]);

        return $value;
    }

    public function getIsLeafAttribute(?bool $value): ?bool
    {
        if (null !== $value || null === $this->getKey()) {
            return $value;
        }
        $value = (0 === $this->children()->count());
        $this->update(['is_leaf' => $value]);

        return $value;
    }

    public function getFullBreadsAttribute(?string $value): ?string // $value is unused but part of attribute getter
    {if (null !== $this->root_name) {
        return $this->root_name.' > '.$this->breads;
    }

        return $this->name;
    }

    public function getBreadsAttribute(?string $value): ?string
    {
        if (null !== $value || null === $this->getKey()) {
            return $value;
        }

        $computed = $this->getBreads();
        $this->update(['breads' => $computed]);

        return $computed;
    }

    public function setParentIdAttribute(?string $value): void
    {
        $path = $this->getPath();
        $breads = $this->getBreads();
        $this->attributes['parent_id'] = $value;
        $this->attributes['path'] = $path;
        $this->attributes['breads'] = $breads;
        // $this->attributes['depth'] = count(explode('/', $path)) - 1;
    }

    /*
    public function getDepthName(): string
    {
        return 'cte_depth';
    }

    public function getPathName(): string
    {
        return 'cte_path';
    }
    */
}
