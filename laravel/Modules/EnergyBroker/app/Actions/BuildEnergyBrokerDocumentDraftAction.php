<?php

declare(strict_types=1);

namespace Modules\EnergyBroker\Actions;

use Carbon\CarbonImmutable;
use Spatie\QueueableAction\QueueableAction;

use function Safe\preg_match;
use function Safe\preg_replace;

final class BuildEnergyBrokerDocumentDraftAction
{
    use QueueableAction;

    /**
     * @return array<string, mixed>
     */
    public function execute(string $filename, string $text): array
    {
        $normalizedText = $this->normalizeText($text);
        $documentType = $this->detectDocumentType($filename, $normalizedText);
        $serviceType = $this->detectServiceType($normalizedText, $documentType);

        return [
            'document_type' => $documentType,
            'service_type' => $serviceType,
            'customer' => [
                'legal_name' => $this->extractCustomerName($normalizedText),
                'tax_code' => $this->match('/Codice Fiscale:\s*([A-Z0-9]+)/iu', $normalizedText),
                'email' => $this->extractEmail($normalizedText),
                'address_line1' => $this->extractAddress($normalizedText),
                'city' => $this->extractCity($normalizedText),
                'postal_code' => $this->extractPostalCode($normalizedText),
                'province' => $this->extractProvince($normalizedText),
            ],
            'document' => [
                'document_number' => $this->extractDocumentNumber($normalizedText),
                'issued_at' => $this->extractDate('/del\s+([0-9]{2}\/[0-9]{2}\/[0-9]{4})/iu', $normalizedText),
                'period_label' => $this->match('/Periodo oggetto di fatturazione:\s*([^\n]+)/iu', $normalizedText),
                'due_at' => $this->extractDate('/Scadenza(?: pagamento)?:\s*([0-9]{2}\/[0-9]{2}\/[0-9]{4})/iu', $normalizedText),
                'total_amount' => $this->extractAmount('/TOTALE DA PAGARE(?: \(Iva inclusa\))?\s*([0-9\.,]+)/iu', $normalizedText),
                'customer_code' => $this->match('/Codice Cliente:\s*([A-Z0-9]+)/iu', $normalizedText),
            ],
            'supply' => [
                'service_type' => $serviceType,
                'pod' => $this->match('/POD:\s*([A-Z0-9]+)/iu', $normalizedText),
                'pdr' => $this->match('/PDR:\s*([A-Z0-9]+)/iu', $normalizedText),
                'supply_code' => $this->match('/Codice Fornitura:\s*([A-Z0-9\-]+)/iu', $normalizedText),
                'supply_address' => $this->match('/Indirizzo di fornitura:\s*([^\n]+)/iu', $normalizedText) ?? $this->extractAddress($normalizedText),
                'power_kw' => $this->extractAmount('/Potenza Impegnata:\s*([0-9\.,]+)/iu', $normalizedText),
                'annual_consumption' => $this->extractAnnualConsumption($normalizedText, $serviceType),
                'annual_consumption_electricity' => $this->extractAmount('/Consumo annuo aggiornato:\s*([0-9\.,]+)\s*kWh/iu', $normalizedText),
                'annual_consumption_gas' => $this->extractAmount('/Consumo annuo aggiornato:\s*([0-9\.,]+)\s*Smc/iu', $normalizedText),
            ],
            'contract' => [
                'current_supplier' => $this->detectCurrentSupplier($filename, $normalizedText),
                'proposed_supplier' => $this->detectProposedSupplier($normalizedText),
                'offer_name' => $this->extractOfferName($normalizedText),
                'offer_code_electricity' => $this->match('/Codice Offerta\s*([A-Z0-9]+)/iu', $normalizedText),
                'offer_code_gas' => $this->match('/Codice Offerta\s*([A-Z0-9]+)/iu', $normalizedText),
                'payment_method' => $this->detectPaymentMethod($normalizedText),
            ],
            'raw_text' => $normalizedText,
        ];
    }

    private function normalizeText(string $text): string
    {
        $text = str_replace(["\r\n", "\r"], "\n", $text);
        $result1 = preg_replace('/[ \t]+/', ' ', $text);
        $text = is_string($result1) ? $result1 : $text;
        $result2 = preg_replace('/\n{2,}/', "\n", $text);
        $text = is_string($result2) ? $result2 : $text;

        return trim($text);
    }

    private function detectDocumentType(string $filename, string $text): string
    {
        $haystack = mb_strtoupper($filename."\n".$text);

        if (str_contains($haystack, 'PROPOSTA DI CONTRATTO') || str_contains($haystack, 'OPERAZIONE DI SWITCH')) {
            return 'supply_contract';
        }

        if (str_contains($haystack, 'BOLLETTA ENERGIA ELETTRICA') || str_contains($haystack, 'BOLLETTA LUCE')) {
            return 'utility_bill_electricity';
        }

        if (str_contains($haystack, 'BOLLETTA GAS NATURALE') || str_contains($haystack, 'GAS NATURALE')) {
            return 'utility_bill_gas';
        }

        return 'unknown';
    }

