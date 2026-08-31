<?php

declare(strict_types=1);

uses(\Modules\Signage\Tests\TestCase::class);

use Illuminate\Http\Testing\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Modules\Customer\Models\Customer;
use Modules\Quotation\Models\Quotation;
use Modules\Signage\Actions\PriceSignSpecAction;
use Modules\Signage\Database\Seeders\SignEstimationRuleSeeder;
use Modules\Signage\Database\Seeders\SignPriceListSeeder;
use Modules\Signage\Datas\SignQuotation;
use Modules\Signage\Datas\SignQuotationLine;
use Modules\Signage\Filament\Pages\SignArtworkQuotationPage;
use Modules\Signage\Models\SignSpec;
use Modules\Signage\Tests\TestCase;
use Modules\User\Models\User;
use Spatie\Permission\Models\Role;

use function Pest\Laravel\actingAs;

function signageAdmin(): User
{
    $admin = User::factory()->create();
    $admin->syncRoles([Role::findOrCreate('admin')]);

    return $admin;
}

function signageArtworkUpload(): File
{
    // The real drawing shipped as a fixture: keeping the extension is what makes
    // the page accept it, and the page copies it back under this name.
    return UploadedFile::fake()->createWithContent(
        'mm-berlino-podium-signs.ai',
        (string) file_get_contents(__DIR__.'/../Fixtures/mm-berlino-podium-signs.ai'),
    );
}

function seedSignageCatalogue(): void
{
    (new SignPriceListSeeder())->run();
    (new SignEstimationRuleSeeder())->run();
}

it('reads an uploaded drawing and prices it on screen', function (): void {
    seedSignageCatalogue();
    actingAs(signageAdmin());
    filament()->setCurrentPanel('admin');

    $page = Livewire::test(SignArtworkQuotationPage::class)
        ->assertOk()
        ->set('artwork', signageArtworkUpload())
        ->call('extract')
        ->assertHasNoErrors();

    $specId = $page->get('specId');

    expect($specId)->toBeInt();

    $spec = SignSpec::query()->findOrFail($specId);

    expect($spec->components()->count())->toBeGreaterThan(0)
        ->and($spec->source_file)->toBe('mm-berlino-podium-signs.ai');

    // The LED strip is the line the operator cares about: it must be recognised
    // and matched to a price-list item.
    $page->assertSee('STRIPLED');
});

it('rejects a file that is not a drawing', function (): void {
    actingAs(signageAdmin());
    filament()->setCurrentPanel('admin');

    Livewire::test(SignArtworkQuotationPage::class)
        ->set('artwork', UploadedFile::fake()->create('listino.xlsx', 12))
        ->call('extract')
        ->assertHasErrors('artwork')
        ->assertSet('specId', null);
});

it('recomputes the quotation when the operator types a missing quantity', function (): void {
    seedSignageCatalogue();
    actingAs(signageAdmin());
    filament()->setCurrentPanel('admin');

    $page = Livewire::test(SignArtworkQuotationPage::class)
        ->set('artwork', signageArtworkUpload())
        ->call('extract')
        ->set('markupPercent', '0');

    $specId = $page->get('specId');
    $baseline = priceSignageSpec($specId, []);

    /** @var \Modules\Signage\Datas\SignQuotationLine $cutting */
    $cutting = collect($baseline->lines)->firstWhere('role', 'cutting');
    $doubledQuantity = (float) $cutting->quantity * 2;

    $doubled = priceSignageSpec($specId, ['cutting' => $doubledQuantity]);

    // Doubling the milling metres has to add exactly one more milling line worth.
    expect(round($doubled->totalCost - $baseline->totalCost, 2))
        ->toBe(round((float) $cutting->lineTotal, 2));

    // And the same input typed on the page has to land on that number.
    $page->set('quantities.cutting', (string) $doubledQuantity)
        ->assertSee(number_format($doubled->totalCost, 2, ',', '.'));
});

