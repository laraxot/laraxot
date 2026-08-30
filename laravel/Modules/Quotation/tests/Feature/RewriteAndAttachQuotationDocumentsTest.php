<?php

declare(strict_types=1);

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Modules\Document\Models\Media;
use Modules\Quotation\Actions\RewriteAndAttachQuotationDocumentsAction;
use Modules\Quotation\Import\QuotationDocumentTextExtractor;
use Modules\Quotation\Models\Quotation;
use Modules\Quotation\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(\Modules\Quotation\Tests\TestCase::class);

use function Safe\file_get_contents;
use function Safe\json_encode;

/**
 * @param  list<array{0: string, 1: float}>  $lines
 */
function quotationWithAttachment(string $legalName, string $title, array $lines, string $fixturePath): Quotation
{
    $customerId = DB::table('customers')->insertGetId([
        'legal_name' => $legalName, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
    ]);
    $q = Quotation::query()->create(['customer_id' => $customerId, 'status_code' => 'draft', 'title' => $title]);
    $sort = 0;
    foreach ($lines as [$desc, $price]) {
        $q->items()->create(['description' => $desc, 'quantity' => 1, 'unit_price' => $price, 'line_total' => $price, 'sort_order' => $sort++]);
    }

    $path = 'quotation-attachments/'.$q->id.'/doc.ods';
    Storage::disk('local')->put($path, file_get_contents($fixturePath));
    Media::query()->create([
        'mediable_type' => Quotation::class, 'mediable_id' => $q->id,
        'kind' => 'document', 'disk' => 'local', 'path' => $path, 'title' => 'Preventivo.ods',
    ]);

    return $q;
}

it('AI-rewrites the source attachment into the new quotation document and attaches it', function (): void {
    Storage::fake('local');
    config(['services.deepseek.key' => 'test-key', 'services.deepseek.base_uri' => 'https://api.deepseek.com/v1']);

    // The AI returns a valid, rewritten content.xml (here minimal) reflecting the new quotation.
    $capturedPrompt = '';
    Http::fake([
        '*/chat/completions' => function (Request $request) use (&$capturedPrompt) {
            $data = httpClientRequestData($request);
            $messages = $data['messages'] ?? [];
            $capturedPrompt = json_encode(is_array($messages) ? $messages : []);
            // The AI returns only small find/replace edits applied to the body.
            $payload = ['replacements' => [
                ['find' => 'Ariston Clas One Wifi 24', 'replace' => 'Scaldabagno elettrico 80 lt verticale'],
            ]];

            return Http::response(['choices' => [['message' => ['content' => json_encode($payload, JSON_UNESCAPED_UNICODE)]]]], 200);
        },
    ]);

    $source = quotationWithAttachment('Pico Pallino', 'Preventivo per sostituzione caldaia', [
        ['Fornitura e posa di Ariston Clas One Wifi 24', 1158.0],
    ], base_path('tests/Fixtures/quotations/pinco-pallino.ods'));

    $target = Quotation::query()->create([
        'customer_id' => DB::table('customers')->insertGetId(['legal_name' => 'Selmec S.p.A.', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()]),
        'status_code' => 'draft', 'title' => 'Preventivo per sostituzione caldaia', 'total' => 231.0,
    ]);
    $target->items()->create(['description' => 'Scaldabagno elettrico 80 lt', 'quantity' => 1, 'unit_price' => 210.0, 'line_total' => 210.0, 'sort_order' => 0]);

    $count = app(RewriteAndAttachQuotationDocumentsAction::class)->execute($source, $target, 'Selmec S.p.A.');

    expect($count)->toBe(1);

    // The prompt carried the REAL source document content + the new quotation data.
    expect($capturedPrompt)->toContain('Ariston');            // saw the real source doc
    expect($capturedPrompt)->toContain('Selmec S.p.A.');      // new customer
    expect($capturedPrompt)->toContain('Scaldabagno elettrico 80 lt'); // new line

    // The attached document has the AI's replacements applied (the layout is kept).
    $media = $target->media()->where('kind', 'document')->first();
    Assert::assertInstanceOf(Media::class, $media);
    $text = app(QuotationDocumentTextExtractor::class)->extract(Storage::disk('local')->path((string) $media->path), 'x.ods');
    expect($text)->toContain('Scaldabagno elettrico 80 lt verticale');
    expect($text)->not->toContain('Ariston');
});

