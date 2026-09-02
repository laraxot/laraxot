<?php

declare(strict_types=1);

namespace Modules\Blog\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Xot\Models\XotBasePivot;

/**
 * Base Pivot for Blog module.
 *
 * Extends XotBasePivot and adds:
 * - Soft Deletes support
 *
 * Standard properties (snakeAttributes, incrementing, perPage, etc.)
 * and common casts are inherited from XotBasePivot.
 *
 * @see XotBasePivot
 */
abstract class BasePivot extends XotBasePivot
{
    use SoftDeletes;

    /**
     * The connection name for the model.
     */
    protected $connection = 'blog';

    /**
     * Get the attributes that should be cast.
     *
     * Common casts (including deleted_at for SoftDeletes) are inherited from XotBasePivot.
     * Add module-specific casts here if needed.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            // Module-specific casts only
            // 'uuid' => 'string',  // If needed
        ]);
    }
}
