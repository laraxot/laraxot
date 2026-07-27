<?php

declare(strict_types=1);

use Illuminate\Support\Facades\DB;
use Modules\Catalog\Models\Product;
use Modules\Customer\Models\Customer;
use Modules\Quotation\Actions\BuildQuotationDraftAction;
use Modules\Quotation\Actions\CreateQuotationFromDraftAction;
use Modules\Quotation\Actions\DuplicateQuotationAsDraftAction;
use Modules\Quotation\Actions\MatchDictatedItemsToCatalogAction;
use Modules\Quotation\Actions\ResolveOrCreateCustomerFromDraftAction;
use Modules\Quotation\AI\QuotationDraftExtractorInterface;
use Modules\Quotation\DTO\DictatedQuotationItem;
use Modules\Quotation\DTO\QuotationCustomerDraft;
use Modules\Quotation\DTO\QuotationDraft;
use Modules\Quotation\Models\Quotation;
use Modules\Quotation\Tests\TestCase;

uses(TestCase::class);

/**
 * @param  array<string, mixed>  $payload
 */
function fakeQuotationExtractor(array $payload): QuotationDraftExtractorInterface
{
    return new class($payload) implements QuotationDraftExtractorInterface
    {
        /** @param array<string, mixed> $payload */
        public function __construct(private array $payload) {}

        /** @return array<string, mixed> */
        public function extract(string $transcript): array
        {
            return $this->payload;
        }
    };
}

/** @param array<string, mixed> $attrs */
function makeProduct(array $attrs): Product
{
    return Product::query()->create(array_merge([
        'name' => 'Prodotto',
        'is_active' => true,
        'unit_price' => 10,
    ], $attrs));
}

// ─── DTOs ────────────────────────────────────────────────────────────────────

it('computes DictatedQuotationItem behaviour', function (): void {
    $free = new DictatedQuotationItem('Tubo', 3.0, null);
    expect($free->isMatched())->toBeFalse();
    expect($free->lineTotal())->toBe(0.0);

    $priced = new DictatedQuotationItem('Tubo', 3.0, 2.5);
    expect($priced->lineTotal())->toBe(7.5);

    $matched = $free->withCatalogMatch(9, 4.0);
    expect($matched->isMatched())->toBeTrue();
    expect($matched->unitPrice)->toBe(4.0);

    // Null catalog price keeps the dictated price.
    $keep = $priced->withCatalogMatch(9, null);
    expect($keep->unitPrice)->toBe(2.5);

    expect($matched->toArray())->toMatchArray([
        'description' => 'Tubo',
        'matched_product_id' => 9,
        'is_matched' => true,
    ]);
});

it('computes QuotationCustomerDraft identity and attributes', function (): void {
    expect((new QuotationCustomerDraft(''))->hasIdentity())->toBeFalse();
    expect((new QuotationCustomerDraft('', '  '))->hasIdentity())->toBeFalse();
    expect((new QuotationCustomerDraft('', 'IT123'))->hasIdentity())->toBeTrue();
    expect((new QuotationCustomerDraft('Acme'))->hasIdentity())->toBeTrue();

    $attrs = (new QuotationCustomerDraft('  Acme  ', 'IT1', null, 'a@b.it'))->toCustomerAttributes();
    expect($attrs['legal_name'])->toBe('Acme');
    expect($attrs['vat_number'])->toBe('IT1');
    expect($attrs['email'])->toBe('a@b.it');
});

it('computes QuotationDraft totals and serialization', function (): void {
    $items = [
        new DictatedQuotationItem('A', 2.0, 5.0, 1),
        new DictatedQuotationItem('B', 1.0, 3.0),
    ];
    $draft = new QuotationDraft(new QuotationCustomerDraft('Acme'), 'Titolo', 'Note', $items, 22.0);

    expect($draft->itemsTotal())->toBe(13.0);
    expect($draft->hasUnmatchedItems())->toBeTrue();

    $matchedOnly = new QuotationDraft(null, null, null, [new DictatedQuotationItem('A', 1.0, 1.0, 5)]);
    expect($matchedOnly->hasUnmatchedItems())->toBeFalse();
    expect($matchedOnly->toArray()['customer'])->toBeNull();
    expect($draft->toArray()['customer'])->not->toBeNull();
});

