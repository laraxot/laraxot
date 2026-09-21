<?php

declare(strict_types=1);

namespace Modules\Rating\Models\Contracts;

use Illuminate\Database\Eloquent\Relations\Relation;

/** Contract for models that expose the shared ratings relation. */
interface HasRatingContract
{
    public function ratings(): Relation;
}
