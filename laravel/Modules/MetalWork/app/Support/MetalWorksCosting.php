<?php

declare(strict_types=1);

namespace Modules\MetalWork\Support;

/**
 * MetalWorks' slice of the shared costing tables. Every work-centre read goes
 * through this context so a sheet-metal shop never sees another trade's centres.
 */
final class MetalWorksCosting
{
    /** @var string */
    public const string CONTEXT = 'metalwork';
}
