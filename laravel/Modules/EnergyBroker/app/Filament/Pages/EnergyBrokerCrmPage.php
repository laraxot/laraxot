<?php

declare(strict_types=1);

namespace Modules\EnergyBroker\Filament\Pages;

final class EnergyBrokerCrmPage extends EnergyBrokerStaticPage
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationLabel = 'UI-02 - CRM lead e clienti';

    protected static ?string $title = 'UI-02 - CRM lead, clienti e utenze';

    protected static ?string $slug = 'crm';

    protected static ?int $navigationSort = 2;

    protected static ?string $energyBrokerFeature = 'energy_broker.crm';

    protected function eyebrow(): string
    {
        return 'UI-02';
    }

    protected function heading(): string
    {
        return 'CRM lead, clienti e punti di fornitura';
    }

    protected function description(): string
    {
        return 'Anagrafiche commerciali pensate per broker luce/gas: lead, clienti, sedi, utenze, consensi e storico contatti.';
    }

    protected function metrics(): array
    {
        return [
            ['label' => 'Lead attivi', 'value' => '184'],
            ['label' => 'Utenze censite', 'value' => '426'],
            ['label' => 'Consensi da verificare', 'value' => '12'],
        ];
    }

    protected function sections(): array
    {
        return [
            [
                'title' => 'Scheda cliente broker',
                'items' => [
                    'Ragione sociale, codice fiscale o partita IVA, recapiti e referente.',
                    'Sedi operative con POD/PDR multipli e storico fornitori.',
                    'Origine lead, agente assegnato, privacy marketing e note commerciali.',
                ],
            ],
            [
                'title' => 'Vista utenze',
                'items' => [
                    'Punti luce e gas collegati al cliente.',
                    'Consumo annuo stimato, potenza impegnata e fornitore corrente.',
                    'Scadenze, rinnovi e documenti mancanti.',
                ],
            ],
        ];
    }

    protected function sidebar(): array
    {
        return [
            ['title' => 'Perche serve', 'body' => 'Il broker deve vedere subito chi e il cliente, quali utenze possiede e quali opportunita commerciali sono aperte.'],
            ['title' => 'Demo', 'body' => 'La scheda e mockata, ma e costruita per agganciare in seguito import CSV, form lead e acquisizione bollette.'],
        ];
    }
}
