<?php

declare(strict_types=1);

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Modules\Billing\Actions\SendElectronicInvoiceAction;
use Modules\Billing\Models\Invoice;
use Modules\Billing\Tests\TestCase;
use Modules\Platform\Models\PlatformSetting;

uses(\Modules\Billing\Tests\TestCase::class);

it('sends electronic invoice with provider settings and stores tracking data', function (): void {
    $customerId = DB::table('customers')->insertGetId([
        'legal_name' => 'Acme S.p.A.',
        'email' => 'amministrazione@acme.test',
        'vat_number' => 'IT12345678901',
        'is_active' => 1,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    /** @var \Modules\Billing\Models\Invoice $invoice */
    $invoice = Invoice::query()->create([
        'number' => 'FAT-EINV-0001',
        'issued_on' => now()->toDateString(),
        'customer_id' => $customerId,
        'work_order_id' => null,
        'total' => 120.50,
        'notes' => 'Invio elettronico test',
    ]);

    PlatformSetting::query()->create(['key' => 'billing.einvoice.provider', 'value_json' => ['value' => 'custom_http']]);
    PlatformSetting::query()->create(['key' => 'billing.einvoice.api_key', 'value_json' => ['value' => 'secret-token']]);
    PlatformSetting::query()->create(['key' => 'billing.einvoice.endpoint', 'value_json' => ['value' => 'https://provider.test/einvoice/send']]);

    Http::fake([
        'https://provider.test/einvoice/send' => Http::response([
            'external_id' => 'EXT-INV-99',
            'status' => 'accepted',
        ], 201),
    ]);

    $result = app(SendElectronicInvoiceAction::class)->execute($invoice->refresh());

    expect($result['provider'])->toBe('custom_http')
        ->and($result['status'])->toBe(201)
        ->and($result['external_id'])->toBe('EXT-INV-99');

    $invoice->refresh();
    expect($invoice->electronic_invoice_provider)->toBe('custom_http')
        ->and($invoice->electronic_invoice_external_id)->toBe('EXT-INV-99')
        ->and($invoice->electronic_invoice_sent_at)->not->toBeNull()
        ->and($invoice->electronic_invoice_last_error)->toBeNull();

    Http::assertSent(function (Request $request): bool {
        $payload = $request->data();

        return $request->url() === 'https://provider.test/einvoice/send'
            && $request->hasHeader('Authorization', 'Bearer secret-token')
            && is_array($payload)
            && isset($payload['provider'])
            && $payload['provider'] === 'custom_http'
            && isset($payload['invoice'])
            && is_array($payload['invoice'])
            && isset($payload['invoice']['number'])
            && $payload['invoice']['number'] === 'FAT-EINV-0001';
    });
});

it('fails sending electronic invoice when api key is missing', function (): void {
    $customerId = DB::table('customers')->insertGetId([
        'legal_name' => 'No Api Key S.r.l.',
        'is_active' => 1,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    /** @var \Modules\Billing\Models\Invoice $invoice */
    $invoice = Invoice::query()->create([
        'number' => 'FAT-EINV-0002',
        'issued_on' => now()->toDateString(),
        'customer_id' => $customerId,
        'work_order_id' => null,
        'total' => 80.00,
        'notes' => null,
    ]);

    PlatformSetting::query()->create(['key' => 'billing.einvoice.provider', 'value_json' => ['value' => 'custom_http']]);
    PlatformSetting::query()->create(['key' => 'billing.einvoice.endpoint', 'value_json' => ['value' => 'https://provider.test/einvoice/send']]);

    expect(fn () => app(SendElectronicInvoiceAction::class)->execute($invoice->refresh()))
        ->toThrow(RuntimeException::class, 'API key provider fattura elettronica mancante');
});

it('stores last error when provider responds with an error', function (): void {
    $customerId = DB::table('customers')->insertGetId([
        'legal_name' => 'Provider Error S.p.A.',
        'is_active' => 1,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    /** @var \Modules\Billing\Models\Invoice $invoice */
    $invoice = Invoice::query()->create([
        'number' => 'FAT-EINV-0003',
        'issued_on' => now()->toDateString(),
        'customer_id' => $customerId,
        'work_order_id' => null,
        'total' => 50.00,
        'notes' => null,
    ]);

    PlatformSetting::query()->create(['key' => 'billing.einvoice.provider', 'value_json' => ['value' => 'custom_http']]);
    PlatformSetting::query()->create(['key' => 'billing.einvoice.api_key', 'value_json' => ['value' => 'secret-token']]);
    PlatformSetting::query()->create(['key' => 'billing.einvoice.endpoint', 'value_json' => ['value' => 'https://provider.test/einvoice/send']]);

    Http::fake([
        'https://provider.test/einvoice/send' => Http::response(['message' => 'invalid vat number'], 422),
    ]);

    expect(fn () => app(SendElectronicInvoiceAction::class)->execute($invoice->refresh()))
        ->toThrow(RuntimeException::class, 'Invio fattura elettronica fallito');

    $invoice->refresh();
    expect($invoice->electronic_invoice_provider)->toBe('custom_http')
        ->and($invoice->electronic_invoice_last_error)->toContain('invalid vat number');
});

it('fails sending electronic invoice when provider is disabled', function (): void {
    $customerId = DB::table('customers')->insertGetId([
        'legal_name' => 'Provider Disabled S.r.l.',
        'is_active' => 1,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    /** @var \Modules\Billing\Models\Invoice $invoice */
    $invoice = Invoice::query()->create([
        'number' => 'FAT-EINV-0004',
        'issued_on' => now()->toDateString(),
        'customer_id' => $customerId,
        'work_order_id' => null,
        'total' => 10.00,
        'notes' => null,
    ]);

    PlatformSetting::query()->create(['key' => 'billing.einvoice.provider', 'value_json' => ['value' => 'disabled']]);

    expect(fn () => app(SendElectronicInvoiceAction::class)->execute($invoice->refresh()))
        ->toThrow(RuntimeException::class, 'Provider fattura elettronica non configurato');
});

it('fails sending electronic invoice when endpoint is missing', function (): void {
    $customerId = DB::table('customers')->insertGetId([
        'legal_name' => 'No Endpoint S.r.l.',
        'is_active' => 1,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    /** @var \Modules\Billing\Models\Invoice $invoice */
    $invoice = Invoice::query()->create([
        'number' => 'FAT-EINV-0005',
        'issued_on' => now()->toDateString(),
        'customer_id' => $customerId,
        'work_order_id' => null,
        'total' => 20.00,
        'notes' => null,
    ]);

    PlatformSetting::query()->create(['key' => 'billing.einvoice.provider', 'value_json' => ['value' => 'custom_http']]);
    PlatformSetting::query()->create(['key' => 'billing.einvoice.api_key', 'value_json' => ['value' => 'secret-token']]);
    PlatformSetting::query()->create(['key' => 'billing.einvoice.endpoint', 'value_json' => ['value' => '']]);

    expect(fn () => app(SendElectronicInvoiceAction::class)->execute($invoice->refresh()))
        ->toThrow(RuntimeException::class, 'Endpoint invio fattura elettronica mancante');
});

it('extracts external id from data.id and normalizes timeout to default', function (): void {
    $customerId = DB::table('customers')->insertGetId([
        'legal_name' => 'ExternalId DataId S.r.l.',
        'is_active' => 1,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    /** @var \Modules\Billing\Models\Invoice $invoice */
    $invoice = Invoice::query()->create([
        'number' => 'FAT-EINV-0006',
        'issued_on' => now()->toDateString(),
        'customer_id' => $customerId,
        'work_order_id' => null,
        'total' => 30.00,
        'notes' => 'test data.id',
    ]);

    PlatformSetting::query()->create(['key' => 'billing.einvoice.provider', 'value_json' => ['value' => 'custom_http']]);
    PlatformSetting::query()->create(['key' => 'billing.einvoice.api_key', 'value_json' => ['value' => 'secret-token']]);
    PlatformSetting::query()->create(['key' => 'billing.einvoice.endpoint', 'value_json' => ['value' => 'https://provider.test/einvoice/send']]);
    PlatformSetting::query()->create(['key' => 'billing.einvoice.timeout_seconds', 'value_json' => ['value' => 0]]);

    Http::fake([
        'https://provider.test/einvoice/send' => Http::response(['data' => ['id' => 123]], 200),
    ]);

    $result = app(SendElectronicInvoiceAction::class)->execute($invoice->refresh());

    expect($result['external_id'])->toBe('123');
});

it('stores http status error message when response body is empty', function (): void {
    $customerId = DB::table('customers')->insertGetId([
        'legal_name' => 'Empty Body S.r.l.',
        'is_active' => 1,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    /** @var \Modules\Billing\Models\Invoice $invoice */
    $invoice = Invoice::query()->create([
        'number' => 'FAT-EINV-0007',
        'issued_on' => now()->toDateString(),
        'customer_id' => $customerId,
        'work_order_id' => null,
        'total' => 40.00,
        'notes' => null,
    ]);

    PlatformSetting::query()->create(['key' => 'billing.einvoice.provider', 'value_json' => ['value' => 'custom_http']]);
    PlatformSetting::query()->create(['key' => 'billing.einvoice.api_key', 'value_json' => ['value' => 'secret-token']]);
    PlatformSetting::query()->create(['key' => 'billing.einvoice.endpoint', 'value_json' => ['value' => 'https://provider.test/einvoice/send']]);

    Http::fake([
        'https://provider.test/einvoice/send' => Http::response('', 500, ['Content-Type' => 'text/plain']),
    ]);

    expect(fn () => app(SendElectronicInvoiceAction::class)->execute($invoice->refresh()))
        ->toThrow(RuntimeException::class, 'Invio fattura elettronica fallito');

    $invoice->refresh();
    expect($invoice->electronic_invoice_last_error)->toBe('Errore HTTP 500');
});

it('stores provider response body when error json is missing', function (): void {
    $customerId = DB::table('customers')->insertGetId([
        'legal_name' => 'Plain Body S.r.l.',
        'is_active' => 1,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    /** @var \Modules\Billing\Models\Invoice $invoice */
    $invoice = Invoice::query()->create([
        'number' => 'FAT-EINV-0008',
        'issued_on' => now()->toDateString(),
        'customer_id' => $customerId,
        'work_order_id' => null,
        'total' => 50.00,
        'notes' => null,
    ]);

    PlatformSetting::query()->create(['key' => 'billing.einvoice.provider', 'value_json' => ['value' => 'custom_http']]);
    PlatformSetting::query()->create(['key' => 'billing.einvoice.api_key', 'value_json' => ['value' => 'secret-token']]);
    PlatformSetting::query()->create(['key' => 'billing.einvoice.endpoint', 'value_json' => ['value' => 'https://provider.test/einvoice/send']]);

    Http::fake([
        'https://provider.test/einvoice/send' => Http::response('provider is down', 503, ['Content-Type' => 'text/plain']),
    ]);

    expect(fn () => app(SendElectronicInvoiceAction::class)->execute($invoice->refresh()))
        ->toThrow(RuntimeException::class, 'Invio fattura elettronica fallito');

    $invoice->refresh();
    expect($invoice->electronic_invoice_last_error)->toContain('provider is down');
});
