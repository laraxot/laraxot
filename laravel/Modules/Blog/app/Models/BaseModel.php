<?php

declare(strict_types=1);

namespace Modules\Blog\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Tenant\Providers\TenantServiceProvider;
use Modules\Xot\Models\XotBaseModel;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * Base Model for Blog module.
 *
 * Extends XotBaseModel and adds:
 * - Spatie Media Library support (HasMedia, InteractsWithMedia)
 * - Soft Deletes support
 *
 * @see XotBaseModel
 */
abstract class BaseModel extends XotBaseModel implements HasMedia
{
    use InteractsWithMedia;
    use SoftDeletes;

    /**
     * The connection name for the model.
     */
    protected $connection = 'blog';

    /**
     * Database Architecture: Multi-Database via TenantServiceProvider.
     *
     * The 'blog' connection is configured in:
     * - config/local/predict/database.php (static configuration)
     * - Modules/Tenant/app/Providers/TenantServiceProvider.php (dynamic registration)
     *
     * Default: Points to 'predict_data' database (same as DB_DATABASE)
     * Can be overridden via DB_BLOG_DATABASE environment variable.
     *
     * @see TenantServiceProvider::registerDB()
     * @see config/local/predict/database.php
     */

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            // Module-specific casts only
            // Common casts (id, uuid, timestamps) are inherited from XotBaseModel
        ]);
    }
}
