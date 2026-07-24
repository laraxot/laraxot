<?php

declare(strict_types=1);

namespace Modules\Customer\Tests\Feature;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Modules\AiAssistant\Lookup\WebAiCompanyLookupProvider;
use Modules\Customer\Lookup\CompanyLookupProviderInterface;
use Modules\Customer\Lookup\CompositeCompanyLookupProvider;
use Modules\Customer\Lookup\OpenApiCompanyLookupProvider;
use Modules\Customer\Lookup\OpenCorporatesCompanyLookupProvider;
use Modules\Customer\Lookup\ViesCompanyLookupProvider;
use Modules\Customer\Tests\TestCase;

/*
 * Migrato da gestionale_commesse/tests/Feature/Customers/CompanyLookupSourcesTest.php.
 * Namespace adattato: Modules\Customers\Application\Lookup -> Modules\Customer\Lookup.
 * WebAiCompanyLookupProvider vive ora in Modules\AiAssistant\Lookup (era Application\Lookup).
 */
uses(TestCase::class);

it('resolves a company from its VAT via VIES (free, no captcha)', function (): void {
    Http::fake(['ec.europa.eu/*' => Http::response([
        'isValid' => true,
        'name' => '32BIT S.R.L.',
        'address' => "VIA BRENNERO 220\n38121 TRENTO TN",
    ], 200)]);

    $results = app(ViesCompanyLookupProvider::class)->search('la ditta con partita iva IT02186950224');

    expect($results)->toHaveCount(1);
    expect($results[0]['legal_name'])->toBe('32BIT S.R.L.');
    expect($results[0]['vat_number'])->toBe('IT02186950224');
    expect($results[0]['address_line1'])->toBe('VIA BRENNERO 220');
    expect($results[0]['postal_code'])->toBe('38121');
    expect($results[0]['city'])->toBe('TRENTO');
    expect($results[0]['province'])->toBe('TN');
});

it('VIES returns nothing (and makes no call) when the query has no VAT', function (): void {
    Http::fake();

    expect(app(ViesCompanyLookupProvider::class)->search('Tognana Porcellane'))->toBe([]);
    Http::assertNothingSent();
});

it('VIES returns nothing for an invalid VAT', function (): void {
    Http::fake(['ec.europa.eu/*' => Http::response(['isValid' => false], 200)]);

    expect(app(ViesCompanyLookupProvider::class)->search('IT00000000000'))->toBe([]);
});

it('searches by NAME via OpenAPI Italia (Registro Imprese) when a token is set', function (): void {
    config(['company_lookup.openapi_token' => 'tok_123']);
    Http::fake(['company.openapi.com/*' => Http::response([
        'data' => [[
            'companyName' => 'BIELLEMEC S.R.L.',
            'vatCode' => 'IT02501230028',
            'taxCode' => '02501230028',
            'address' => ['registeredOffice' => ['streetName' => 'Via dell\'Industria', 'streetNumber' => '12', 'zipCode' => '13900', 'town' => 'BIELLA', 'province' => 'BI']],
        ]],
    ], 200)]);

    $results = app(OpenApiCompanyLookupProvider::class)->search('Biellemec');

    expect($results)->toHaveCount(1);
    expect($results[0]['legal_name'])->toBe('BIELLEMEC S.R.L.');
    expect($results[0]['vat_number'])->toBe('IT02501230028');
    expect($results[0]['address_line1'])->toBe('Via dell\'Industria 12');
    expect($results[0]['city'])->toBe('BIELLA');
    expect($results[0]['postal_code'])->toBe('13900');
    expect($results[0]['province'])->toBe('BI');
});

it('resolves a company by VAT via OpenAPI /IT-advanced (phone, email/PEC included)', function (): void {
    config(['company_lookup.openapi_token' => 'tok_123']);
    Http::fake(['company.openapi.com/IT-advanced/*' => Http::response([
        'data' => [[
            'companyName' => 'TOGNANA PORCELLANE S.P.A.',
            'vatCode' => 'IT00154900269',
            'taxCode' => '00154900269',
            'address' => ['registeredOffice' => ['streetName' => 'Viale Venezia', 'streetNumber' => '89', 'zipCode' => '31100', 'town' => 'TREVISO', 'province' => 'TV']],
            'contacts' => ['telephoneNumber' => '+39 0422 4291'],
            'pec' => 'tognana@pec.it',
        ]],
    ], 200)]);

    $results = app(OpenApiCompanyLookupProvider::class)->search('IT00154900269');

    expect($results)->toHaveCount(1);
    expect($results[0]['legal_name'])->toBe('TOGNANA PORCELLANE S.P.A.');
    expect($results[0]['vat_number'])->toBe('IT00154900269');
    expect($results[0]['address_line1'])->toBe('Viale Venezia 89');
    expect($results[0]['city'])->toBe('TREVISO');
    expect($results[0]['province'])->toBe('TV');
    expect($results[0]['phone'])->toBe('+39 0422 4291');
    expect($results[0]['email'])->toBe('tognana@pec.it');

    // The VAT endpoint (not the name search) was used.
    Http::assertSent(fn (Request $request): bool => str_contains($request->url(), '/IT-advanced/00154900269'));
});

