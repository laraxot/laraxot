<?php

declare(strict_types=1);

namespace Modules\Xot\Tests\Fixtures\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Modules\Xot\Contracts\ExtraContract;
<<<<<<< HEAD
=======
use Spatie\SchemalessAttributes\Casts\SchemalessAttributes as SchemalessAttributesCast;
>>>>>>> laraxot/dev
use Spatie\SchemalessAttributes\SchemalessAttributes;

/**
 * @property SchemalessAttributes|null $extra_attributes
 */
class ExtraModelTest extends Model implements ExtraContract
{
    protected $table = 'test_extras';

    /** @var list<string> */
    protected $fillable = ['model_id', 'model_type', 'extra_attributes'];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
<<<<<<< HEAD
            'extra_attributes' => SchemalessAttributes::class,
=======
            'extra_attributes' => SchemalessAttributesCast::class,
>>>>>>> laraxot/dev
        ];
    }

    /**
     * @return MorphTo<Model, $this>
     */
    public function model(): MorphTo
    {
        return $this->morphTo();
    }
}
