<?php

declare(strict_types=1);

namespace Modules\User\Filament\Widgets;

use Illuminate\Contracts\View\View;
use Modules\Xot\Filament\Widgets\XotBaseWidget;

/**
 * TermsOfServiceWidget: widget per visualizzare i termini di servizio nel panel.
 *
 * Sostituisce il vecchio Livewire `Modules\User\Http\Livewire\TermsOfService`.
 */
final class TermsOfServiceWidget extends XotBaseWidget
{
    public function render(): View
    {
        /** @var view-string $viewName */
        $viewName = 'user::filament.widgets.terms-of-service';

        return view($viewName, [
            'terms' => '',
        ]);
    }
}
