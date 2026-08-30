<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\Model;
use Modules\Platform\Models\AuditLog;
use Modules\Platform\Models\PlatformSetting;
use Modules\Platform\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(\Modules\Platform\Tests\TestCase::class);

it('has PlatformSetting model', function (): void {
    Assert::assertInstanceOf(Model::class, new PlatformSetting());
});

it('has AuditLog model', function (): void {
    Assert::assertInstanceOf(Model::class, new AuditLog());
});

it('resolves a boolean platform setting with default when missing', function (): void {
    expect(PlatformSetting::getBool('missing.key.does.not.exist', true))->toBeTrue();
    expect(PlatformSetting::getBool('missing.key.does.not.exist', false))->toBeFalse();
});

it('resolves a boolean platform setting from the stored value', function (): void {
    PlatformSetting::query()->create([
        'key' => 'inventory.enable_storage_zones',
        'value_json' => ['value' => true],
    ]);

    expect(PlatformSetting::getBool('inventory.enable_storage_zones', false))->toBeTrue();
});
