<?php

declare(strict_types=1);

namespace Modules\EnergyBroker\Support;

use Barryvdh\DomPDF\Facade\Pdf;

final class GenerateEnergyBrokerDemoPdfAction
{
    public function execute(string $type): string
    {
        $data = $this->dataFor($type);

        return Pdf::loadView('energybroker::pdf.demo-document', [
            'type' => $type,
            'document' => $data,
        ])->setPaper('a4')->output();
    }

    /**
     * @return array<string, mixed>
     */
    public function dataFor(string $type): array
    {
        return match ($type) {
            'supply_contract' => [
                'title' => 'Contratto demo fornitura energia',
                'badge' => 'DOCUMENTO SINTETICO DEMO',
                'number' => 'CTR-EB-2026-044',
                'date' => '08/05/2026',
                'customer' => 'Demo Broker Energia Srl',
                'tax_code' => '09876540961',
                'address' => 'Via delle Utenze 12, 20100 Milano (MI)',
                'supplier' => 'PowerNext Energia Spa',
                'offer_code' => 'EB-POWER-FIX-2026',
                'pod' => 'IT001E98765432',
                'pdr' => '12345678901234',
                'duration' => '24 mesi',
                'payment' => 'Addebito SEPA su IBAN demo',
                'rows' => [
                    ['label' => 'Offerta luce', 'value' => 'Prezzo fisso 0,142 euro/kWh, fasce F1-F2-F3 aggregate'],
                    ['label' => 'Offerta gas', 'value' => 'Prezzo fisso 0,58 euro/Smc, quota commercializzazione inclusa'],
                    ['label' => 'Decorrenza stimata', 'value' => '01/07/2026, salvo validazione distributore'],
                    ['label' => 'Clausole da verificare', 'value' => 'Recesso, privacy, recapito bolletta, delega cambio fornitore'],
                ],
                'notes' => 'Contratto creato per testare acquisizione dati LLM nel mockup broker luce/gas. Non contiene dati reali.',
            ],
            default => [
                'title' => 'Bolletta demo luce e gas',
                'badge' => 'DOCUMENTO SINTETICO DEMO',
                'number' => 'BOL-2026-051',
                'date' => '08/05/2026',
                'customer' => 'Demo Broker Energia Srl',
                'tax_code' => '09876540961',
                'address' => 'Via delle Utenze 12, 20100 Milano (MI)',
                'supplier' => 'Fornitore Attuale Energia Spa',
                'pod' => 'IT001E98765432',
                'pdr' => '12345678901234',
                'period' => '01/03/2026 - 30/04/2026',
                'annual_power' => '18.400 kWh',
                'annual_gas' => '2.750 Smc',
                'power' => '15 kW',
                'total' => '3.428,70 euro',
                'rows' => [
                    ['label' => 'Energia elettrica', 'value' => '3.040 kWh nel periodo, spesa materia energia 612,80 euro'],
                    ['label' => 'Gas naturale', 'value' => '460 Smc nel periodo, spesa materia gas 388,40 euro'],
                    ['label' => 'Oneri e imposte', 'value' => 'Totale oneri, trasporto, IVA e accise 2.427,50 euro'],
                    ['label' => 'Scadenza pagamento', 'value' => '31/05/2026'],
                ],
                'notes' => 'Bolletta creata per testare acquisizione dati LLM nel mockup broker luce/gas. Non contiene dati reali.',
            ],
        };
    }
}
