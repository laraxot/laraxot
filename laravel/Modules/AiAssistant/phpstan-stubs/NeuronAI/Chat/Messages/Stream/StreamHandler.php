<?php

declare(strict_types=1);

namespace NeuronAI\Chat\Messages\Stream;

use Traversable;

class StreamHandler
{
    /** @return Traversable<int, mixed> */
    public function events(): Traversable
    {
        yield from [];
    }
}
