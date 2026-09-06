<?php

declare(strict_types=1);

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Modules\Catalog\Models\MetelCatalog;
use Modules\Document\Models\Media;
use Modules\Quotation\AI\QuotationModificationExtractorInterface;
use Modules\Quotation\Import\QuotationDocumentTextExtractor;
use Modules\Quotation\Models\Quotation;
use Modules\Quotation\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(\Modules\Quotation\Tests\TestCase::class);

use function Pest\Laravel\actingAs;
use function Safe\file_get_contents;
use function Safe\json_encode;

/**
 * @param  array<string, mixed>  $ops
 */
function bindSimilarExtractor(array $ops): void
{
    app()->bind(QuotationModificationExtractorInterface::class, fn (): QuotationModificationExtractorInterface => new class($ops) implements QuotationModificationExtractorInterface
    {
        /** @param array<string, mixed> $ops */
        public function __construct(private array $ops) {}

        public function extract(string $transcript, array $context): array
        {
            return $this->ops;
        }
    });
}

function makePincoSource(): Quotation
{
    $customerId = DB::table('customers')->insertGetId([
        'legal_name' => 'Pinco Pallino', 'city' => 'Rimini', 'province' => 'RN',
        'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
    ]);

    $source = Quotation::query()->create([
        'customer_id' => $customerId, 'status_code' => 'draft',
        'title' => 'Preventivo per sostituzione caldaia', 'tax_percent' => 22,
    ]);

    $lines = [
        ['Fornitura e posa di Ariston Clas One Wifi 24 in con produzione di 15,7 litri acqua calda sanitaria al minuto. Scambiatore in acciaio 316.', 1158.00],
        ['Fornitura e posa di canna fumaria a parete o tetto (prezzo a corpo)', 190.00],
        ['Fornitura e posa di punto scarico condensa in zona limitrofa', 95.00],
        ['Manodopera', 200.00],
    ];
    $sort = 0;
    foreach ($lines as [$desc, $price]) {
        $source->items()->create(['description' => $desc, 'quantity' => 1, 'unit_price' => $price, 'line_total' => $price, 'sort_order' => $sort++]);
    }

    return $source;
}

beforeEach(function (): void {
    filament()->setCurrentPanel('operator');
});

it('creates a quotation like Pinco Pallino for another customer, adding a METEL article (copy then modify)', function (): void {
    $source = makePincoSource();

    // An article available in the METEL catalog (as seeded).
    MetelCatalog::query()->create(['code' => 'demo', 'name' => 'Demo', 'source_type' => 'file', 'is_active' => true])
        ->items()->create(['metel_code' => 'DMO0100010', 'description' => 'Tubo multistrato PEX-AL-PEX 16x2 mm', 'gross_price' => 1.85, 'net_price' => 0.92]);

    // The plumber speaks loosely; the AI recognizes the structure and returns edit
    // operations: new customer + add the extra article (other lines left untouched).
    bindSimilarExtractor([
        'customer' => ['legal_name' => 'Mario Rossi', 'city' => 'Cesena'],
        'add' => [
            ['description' => 'tubo multistrato 16', 'quantity' => 5],
        ],
    ]);

    actingAs(quotationOperator(['name' => 'Idraulico Simile']));

    testQuotationVoicePageWithQuery(['source' => (string) $source->id])
        ->assertSet('hasDraft', true)
        ->assertSet('customerLegalName', 'Pinco Pallino')          // prefilled from source (copy)
        ->set('transcript', 'fammi lo stesso di Pinco Pallino ma per Mario Rossi a Cesena e mettici pure un tubo multistrato')
        ->call('generateDraft')                                    // voice modifies
        ->assertSet('customerLegalName', 'Mario Rossi')            // customer overridden
        ->assertSet('customerCity', 'Cesena')
        ->call('confirm')
        ->assertRedirect();

    // A brand-new quotation for the NEW customer.
    $marioId = DB::table('customers')->where('legal_name', 'Mario Rossi')->value('id');
    expect($marioId)->not->toBeNull();
    $new = Quotation::query()->where('customer_id', is_int($marioId) ? $marioId : 0)->firstOrFail();
    expect($new->id)->not->toBe($source->id);

    $descriptions = $new->items()->pluck('description')->all();
    // Pinco's lines are preserved VERBATIM (copy, then modify — not recreated).
    expect($descriptions)->toContain('Fornitura e posa di canna fumaria a parete o tetto (prezzo a corpo)');
    expect($descriptions)->toContain('Fornitura e posa di Ariston Clas One Wifi 24 in con produzione di 15,7 litri acqua calda sanitaria al minuto. Scambiatore in acciaio 316.');
    // Plus the new METEL article, priced from the METEL catalog.
    $tubo = $new->items()->where('description', 'LIKE', '%multistrato%')->firstOrFail();
    expect((float) $tubo->unit_price)->toBe(0.92);
    expect($tubo->product_id)->not->toBeNull();

    // Source Ariston price preserved unchanged on the new quotation.
    $ariston = $new->items()->where('description', 'LIKE', '%Ariston%')->firstOrFail();
    expect((float) $ariston->unit_price)->toBe(1158.00);
});

