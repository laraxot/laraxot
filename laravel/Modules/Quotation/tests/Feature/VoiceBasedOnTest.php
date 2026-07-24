<?php

declare(strict_types=1);

uses(\Modules\Quotation\Tests\TestCase::class);

use Illuminate\Support\Facades\DB;
use Modules\Catalog\Models\Product;
use Modules\Document\Models\Media;
use Modules\Quotation\Actions\BuildQuotationDraftAction;
use Modules\Quotation\Actions\MatchDictatedItemsToCatalogAction;
use Modules\Quotation\AI\QuotationDraftExtractorInterface;
use Modules\Quotation\DTO\DictatedQuotationItem;
use Modules\Quotation\Models\Quotation;

function basedOnExtractor(array $payload): QuotationDraftExtractorInterface
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

function quotationWithItems(string $legalName, array $lines): Quotation
{
    $customerId = DB::table('customers')->where('legal_name', $legalName)->value('id')
        ?? DB::table('customers')->insertGetId(['legal_name' => $legalName, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()]);

    /** @var Quotation $q */
    $q = Quotation::query()->create(['customer_id' => $customerId, 'status_code' => 'draft', 'title' => 'X']);
    $sort = 0;
    foreach ($lines as [$desc, $price]) {
        $q->items()->create(['description' => $desc, 'quantity' => 1, 'unit_price' => $price, 'line_total' => $price, 'sort_order' => $sort++]);
    }

    return $q;
}

it('by default puts ONLY the dictated articles, using the source only as template', function (): void {
    $pinco = quotationWithItems('Pinco Pallino', [['Fornitura e posa di Ariston Clas One', 1158.0]]);
    Product::query()->create(['name' => 'Scaldabagno elettrico 80 lt', 'sku' => 'IDR-SCALD-80', 'unit_price' => 210.0, 'is_active' => true]);

    // "metteci solo uno scaldabagno" -> keep_source_items false (or absent).
    $extractor = basedOnExtractor([
        'customer' => ['legal_name' => 'Davide Cavallini'],
        'based_on_customer' => 'Pinco Pallino',
        'keep_source_items' => false,
        'items' => [['description' => 'scaldabagno elettrico', 'quantity' => 1]],
    ]);

    $draft = (new BuildQuotationDraftAction($extractor, app(MatchDictatedItemsToCatalogAction::class)))
        ->execute('Copia il preventivo di Pinco Pallino per Davide Cavallini e metteci solo uno scaldabagno');

    expect($draft->customer?->legalName)->toBe('Davide Cavallini');
    // Source still referenced (so the attachment can be copied)...
    expect($draft->basedOnQuotationId)->toBe((int) $pinco->id);
    // ...but only the dictated article is present, NOT Pinco's line items.
    $descriptions = array_map(static fn (DictatedQuotationItem $i): string => $i->description, $draft->items);
    expect($descriptions)->toBe(['Scaldabagno elettrico 80 lt']);
});

it('keeps ALL source items only when keep_source_items is true', function (): void {
    $pinco = quotationWithItems('Pinco Pallino', [['Fornitura e posa di Ariston Clas One', 1158.0]]);
    Product::query()->create(['name' => 'Scaldabagno elettrico 80 lt', 'sku' => 'IDR-SCALD-80', 'unit_price' => 210.0, 'is_active' => true]);

    // "tieni tutti gli articoli e aggiungi..." -> keep_source_items true.
    $extractor = basedOnExtractor([
        'customer' => ['legal_name' => 'Davide Cavallini'],
        'based_on_customer' => 'Pinco Pallino',
        'keep_source_items' => true,
        'items' => [['description' => 'scaldabagno elettrico', 'quantity' => 1]],
    ]);

    $draft = (new BuildQuotationDraftAction($extractor, app(MatchDictatedItemsToCatalogAction::class)))
        ->execute('Fammi lo stesso preventivo di Pinco Pallino per Davide Cavallini, tieni tutti gli articoli e aggiungi uno scaldabagno');

    expect($draft->basedOnQuotationId)->toBe((int) $pinco->id);
    $descriptions = array_map(static fn (DictatedQuotationItem $i): string => $i->description, $draft->items);
    expect($descriptions)->toContain('Fornitura e posa di Ariston Clas One'); // source structure kept
    expect($descriptions)->toContain('Scaldabagno elettrico 80 lt');          // catalog article added
});

it('finds the source customer despite a typo in the dictated name', function (): void {
    // The customer was imported from a file with a typo: stored "Pico Pallino".
    $pico = quotationWithItems('Pico Pallino', [['Fornitura e posa di Ariston Clas One', 1158.0]]);
    Media::query()->create([
        'mediable_type' => Quotation::class, 'mediable_id' => $pico->id,
        'kind' => 'document', 'disk' => 'local', 'path' => 'quotation-attachments/'.$pico->id.'/pico.ods', 'title' => 'Preventivo',
    ]);

    // The operator dictates "Pinco Pallino" (the natural spelling).
    $extractor = basedOnExtractor([
        'customer' => ['legal_name' => 'Davide Cavallini'],
        'based_on_customer' => 'Pinco Pallino',
        'keep_source_items' => true,
        'items' => [],
    ]);

    $draft = (new BuildQuotationDraftAction($extractor, app(MatchDictatedItemsToCatalogAction::class)))
        ->execute('Fammi lo stesso preventivo di Pinco Pallino per Davide Cavallini tenendo tutti gli articoli');

    expect($draft->basedOnQuotationId)->toBe((int) $pico->id);
    $descriptions = array_map(static fn (DictatedQuotationItem $i): string => $i->description, $draft->items);
    expect($descriptions)->toContain('Fornitura e posa di Ariston Clas One');
});

it('prefers the source quotation that has a document attachment', function (): void {
    // Older quotation WITH an attachment.
    $withDoc = quotationWithItems('Pinco Pallino', [['Fornitura e posa di Ariston Clas One', 1158.0]]);
    Media::query()->create([
        'mediable_type' => Quotation::class, 'mediable_id' => $withDoc->id,
        'kind' => 'document', 'disk' => 'local', 'path' => 'quotation-attachments/'.$withDoc->id.'/pinco.ods', 'title' => 'Preventivo',
    ]);
    // Newer quotation WITHOUT an attachment.
    $latest = quotationWithItems('Pinco Pallino', [['Solo manodopera', 200.0]]);

    $extractor = basedOnExtractor(['based_on_customer' => 'Pinco Pallino', 'keep_source_items' => true, 'items' => []]);

    $draft = (new BuildQuotationDraftAction($extractor, app(MatchDictatedItemsToCatalogAction::class)))->execute('come quello di Pinco Pallino, tieni tutti gli articoli');

    expect($draft->basedOnQuotationId)->toBe((int) $withDoc->id); // not $latest
    $descriptions = array_map(static fn (DictatedQuotationItem $i): string => $i->description, $draft->items);
    expect($descriptions)->toContain('Fornitura e posa di Ariston Clas One');
    expect($descriptions)->not->toContain('Solo manodopera');
});
