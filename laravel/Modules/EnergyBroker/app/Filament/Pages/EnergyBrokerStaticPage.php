<?php

declare(strict_types=1);

namespace Modules\EnergyBroker\Filament\Pages;

abstract class EnergyBrokerStaticPage extends EnergyBrokerPage
{
    protected string $view = 'energybroker::filament.pages.feature-page';

    protected function getViewData(): array
    {
        return [
            'eyebrow' => $this->eyebrow(),
            'heading' => $this->heading(),
            'description' => $this->description(),
            'metrics' => $this->metrics(),
            'sections' => $this->sections(),
            'sidebar' => $this->sidebar(),
        ];
    }

    abstract protected function eyebrow(): string;

    abstract protected function heading(): string;

    abstract protected function description(): string;

    /**
     * @return array<int, array{label: string, value: string}>
     */
    abstract protected function metrics(): array;

    /**
     * @return array<int, array{title: string, items: array<int, string>}>
     */
    abstract protected function sections(): array;

    /**
     * @return array<int, array{title: string, body: string}>
     */
    abstract protected function sidebar(): array;
}
