<?php

declare(strict_types=1);

namespace Modules\Rating\Models;

use Modules\Rating\Models\Traits\HasRating;

/** Probe host so PHPStan analyses HasRating trait in app context. */
final class RatingPhpstanTraitProbe extends BaseModel
{
    use HasRating;

    protected $table = 'rating_phpstan_trait_probe';

    /** @var list<string> */
    protected $guarded = [];
}
