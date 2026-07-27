<?php

declare(strict_types=1);

use Illuminate\Support\Facades\DB;
use Modules\Catalog\Models\Product;
use Modules\Customer\Models\Customer;
use Modules\Quotation\Actions\BuildQuotationDraftAction;
use Modules\Quotation\Actions\MatchDictatedItemsToCatalogAction;
use Modules\Quotation\Actions\ResolveOrCreateCustomerFromDraftAction;
use Modules\Quotation\AI\QuotationDraftExtractorInterface;
use Modules\Quotation\DTO\DictatedQuotationItem;
use Modules\Quotation\DTO\QuotationCustomerDraft;
use Modules\Quotation\Models\Quotation;
use Modules\Quotation\Tests\TestCase;

uses(TestCase::class);

/**
 * @param  array<string, mixed>  $payload
 */
function fuzzyExtractor(array $payload): QuotationDraftExtractorInterface
{
    return new class($payload) implements QuotationDraftExtractorInterface
    {
        /** @param array<string, mixed> $payload */
        public function __construct(private array $payload) {}

        public function extract(string $transcript): array
        {
            return $this->payload;
        }
    };
}

// ─── Catalog: typo-tolerant matching ──────────────────────────────────────────

it('matches a mistyped article to the right catalog product', function (): void {
    Product::query()->create(['name' => 'Scaldabagno elettrico 80 lt', 'sku' => 'IDR-SCALD-80', 'unit_price' => 210.0, 'is_active' => true]);

    $result = app(MatchDictatedItemsToCatalogAction::class)->execute([
        new DictatedQuotationItem('scaldavagno elettrico', 1.0), // typo: scaldaVagno
    ]);

    expect($result[0]->isMatched())->toBeTrue();
    expect($result[0]->description)->toBe('Scaldabagno elettrico 80 lt');
    expect($result[0]->unitPrice)->toBe(210.0);
});

// ─── Customer: reuse a similar existing one instead of creating a duplicate ────

it('reuses an existing customer with a similar name instead of creating a new one', function (): void {
    $existing = Customer::query()->create(['legal_name' => 'Idraulica Rossi SRL', 'is_active' => true]);
    $action = app(ResolveOrCreateCustomerFromDraftAction::class);

    // Missing suffix.
    expect($action->execute(new QuotationCustomerDraft('Idraulica Rossi')))->toBe((int) $existing->id);
    // Typo.
    expect($action->execute(new QuotationCustomerDraft('Idraulica Rossy')))->toBe((int) $existing->id);
    // A clearly different company is NOT merged.
    $other = $action->execute(new QuotationCustomerDraft('Elettrica Bianchi SNC'));
    expect($other)->not->toBe((int) $existing->id);

    expect(Customer::query()->count())->toBe(2);
});

// ─── Dedup: copying a quotation + adding an article it already has = 1 line ────

it('does not duplicate an article already present in the copied quotation', function (): void {
    $product = Product::query()->create(['name' => 'Scaldabagno elettrico 80 lt', 'sku' => 'IDR-SCALD-80', 'unit_price' => 210.0, 'is_active' => true]);

    $pincoId = DB::table('customers')->insertGetId([
        'legal_name' => 'Pinco Pallino', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
    ]);
    $pinco = Quotation::query()->create(['customer_id' => $pincoId, 'status_code' => 'draft', 'title' => 'X']);
    $pinco->items()->create(['product_id' => $product->id, 'description' => 'Scaldabagno elettrico 80 lt', 'quantity' => 1, 'unit_price' => 210.0, 'line_total' => 210.0, 'sort_order' => 0]);

    $extractor = fuzzyExtractor([
        'based_on_customer' => 'Pinco Pallino',
        'customer' => ['legal_name' => ''],
        'items' => [['description' => 'scaldabagno elettrico', 'quantity' => 1]],
    ]);

    $draft = (new BuildQuotationDraftAction($extractor, app(MatchDictatedItemsToCatalogAction::class)))->execute('come quello di Pinco con uno scaldabagno');

    $scaldabagni = collect($draft->items)->filter(fn (DictatedQuotationItem $i): bool => str_contains(mb_strtolower($i->description), 'scaldabagno'))->count();
    expect($scaldabagni)->toBe(1);
});
