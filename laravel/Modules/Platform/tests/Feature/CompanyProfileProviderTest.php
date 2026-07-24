<?php

declare(strict_types=1);

use Modules\Platform\Company\CompanyProfileProvider;
use Modules\Platform\Models\PlatformSetting;
use Modules\Platform\Tests\TestCase;

uses(TestCase::class);

/**
 * @param  array<string, mixed>  $data
 */
function storePlatformCompanyProfile(array $data): void
{
    PlatformSetting::query()->updateOrCreate(
        ['key' => CompanyProfileProvider::SETTINGS_KEY],
        ['value_json' => $data],
    );
}

it('returns the installation defaults when no profile is stored', function (): void {
    $profile = app(CompanyProfileProvider::class)->current();

    expect($profile->legalName)->toBe('Davide Cavallini')
        ->and($profile->vatNumber)->toBe('IT04914550274')
        ->and($profile->taxCode)->toBe('CVLDVD87M23L736P')
        ->and($profile->logoPath)->toBeNull();
});

it('exposes the same defaults statically', function (): void {
    $defaults = CompanyProfileProvider::defaults();

    expect($defaults->legalName)->toBe('Davide Cavallini')
        ->and($defaults->city)->toBe('Noale')
        ->and($defaults->province)->toBe('VE');
});

it('overrides the defaults with the stored profile', function (): void {
    storePlatformCompanyProfile([
        'legal_name' => 'Boutique Idraulica S.r.l.',
        'vat_number' => 'IT04655810408',
        'city' => 'Rimini',
        'province' => 'RN',
    ]);

    $profile = app(CompanyProfileProvider::class)->current();

    expect($profile->legalName)->toBe('Boutique Idraulica S.r.l.')
        ->and($profile->vatNumber)->toBe('IT04655810408')
        ->and($profile->city)->toBe('Rimini')
        ->and($profile->province)->toBe('RN')
        // a field absent from the stored profile still falls back to the default
        ->and($profile->taxCode)->toBe('CVLDVD87M23L736P');
});

it('returns null logo when no logo path is set', function (): void {
    expect(app(CompanyProfileProvider::class)->logoDataUri())->toBeNull();
});

it('returns null logo when the file does not exist', function (): void {
    storePlatformCompanyProfile(['logo_path' => '/does/not/exist/logo.png']);

    expect(app(CompanyProfileProvider::class)->logoDataUri())->toBeNull();
});

it('encodes an existing logo file as a base64 data URI', function (): void {
    // 1x1 transparent PNG
    $png = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+M8AAAMBAQDJ/pLvAAAAAElFTkSuQmCC');
    $path = tempnam(sys_get_temp_dir(), 'logo').'.png';
    file_put_contents($path, $png);

    storePlatformCompanyProfile(['logo_path' => $path]);

    $uri = app(CompanyProfileProvider::class)->logoDataUri();

    expect($uri)->toStartWith('data:image/png;base64,')
        ->and($uri)->toBe('data:image/png;base64,'.base64_encode($png));

    @unlink($path);
});

it('detects the mime type from the extension', function (string $extension, string $expectedMime): void {
    $path = tempnam(sys_get_temp_dir(), 'logo').'.'.$extension;
    file_put_contents($path, 'binary-content');

    storePlatformCompanyProfile(['logo_path' => $path]);

    expect(app(CompanyProfileProvider::class)->logoDataUri())
        ->toStartWith('data:'.$expectedMime.';base64,');

    @unlink($path);
})->with([
    'jpg' => ['jpg', 'image/jpeg'],
    'jpeg' => ['jpeg', 'image/jpeg'],
    'gif' => ['gif', 'image/gif'],
    'svg' => ['svg', 'image/svg+xml'],
    'unknown' => ['bin', 'application/octet-stream'],
]);
