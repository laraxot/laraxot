<?php

declare(strict_types=1);

namespace Modules\EnergyBroker\Filament\Pages;

final class EnergyBrokerCommissionsPage extends EnergyBrokerStaticPage
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $navigationLabel = 'UI-07 - Provvigioni e agenti';

    protected static ?string $title = 'UI-07 - Provvigioni, agenti e riconciliazioni';

    protected static ?string $slug = 'commissions';

    protected static ?int $navigationSort = 7;

    protected static ?string $energyBrokerFeature = 'energy_broker.commissions';

    protected function eyebrow(): string
    {
        return 'UI-07';
    }

    protected function heading(): string
    {
        return 'Provvigioni agenti e riconciliazione fornitori';
    }

    protected function description(): string
    {
        return 'Vista economica per seguire commissioni attese, maturate, pagate, contestate e ripartite tra agente e agenzia.';
    }

    protected function metrics(): array
    {
        return [
            ['label' => 'Maturato mese', 'value' => '18.420 euro'],
            ['label' => 'Da riconciliare', 'value' => '42'],
            ['label' => 'Contestazioni', 'value' => '5'],
        ];
    }

    protected function sections(): array
    {
        return [
            [
                'title' => 'Riconciliazione',
                'items' => [
                    'Import file provvigioni dei fornitori.',
                    'Matching con pratica, POD/PDR, agente e data attivazione.',
                    'Evidenza di scarti e importi non riconosciuti.',
                ],
            ],
            [
                'title' => 'Liquidazione agenti',
                'items' => [
                    'Regole percentuali per ruolo o campagna.',
                    'Split agente, team leader e agenzia.',
                    'Report mensile esportabile.',
                ],
            ],
        ];
    }

    protected function sidebar(): array
    {
        return [
            ['title' => 'Perche venderla', 'body' => 'La provvigione e spesso il punto dolente dei broker: rende visibile cosa e maturato e cosa manca.'],
            ['title' => 'Fuori MVP', 'body' => 'Le regole reali di commissione possono diventare un modulo dominio dedicato.'],
        ];
    }
}
