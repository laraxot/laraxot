<?php

declare(strict_types=1);

namespace Modules\User\Http\Livewire;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Livewire\Component;

use function Safe\file_get_contents;

class PrivacyPolicy extends Component
{
    /**
     * Show the terms of service for the application.
     */
    public function render(): View
    {
        /** @var view-string $viewName */
        $viewName = 'filament-jet::livewire.privacy-policy';
        $policyFile = resource_path('markdown/privacy-policy.md');
        $policyContent = file_get_contents($policyFile);
        $view = view($viewName, [
            'terms' => Str::markdown($policyContent),
        ]);

        $view->layout('filament::components.layouts.base', [
            'title' => __('filament-jet::registration.privacy_policy'),
        ]);

        return $view;
    }
}
