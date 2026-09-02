<?php

declare(strict_types=1);

namespace Modules\Comment\Actions;

use Spatie\QueueableAction\QueueableAction;

class ResolveMentionsParserAction
{
    use QueueableAction;

    public function execute(): callable
    {
        return $this->handle();
    }

    public function handle(): callable
    {
        $parser = config('commenti.mentions_parser');

        if (is_callable($parser)) {
            return $parser;
        }

        return static fn (string $text): string => $text;
    }
}
