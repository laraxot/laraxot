<?php

declare(strict_types=1);

namespace Modules\Signage\Support;

/**
 * Signage's slice of the shared costing tables. Every work-centre read goes
 * through this context so a sign shop never sees another trade's shop floor.
 */
final class SignageCosting
{
    /** @var string */
    public const CONTEXT = 'signage';
}
