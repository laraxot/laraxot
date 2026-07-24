<?php

declare(strict_types=1);

namespace Modules\Billing\Actions;

use DateTimeInterface;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Modules\Billing\Models\Invoice;
use Modules\Platform\Settings\SettingsRepository;
use Spatie\QueueableAction\QueueableAction;

final class SendElectronicInvoiceAction
{
    use QueueableAction;

    public function __construct(
        private readonly SettingsRepository $settings,
    ) {}

    /**
     * @return array{provider: string, status: int, external_id: string|null}
     */
    public function execute(Invoice $invoice): array
    {
        $provider = trim((string) $this->settings->getString('billing.einvoice.provider', 'disabled'));
        if ($provider === '' || $provider === 'disabled') {
            throw new \RuntimeException('Provider fattura elettronica non configurato nelle impostazioni piattaforma.');
        }

        $apiKey = trim((string) $this->settings->getString('billing.einvoice.api_key', ''));
        if ($apiKey === '') {
            throw new \RuntimeException('API key provider fattura elettronica mancante nelle impostazioni piattaforma.');
        }

        $endpoint = trim((string) $this->settings->getString('billing.einvoice.endpoint', ''));
        if ($endpoint === '') {
            throw new \RuntimeException('Endpoint invio fattura elettronica mancante nelle impostazioni piattaforma.');
        }

        $timeout = $this->settings->getInt('billing.einvoice.timeout_seconds', 20);
        if ($timeout <= 0) {
            $timeout = 20;
        }

        $customerRaw = DB::table('customers')
            ->where('id', (int) $invoice->customer_id)
            ->first([
                'id',
                'legal_name',
                'email',
                'vat_number',
                'tax_code',
            ]);

        /** @var array<string, mixed> $customer */
        $customer = is_object($customerRaw) ? get_object_vars($customerRaw) : [];
        $customerId = isset($customer['id']) && is_numeric($customer['id']) ? (int) $customer['id'] : null;
        $customerName = isset($customer['legal_name']) && is_string($customer['legal_name']) ? $customer['legal_name'] : null;
        $customerEmail = isset($customer['email']) && is_string($customer['email']) ? $customer['email'] : null;
        $customerVat = isset($customer['vat_number']) && is_string($customer['vat_number']) ? $customer['vat_number'] : null;
        $customerTaxCode = isset($customer['tax_code']) && is_string($customer['tax_code']) ? $customer['tax_code'] : null;

        $issuedOnRaw = $invoice->issued_on;
        $issuedOn = is_string($issuedOnRaw) ? $issuedOnRaw : null;
        if ($issuedOnRaw instanceof DateTimeInterface) {
            $issuedOn = $issuedOnRaw->format('Y-m-d');
        }

        $payload = [
            'provider' => $provider,
            'invoice' => [
                'id' => (int) $invoice->id,
                'number' => $invoice->number,
                'issued_on' => $issuedOn,
                'total' => (float) $invoice->total,
                'notes' => $invoice->notes,
                'customer' => [
                    'id' => $customerId,
                    'name' => $customerName,
                    'email' => $customerEmail,
                    'vat_number' => $customerVat,
                    'tax_code' => $customerTaxCode,
                ],
                'work_order_id' => $invoice->work_order_id === null ? null : (int) $invoice->work_order_id,
            ],
        ];

        $response = Http::acceptJson()
            ->asJson()
            ->withToken($apiKey)
            ->timeout($timeout)
            ->post($endpoint, $payload);

        if (! $response->successful()) {
            $errorMessage = $this->extractErrorMessage($response);

            $invoice->forceFill([
                'electronic_invoice_provider' => $provider,
                'electronic_invoice_last_error' => $errorMessage,
            ])->save();

            throw new \RuntimeException('Invio fattura elettronica fallito: '.$errorMessage);
        }

        /** @var mixed $json */
        $json = $response->json();
        $externalId = null;
        if (is_array($json)) {
            $candidate = data_get($json, 'external_id')
                ?? data_get($json, 'invoice_id')
                ?? data_get($json, 'id')
                ?? data_get($json, 'data.id');

            if ($candidate !== null && (is_string($candidate) || is_int($candidate) || is_float($candidate))) {
                $externalId = (string) $candidate;
            }
        }

        $invoice->forceFill([
            'electronic_invoice_provider' => $provider,
            'electronic_invoice_external_id' => $externalId,
            'electronic_invoice_sent_at' => now(),
            'electronic_invoice_last_error' => null,
        ])->save();

        return [
            'provider' => $provider,
            'status' => $response->status(),
            'external_id' => $externalId,
        ];
    }

    private function extractErrorMessage(Response $response): string
    {
        /** @var mixed $json */
        $json = $response->json();
        if (is_array($json)) {
            $message = data_get($json, 'message') ?? data_get($json, 'error.message') ?? data_get($json, 'error');
            if (is_string($message) && trim($message) !== '') {
                return mb_substr(trim($message), 0, 1900);
            }
        }

        $body = trim($response->body());
        if ($body === '') {
            return 'Errore HTTP '.$response->status();
        }

        return mb_substr($body, 0, 1900);
    }
}