it('OpenAPI Italia is disabled (no call) without a token', function (): void {
    config(['company_lookup.openapi_token' => '']);
    Http::fake();

    expect(app(OpenApiCompanyLookupProvider::class)->search('Biellemec'))->toBe([]);
    Http::assertNothingSent();
});

it('searches a company by NAME via OpenCorporates', function (): void {
    Http::fake(['api.opencorporates.com/*' => Http::response([
        'results' => ['companies' => [
            ['company' => ['name' => 'TOGNANA PORCELLANE S.P.A.', 'company_number' => 'TV-123', 'registered_address_in_full' => 'Viale Venezia 89, Treviso']],
        ]],
    ], 200)]);

    $results = app(OpenCorporatesCompanyLookupProvider::class)->search('Tognana');

    expect($results)->toHaveCount(1);
    expect($results[0]['legal_name'])->toBe('TOGNANA PORCELLANE S.P.A.');
    expect($results[0]['address_line1'])->toBe('Viale Venezia 89, Treviso');
});

it('OpenCorporates degrades to [] on rate limit / error', function (): void {
    Http::fake(['api.opencorporates.com/*' => Http::response('rate limited', 429)]);

    expect(app(OpenCorporatesCompanyLookupProvider::class)->search('Tognana'))->toBe([]);
});

it('composite returns the first non-empty source (VIES before OpenCorporates)', function (): void {
    Http::fake([
        'ec.europa.eu/*' => Http::response(['isValid' => true, 'name' => 'DA VIES SPA', 'address' => 'VIA X 1 20100 MILANO MI'], 200),
        'api.opencorporates.com/*' => Http::response(['results' => ['companies' => [['company' => ['name' => 'DA OPENCORP']]]]], 200),
    ]);

    $composite = new CompositeCompanyLookupProvider([
        app(ViesCompanyLookupProvider::class),
        app(OpenCorporatesCompanyLookupProvider::class),
    ]);

    $results = $composite->search('IT02186950224');

    expect($results)->toHaveCount(1);
    expect($results[0]['legal_name'])->toBe('DA VIES SPA'); // VIES wins
});

it('uses Brave Search (no captcha) instead of DuckDuckGo when a key is set', function (): void {
    config([
        'services.deepseek.key' => 'test-key', 'services.deepseek.base_uri' => 'https://api.deepseek.com/v1',
        'company_lookup.brave_key' => 'brave-key-123',
    ]);
    Http::fake([
        'api.search.brave.com/*' => Http::response(['web' => ['results' => [
            ['title' => 'Tognana Porcellane S.p.A.', 'url' => 'https://tognana.com', 'description' => 'P.IVA IT00154900269 - Selvana di Treviso (TV)'],
        ]]], 200),
        '*/chat/completions' => Http::response(['choices' => [['message' => ['content' => \Safe\json_encode(['candidates' => [
            ['legal_name' => 'Tognana Porcellane S.p.A.', 'vat_number' => 'IT00154900269', 'city' => 'Selvana di Treviso'],
        ]])]]]], 200),
    ]);

    $results = app(WebAiCompanyLookupProvider::class)->search('Tognana');

    expect($results)->toHaveCount(1);
    expect($results[0]['legal_name'])->toBe('Tognana Porcellane S.p.A.');
    // Brave used (with its auth header), DuckDuckGo not touched.
    Http::assertSent(fn (Request $request): bool => str_contains($request->url(), 'api.search.brave.com') && $request->hasHeader('X-Subscription-Token', 'brave-key-123'));
    Http::assertNotSent(fn (Request $request): bool => str_contains($request->url(), 'duckduckgo'));
});

it('web+AI skips the LLM when DuckDuckGo returns an antibot/captcha page', function (): void {
    config(['services.deepseek.key' => 'test-key', 'services.deepseek.base_uri' => 'https://api.deepseek.com/v1']);
    Http::fake([
        'html.duckduckgo.com/*' => Http::response('<html><body>Unfortunately, bots use DuckDuckGo too. Please complete the following challenge... Select all squares containing a duck</body></html>', 200),
        '*/chat/completions' => Http::response(['choices' => [['message' => ['content' => '{"candidates":[]}']]]], 200),
    ]);

    $results = app(WebAiCompanyLookupProvider::class)->search('Idraulica Verdi');

    expect($results)->toBe([]);
    // No wasted LLM call on the captcha page.
    Http::assertNotSent(fn (Request $request): bool => str_contains($request->url(), 'chat/completions'));
});

it('the bound provider is the composite (VIES + OpenCorporates + web)', function (): void {
    expect(app(CompanyLookupProviderInterface::class))->toBeInstanceOf(CompositeCompanyLookupProvider::class);
});
