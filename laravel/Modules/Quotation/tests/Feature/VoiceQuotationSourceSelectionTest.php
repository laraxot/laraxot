<?php

declare(strict_types=1);

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Modules\Catalog\Models\Product;
use Modules\Document\Models\Media;
use Modules\Quotation\Models\Quotation;
use Modules\Quotation\Tests\TestCase;
use Modules\User\Models\User;

uses(TestCase::class);

use function Pest\Laravel\actingAs;

beforeEach(function (): void {
    filament()->setCurrentPanel('operator');
    actingAs(sourceSelectOperator());
});

function sourceSelectOperator(): User
{
    return quotationOperator();
}

it('offers a "none" option plus recent quotations labelled by title and customer', function (): void {
    $customerId = DB::table('customers')->insertGetId(['legal_name' => 'Pico Pallino', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()]);
    $q = Quotation::query()->create(['customer_id' => $customerId, 'status_code' => 'draft', 'title' => 'Sostituzione caldaia']);

    /** @var array<int|string, string> $options */
    $options = testQuotationVoicePage()->instance()->sourceOptions();

    // "None" is present and first.
    expect($options[0] ?? null)->toBe(__('quotations.voice.based_on_none'));
    // The quotation is listed with a readable "title — customer" label.
    expect($options[(int) $q->id] ?? null)->toBe('Sostituzione caldaia — Pico Pallino');
});

it('preselects the detected source and keeps it in the options', function (): void {
    $customerId = DB::table('customers')->insertGetId(['legal_name' => 'Rossi SRL', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()]);
    $q = Quotation::query()->create(['customer_id' => $customerId, 'status_code' => 'draft', 'title' => 'Impianto']);

    $component = testQuotationVoicePage()->set('sourceId', (int) $q->id);

    $component->assertSet('sourceId', (int) $q->id);
    expect($component->instance()->sourceOptions())->toHaveKey((int) $q->id);
});

it('does not copy any attachment when the source is set to "none"', function (): void {
    Storage::fake('local');
    Http::fake(); // any AI call would fail the assertion below
    Product::query()->create(['name' => 'Valvola', 'unit_price' => 8.0, 'is_active' => true]);

    // A source quotation WITH an attachment exists...
    $customerId = DB::table('customers')->insertGetId(['legal_name' => 'Pico Pallino', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()]);
    $source = Quotation::query()->create(['customer_id' => $customerId, 'status_code' => 'draft', 'title' => 'Caldaia']);
    $path = 'quotation-attachments/'.$source->id.'/doc.ods';
    Storage::disk('local')->put($path, 'x');
    Media::query()->create(['mediable_type' => Quotation::class, 'mediable_id' => $source->id, 'kind' => 'document', 'disk' => 'local', 'path' => $path, 'title' => 'doc']);

    // ...but the operator chose "none" (0) in the dropdown.
    testQuotationVoicePage()
        ->set('hasDraft', true)
        ->set('sourceId', 0)
        ->set('customerLegalName', 'Nuovo Cliente')
        ->set('items', [['description' => 'Valvola', 'quantity' => 1.0, 'unit_price' => 8.0, 'matched_product_id' => null, 'is_matched' => false]])
        ->call('confirm')
        ->assertRedirect();

    $new = Quotation::query()->where('customer_id', DB::table('customers')->where('legal_name', 'Nuovo Cliente')->value('id'))->firstOrFail();
    expect($new->media()->where('kind', 'document')->count())->toBe(0);
    Http::assertNothingSent();
});
