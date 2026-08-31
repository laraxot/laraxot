<?php

declare(strict_types=1);

namespace Modules\Platform\Filament\Widgets\Concerns;

use Filament\Facades\Filament;
use Illuminate\Support\Facades\Auth;

use function Safe\parse_url;

trait ResolvesPanelUserScope
{
    protected function isOperatorPanelRequest(): bool
    {
        $currentPanelId = Filament::getCurrentPanel()?->getId();

        if ($currentPanelId === 'operator') {
            return true;
        }

        $refererPath = $this->refererPath();

        return request()->is('operator')
            || request()->is('operator/*')
            || $refererPath === '/operator'
            || str_starts_with($refererPath, '/operator/');
    }

    /**
     * Il path del referer, o stringa vuota se il browser non ne ha mandato uno
     * valido: Safe\parse_url solleva invece di restituire false, e un referer
     * malformato per noi vale quanto un referer assente.
     */
    private function refererPath(): string
    {
        $referer = (string) request()->headers->get('referer', '');
        if ($referer === '') {
            return '';
        }

        try {
            $path = parse_url($referer, PHP_URL_PATH);
        } catch (\Throwable) {
            return '';
        }

        return is_string($path) ? $path : '';
    }

    protected function shouldScopeOperatorPanelData(): bool
    {
        return $this->isOperatorPanelRequest() && ! (bool) config('app.demo_mode');
    }

    protected function currentUserId(): ?int
    {
        $userId = Auth::id();

        return $userId === null ? null : (int) $userId;
    }
}