it('gives the new quotation a copy of the source attachment with the customer substituted', function (): void {
    Storage::fake('local');

    // The attachment is rewritten by the AI: fake its HTTP response.
    config(['services.deepseek.key' => 'test-key', 'services.deepseek.base_uri' => 'https://api.deepseek.com/v1']);
    Http::fake([
        '*/chat/completions' => Http::response([
            'choices' => [['message' => ['content' => json_encode(['replacements' => [['find' => 'Pico', 'replace' => 'Mario Rossi']]], JSON_UNESCAPED_UNICODE)]]],
        ], 200),
    ]);

    // Source quotation whose customer name matches the .ods content ("Pico Pallino").
    $customerId = DB::table('customers')->insertGetId([
        'legal_name' => 'Pico Pallino', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
    ]);
    $source = Quotation::query()->create(['customer_id' => $customerId, 'status_code' => 'draft', 'title' => 'Caldaia']);
    $source->items()->create(['description' => 'Fornitura e posa di Ariston', 'quantity' => 1, 'unit_price' => 1158.0, 'line_total' => 1158.0, 'sort_order' => 0]);

    // Attach the real .ods to the source.
    $path = 'quotation-attachments/'.$source->id.'/pinco.ods';
    Storage::disk('local')->put($path, file_get_contents(base_path('tests/Fixtures/quotations/pinco-pallino.ods')));
    Media::query()->create([
        'mediable_type' => Quotation::class, 'mediable_id' => $source->id,
        'kind' => 'document', 'disk' => 'local', 'path' => $path, 'title' => 'Preventivo',
    ]);

    // The voice modification only changes the customer.
    bindSimilarExtractor(['customer' => ['legal_name' => 'Mario Rossi']]);
    actingAs(quotationOperator(['name' => 'Idraulico Simile']));

    testQuotationVoicePageWithQuery(['source' => (string) $source->id])
        ->set('transcript', 'fai lo stesso ma per Mario Rossi')
        ->call('generateDraft')
        ->assertSet('customerLegalName', 'Mario Rossi')
        ->call('confirm')
        ->assertRedirect();

    $marioId = DB::table('customers')->where('legal_name', 'Mario Rossi')->value('id');
    expect($marioId)->not->toBeNull();
    $new = Quotation::query()->where('customer_id', is_int($marioId) ? $marioId : 0)->firstOrFail();

    // The new quotation carries an attachment like the source one, but modified.
    $media = $new->media()->where('kind', 'document')->first();
    Assert::assertInstanceOf(Media::class, $media);
    $text = app(QuotationDocumentTextExtractor::class)->extract(Storage::disk('local')->path(is_string($media->path) ? $media->path : ''), 'x.ods');
    expect($text)->toContain('Mario Rossi');
    expect($text)->not->toContain('Pico Pallino');
});
