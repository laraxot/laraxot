<?php

declare(strict_types=1);

namespace Modules\Comment\Actions;

use Illuminate\Support\Str;
use Spatie\QueueableAction\QueueableAction;

class ResolveMarkdownParserAction
{
    use QueueableAction;

    public function execute(): callable
    {
        return $this->handle();
    }

    public function handle(): callable
    {
        $parser = config('commenti.markdown_parser');

        if (is_callable($parser)) {
            return $parser;
        }

        return static fn (string $text): string => Str::markdown($text);
    }
}
