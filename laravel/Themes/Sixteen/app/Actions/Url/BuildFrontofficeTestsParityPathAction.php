<?php

declare(strict_types=1);

namespace Themes\Sixteen\Actions\Url;

use Spatie\QueueableAction\QueueableAction;

/**
 * Solo parity Design Comuni / demo statiche — non usare in header produzione.
 */
final class BuildFrontofficeTestsParityPathAction
{
    use QueueableAction;

    public function execute(string $slug): string
    {
        return app(BuildLocalizedFrontofficePathAction::class)->execute('/tests/'.$slug);
    }
}
