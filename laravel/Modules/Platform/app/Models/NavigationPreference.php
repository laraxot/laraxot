<?php

declare(strict_types=1);

namespace Modules\Platform\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\User\Models\User;

/**
 * Runtime on/off state for a single navigation node (module, group or menu item).
 *
 * A missing row means "enabled": only explicit overrides are persisted, so newly
 * added resources/pages default to visible without any migration.
 *
 * @property int $id
 * @property string $node_key
 * @property bool $is_enabled
 * @property string|null $updated_by_user_id
 */
final class NavigationPreference extends BaseModel
{
    protected $table = 'navigation_preferences';

    /** @var list<string> */
    protected $fillable = [
        'node_key',
        'is_enabled',
        'updated_by_user_id',
    ];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'is_enabled' => 'boolean',
        ];
    }

    /** @return BelongsTo<User, $this> */
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by_user_id');
    }
}
