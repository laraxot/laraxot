<?php

declare(strict_types=1);

namespace Modules\User\Models;

<<<<<<< HEAD
<<<<<<< HEAD
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Modules\Xot\Contracts\ProfileContract;
use Modules\Xot\Models\BaseExtra;
use Spatie\SchemalessAttributes\SchemalessAttributes;
=======
=======
>>>>>>> 87273113 (.)
use Spatie\SchemalessAttributes\SchemalessAttributes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Modules\Xot\Contracts\ProfileContract;
use Modules\User\Database\Factories\ExtraFactory;
use Modules\Xot\Models\Extra as XotBaseExtra;
>>>>>>> f548be94 (.)

/**
 * @property SchemalessAttributes $extra_attributes
=======
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Modules\Xot\Contracts\ProfileContract;
use Modules\Xot\Models\BaseExtra;
use Spatie\SchemalessAttributes\SchemalessAttributes;

/**
 * @property SchemalessAttributes $extra_attributes
 *
>>>>>>> 2024e2e7 (.)
 * @method static Builder|Extra newModelQuery()
 * @method static Builder|Extra newQuery()
 * @method static Builder|Extra query()
 * @method static Builder|Extra withExtraAttributes()
<<<<<<< HEAD
<<<<<<< HEAD
 * @property int         $id
 * @property string      $model_type
 * @property string      $model_id
=======
 * @property int $id
 * @property string $model_type
 * @property string $model_id
>>>>>>> f548be94 (.)
=======
 * @property int $id
 * @property string $model_type
 * @property string $model_id
=======
 *
 * @property int         $id
 * @property string      $model_type
 * @property string      $model_id
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $updated_by
 * @property string|null $created_by
 * @property Carbon|null $deleted_at
 * @property string|null $deleted_by
<<<<<<< HEAD
=======
 *
>>>>>>> 2024e2e7 (.)
 * @method static Builder|Extra whereCreatedAt($value)
 * @method static Builder|Extra whereCreatedBy($value)
 * @method static Builder|Extra whereDeletedAt($value)
 * @method static Builder|Extra whereDeletedBy($value)
 * @method static Builder|Extra whereExtraAttributes($value)
 * @method static Builder|Extra whereId($value)
 * @method static Builder|Extra whereModelId($value)
 * @method static Builder|Extra whereModelType($value)
 * @method static Builder|Extra whereUpdatedAt($value)
 * @method static Builder|Extra whereUpdatedBy($value)
<<<<<<< HEAD
 * @property ProfileContract|null $creator
 * @property ProfileContract|null $updater
<<<<<<< HEAD
 * @property ProfileContract|null $deleter
 * @method static \Modules\User\Database\Factories\ExtraFactory factory($count = null, $state = [])
 * @mixin \Eloquent
 */
final class Extra extends BaseExtra
{
    public function getConnectionName(): string
    {
        return 'user';
    }
=======
 * @method static ExtraFactory factory($count = null, $state = [])
 * @mixin IdeHelperExtra
 * @mixin \Eloquent
 */
class Extra extends XotBaseExtra
{
    /** @var string */
    protected $connection = 'user';
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
 *
 * @property ProfileContract|null $creator
 * @property ProfileContract|null $updater
 * @property ProfileContract|null $deleter
 *
 * @method static \Modules\User\Database\Factories\ExtraFactory factory($count = null, $state = [])
 *
 * @mixin \Eloquent
 */
final class Extra extends BaseExtra
{
    public function getConnectionName(): string
    {
        return 'user';
    }
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
}
