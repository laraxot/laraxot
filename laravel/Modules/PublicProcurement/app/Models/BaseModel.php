<?php

declare(strict_types=1);

namespace Modules\PublicProcurement\Models;

use Modules\Xot\Models\XotBaseModel;

/**
 * Base model for PublicProcurement module, inherits from XotBaseModel.
 */
abstract class BaseModel extends XotBaseModel
{
    /**
     * The connection name for the model.
     */
    // Le connessioni sono generate dal nome del modulo in snake_case: la base ne
    // registra 53 e questa si chiama 'public_procurement', come 'energy_broker' o
    // 'metal_work'. Attaccate insieme non corrispondeva a nessuna, e ogni query del
    // modulo moriva su «Database connection [publicprocurement] not configured».
    protected $connection = 'public_procurement';
}
