<?php

declare(strict_types=1);

namespace Modules\Rating\Tests\Fixtures;

use Illuminate\Database\Eloquent\Model;

/**
 * Modello valutato di comodo per le policy: espone `user_id` e `matr` come attributi
 * Eloquent, che è la forma su cui `RatingMorphPolicy::isOwner()` fa `isset()`.
 * Non mappa una tabella reale — le policy non interrogano il database.
 *
 * @see \Modules\Rating\Models\Policies\RatingMorphPolicy
 */
class OwnedModelStub extends Model
{
    protected $table = 'owned_model_stub';

    /** @var list<string> */
    protected $fillable = ['user_id', 'matr'];
}
