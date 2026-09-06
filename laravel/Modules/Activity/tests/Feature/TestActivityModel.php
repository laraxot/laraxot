<?php

declare(strict_types=1);

namespace Modules\Activity\Tests\Feature;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Carbon;
use Modules\Activity\Models\BaseModel;
use Modules\Xot\Models\Traits\HasXotFactory;

/**
 * Classe concreta di test per BaseModel.
 * Usata per testare BaseModel senza classi anonime.
 *
 * @property string|null $uuid
 * @property string|null $name
 * @property string|null $value
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property Carbon|null $published_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 *
 * @method static Factory<static> factory()
 *
 * @coversNothing
 */
class TestActivityModel extends BaseModel
{
    /**
     * @use HasFactory<Factory<self>>
     *
     * newFactory() è fornito da HasXotFactory (già tipizzato `: Factory`, ereditato da
     * XotBaseModel tramite BaseModel): senza insteadof la versione non tipizzata di
     * HasFactory::newFactory() viola la firma dell'antenato e PHP va in fatal error
     * "Declaration ... must be compatible" al primo autoload della classe.
     */
    use HasFactory, HasXotFactory {
        HasXotFactory::newFactory insteadof HasFactory;
    }
    /** @var string */
    protected $table = 'test_models';

    /** @var list<string> */
    protected $fillable = ['name', 'value', 'uuid', 'published_at', 'created_by', 'updated_by', 'deleted_by'];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            // Module-specific casts only
        ]);
    }
}
