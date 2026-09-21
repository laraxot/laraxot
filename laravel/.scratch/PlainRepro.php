<?php

declare(strict_types=1);

namespace Modules\Compliance\Actions\_scratch_repro;

/**
 * @property-read string|null $notRelationName
 */
final class PlainRepro
{
    public function run(): string
    {
        $x = $this->notRelationName;

        return $x?->foo ?? 'x';
    }
}
