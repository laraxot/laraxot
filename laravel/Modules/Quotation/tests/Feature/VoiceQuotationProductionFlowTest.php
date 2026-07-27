<?php

declare(strict_types=1);

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Modules\AiAssistant\Reports\OpenAiQuotationDraftExtractor;
use Modules\Catalog\Models\Product;
use Modules\Document\Models\Media;
use Modules\Quotation\AI\QuotationDraftExtractorInterface;
use Modules\Quotation\Models\Quotation;
use Modules\Quotation\Tests\TestCase;

uses(TestCase::class);

use function Pest\Laravel\actingAs;
use function Safe\file_get_contents;
use function Safe\json_encode;

/**
 * PRODUCTION-FAITHFUL test: runs the REAL LLM extractor (real prompt + real JSON
 * parsing) and the whole chain (BuildQuotationDraftAction → catalog matcher → page
 * → confirm → attachment copy). The ONLY thing faked is the LLM provider's HTTP
 * response — exactly what you would record from a live call. No fake interfaces.
 *
 * @param  array<string, mixed>  $llmPayload  the JSON object a real LLM returns
 */
function fakeLlmReturning(array $llmPayload): void
{
    // Pick a provider so LlmChatJsonClient actually issues the HTTP call.
    config(['services.deepseek.key' => 'test-key', 'services.deepseek.base_uri' => 'https://api.deepseek.com/v1']);

    Http::fake([
        '*/chat/completions' => Http::response([
            'choices' => [['message' => ['content' => json_encode($llmPayload, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR)]]],
        ], 200),
    ]);

    // Ensure the container resolves the REAL extractor (not any test fake).
    app()->bind(QuotationDraftExtractorInterface::class, OpenAiQuotationDraftExtractor::class);
}

beforeEach(function (): void {
    filament()->setCurrentPanel('operator');
});

it('reproduces the exact voice flow end-to-end against the real extractor (Http-faked LLM)', function (): void {
    Storage::fake('local');

    actingAs(quotationOperator());

    // Source customer stored with the file's typo ("Pico Pallino") + a real .ods.
    $sourceCustomerId = DB::table('customers')->insertGetId([
        'legal_name' => 'Pico Pallino', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
    ]);
    // Source quotation mirrors the .ods fixture (its lines/prices/total appear
    // verbatim in the document) so the substitution can be verified end-to-end.
    $source = Quotation::query()->create([
        'customer_id' => $sourceCustomerId, 'status_code' => 'draft',
        'title' => 'Preventivo per sostituzione caldaia', 'subtotal' => 2792.0, 'total' => 2764.08,
    ]);
    $source->items()->create(['description' => 'Fornitura e posa di Ariston Clas One Wifi 24 in con produzione di15,7 litri', 'quantity' => 1, 'unit_price' => 1158.0, 'line_total' => 1158.0, 'sort_order' => 0]);
    $source->items()->create(['description' => 'Fornitura e posa di canna fumaria a parete o tetto (prezzo a corpo)', 'quantity' => 1, 'unit_price' => 190.0, 'line_total' => 190.0, 'sort_order' => 1]);

    $path = 'quotation-attachments/'.$source->id.'/pico.ods';
    Storage::disk('local')->put($path, file_get_contents(base_path('tests/Fixtures/quotations/pinco-pallino.ods')));
    Media::query()->create([
        'mediable_type' => Quotation::class, 'mediable_id' => $source->id,
        'kind' => 'document', 'disk' => 'local', 'path' => $path, 'title' => 'Preventivo Pico Pallino.ods',
    ]);

    // The only scaldabagno in the catalog (as the operator insists).
    Product::query()->create(['name' => 'Scaldabagno elettrico 80 lt verticale', 'sku' => 'DMO0600002', 'unit_price' => 210.0, 'is_active' => true]);

    // What a correct LLM returns for "...metteci SOLO uno scaldabagno": keep_source_items
    // false — only the dictated article, Pinco used only as template + attachment.
    fakeLlmReturning([
        'based_on_customer' => 'Pinco Pallino',
        'keep_source_items' => false,
        'customer' => ['legal_name' => 'Davide Cavallini', 'vat_number' => null, 'tax_code' => null, 'email' => null, 'phone' => null, 'address_line1' => null, 'city' => null, 'postal_code' => null, 'province' => null],
        'title' => null,
        'notes' => null,
        'tax_percent' => null,
        'technical_params' => [],
        'items' => [['description' => 'scaldabagno elettrico', 'quantity' => 1, 'unit_price' => null]],
    ]);

    testQuotationVoicePage()
        ->set('transcript', 'Copia il preventivo di picco pallino e fallo per Davide Cavallini e metteci solo uno scaldabagno')
        ->call('generateDraft')
        ->assertSet('hasDraft', true)
        ->assertSet('customerLegalName', 'Davide Cavallini')
        ->assertSet('sourceId', (int) $source->id)
        // ONLY the dictated scaldabagno (catalog name + price) — NOT Pinco's items.
        ->assertSet('items.0.description', 'Scaldabagno elettrico 80 lt verticale')
        ->assertSet('items.0.is_matched', true)
        ->assertSet('items.0.unit_price', 210.0)
        ->assertCount('items', 1)
        ->call('confirm')
        ->assertRedirect();

    // New quotation belongs to Davide (not Pico). The attachment rewrite is AI-driven
    // and covered end-to-end by RewriteAndAttachQuotationDocumentsTest.
    $newCustomerId = DB::table('customers')->where('legal_name', 'Davide Cavallini')->value('id');
    $created = Quotation::query()->where('customer_id', $newCustomerId)->latest('id')->firstOrFail();
    expect((int) $created->id)->not->toBe((int) $source->id);
    expect($created->items()->count())->toBe(1);
    $item = $created->items()->firstOrFail();
    expect($item->description)->toBe('Scaldabagno elettrico 80 lt verticale');
});
