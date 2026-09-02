<?php

declare(strict_types=1);

namespace Modules\Comment\Models;

/**
 * Class BaseModel.
 *
 * Base model for Comment module.
 * Extends XotBaseModel for common functionality.
 */
abstract class BaseModel extends \Modules\Xot\Models\XotBaseModel
{
    /** @var string Database connection name */
    protected $connection = 'comment';

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            // Module-specific casts only
            // Common casts (id, uuid, published_at, created_at, updated_at, deleted_at, etc.)
            // are inherited from XotBaseModel
        ]);
    }
}