it('gives the AI todays date, the new total and instructions to recompute validity and payments', function (): void {
    Storage::fake('local');
    config(['services.deepseek.key' => 'test-key', 'services.deepseek.base_uri' => 'https://api.deepseek.com/v1']);

    $capturedPrompt = '';
    Http::fake([
        '*/chat/completions' => function (Request $request) use (&$capturedPrompt) {
            $data = httpClientRequestData($request);
            $messages = $data['messages'] ?? [];
            if (! is_array($messages)) {
                $messages = [];
            }
            $capturedPrompt = httpMessagesPrompt($messages);

            return Http::response(['choices' => [['message' => ['content' => json_encode(['replacements' => [['find' => 'Ariston', 'replace' => 'Scaldabagno']]])]]]], 200);
        },
    ]);

    $source = quotationWithAttachment('Pico Pallino', 'X', [['Ariston', 1158.0]], base_path('tests/Fixtures/quotations/pinco-pallino.ods'));
    // The new quotation inherited the commercial conditions in its notes.
    $target = Quotation::query()->create([
        'customer_id' => DB::table('customers')->insertGetId(['legal_name' => 'Selmec S.p.A.', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()]),
        'status_code' => 'draft', 'title' => 'X',
        'notes' => 'Validità 15 giorni. Pagamento in 10 rate a tasso zero.', 'total' => 231.0,
    ]);
    $target->items()->create(['description' => 'Scaldabagno', 'quantity' => 1, 'unit_price' => 210.0, 'line_total' => 210.0, 'sort_order' => 0]);

    app(RewriteAndAttachQuotationDocumentsAction::class)->execute($source, $target, 'Selmec S.p.A.');

    // The model got what it needs to modify the document intelligently:
    expect($capturedPrompt)->toContain(now()->format('d/m/Y')); // today's date
    expect($capturedPrompt)->toContain('231');                   // the new total (for installments)
    expect($capturedPrompt)->toContain('Validità 15 giorni');    // the conditions to adapt
    // ...and the explicit recompute rules.
    expect(mb_strtolower($capturedPrompt))->toContain('ricalcola');
    expect(mb_strtolower($capturedPrompt))->toContain('validita');
    expect(mb_strtolower($capturedPrompt))->toContain('rate');
});

it('rejects and attaches nothing when the AI edits produce malformed XML', function (): void {
    Storage::fake('local');
    config(['services.deepseek.key' => 'test-key', 'services.deepseek.base_uri' => 'https://api.deepseek.com/v1']);
    // A replacement that injects a stray "<" into an attribute -> breaks the XML.
    Http::fake(['*/chat/completions' => Http::response(['choices' => [['message' => ['content' => json_encode([
        'replacements' => [['find' => 'office:value="1158"', 'replace' => 'office:value="1<58"']],
    ])]]]], 200)]);

    $source = quotationWithAttachment('Pico Pallino', 'X', [['Ariston', 1158.0]], base_path('tests/Fixtures/quotations/pinco-pallino.ods'));
    $target = Quotation::query()->create([
        'customer_id' => DB::table('customers')->insertGetId(['legal_name' => 'Selmec', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()]),
        'status_code' => 'draft', 'title' => 'X',
    ]);
    $target->items()->create(['description' => 'Scaldabagno', 'quantity' => 1, 'unit_price' => 210.0, 'line_total' => 210.0, 'sort_order' => 0]);

    $count = app(RewriteAndAttachQuotationDocumentsAction::class)->execute($source, $target, 'Selmec');

    // Malformed XML is never written/attached — the caller sees a failure (0).
    expect($count)->toBe(0);
    expect($target->media()->where('kind', 'document')->count())->toBe(0);
});

it('attaches nothing when the AI cannot produce a usable rewrite', function (): void {
    Storage::fake('local');
    config(['services.deepseek.key' => 'test-key', 'services.deepseek.base_uri' => 'https://api.deepseek.com/v1']);
    // Malformed XML on every attempt -> rewriter yields null -> nothing attached.
    Http::fake(['*/chat/completions' => Http::response(['choices' => [['message' => ['content' => json_encode(['content' => '<broken'])]]]], 200)]);

    $source = quotationWithAttachment('Pico Pallino', 'X', [['Ariston', 1158.0]], base_path('tests/Fixtures/quotations/pinco-pallino.ods'));
    $target = Quotation::query()->create([
        'customer_id' => DB::table('customers')->insertGetId(['legal_name' => 'Selmec', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()]),
        'status_code' => 'draft', 'title' => 'X',
    ]);
    $target->items()->create(['description' => 'Scaldabagno', 'quantity' => 1, 'unit_price' => 210.0, 'line_total' => 210.0, 'sort_order' => 0]);

    $count = app(RewriteAndAttachQuotationDocumentsAction::class)->execute($source, $target, 'Selmec');

    expect($count)->toBe(0);
    expect($target->media()->where('kind', 'document')->count())->toBe(0);
});