    private function detectServiceType(string $text, string $documentType): string
    {
        return match ($documentType) {
            'utility_bill_electricity' => 'electricity',
            'utility_bill_gas' => 'gas',
            'supply_contract' => str_contains(mb_strtoupper($text), 'SOMMINISTRAZIONE DI GAS NATURALE E ENERGIA ELETTRICA') ? 'dual' : 'electricity',
            default => 'unknown',
        };
    }

    private function extractCustomerName(string $text): ?string
    {
        $value = $this->match('/Intestatario:\s*([^\n]+)/iu', $text)
            ?? $this->match('/\n([A-Z\' ]{5,})\nVIA /u', $text);

        return $value !== null ? mb_strtoupper(trim($value)) : null;
    }

    private function extractAddress(string $text): ?string
    {
        return $this->match('/Indirizzo:\s*([^\n]+)/iu', $text)
            ?? $this->match('/\n([A-Z0-9\'\., ]+,\s*[0-9]+(?:\s*-\s*[0-9]{5}\s*[A-Z\' ]+\s*[A-Z]{2})?)/u', $text);
    }

    private function extractCity(string $text): ?string
    {
        $address = $this->extractAddress($text);
        if ($address === null) {
            return null;
        }

        if (preg_match('/[0-9]{5}\s+([A-Z\' ]+)\s+[A-Z]{2}$/u', $address, $matches) !== 1) {
            return null;
        }

        return trim((string) ($matches[1] ?? ''));
    }

    private function extractPostalCode(string $text): ?string
    {
        return $this->match('/\b([0-9]{5})\b/u', $this->extractAddress($text) ?? '');
    }

    private function extractProvince(string $text): ?string
    {
        $address = $this->extractAddress($text);
        if ($address === null) {
            return null;
        }

        return $this->match('/([A-Z]{2})$/u', trim(str_replace(['(', ')'], '', $address)));
    }

    private function extractDocumentNumber(string $text): ?string
    {
        return $this->match('/n\.\s*([A-Z0-9]+)/iu', $text)
            ?? $this->match('/NUMERO FATTURA ELETTRONICA VALIDA AI FINI FISCALI:\s*([A-Z0-9]+)/iu', $text);
    }

    private function extractOfferName(string $text): ?string
    {
        return $this->match('/Nome dell\'offerta commerciale:\s*([^\n]+)/iu', $text)
            ?? $this->match('/NOME OFFERTA(?: COMMERCIALE)?:\s*([^\n]+)/iu', $text);
    }

    private function detectCurrentSupplier(string $filename, string $text): ?string
    {
        $haystack = mb_strtoupper($filename."\n".$text);

        return match (true) {
            str_contains($haystack, 'DUFERCO') => 'DUFERCO ENERGIA',
            str_contains($haystack, 'DOLOMITI') => 'DOLOMITI ENERGIA',
            str_contains($haystack, 'OCTOPUS') => 'OCTOPUS ENERGY',
            str_contains($haystack, 'PLENITUDE') => 'PLENITUDE',
            default => null,
        };
    }

    private function detectProposedSupplier(string $text): ?string
    {
        return str_contains(mb_strtoupper($text), 'PLENITUDE') ? 'PLENITUDE' : null;
    }

    private function detectPaymentMethod(string $text): ?string
    {
        return match (true) {
            str_contains(mb_strtoupper($text), 'SDD') => 'sepa',
            str_contains(mb_strtoupper($text), 'SEPA') => 'sepa',
            default => null,
        };
    }

    private function extractAnnualConsumption(string $text, string $serviceType): ?string
    {
        return match ($serviceType) {
            'electricity' => $this->extractAmount('/Consumo annuo aggiornato:\s*([0-9\.,]+)\s*kWh/iu', $text),
            'gas' => $this->extractAmount('/Consumo annuo aggiornato:\s*([0-9\.,]+)\s*Smc/iu', $text),
            default => null,
        };
    }

    private function extractDate(string $pattern, string $text): ?string
    {
        $value = $this->match($pattern, $text);
        if ($value === null) {
            return null;
        }

        try {
            $date = CarbonImmutable::createFromFormat('d/m/Y', $value);
        } catch (\Throwable) {
            return null;
        }

        return $date instanceof CarbonImmutable ? $date->toDateString() : null;
    }

    private function extractEmail(string $text): ?string
    {
        return $this->match('/([A-Z0-9._%+\-]+@[A-Z0-9.\-]+\.[A-Z]{2,})/iu', $text);
    }

    private function extractAmount(string $pattern, string $text): ?string
    {
        $value = $this->match($pattern, $text);
        if ($value === null) {
            return null;
        }

        $value = str_replace('.', '', $value);
        $value = str_replace(',', '.', $value);
        $result = preg_replace('/[^0-9.]+/', '', $value);
        $value = is_string($result) ? $result : '';

        return $value !== '' ? $value : null;
    }

    private function match(string $pattern, string $text): ?string
    {
        if (preg_match($pattern, $text, $matches) !== 1) {
            return null;
        }

        $value = trim((string) ($matches[1] ?? ''));

        return $value !== '' ? $value : null;
    }
}
