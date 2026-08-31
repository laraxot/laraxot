<?php

declare(strict_types=1);

namespace Modules\EnergyBroker\Filament\Pages;

final class EnergyBrokerCustomerCarePage extends EnergyBrokerStaticPage
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    protected static ?string $navigationLabel = 'UI-08 - Customer care e rinnovi';

    protected static ?string $title = 'UI-08 - Customer care, rinnovi e retention';

    protected static ?string $slug = 'customer-care';

    protected static ?int $navigationSort = 8;

    protected static ?string $energyBrokerFeature = 'energy_broker.customer_care';

    protected function eyebrow(): string
    {
        return 'UI-08';
    }

    protected function heading(): string
    {
        return 'Customer care e rinnovi contrattuali';
    }

    protected function description(): string
    {
        return 'Ticket, cambio tariffa, reclami, scadenze e opportunita di rinnovo in una vista unica per il post vendita.';
    }

    protected function metrics(): array
    {
        return [
            ['label' => 'Ticket aperti', 'value' => '27'],
            ['label' => 'Rinnovi 60gg', 'value' => '41'],
            ['label' => 'Retention risk', 'value' => '8'],
        ];
    }

    protected function sections(): array
    {
        return [
            [
                'title' => 'Assistenza cliente',
                'items' => [
                    'Ticket per bolletta anomala, cambio recapito, pagamento o voltura.',
                    'Storico comunicazioni e documenti collegati alla pratica.',
                    'SLA e assegnazione a support, backoffice o agente.',
                ],
            ],
            [
                'title' => 'Rinnovi e retention',
                'items' => [
                    'Reminder su scadenze e fine prezzo fisso.',
                    'Suggerimento nuova offerta in base ai consumi aggiornati.',
                    'Campagne di retention per clienti ad alto valore.',
                ],
            ],
        ];
    }

    protected function sidebar(): array
    {
        return [
            ['title' => 'Valore operativo', 'body' => 'Dopo la firma, il broker resta il riferimento del cliente: serve una vista per non perdere rinnovi e reclami.'],
            ['title' => 'Automazioni', 'body' => 'Promemoria email/SMS/WhatsApp sono integrazioni successive, non obbligatorie nel mockup.'],
        ];
    }
}