// ─── Matcher ─────────────────────────────────────────────────────────────────

it('matches dictated items to catalog by name, sku, partial and keyword', function (): void {
    makeProduct(['name' => 'Valvola a sfera 1/2"', 'sku' => 'VAL12', 'unit_price' => 7.5]);
    makeProduct(['name' => 'Tubo multistrato 16', 'sku' => 'TUBO16', 'unit_price' => null]);

    $action = app(MatchDictatedItemsToCatalogAction::class);

    $result = $action->execute([
        new DictatedQuotationItem('valvola a sfera 1/2"', 1.0),   // exact name (ci)
        new DictatedQuotationItem('VAL12', 1.0),                   // exact sku
        new DictatedQuotationItem('mi serve un tubo multistrato 16 blu', 2.0), // keyword/partial
        new DictatedQuotationItem('pezzo inesistente xyz', 1.0),   // no match
        new DictatedQuotationItem('già abbinato', 1.0, 1.0, 99),   // already matched -> untouched
        new DictatedQuotationItem('   ', 1.0),                     // empty -> untouched
    ]);

    expect($result[0]->isMatched())->toBeTrue();
    expect($result[0]->unitPrice)->toBe(7.5);
    expect($result[1]->isMatched())->toBeTrue();
    expect($result[2]->isMatched())->toBeTrue();
    expect($result[2]->unitPrice)->toBeNull(); // matched product has null price
    expect($result[3]->isMatched())->toBeFalse();
    expect($result[4]->matchedProductId)->toBe(99);
    expect($result[5]->isMatched())->toBeFalse();
});

// ─── Build from voice ────────────────────────────────────────────────────────

it('builds a normalized quotation draft from an untrusted extractor payload', function (): void {
    makeProduct(['name' => 'Raccordo', 'unit_price' => 4.0]);

    $extractor = fakeQuotationExtractor([
        'customer' => ['legal_name' => 'Idraulica Bianchi', 'vat_number' => 'IT99', 'city' => 'Trento'],
        'title' => '  Bagno nuovo ',
        'notes' => '',
        'tax_percent' => 10,
        'items' => [
            ['description' => 'Raccordo', 'quantity' => 3, 'unit_price' => null],
            ['description' => '', 'quantity' => 1], // dropped
            'garbage',                               // dropped
            ['description' => 'Manodopera', 'quantity' => -5, 'unit_price' => 30], // qty normalized to 1
        ],
    ]);

    $draft = (new BuildQuotationDraftAction($extractor, new MatchDictatedItemsToCatalogAction()))->execute('bla');

    expect($draft->customer?->legalName)->toBe('Idraulica Bianchi');
    expect($draft->customer?->city)->toBe('Trento');
    expect($draft->title)->toBe('Bagno nuovo');
    expect($draft->notes)->toBeNull();
    expect($draft->taxPercent)->toBe(10.0);
    expect($draft->items)->toHaveCount(2);
    expect($draft->items[0]->isMatched())->toBeTrue();
    expect($draft->items[0]->unitPrice)->toBe(4.0);
    expect($draft->items[1]->quantity)->toBe(1.0);
});

it('returns a draft with no customer and no items when the extractor is empty', function (): void {
    $draft = (new BuildQuotationDraftAction(fakeQuotationExtractor([]), new MatchDictatedItemsToCatalogAction()))->execute('x');

    expect($draft->customer)->toBeNull();
    expect($draft->items)->toBe([]);
    expect($draft->title)->toBeNull();
});

it('ignores a customer payload without legal name or vat', function (): void {
    $draft = (new BuildQuotationDraftAction(
        fakeQuotationExtractor(['customer' => ['city' => 'Trento']]),
        new MatchDictatedItemsToCatalogAction(),
    ))->execute('x');

    expect($draft->customer)->toBeNull();
});

// ─── Duplicate from source ───────────────────────────────────────────────────

