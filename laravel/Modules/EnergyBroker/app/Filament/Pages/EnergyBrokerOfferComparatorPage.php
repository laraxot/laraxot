<?php

declare(strict_types=1);

namespace Modules\EnergyBroker\Filament\Pages;

final class EnergyBrokerOfferComparatorPage extends EnergyBrokerStaticPage
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-scale';

    protected static ?string $navigationLabel = 'UI-04 - Comparatore offerte';

    protected static ?string $title = 'UI-04 - Comparatore offerte luce e gas';

    protected static ?string $slug = 'offers';

    protected static ?int $navigationSort = 4;

    protected static ?string $energyBrokerFeature = 'energy_broker.offers';

    protected function eyebrow(): string
    {
        return 'UI-04';
    }

    protected function heading(): string
    {
        return 'Comparatore offerte e marginalita broker';
    }

    protected function description(): string
    {
        return 'Confronto offerte luce/gas partendo dai consumi estratti da bolletta e dai vincoli commerciali del cliente.';
    }

    protected function metrics(): array
    {
        return [
            ['label' => 'Offerte attive', 'value' => '18'],
            ['label' => 'Risparmio stimato', 'value' => '14%'],
            ['label' => 'Margine medio', 'value' => '7,8%'],
        ];
    }

    protected function sections(): array
    {
        return [
            [
                'title' => 'Simulazione proposta',
                'items' => [
                    'Profilo consumo importato da bolletta: kWh, Smc, potenza e fasce.',
                    'Confronto tra fornitore attuale, prezzo fisso, prezzo indicizzato e offerte partner.',
                    'Evidenza di risparmio annuo, vincoli contrattuali e marginalita per il broker.',
                ],
            ],
            [
                'title' => 'Output commerciale',
                'items' => [
                    'Offerta consigliata con motivazione leggibile.',
                    'PDF proposta o link firma da generare in una fase successiva.',
                    'Checklist documenti necessari prima dell invio al fornitore.',
                ],
            ],
        ];
    }

    protected function sidebar(): array
    {
        return [
            ['title' => 'Input principale', 'body' => 'La qualita del comparatore dipende dai dati estratti da UI-03: consumi, potenza, tipo cliente e fornitore attuale.'],
            ['title' => 'Da validare', 'body' => 'Le formule economiche reali cambiano per fornitore e listino: nel mockup restano simulate.'],
        ];
    }
}
