<?php

declare(strict_types=1);

uses(\Modules\Quotation\Tests\TestCase::class);

use Modules\User\Models\User;
use Livewire\Livewire;
use Modules\Catalog\Models\Product;
use Modules\Customer\Lookup\CompanyLookupProviderInterface;
use Modules\Customer\Models\Customer;
use Modules\Quotation\AI\QuotationDraftExtractorInterface;
use Modules\Quotation\Filament\Pages\RecordQuotationVoicePage;
use Modules\Quotation\Models\Quotation;
use Spatie\Permission\Models\Role;

use function Pest\Laravel\actingAs;

function voiceOperator(): User
{
    $role = Role::findOrCreate('operator');
    $user = User::factory()->create(['name' => 'Idraulico']);
    $user->syncRoles([$role]);

    return $user;
}

/**
 * @param  array<string, mixed>  $payload
 */
function bindFakeQuotationExtractor(array $payload): void
{
    app()->bind(QuotationDraftExtractorInterface::class, fn (): QuotationDraftExtractorInterface => new class($payload) implements QuotationDraftExtractorInterface
    {
        /** @param array<string, mixed> $payload */
        public function __construct(private array $payload) {}

        public function extract(string $transcript): array
        {
            return $this->payload;
        }
    });
}

beforeEach(function (): void {
    filament()->setCurrentPanel('operator');
});

it('generates a quotation draft from the transcript and matches items to catalog', function (): void {
    Product::query()->create(['name' => 'Miscelatore', 'unit_price' => 58.0, 'is_active' => true]);
    bindFakeQuotationExtractor([
        'customer' => ['legal_name' => 'Idraulica Verdi', 'city' => 'Trento'],
        'title' => 'Rifacimento bagno',
        'items' => [
            ['description' => 'Miscelatore', 'quantity' => 2],
            ['description' => 'Manodopera', 'quantity' => 1, 'unit_price' => 30],
        ],
    ]);
    actingAs(voiceOperator());

    Livewire::test(RecordQuotationVoicePage::class)
        ->set('transcript', 'rifacimento bagno per Idraulica Verdi, due miscelatori')
        ->call('generateDraft')
        ->assertSet('hasDraft', true)
        ->assertSet('customerLegalName', 'Idraulica Verdi')
        ->assertSet('customerCity', 'Trento')
        ->assertSet('quotationTitle', 'Rifacimento bagno')
        ->assertSet('items.0.is_matched', true)
        ->assertSet('items.0.unit_price', 58.0)
        ->assertSet('items.1.is_matched', false);
});

it('stores the transcript and the raw AI response on the created quotation', function (): void {
    Product::query()->create(['name' => 'Valvola', 'unit_price' => 8.0, 'is_active' => true]);
    bindFakeQuotationExtractor([
        'customer' => ['legal_name' => 'Idraulica Blu'],
        'items' => [['description' => 'Valvola', 'quantity' => 1]],
    ]);
    actingAs(voiceOperator());

    Livewire::test(RecordQuotationVoicePage::class)
        ->set('transcript', 'fammi un preventivo per Idraulica Blu con una valvola')
        ->call('generateDraft')
        ->call('confirm')
        ->assertRedirect();

    $customer = Customer::query()->where('legal_name', 'Idraulica Blu')->firstOrFail();
    $quotation = Quotation::query()->where('customer_id', $customer->id)->firstOrFail();

    expect($quotation->voice_transcript)->toContain('Idraulica Blu');
    expect($quotation->voice_ai_response['customer']['legal_name'] ?? null)->toBe('Idraulica Blu');
    expect($quotation->voice_ai_response['items'][0]['description'] ?? null)->toBe('Valvola');
});

it('warns and does nothing when the transcript is empty', function (): void {
    actingAs(voiceOperator());

    Livewire::test(RecordQuotationVoicePage::class)
        ->set('transcript', '   ')
        ->call('generateDraft')
        ->assertSet('hasDraft', false);
});

it('creates a new quotation on confirm, resolving/creating the customer', function (): void {
    $product = Product::query()->create(['name' => 'Valvola', 'unit_price' => 8.0, 'is_active' => true]);
    actingAs(voiceOperator());

    Livewire::test(RecordQuotationVoicePage::class)
        ->set('hasDraft', true)
        ->set('customerLegalName', 'Nuovo Idraulico SRL')
        ->set('customerVat', 'IT12345')
        ->set('quotationTitle', 'Preventivo vocale')
        ->set('items', [
            ['description' => 'Valvola', 'quantity' => 3.0, 'unit_price' => 8.0, 'matched_product_id' => (int) $product->id, 'is_matched' => true],
        ])
        ->call('confirm')
        ->assertRedirect();

    $customer = Customer::query()->where('vat_number', 'IT12345')->firstOrFail();
    $quotation = Quotation::query()->where('customer_id', $customer->id)->firstOrFail();
    expect($quotation->title)->toBe('Preventivo vocale');
    expect($quotation->items()->count())->toBe(1);
    expect((float) $quotation->subtotal)->toBe(24.0);
});

it('searches a company online and prefills the customer fields on pick', function (): void {
    app()->bind(CompanyLookupProviderInterface::class, fn (): CompanyLookupProviderInterface => new class implements CompanyLookupProviderInterface
    {
        public function search(string $query): array
        {
            return [['legal_name' => 'Idraulica Verdi SRL', 'vat_number' => 'IT0099', 'city' => 'Trento', 'email' => 'info@verdi.it']];
        }
    });
    actingAs(voiceOperator());

    Livewire::test(RecordQuotationVoicePage::class)
        ->set('companyQuery', 'verdi idraulica')
        ->call('searchCompany')
        ->assertSet('hasDraft', true)
        ->assertSet('companyCandidates.0.legal_name', 'Idraulica Verdi SRL')
        ->call('pickCompany', 0)
        ->assertSet('customerLegalName', 'Idraulica Verdi SRL')
        ->assertSet('customerVat', 'IT0099')
        ->assertSet('customerCity', 'Trento')
        ->assertSet('companyCandidates', []);
});

it('recomputes rule-driven lines from technical parameters, keeping manual ones', function (): void {
    Product::query()->create(['sku' => 'THERMO-CANNA-1M', 'name' => 'Canna fumaria al metro', 'unit_price' => 28.0, 'is_active' => true]);
    actingAs(voiceOperator());

    Livewire::test(RecordQuotationVoicePage::class)
        ->set('hasDraft', true)
        ->set('items', [
            ['description' => 'Manodopera', 'quantity' => 2.0, 'unit_price' => 30.0, 'matched_product_id' => null, 'is_matched' => false],
        ])
        ->set('technicalParams', ['flue_length_m' => 8, 'thermostat_present' => true])
        ->call('recalcFromParams')
        ->assertSet('items.0.description', 'Manodopera')
        ->assertSet('items.1.description', 'Canna fumaria al metro')
        ->assertSet('items.1.quantity', 8.0)
        ->assertSet('items.1.unit_price', 28.0);
});

it('blocks confirm when there is no customer identity', function (): void {
    actingAs(voiceOperator());

    Livewire::test(RecordQuotationVoicePage::class)
        ->set('hasDraft', true)
        ->set('items', [['description' => 'Valvola', 'quantity' => 1.0, 'unit_price' => 8.0, 'matched_product_id' => null, 'is_matched' => false]])
        ->call('confirm')
        ->assertNoRedirect();

    expect(Quotation::query()->count())->toBe(0);
});