it('turns an existing quotation into an editable draft', function (): void {
    $customerId = DB::table('customers')->insertGetId([
        'legal_name' => 'Cliente Origine', 'vat_number' => 'IT1', 'is_active' => 1,
        'created_at' => now(), 'updated_at' => now(),
    ]);
    $product = makeProduct(['name' => 'Tubo', 'unit_price' => 2.0]);

    $source = Quotation::query()->create([
        'customer_id' => $customerId, 'status_code' => 'draft',
        'title' => 'Origine', 'notes' => 'note origine', 'tax_percent' => 10,
    ]);
    $source->items()->create(['product_id' => $product->id, 'description' => 'Tubo', 'quantity' => 5, 'unit_price' => 2.0, 'sort_order' => 0, 'line_total' => 0]);

    $draft = app(DuplicateQuotationAsDraftAction::class)->execute($source);

    expect($draft->customer?->legalName)->toBe('Cliente Origine');
    expect($draft->title)->toBe('Origine');
    expect($draft->notes)->toBe('note origine');
    expect($draft->taxPercent)->toBe(10.0);
    expect($draft->items)->toHaveCount(1);
    expect($draft->items[0]->matchedProductId)->toBe((int) $product->id);
});

it('duplicates a quotation whose customer is missing and header is empty', function (): void {
    // Soft-deleted customer: the FK row still exists but the model is not found.
    $customer = Customer::query()->create(['legal_name' => 'Sparito', 'is_active' => true]);
    $customerId = (int) $customer->id;
    $customer->delete();

    $source = Quotation::query()->create([
        'customer_id' => $customerId, 'status_code' => 'draft', 'title' => '', 'notes' => '',
    ]);

    $draft = app(DuplicateQuotationAsDraftAction::class)->execute($source);

    expect($draft->customer)->toBeNull();
    expect($draft->title)->toBeNull();
    expect($draft->notes)->toBeNull();
    expect($draft->items)->toBe([]);
});

// ─── Resolve/create customer ─────────────────────────────────────────────────

it('resolves an existing customer by VAT, then by name, else creates one', function (): void {
    $existing = Customer::query()->create(['legal_name' => 'Rossi SRL', 'vat_number' => 'IT555', 'is_active' => true]);
    $action = app(ResolveOrCreateCustomerFromDraftAction::class);

    expect($action->execute(new QuotationCustomerDraft('qualsiasi', 'IT555')))->toBe((int) $existing->id);
    expect($action->execute(new QuotationCustomerDraft('rossi srl')))->toBe((int) $existing->id);

    $newId = $action->execute(new QuotationCustomerDraft('Verdi SNC', 'IT777', null, 'v@x.it'));
    expect($newId)->not->toBe((int) $existing->id);
    expect(Customer::query()->find($newId)?->email)->toBe('v@x.it');
});

// ─── Create quotation from draft ─────────────────────────────────────────────

it('persists a reviewed draft as a new quotation with recalculated totals', function (): void {
    $customerId = DB::table('customers')->insertGetId([
        'legal_name' => 'Nuovo Cliente', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
    ]);
    $product = makeProduct(['name' => 'Valvola', 'unit_price' => 8.0]);

    $draft = new QuotationDraft(
        customer: new QuotationCustomerDraft('Nuovo Cliente'),
        title: 'Preventivo vocale',
        notes: null,
        items: [
            new DictatedQuotationItem('Valvola', 2.0, 8.0, (int) $product->id),
            new DictatedQuotationItem('Manodopera', 1.0, null),
        ],
        taxPercent: null,
    );

    $quotation = app(CreateQuotationFromDraftAction::class)->execute($draft, $customerId);

    expect((int) $quotation->customer_id)->toBe($customerId);
    expect($quotation->title)->toBe('Preventivo vocale');
    expect((float) $quotation->tax_percent)->toBe(22.0); // default applied
    expect($quotation->items()->count())->toBe(2);
    expect((float) $quotation->subtotal)->toBe(16.0);
    expect((float) $quotation->total)->toBeGreaterThan(16.0);
    expect((int) $quotation->items()->where('description', 'Valvola')->value('product_id'))->toBe((int) $product->id);
});
