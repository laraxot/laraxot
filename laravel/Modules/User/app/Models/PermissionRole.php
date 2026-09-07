<?php

declare(strict_types=1);

namespace Modules\User\Models;

<<<<<<< HEAD
<<<<<<< HEAD
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Modules\Xot\Contracts\ProfileContract;
=======
=======
>>>>>>> 87273113 (.)
use Override;
use Modules\Xot\Contracts\ProfileContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Modules\Xot\Contracts\ProfileContract;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
use Webmozart\Assert\Assert;

/**
 * @property ProfileContract|null $creator
 * @property ProfileContract|null $updater
<<<<<<< HEAD
 * @method static Builder|PermissionRole newModelQuery()
 * @method static Builder|PermissionRole newQuery()
 * @method static Builder|PermissionRole query()
<<<<<<< HEAD
 * @property string      $id
=======
 * @property string $id
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
 *
 * @method static Builder|PermissionRole newModelQuery()
 * @method static Builder|PermissionRole newQuery()
 * @method static Builder|PermissionRole query()
 *
 * @property string      $id
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
 * @property string|null $permission_id
 * @property string|null $role_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $updated_by
 * @property string|null $created_by
<<<<<<< HEAD
=======
 *
>>>>>>> 2024e2e7 (.)
 * @method static Builder|PermissionRole whereCreatedAt($value)
 * @method static Builder|PermissionRole whereCreatedBy($value)
 * @method static Builder|PermissionRole whereId($value)
 * @method static Builder|PermissionRole wherePermissionId($value)
 * @method static Builder|PermissionRole whereRoleId($value)
 * @method static Builder|PermissionRole whereUpdatedAt($value)
 * @method static Builder|PermissionRole whereUpdatedBy($value)
<<<<<<< HEAD
<<<<<<< HEAD
 * @property ProfileContract|null $deleter
=======
 * @mixin IdeHelperPermissionRole
>>>>>>> f548be94 (.)
=======
 * @mixin IdeHelperPermissionRole
=======
 *
 * @property ProfileContract|null $deleter
 *
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
 * @mixin \Eloquent
 */
class PermissionRole extends BasePivot
{
    /**
     * @var list<string>
     *
     * @psalm-var list{'permission_id', 'role_id'}
     */
    protected $fillable = ['permission_id', 'role_id'];

    public function getTable(): string
    {
        Assert::string($table = config('permission.table_names.role_has_permissions'));

        return $table;
    }

    /** @return array<string, string> */
<<<<<<< HEAD
<<<<<<< HEAD
    #[\Override]
=======
    #[Override]
>>>>>>> f548be94 (.)
=======
    #[Override]
=======
    #[\Override]
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    protected function casts(): array
    {
        $parent = parent::casts();
        $up = [
            'permission_id' => 'string',
            'role_id' => 'string',
        ];

        return array_merge($parent, $up);
    }
}
