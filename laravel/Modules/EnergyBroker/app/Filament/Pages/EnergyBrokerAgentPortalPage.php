<?php

declare(strict_types=1);

namespace Modules\EnergyBroker\Filament\Pages;

final class EnergyBrokerAgentPortalPage extends EnergyBrokerStaticPage
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-device-phone-mobile';

    protected static ?string $navigationLabel = 'UI-09 - Portale agente';

    protected static ?string $title = 'UI-09 - Portale agente e acquisizione mobile';

    protected static ?string $slug = 'agent-portal';

    protected static ?int $navigationSort = 9;

    protected static ?string $energyBrokerFeature = 'energy_broker.agent_portal';

    protected function eyebrow(): string
    {
        return 'UI-09';
    }

    protected function heading(): string
    {
        return 'Portale agente per lead, bollette e stato pratiche';
    }

    protected function description(): string
    {
        return 'Esperienza mobile per agenti: inserimento lead, upload bolletta, firma guidata e controllo provvigioni personali.';
    }

    protected function metrics(): array
    {
        return [
            ['label' => 'Lead personali', 'value' => '38'],
            ['label' => 'Upload settimana', 'value' => '19'],
            ['label' => 'Provvigioni stimate', 'value' => '2.840 euro'],
        ];
    }

    protected function sections(): array
    {
        return [
            [
                'title' => 'Flusso agente',
                'items' => [
                    'Crea lead con dati minimi e consenso.',
                    'Carica foto/PDF bolletta e lascia al backoffice la validazione.',
                    'Segue stato pratica, richieste integrazione e firma.',
                ],
            ],
            [
                'title' => 'Trasparenza provvigioni',
                'items' => [
                    'Pratiche in attesa, attivate, liquidabili e pagate.',
                    'Motivi di blocco o KO visibili senza telefonate al backoffice.',
                    'Storico performance per periodo e campagna.',
                ],
            ],
        ];
    }

    protected function sidebar(): array
    {
        return [
            ['title' => 'Demo mobile first', 'body' => 'La UI puo essere aperta da browser: app nativa non necessaria per validare il flusso.'],
            ['title' => 'Controllo accessi', 'body' => 'Gli agenti vedono solo le proprie pratiche e non vedono provvigioni globali o compliance interna.'],
        ];
    }
}
