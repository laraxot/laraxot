<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Modules\Catalog\Models\Product;
use Modules\Document\Models\Media;
use Modules\Quotation\Jobs\RewriteQuotationAttachmentsJob;
use Modules\Quotation\Models\Quotation;
use Modules\Quotation\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(\Modules\Quotation\Tests\TestCase::class);

use function Pest\Laravel\actingAs;
use function Safe\file_get_contents;
use function Safe\json_encode;

function sourceQuotationWithOds(): Quotation
{
    $customerId = DB::table('customers')->insertGetId(['legal_name' => 'Pico Pallino', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()]);
    $source = Quotation::query()->create(['customer_id' => $customerId, 'status_code' => 'draft', 'title' => 'Caldaia']);
    $source->items()->create(['description' => 'Fornitura e posa di Ariston', 'quantity' => 1, 'unit_price' => 1158.0, 'line_total' => 1158.0, 'sort_order' => 0]);
    $path = 'quotation-attachments/'.$source->id.'/doc.ods';
    Storage::disk('local')->put($path, file_get_contents(base_path('tests/Fixtures/quotations/pinco-pallino.ods')));
    Media::query()->create(['mediable_type' => Quotation::class, 'mediable_id' => $source->id, 'kind' => 'document', 'disk' => 'local', 'path' => $path, 'title' => 'Preventivo']);

    return $source;
}

function confirmFromSource(Quotation $source): Quotation
{
    testQuotationVoicePage()
        ->set('hasDraft', true)
        ->set('sourceId', (int) $source->id)
        ->set('customerLegalName', 'Selmec S.p.A.')
        ->set('items', [['description' => 'Scaldabagno', 'quantity' => 1.0, 'unit_price' => 210.0, 'matched_product_id' => null, 'is_matched' => false]])
        ->call('confirm')
        ->assertRedirect();

    return Quotation::query()->where('customer_id', DB::table('customers')->where('legal_name', 'Selmec S.p.A.')->value('id'))->latest('id')->firstOrFail();
}

beforeEach(function (): void {
    filament()->setCurrentPanel('operator');
    actingAs(quotationOperator());
    Storage::fake('local');
    Product::query()->create(['name' => 'Scaldabagno', 'unit_price' => 210.0, 'is_active' => true]);
});

it('shows a "being prepared" placeholder row and queues the rewrite job', function (): void {
    Bus::fake(); // don't run the job: we want to see the placeholder as it appears
    $source = sourceQuotationWithOds();

    $new = confirmFromSource($source);

    // A placeholder attachment is visible in the table right away.
    $media = $new->media()->where('kind', 'document')->first();
    Assert::assertInstanceOf(Media::class, $media);
    Assert::assertSame(__('quotations.voice.attachment_preparing_title'), $media->title);
    $mediaId = is_int($media->id) ? $media->id : 0;
    $newId = is_int($new->id) ? $new->id : 0;
    Bus::assertDispatched(RewriteQuotationAttachmentsJob::class, fn (RewriteQuotationAttachmentsJob $job): bool => $job->targetId === $newId && $job->placeholderMediaId === $mediaId);
});

it('swaps the placeholder for the real document when the job succeeds', function (): void {
    // Queue is sync in tests: the job runs during confirm. Fake a valid AI rewrite.
    config(['services.deepseek.key' => 'test-key', 'services.deepseek.base_uri' => 'https://api.deepseek.com/v1']);
    Http::fake(['*/chat/completions' => Http::response([
        'choices' => [['message' => ['content' => json_encode(['replacements' => [['find' => 'Ariston', 'replace' => 'Scaldabagno']]], JSON_UNESCAPED_UNICODE)]]],
    ], 200)]);
    $source = sourceQuotationWithOds();

    $new = confirmFromSource($source);

    $docs = $new->media()->where('kind', 'document')->get();
    // Only the real (.ods) attachment remains; the placeholder is gone.
    expect($docs)->toHaveCount(1);
    $firstDoc = $docs->first();
    Assert::assertInstanceOf(Media::class, $firstDoc);
    Assert::assertNotSame(__('quotations.voice.attachment_preparing_title'), $firstDoc->title);
    expect(strtolower(is_string($firstDoc->path) ? $firstDoc->path : ''))->toEndWith('.ods');
});

it('recomputes the quotation notes (validity/payments) via AI in the job', function (): void {
    config(['services.deepseek.key' => 'test-key', 'services.deepseek.base_uri' => 'https://api.deepseek.com/v1']);
    Http::fake(['*/chat/completions' => Http::response([
        'choices' => [['message' => ['content' => json_encode(['notes' => 'Validità fino al 30/07/2026. Pagamento in 10 rate a tasso zero da € 23,10 (totale € 231,00).'], JSON_UNESCAPED_UNICODE)]]],
    ], 200)]);

    // A source (no attachment) whose notes get inherited onto the new quotation.
    $customerId = DB::table('customers')->insertGetId(['legal_name' => 'Pico Pallino', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()]);
    $source = Quotation::query()->create(['customer_id' => $customerId, 'status_code' => 'draft', 'title' => 'Caldaia']);

    testQuotationVoicePage()
        ->set('hasDraft', true)
        ->set('sourceId', (int) $source->id)
        ->set('customerLegalName', 'Selmec S.p.A.')
        ->set('notes', 'Validità 15 giorni. Pagamento in 10 rate a tasso zero da € 290,23 (totale € 2.902,28).')
        ->set('items', [['description' => 'Scaldabagno', 'quantity' => 1.0, 'unit_price' => 210.0, 'matched_product_id' => null, 'is_matched' => false]])
        ->call('confirm')
        ->assertRedirect();

    $new = Quotation::query()->where('customer_id', DB::table('customers')->where('legal_name', 'Selmec S.p.A.')->value('id'))->latest('id')->firstOrFail();
    // Notes were recomputed by the AI to the new total/date — no old amounts.
    expect($new->notes)->toContain('23,10');
    expect($new->notes)->not->toContain('290,23');
    expect($new->notes)->not->toContain('2.902,28');
});

it('marks the placeholder as failed when the AI cannot produce a document', function (): void {
    config(['services.deepseek.key' => 'test-key', 'services.deepseek.base_uri' => 'https://api.deepseek.com/v1']);
    // Malformed XML on every attempt -> no document produced.
    Http::fake(['*/chat/completions' => Http::response(['choices' => [['message' => ['content' => json_encode(['content' => '<broken'])]]]], 200)]);
    $source = sourceQuotationWithOds();

    $new = confirmFromSource($source);

    $docs = $new->media()->where('kind', 'document')->get();
    expect($docs)->toHaveCount(1);
    $firstDoc = $docs->first();
    Assert::assertInstanceOf(Media::class, $firstDoc);
    Assert::assertSame(__('quotations.voice.attachment_failed_title'), $firstDoc->title);
});