it('creates a draft quotation for the chosen customer', function (): void {
    seedSignageCatalogue();
    $customer = Customer::query()->create(['legal_name' => 'Insegne Demo S.r.l.', 'is_active' => true]);

    actingAs(signageAdmin());
    filament()->setCurrentPanel('admin');

    Livewire::test(SignArtworkQuotationPage::class)
        ->set('artwork', signageArtworkUpload())
        ->call('extract')
        ->set('markupPercent', '50')
        ->set('customerId', $customer->id)
        ->call('createQuotation')
        ->assertRedirect();

    $quotation = Quotation::query()->where('customer_id', $customer->id)->firstOrFail();

    expect($quotation->status_code)->toBe('draft')
        ->and($quotation->items()->count())->toBeGreaterThan(0)
        ->and((float) $quotation->total)->toBeGreaterThan(0.0)
        // Cost and project travel with the quotation, otherwise the margin and
        // the per-brand dashboard have nothing to work with.
        ->and((float) $quotation->cost_total)->toBeGreaterThan(0.0)
        ->and((float) $quotation->total)->toBeGreaterThan((float) $quotation->cost_total)
        ->and($quotation->project)->not->toBeNull();
});

it('refuses to create the quotation without a customer', function (): void {
    seedSignageCatalogue();
    actingAs(signageAdmin());
    filament()->setCurrentPanel('admin');

    Livewire::test(SignArtworkQuotationPage::class)
        ->set('artwork', signageArtworkUpload())
        ->call('extract')
        ->call('createQuotation');

    expect(Quotation::query()->count())->toBe(0);
});

it('reopens a drawing from the recent list and closes it', function (): void {
    seedSignageCatalogue();
    actingAs(signageAdmin());
    filament()->setCurrentPanel('admin');

    $page = Livewire::test(SignArtworkQuotationPage::class)
        ->set('artwork', signageArtworkUpload())
        ->call('extract');

    $specId = $page->get('specId');

    $page->call('clearSpec')
        ->assertSet('specId', null)
        ->call('openSpec', $specId)
        ->assertSet('specId', $specId)
        ->call('openSpec', 999999)
        ->assertSet('specId', $specId);
});

/**
 * @param  array<string, float>  $quantities
 */
function priceSignageSpec(int $specId, array $quantities): SignQuotation
{
    $spec = SignSpec::query()->with('components')->findOrFail($specId);

    return app(PriceSignSpecAction::class)
        ->execute($spec, ['markup_percent' => 0.0, 'quantities' => $quantities]);
}

// ── Reference drawing ────────────────────────────────────────────────────────

it('keeps the uploaded drawing and serves it as a preview', function (): void {
    Storage::fake('public');
    seedSignageCatalogue();
    $admin = signageAdmin();
    actingAs($admin);
    filament()->setCurrentPanel('admin');

    $page = Livewire::test(SignArtworkQuotationPage::class)
        ->set('artwork', signageArtworkUpload())
        ->call('extract');

    $spec = SignSpec::query()->findOrFail($page->get('specId'));

    expect($spec->artwork_path)->not->toBeNull();
    Storage::disk('public')->assertExists($spec->artwork_path);

    // The page links the preview...
    $page->assertSee(route('signage.artwork', ['spec' => $spec->id]), escape: false);

    // ...and the route serves the drawing inline as a PDF, so the browser draws it.
    $response = actingAs($admin)->get(route('signage.artwork', ['spec' => $spec->id]));

    $response->assertOk()
        ->assertHeader('Content-Type', 'application/pdf');

    expect($response->headers->get('Content-Disposition'))->toContain('inline');
});

it('does not serve a drawing to anonymous visitors', function (): void {
    Storage::fake('public');
    seedSignageCatalogue();
    actingAs(signageAdmin());
    filament()->setCurrentPanel('admin');

    $page = Livewire::test(SignArtworkQuotationPage::class)
        ->set('artwork', signageArtworkUpload())
        ->call('extract');

    $specId = $page->get('specId');

    auth()->logout();

    $this->get(route('signage.artwork', ['spec' => $specId]))->assertForbidden();
});

it('answers 404 for a spec whose file is gone', function (): void {
    Storage::fake('public');
    $admin = signageAdmin();

    $spec = SignSpec::query()->create([
        'source_file' => 'disegno-perduto.ai',
        'artwork_path' => 'signage-artwork/spec_x/disegno-perduto.ai',
    ]);

    actingAs($admin)->get(route('signage.artwork', ['spec' => $spec->id]))->assertNotFound();
});
