<?php

declare(strict_types=1);

namespace Modules\Intervention\Models;

use Modules\Xot\Models\XotBasePivot;

/**
 * Pivot intervento↔operatore assegnato.
 *
 * La tabella storica del gestionale è `intervention_user_assignments`
 * (non il convenzionale `intervention_user`): il nome resta esplicito
 * perché rinominarla romperebbe le installazioni esistenti.
 */
class InterventionUser extends XotBasePivot
{
    protected $connection = 'intervention';

    protected $table = 'intervention_user_assignments';

    /** @var list<string> */
    protected $fillable = [
        'intervention_id',
        'user_id',
    ];
}
