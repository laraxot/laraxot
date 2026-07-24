<?php

declare(strict_types=1);

use Modules\Platform\Tests\TestCase;
use Modules\Platform\ValueObjects\CompanyProfile;

uses(TestCase::class);

function platformDefaultsFixture(): CompanyProfile
{
    return new CompanyProfile(
        legalName: 'Default Srl',
        vatNumber: 'IT00000000000',
        taxCode: 'DFLTAX',
        taxRegime: 'RF01',
        street: 'Via Default 1',
        postalCode: '00100',
        city: 'Roma',
        province: 'RM',
        country: 'Italia',
        email: 'default@example.test',
        phone: '000',
        logoPath: '/default/logo.png',
    );
}

it('builds from a full array using every provided value', function (): void {
    $profile = CompanyProfile::fromArray([
        'legal_name' => 'Boutique Idraulica S.r.l.',
        'vat_number' => 'IT04655810408',
        'tax_code' => '04655810408',
        'tax_regime' => 'RF19',
        'street' => 'Via Marecchiese 273',
        'postal_code' => '47922',
        'city' => 'Rimini',
        'province' => 'RN',
        'country' => 'Italia',
        'email' => 'boutique.idraulica@gmail.com',
        'phone' => '0541 000000',
        'logo_path' => '/logos/boutique.png',
    ], platformDefaultsFixture());

    expect($profile->legalName)->toBe('Boutique Idraulica S.r.l.')
        ->and($profile->vatNumber)->toBe('IT04655810408')
        ->and($profile->taxCode)->toBe('04655810408')
        ->and($profile->taxRegime)->toBe('RF19')
        ->and($profile->street)->toBe('Via Marecchiese 273')
        ->and($profile->postalCode)->toBe('47922')
        ->and($profile->city)->toBe('Rimini')
        ->and($profile->province)->toBe('RN')
        ->and($profile->country)->toBe('Italia')
        ->and($profile->email)->toBe('boutique.idraulica@gmail.com')
        ->and($profile->phone)->toBe('0541 000000')
        ->and($profile->logoPath)->toBe('/logos/boutique.png');
});

it('inherits every absent key from the defaults', function (): void {
    $profile = CompanyProfile::fromArray([], platformDefaultsFixture());

    expect($profile->legalName)->toBe('Default Srl')
        ->and($profile->vatNumber)->toBe('IT00000000000')
        ->and($profile->taxCode)->toBe('DFLTAX')
        ->and($profile->taxRegime)->toBe('RF01')
        ->and($profile->street)->toBe('Via Default 1')
        ->and($profile->postalCode)->toBe('00100')
        ->and($profile->city)->toBe('Roma')
        ->and($profile->province)->toBe('RM')
        ->and($profile->country)->toBe('Italia')
        ->and($profile->email)->toBe('default@example.test')
        ->and($profile->phone)->toBe('000')
        ->and($profile->logoPath)->toBe('/default/logo.png');
});

it('keeps explicitly-blank string fields blank instead of leaking defaults', function (): void {
    $profile = CompanyProfile::fromArray([
        'legal_name' => 'De Luca Francesco',
        'vat_number' => 'IT03185140617',
        'tax_code' => '', // present-but-empty -> stays empty, does NOT inherit the default C.F.
        // city/province/... absent -> inherited
    ], platformDefaultsFixture());

    expect($profile->legalName)->toBe('De Luca Francesco')
        ->and($profile->vatNumber)->toBe('IT03185140617')
        ->and($profile->taxCode)->toBe('')
        ->and($profile->city)->toBe('Roma'); // absent key still inherits
});

it('treats a blank logo path as no logo (falls back to default)', function (): void {
    $withDefaultLogo = CompanyProfile::fromArray(['logo_path' => ''], platformDefaultsFixture());
    expect($withDefaultLogo->logoPath)->toBe('/default/logo.png');

    $defaultsNoLogo = new CompanyProfile(
        legalName: 'X', vatNumber: 'X', taxCode: '', taxRegime: '',
        street: '', postalCode: '', city: '', province: '',
        country: '', email: '', phone: '', logoPath: null,
    );
    $noLogo = CompanyProfile::fromArray(['logo_path' => ''], $defaultsNoLogo);
    expect($noLogo->logoPath)->toBeNull();
});

it('ignores non-string values and falls back to defaults', function (): void {
    $profile = CompanyProfile::fromArray([
        'legal_name' => 123,
        'logo_path' => ['not', 'a', 'string'],
    ], platformDefaultsFixture());

    expect($profile->legalName)->toBe('Default Srl')
        ->and($profile->logoPath)->toBe('/default/logo.png');
});

it('formats a full single-line address', function (): void {
    $profile = CompanyProfile::fromArray([
        'street' => 'Via Cornara 24',
        'postal_code' => '30030',
        'city' => 'Pianiga',
        'province' => 'VE',
        'country' => 'Italia',
    ], platformDefaultsFixture());

    expect($profile->singleLineAddress())->toBe('Via Cornara 24 — 30030 Pianiga (VE) — Italia');
});

it('omits the province parentheses when province is blank', function (): void {
    $profile = new CompanyProfile(
        legalName: 'X', vatNumber: 'X', taxCode: '', taxRegime: '',
        street: 'Via Roma 1', postalCode: '20100', city: 'Milano', province: '',
        country: 'Italia', email: '', phone: '', logoPath: null,
    );

    expect($profile->singleLineAddress())->toBe('Via Roma 1 — 20100 Milano — Italia');
});

it('drops empty address segments', function (): void {
    $onlyStreet = new CompanyProfile(
        legalName: 'X', vatNumber: 'X', taxCode: '', taxRegime: '',
        street: 'Via Sola 9', postalCode: '', city: '', province: '',
        country: '', email: '', phone: '', logoPath: null,
    );
    expect($onlyStreet->singleLineAddress())->toBe('Via Sola 9');

    $empty = new CompanyProfile(
        legalName: 'X', vatNumber: 'X', taxCode: '', taxRegime: '',
        street: '', postalCode: '', city: '', province: '',
        country: '', email: '', phone: '', logoPath: null,
    );
    expect($empty->singleLineAddress())->toBe('');
});

it('exposes the supplier array shape used by the PDF templates', function (): void {
    $profile = CompanyProfile::fromArray([
        'legal_name' => 'De Luca Francesco',
        'vat_number' => 'IT03185140617',
        'tax_code' => '',
        'street' => 'Via Cornara 24',
        'postal_code' => '30030',
        'city' => 'Pianiga',
        'province' => 'VE',
        'country' => 'Italia',
        'phone' => '333',
        'email' => 'Delucaernesto9@gmail.com',
    ], platformDefaultsFixture());

    expect($profile->toSupplierArray())->toBe([
        'legal_name' => 'De Luca Francesco',
        'address' => 'Via Cornara 24 — 30030 Pianiga (VE) — Italia',
        'vat_number' => 'IT03185140617',
        'tax_code' => '',
        'phone' => '333',
        'email' => 'Delucaernesto9@gmail.com',
    ]);
});

it('serializes back to the settings array shape', function (): void {
    $profile = platformDefaultsFixture();

    expect($profile->toArray())->toBe([
        'legal_name' => 'Default Srl',
        'vat_number' => 'IT00000000000',
        'tax_code' => 'DFLTAX',
        'tax_regime' => 'RF01',
        'street' => 'Via Default 1',
        'postal_code' => '00100',
        'city' => 'Roma',
        'province' => 'RM',
        'country' => 'Italia',
        'email' => 'default@example.test',
        'phone' => '000',
        'logo_path' => '/default/logo.png',
    ]);
});
