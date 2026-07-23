<?php

declare(strict_types=1);

namespace Modules\Comment\View\Components;

use Illuminate\Support\HtmlString;
use Illuminate\View\Component;
use RuntimeException;

use function Safe\file_get_contents;

class Styles extends Component
{
    public function render(): \Illuminate\Contracts\View\View
    {
        $path = dirname(__DIR__, 3).'/resources/css/livewire-comments.css';

        if (! is_readable($path)) {
            throw new RuntimeException("Comment styles CSS not found: {$path}");
        }

        $contents = file_get_contents($path);

        /** @var view-string $view */
        $view = 'comment::components.styles';

        return view($view, [
            'stylesheet' => new HtmlString($contents),
        ]);
    }
}
