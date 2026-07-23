<?php

declare(strict_types=1);

namespace Modules\Xot\Actions\Route;

use Spatie\QueueableAction\QueueableAction;

class IsAdminRouteAction
{
    use QueueableAction;

    /** @param array<string, mixed> $params */
    public function execute(array $params = []): bool
    {
        if (isset($params['in_admin'])) {
            return (bool) $params['in_admin'];
        }

        if ('admin' === request()->segment(1)) {
            return true;
        }

        $segments = request()->segments();

        return [] !== $segments && 'livewire' === $segments[0] && true === session('in_admin', false);
    }
}
