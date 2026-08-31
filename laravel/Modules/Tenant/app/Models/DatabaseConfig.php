<?php

declare(strict_types=1);

namespace Modules\Tenant\Models;

use Modules\User\Models\User;

/**
 * @property-read User|null $creator
 * @property-read User|null $updater
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DatabaseConfig newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DatabaseConfig newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DatabaseConfig query()
 *
 * @mixin \Eloquent
 */
class DatabaseConfig extends BaseModel
{
    protected $table = 'database_configs';

    protected $fillable = [
        'host',
        'port',
        'database',
        'username',
        'password',
        'charset',
        'collation',
        'prefix',
        'prefix_indexes',
        'strict',
        'engine',
        'options',
    ];

    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'port' => 'integer',
            'prefix_indexes' => 'boolean',
            'strict' => 'boolean',
            'options' => 'array',
        ]);
    }
}
