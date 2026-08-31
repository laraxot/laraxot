<?php

declare(strict_types=1);

namespace Modules\EnergyBroker\Filament\Pages;

final class EnergyBrokerCompliancePage extends EnergyBrokerStaticPage
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-shield-check';

    protected static ?string $navigationLabel = 'UI-06 - Backoffice e compliance';

    protected static ?string $title = 'UI-06 - Backoffice, privacy e compliance';

    protected static ?string $slug = 'backoffice-compliance';

    protected static ?int $navigationSort = 6;

    protected static ?string $energyBrokerFeature = 'energy_broker.compliance';

    protected function eyebrow(): string
    {
        return 'UI-06';
    }

    protected function heading(): string
    {
        return 'Backoffice pratiche e controllo documentale';
    }

    protected function description(): string
    {
        return 'Area per validare documenti, consensi, firma, metodo di pagamento e condizioni prima dell invio al fornitore.';
    }

    protected function metrics(): array
    {
        return [
            ['label' => 'Da validare', 'value' => '31'],
            ['label' => 'Privacy KO', 'value' => '4'],
            ['label' => 'IBAN mancanti', 'value' => '6'],
        ];
    }

    protected function sections(): array
    {
        return [
            [
                'title' => 'Checklist backoffice',
                'items' => [
                    'Documento identita, consenso privacy e delega cliente.',
                    'Contratto firmato e coerenza tra intestatario, POD/PDR e indirizzo.',
                    'Metodo pagamento, IBAN, recapito bolletta e recapito comunicazioni.',
                ],
            ],
            [
                'title' => 'Gestione anomalie',
                'items' => [
                    'Classificazione KO: dati cliente, dati utenza, firma, allegati.',
                    'Richiesta integrazione all agente o al cliente.',
                    'Storico note e audit delle modifiche prima dell invio.',
                ],
            ],
        ];
    }

    protected function sidebar(): array
    {
        return [
            ['title' => 'Regola demo', 'body' => 'L LLM propone dati e anomalie, ma il backoffice conferma prima di salvare o inviare.'],
            ['title' => 'Sicurezza', 'body' => 'Nessun documento reale deve essere usato nella demo commerciale senza autorizzazione.'],
        ];
    }
}
