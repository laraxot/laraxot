<?php

declare(strict_types=1);

namespace Modules\EnergyBroker\Filament\Pages;

final class EnergyBrokerPipelinePage extends EnergyBrokerStaticPage
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-queue-list';

    protected static ?string $navigationLabel = 'UI-05 - Pipeline pratiche';

    protected static ?string $title = 'UI-05 - Pipeline pratiche e switch';

    protected static ?string $slug = 'pipeline';

    protected static ?int $navigationSort = 5;

    protected static ?string $energyBrokerFeature = 'energy_broker.pipeline';

    protected function eyebrow(): string
    {
        return 'UI-05';
    }

    protected function heading(): string
    {
        return 'Pipeline pratiche, KO e switch attivi';
    }

    protected function description(): string
    {
        return 'Kanban operativo per seguire ogni pratica dal lead alla firma, fino all attivazione e alla provvigione.';
    }

    protected function metrics(): array
    {
        return [
            ['label' => 'Pratiche aperte', 'value' => '73'],
            ['label' => 'KO documentali', 'value' => '9'],
            ['label' => 'Switch in corso', 'value' => '24'],
        ];
    }

    protected function sections(): array
    {
        return [
            [
                'title' => 'Stati pratica',
                'items' => [
                    'Bozza, proposta inviata, contratto firmato, inviato al fornitore.',
                    'KO documentale con motivo, riassegnazione e richiesta integrazione.',
                    'Switch attivo, pratica liquidabile e provvigione riconciliata.',
                ],
            ],
            [
                'title' => 'Controlli rapidi',
                'items' => [
                    'Documenti mancanti o scaduti.',
                    'SLA per agente, backoffice e fornitore.',
                    'Alert su pratiche ferme oltre soglia.',
                ],
            ],
        ];
    }

    protected function sidebar(): array
    {
        return [
            ['title' => 'Vista per responsabile', 'body' => 'Aiuta a capire dove si bloccano le pratiche e quali agenti hanno bisogno di supporto.'],
            ['title' => 'Vista per agente', 'body' => 'Mostra solo le pratiche assegnate e i prossimi passi commerciali.'],
        ];
    }
}
