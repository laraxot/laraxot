<?php

declare(strict_types=1);

<<<<<<< HEAD
<<<<<<< HEAD
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\User\Database\Factories\DeviceFactory;
use Modules\User\Models\Device;
use Modules\User\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

/**
 * @param  array<string, mixed>  $attributes
 */
function modelsDeviceCreate(array $attributes = []): Device
{
    return DeviceFactory::new()->createOne($attributes);
}

/**
 * @param  array<string, mixed>  $where
 */
function modelsDeviceAssertInDatabase(string $id, array $where): void
{
    $query = DB::connection('user')->table('devices')->where('id', $id);
    foreach ($where as $column => $value) {
        $query->where($column, $value);
    }

    Assert::assertTrue($query->exists());
}

test('can create device with minimal data', function (): void {
    $device = modelsDeviceCreate([
        'device' => 'iPhone',
        'platform' => 'iOS',
    ]);

    modelsDeviceAssertInDatabase((string) $device->id, [
        'device' => 'iPhone',
        'platform' => 'iOS',
    ]);
});

test('can create device with all fields', function (): void {
    $device = modelsDeviceCreate([
        'uuid' => '550e8400-e29b-41d4-a716-446655440000',
        'mobile_id' => 'mobile123',
        'languages' => ['en', 'it', 'de'],
        'device' => 'iPhone 13',
        'platform' => 'iOS',
        'browser' => 'Safari',
        'version' => '15.0',
        'is_robot' => false,
        'robot' => null,
        'is_desktop' => false,
        'is_mobile' => true,
        'is_tablet' => false,
        'is_phone' => true,
    ]);

    Assert::assertSame('550e8400-e29b-41d4-a716-446655440000', $device->uuid);
    Assert::assertSame('mobile123', $device->mobile_id);
    Assert::assertSame('iPhone 13', $device->device);
    Assert::assertTrue((bool) $device->is_mobile);
    Assert::assertTrue((bool) $device->is_phone);

    Assert::assertSame(['en', 'it', 'de'], $device->languages);
});

test('can find device by uuid', function (): void {
    $uuid = (string) Str::uuid();
    $device = modelsDeviceCreate(['uuid' => $uuid]);
    $foundDevice = Device::where('uuid', $uuid)->first();

    Assert::assertInstanceOf(Device::class, $foundDevice);
    Assert::assertSame($device->id, $foundDevice->id);
});

test('can find device by mobile id', function (): void {
    $mobileId = 'unique_mobile_'.uniqid();
    $device = modelsDeviceCreate(['mobile_id' => $mobileId]);
    $foundDevice = Device::where('mobile_id', $mobileId)->first();

    Assert::assertInstanceOf(Device::class, $foundDevice);
    Assert::assertSame($device->id, $foundDevice->id);
});

test('can find device by device type', function (): void {
    $deviceName = 'iPhone 13 Pro '.uniqid();
    $device = modelsDeviceCreate(['device' => $deviceName]);
    $foundDevice = Device::where('device', $deviceName)->first();

    Assert::assertInstanceOf(Device::class, $foundDevice);
    Assert::assertSame($device->id, $foundDevice->id);
});

test('can find device by platform', function (): void {
    $marker = uniqid();
    modelsDeviceCreate(['platform' => "iOS-{$marker}"]);
    modelsDeviceCreate(['platform' => "Android-{$marker}"]);

    $iosDevices = Device::where('platform', "iOS-{$marker}")->get();

    Assert::assertCount(1, $iosDevices);
    $first = $iosDevices->first();
    Assert::assertInstanceOf(Device::class, $first);
    Assert::assertSame("iOS-{$marker}", $first->platform);
});

test('can find device by browser', function (): void {
    $marker = uniqid();
    modelsDeviceCreate(['browser' => "Safari-{$marker}"]);
    modelsDeviceCreate(['browser' => "Chrome-{$marker}"]);

    $safariDevices = Device::where('browser', "Safari-{$marker}")->get();

    Assert::assertCount(1, $safariDevices);
    $first = $safariDevices->first();
    Assert::assertInstanceOf(Device::class, $first);
    Assert::assertSame("Safari-{$marker}", $first->browser);
});

test('can find device by version', function (): void {
    $version = '15.0.1-'.uniqid();
    $device = modelsDeviceCreate(['version' => $version]);
    $foundDevice = Device::where('version', $version)->first();

    Assert::assertInstanceOf(Device::class, $foundDevice);
    Assert::assertSame($device->id, $foundDevice->id);
});

test('can find desktop devices', function (): void {
    $marker = uniqid();
    modelsDeviceCreate(['is_desktop' => true, 'device' => "desktop-a-{$marker}"]);
    modelsDeviceCreate(['is_desktop' => false, 'device' => "mobile-{$marker}"]);
    modelsDeviceCreate(['is_desktop' => true, 'device' => "desktop-b-{$marker}"]);

    $desktopDevices = Device::where('is_desktop', true)->where('device', 'like', "%{$marker}")->get();

    Assert::assertCount(2, $desktopDevices);
    foreach ($desktopDevices as $desktopDevice) {
        Assert::assertTrue((bool) $desktopDevice->is_desktop);
    }
});

test('can find mobile devices', function (): void {
    $marker = uniqid();
    modelsDeviceCreate(['is_mobile' => true, 'device' => "mobile-a-{$marker}"]);
    modelsDeviceCreate(['is_mobile' => false, 'device' => "desktop-{$marker}"]);
    modelsDeviceCreate(['is_mobile' => true, 'device' => "mobile-b-{$marker}"]);

    $mobileDevices = Device::where('is_mobile', true)->where('device', 'like', "%{$marker}")->get();

    Assert::assertCount(2, $mobileDevices);
    foreach ($mobileDevices as $mobileDevice) {
        Assert::assertTrue((bool) $mobileDevice->is_mobile);
    }
});

test('can find tablet devices', function (): void {
    $marker = uniqid();
    modelsDeviceCreate(['is_tablet' => true, 'device' => "tablet-a-{$marker}"]);
    modelsDeviceCreate(['is_tablet' => false, 'device' => "phone-{$marker}"]);
    modelsDeviceCreate(['is_tablet' => true, 'device' => "tablet-b-{$marker}"]);

    $tabletDevices = Device::where('is_tablet', true)->where('device', 'like', "%{$marker}")->get();

    Assert::assertCount(2, $tabletDevices);
    foreach ($tabletDevices as $tabletDevice) {
        Assert::assertTrue((bool) $tabletDevice->is_tablet);
    }
});

test('can find phone devices', function (): void {
    $marker = uniqid();
    modelsDeviceCreate(['is_phone' => true, 'device' => "phone-a-{$marker}"]);
    modelsDeviceCreate(['is_phone' => false, 'device' => "tablet-{$marker}"]);
    modelsDeviceCreate(['is_phone' => true, 'device' => "phone-b-{$marker}"]);

    $phoneDevices = Device::where('is_phone', true)->where('device', 'like', "%{$marker}")->get();

    Assert::assertCount(2, $phoneDevices);
    foreach ($phoneDevices as $phoneDevice) {
        Assert::assertTrue((bool) $phoneDevice->is_phone);
    }
});

test('can find robot devices', function (): void {
    $marker = uniqid();
    modelsDeviceCreate(['is_robot' => true, 'robot' => "Googlebot-{$marker}", 'device' => "bot-a-{$marker}"]);
    modelsDeviceCreate(['is_robot' => false, 'robot' => null, 'device' => "human-{$marker}"]);
    modelsDeviceCreate(['is_robot' => true, 'robot' => "Bingbot-{$marker}", 'device' => "bot-b-{$marker}"]);

    $robotDevices = Device::where('is_robot', true)->where('device', 'like', "%{$marker}")->get();

    Assert::assertCount(2, $robotDevices);
    foreach ($robotDevices as $robotDevice) {
        Assert::assertTrue((bool) $robotDevice->is_robot);
    }
});

test('can find devices by language', function (): void {
    $marker = uniqid();
    modelsDeviceCreate(['languages' => ['en', "it-{$marker}"], 'device' => "lang-a-{$marker}"]);
    modelsDeviceCreate(['languages' => ['en', "de-{$marker}"], 'device' => "lang-b-{$marker}"]);
    modelsDeviceCreate(['languages' => ['fr', 'es'], 'device' => "lang-c-{$marker}"]);

    $englishDevices = Device::where('device', 'like', "%{$marker}%")->whereJsonContains('languages', 'en')->get();

    Assert::assertCount(2, $englishDevices);
    foreach ($englishDevices as $englishDevice) {
        Assert::assertContains('en', $englishDevice->languages ?? []);
    }
});

test('can find devices by device pattern', function (): void {
    $marker = uniqid();
    modelsDeviceCreate(['device' => "iPhone 13 {$marker}"]);
    modelsDeviceCreate(['device' => "iPhone 14 {$marker}"]);
    modelsDeviceCreate(['device' => "Samsung Galaxy {$marker}"]);

    $iphoneDevices = Device::where('device', 'like', "%iPhone%{$marker}%")->get();

    Assert::assertCount(2, $iphoneDevices);
    foreach ($iphoneDevices as $iphoneDevice) {
        Assert::assertStringContainsString('iPhone', (string) $iphoneDevice->device);
    }
});

test('can update device', function (): void {
    $device = modelsDeviceCreate(['device' => 'Old Device']);
    $device->update(['device' => 'New Device']);

    modelsDeviceAssertInDatabase((string) $device->id, ['device' => 'New Device']);
});

test('can handle null values', function (): void {
    $device = modelsDeviceCreate([
        'device' => 'Test Device',
        'platform' => 'Test Platform',
        'mobile_id' => null,
        'languages' => null,
        'browser' => null,
        'version' => null,
        'robot' => null,
    ]);

    Assert::assertNull($device->mobile_id);
    Assert::assertNull($device->browser);
    Assert::assertNull($device->version);
    Assert::assertNull($device->robot);
});

test('can find devices by multiple criteria', function (): void {
    $marker = uniqid();
    modelsDeviceCreate([
        'platform' => "iOS-{$marker}",
        'is_mobile' => true,
        'browser' => "Safari-{$marker}",
        'device' => "criteria-a-{$marker}",
    ]);

    modelsDeviceCreate([
        'platform' => "Android-{$marker}",
        'is_mobile' => true,
        'browser' => "Chrome-{$marker}",
        'device' => "criteria-b-{$marker}",
    ]);

    $devices = Device::where('device', 'like', "%{$marker}%")
        ->where('is_mobile', true)
        ->where('browser', "Safari-{$marker}")
        ->get();

    Assert::assertCount(1, $devices);
    $first = $devices->first();
    Assert::assertInstanceOf(Device::class, $first);
    Assert::assertSame("iOS-{$marker}", $first->platform);
    Assert::assertTrue((bool) $first->is_mobile);
    Assert::assertSame("Safari-{$marker}", $first->browser);
});

test('device has users relationship', function (): void {
    $device = modelsDeviceCreate();
    Assert::assertInstanceOf(BelongsToMany::class, $device->users());
});

test('device has factory', function (): void {
    $device = modelsDeviceCreate();
    Assert::assertNotNull($device->id);
    Assert::assertInstanceOf(Device::class, $device);
});

test('device has fillable attributes', function (): void {
    $fillable = (new Device)->getFillable();

    foreach ([
        'id', 'uuid', 'mobile_id', 'languages', 'device', 'platform', 'browser', 'version',
        'is_robot', 'robot', 'is_desktop', 'is_mobile', 'is_tablet', 'is_phone',
    ] as $attribute) {
        Assert::assertContains($attribute, $fillable);
    }
});

test('device has casts', function (): void {
    $casts = (new Device)->getCasts();

    Assert::assertSame('array', $casts['languages']);
    Assert::assertSame('boolean', $casts['is_robot']);
    Assert::assertSame('boolean', $casts['is_mobile']);
    Assert::assertSame('string', $casts['id']);
});
=======
=======
>>>>>>> 87273113 (.)
namespace Modules\User\Tests\Unit\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\User\Models\Device;
use Tests\TestCase;

class DeviceTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_device_with_minimal_data(): void
    {
        $device = Device::factory()->create([
            'device' => 'iPhone',
            'platform' => 'iOS',
        ]);

        $this->assertDatabaseHas('devices', [
            'id' => $device->id,
            'device' => 'iPhone',
            'platform' => 'iOS',
        ]);
    }

    public function test_can_create_device_with_all_fields(): void
    {
        $deviceData = [
            'uuid' => '550e8400-e29b-41d4-a716-446655440000',
            'mobile_id' => 'mobile123',
            'languages' => ['en', 'it', 'de'],
            'device' => 'iPhone 13',
            'platform' => 'iOS',
            'browser' => 'Safari',
            'version' => '15.0',
            'is_robot' => false,
            'robot' => null,
            'is_desktop' => false,
            'is_mobile' => true,
            'is_tablet' => false,
            'is_phone' => true,
        ];

        $device = Device::factory()->create($deviceData);

        $this->assertDatabaseHas('devices', [
            'id' => $device->id,
            'uuid' => '550e8400-e29b-41d4-a716-446655440000',
            'mobile_id' => 'mobile123',
            'device' => 'iPhone 13',
            'platform' => 'iOS',
            'browser' => 'Safari',
            'version' => '15.0',
            'is_robot' => false,
            'is_desktop' => false,
            'is_mobile' => true,
            'is_tablet' => false,
            'is_phone' => true,
        ]);

        // Verifica campi JSON
        static::assertSame(['en', 'it', 'de'], $device->languages);
    }

    public function test_device_has_soft_deletes(): void
    {
        $device = Device::factory()->create();
        $deviceId = $device->id;

        $device->delete();

        $this->assertSoftDeleted('devices', ['id' => $deviceId]);
        $this->assertDatabaseMissing('devices', ['id' => $deviceId]);
    }

    public function test_can_restore_soft_deleted_device(): void
    {
        if (!method_exists(Device::class, 'withTrashed')) {
            $this->markTestSkipped('SoftDeletes trait not present on Device model');
            return;
        }

        $device = Device::factory()->create();
        $deviceId = $device->id;

        $device->delete();
        $this->assertSoftDeleted('devices', ['id' => $deviceId]);

        /** @var Device $restoredDevice */
        $restoredDevice = Device::withTrashed()->find($deviceId);
        $restoredDevice->restore();

        $this->assertDatabaseHas('devices', ['id' => $deviceId]);
        static::assertNull($restoredDevice->deleted_at);
    }

    public function test_can_find_device_by_uuid(): void
    {
        $uuid = '550e8400-e29b-41d4-a716-446655440000';
        $device = Device::factory()->create(['uuid' => $uuid]);

        $foundDevice = Device::where('uuid', $uuid)->first();

        static::assertNotNull($foundDevice);
        static::assertSame($device->id, $foundDevice->id);
    }

    public function test_can_find_device_by_mobile_id(): void
    {
        $device = Device::factory()->create(['mobile_id' => 'unique_mobile_123']);

        $foundDevice = Device::where('mobile_id', 'unique_mobile_123')->first();

        static::assertNotNull($foundDevice);
        static::assertSame($device->id, $foundDevice->id);
    }

    public function test_can_find_device_by_device_type(): void
    {
        $device = Device::factory()->create(['device' => 'iPhone 13 Pro']);

        $foundDevice = Device::where('device', 'iPhone 13 Pro')->first();

        static::assertNotNull($foundDevice);
        static::assertSame($device->id, $foundDevice->id);
    }

    public function test_can_find_device_by_platform(): void
    {
        Device::factory()->create(['platform' => 'iOS']);
        Device::factory()->create(['platform' => 'Android']);
        Device::factory()->create(['platform' => 'Windows']);

        $iosDevices = Device::where('platform', 'iOS')->get();

        static::assertCount(1, $iosDevices);
        static::assertSame('iOS', $iosDevices->first()->platform);
    }

    public function test_can_find_device_by_browser(): void
    {
        Device::factory()->create(['browser' => 'Safari']);
        Device::factory()->create(['browser' => 'Chrome']);
        Device::factory()->create(['browser' => 'Firefox']);

        $safariDevices = Device::where('browser', 'Safari')->get();

        static::assertCount(1, $safariDevices);
        static::assertSame('Safari', $safariDevices->first()->browser);
    }

    public function test_can_find_device_by_version(): void
    {
        $device = Device::factory()->create(['version' => '15.0.1']);

        $foundDevice = Device::where('version', '15.0.1')->first();

        static::assertNotNull($foundDevice);
        static::assertSame($device->id, $foundDevice->id);
    }

    public function test_can_find_desktop_devices(): void
    {
        Device::factory()->create(['is_desktop' => true]);
        Device::factory()->create(['is_desktop' => false]);
        Device::factory()->create(['is_desktop' => true]);

        $desktopDevices = Device::where('is_desktop', true)->get();

        static::assertCount(2, $desktopDevices);
        static::assertTrue($desktopDevices->every(fn($device) => $device->is_desktop));
    }

    public function test_can_find_mobile_devices(): void
    {
        Device::factory()->create(['is_mobile' => true]);
        Device::factory()->create(['is_mobile' => false]);
        Device::factory()->create(['is_mobile' => true]);

        $mobileDevices = Device::where('is_mobile', true)->get();

        static::assertCount(2, $mobileDevices);
        static::assertTrue($mobileDevices->every(fn($device) => $device->is_mobile));
    }

    public function test_can_find_tablet_devices(): void
    {
        Device::factory()->create(['is_tablet' => true]);
        Device::factory()->create(['is_tablet' => false]);
        Device::factory()->create(['is_tablet' => true]);

        $tabletDevices = Device::where('is_tablet', true)->get();

        static::assertCount(2, $tabletDevices);
        static::assertTrue($tabletDevices->every(fn($device) => $device->is_tablet));
    }

    public function test_can_find_phone_devices(): void
    {
        Device::factory()->create(['is_phone' => true]);
        Device::factory()->create(['is_phone' => false]);
        Device::factory()->create(['is_phone' => true]);

        $phoneDevices = Device::where('is_phone', true)->get();

        static::assertCount(2, $phoneDevices);
        static::assertTrue($phoneDevices->every(fn($device) => $device->is_phone));
    }

    public function test_can_find_robot_devices(): void
    {
        Device::factory()->create(['is_robot' => true, 'robot' => 'Googlebot']);
        Device::factory()->create(['is_robot' => false, 'robot' => null]);
        Device::factory()->create(['is_robot' => true, 'robot' => 'Bingbot']);

        $robotDevices = Device::where('is_robot', true)->get();

        static::assertCount(2, $robotDevices);
        static::assertTrue($robotDevices->every(fn($device) => $device->is_robot));
    }

    public function test_can_find_devices_by_language(): void
    {
        Device::factory()->create(['languages' => ['en', 'it']]);
        Device::factory()->create(['languages' => ['en', 'de']]);
        Device::factory()->create(['languages' => ['fr', 'es']]);

        $englishDevices = Device::whereJsonContains('languages', 'en')->get();

        static::assertCount(2, $englishDevices);
        static::assertTrue($englishDevices->every(fn($device) => in_array('en', $device->languages, strict: true)));
    }

    public function test_can_find_devices_by_device_pattern(): void
    {
        Device::factory()->create(['device' => 'iPhone 13']);
        Device::factory()->create(['device' => 'iPhone 14']);
        Device::factory()->create(['device' => 'Samsung Galaxy']);

        $iphoneDevices = Device::where('device', 'like', '%iPhone%')->get();

        static::assertCount(2, $iphoneDevices);
        static::assertTrue($iphoneDevices->every(fn($device) => str_contains($device->device, 'iPhone')));
    }

    public function test_can_update_device(): void
    {
        $device = Device::factory()->create(['device' => 'Old Device']);

        $device->update(['device' => 'New Device']);

        $this->assertDatabaseHas('devices', [
            'id' => $device->id,
            'device' => 'New Device',
        ]);
    }

    public function test_can_handle_null_values(): void
    {
        $device = Device::factory()->create([
            'device' => 'Test Device',
            'platform' => 'Test Platform',
            'mobile_id' => null,
            'languages' => null,
            'browser' => null,
            'version' => null,
            'robot' => null,
        ]);

        $this->assertDatabaseHas('devices', [
            'id' => $device->id,
            'mobile_id' => null,
            'browser' => null,
            'version' => null,
            'robot' => null,
        ]);
    }

    public function test_can_find_devices_by_multiple_criteria(): void
    {
        Device::factory()->create([
            'platform' => 'iOS',
            'is_mobile' => true,
            'browser' => 'Safari',
        ]);

        Device::factory()->create([
            'platform' => 'Android',
            'is_mobile' => true,
            'browser' => 'Chrome',
        ]);

        Device::factory()->create([
            'platform' => 'Windows',
            'is_mobile' => false,
            'browser' => 'Edge',
        ]);

        $devices = Device::where('is_mobile', true)->where('browser', 'Safari')->get();

        static::assertCount(1, $devices);
        static::assertSame('iOS', $devices->first()->platform);
        static::assertTrue($devices->first()->is_mobile);
        static::assertSame('Safari', $devices->first()->browser);
    }

    public function test_device_has_users_relationship(): void
    {
        $device = Device::factory()->create();

        static::assertTrue(method_exists($device, 'users'));
    }

    public function test_device_has_factory(): void
    {
        $device = Device::factory()->create();

        static::assertNotNull($device->id);
        static::assertInstanceOf(Device::class, $device);
    }

    public function test_device_has_fillable_attributes(): void
    {
        $device = new Device();

        $expectedFillable = [
            'id',
            'uuid',
            'mobile_id',
            'languages',
            'device',
            'platform',
            'browser',
            'version',
            'is_robot',
            'robot',
            'is_desktop',
            'is_mobile',
            'is_tablet',
            'is_phone',
        ];

        static::assertSame($expectedFillable, $device->getFillable());
    }

    public function test_device_has_casts(): void
    {
        $device = new Device();

        $expectedCasts = [
            'id' => 'string',
            'uuid' => 'string',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
            'updated_by' => 'string',
            'created_by' => 'string',
            'deleted_by' => 'string',
            'languages' => 'array',
            'is_robot' => 'boolean',
            'is_desktop' => 'boolean',
            'is_mobile' => 'boolean',
            'is_tablet' => 'boolean',
            'is_phone' => 'boolean',
        ];

        static::assertSame($expectedCasts, $device->getCasts());
    }
}
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\User\Database\Factories\DeviceFactory;
use Modules\User\Models\Device;
use Modules\User\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

/**
 * @param  array<string, mixed>  $attributes
 */
function modelsDeviceCreate(array $attributes = []): Device
{
    return DeviceFactory::new()->createOne($attributes);
}

/**
 * @param  array<string, mixed>  $where
 */
function modelsDeviceAssertInDatabase(string $id, array $where): void
{
    $query = DB::connection('user')->table('devices')->where('id', $id);
    foreach ($where as $column => $value) {
        $query->where($column, $value);
    }

    Assert::assertTrue($query->exists());
}

test('can create device with minimal data', function (): void {
    $device = modelsDeviceCreate([
        'device' => 'iPhone',
        'platform' => 'iOS',
    ]);

    modelsDeviceAssertInDatabase((string) $device->id, [
        'device' => 'iPhone',
        'platform' => 'iOS',
    ]);
});

test('can create device with all fields', function (): void {
    $device = modelsDeviceCreate([
        'uuid' => '550e8400-e29b-41d4-a716-446655440000',
        'mobile_id' => 'mobile123',
        'languages' => ['en', 'it', 'de'],
        'device' => 'iPhone 13',
        'platform' => 'iOS',
        'browser' => 'Safari',
        'version' => '15.0',
        'is_robot' => false,
        'robot' => null,
        'is_desktop' => false,
        'is_mobile' => true,
        'is_tablet' => false,
        'is_phone' => true,
    ]);

    Assert::assertSame('550e8400-e29b-41d4-a716-446655440000', $device->uuid);
    Assert::assertSame('mobile123', $device->mobile_id);
    Assert::assertSame('iPhone 13', $device->device);
    Assert::assertTrue((bool) $device->is_mobile);
    Assert::assertTrue((bool) $device->is_phone);

    Assert::assertSame(['en', 'it', 'de'], $device->languages);
});

test('can find device by uuid', function (): void {
    $uuid = (string) Str::uuid();
    $device = modelsDeviceCreate(['uuid' => $uuid]);
    $foundDevice = Device::where('uuid', $uuid)->first();

    Assert::assertInstanceOf(Device::class, $foundDevice);
    Assert::assertSame($device->id, $foundDevice->id);
});

test('can find device by mobile id', function (): void {
    $mobileId = 'unique_mobile_'.uniqid();
    $device = modelsDeviceCreate(['mobile_id' => $mobileId]);
    $foundDevice = Device::where('mobile_id', $mobileId)->first();

    Assert::assertInstanceOf(Device::class, $foundDevice);
    Assert::assertSame($device->id, $foundDevice->id);
});

test('can find device by device type', function (): void {
    $deviceName = 'iPhone 13 Pro '.uniqid();
    $device = modelsDeviceCreate(['device' => $deviceName]);
    $foundDevice = Device::where('device', $deviceName)->first();

    Assert::assertInstanceOf(Device::class, $foundDevice);
    Assert::assertSame($device->id, $foundDevice->id);
});

test('can find device by platform', function (): void {
    $marker = uniqid();
    modelsDeviceCreate(['platform' => "iOS-{$marker}"]);
    modelsDeviceCreate(['platform' => "Android-{$marker}"]);

    $iosDevices = Device::where('platform', "iOS-{$marker}")->get();

    Assert::assertCount(1, $iosDevices);
    $first = $iosDevices->first();
    Assert::assertInstanceOf(Device::class, $first);
    Assert::assertSame("iOS-{$marker}", $first->platform);
});

test('can find device by browser', function (): void {
    $marker = uniqid();
    modelsDeviceCreate(['browser' => "Safari-{$marker}"]);
    modelsDeviceCreate(['browser' => "Chrome-{$marker}"]);

    $safariDevices = Device::where('browser', "Safari-{$marker}")->get();

    Assert::assertCount(1, $safariDevices);
    $first = $safariDevices->first();
    Assert::assertInstanceOf(Device::class, $first);
    Assert::assertSame("Safari-{$marker}", $first->browser);
});

test('can find device by version', function (): void {
    $version = '15.0.1-'.uniqid();
    $device = modelsDeviceCreate(['version' => $version]);
    $foundDevice = Device::where('version', $version)->first();

    Assert::assertInstanceOf(Device::class, $foundDevice);
    Assert::assertSame($device->id, $foundDevice->id);
});

test('can find desktop devices', function (): void {
    $marker = uniqid();
    modelsDeviceCreate(['is_desktop' => true, 'device' => "desktop-a-{$marker}"]);
    modelsDeviceCreate(['is_desktop' => false, 'device' => "mobile-{$marker}"]);
    modelsDeviceCreate(['is_desktop' => true, 'device' => "desktop-b-{$marker}"]);

    $desktopDevices = Device::where('is_desktop', true)->where('device', 'like', "%{$marker}")->get();

    Assert::assertCount(2, $desktopDevices);
    foreach ($desktopDevices as $desktopDevice) {
        Assert::assertTrue((bool) $desktopDevice->is_desktop);
    }
});

test('can find mobile devices', function (): void {
    $marker = uniqid();
    modelsDeviceCreate(['is_mobile' => true, 'device' => "mobile-a-{$marker}"]);
    modelsDeviceCreate(['is_mobile' => false, 'device' => "desktop-{$marker}"]);
    modelsDeviceCreate(['is_mobile' => true, 'device' => "mobile-b-{$marker}"]);

    $mobileDevices = Device::where('is_mobile', true)->where('device', 'like', "%{$marker}")->get();

    Assert::assertCount(2, $mobileDevices);
    foreach ($mobileDevices as $mobileDevice) {
        Assert::assertTrue((bool) $mobileDevice->is_mobile);
    }
});

test('can find tablet devices', function (): void {
    $marker = uniqid();
    modelsDeviceCreate(['is_tablet' => true, 'device' => "tablet-a-{$marker}"]);
    modelsDeviceCreate(['is_tablet' => false, 'device' => "phone-{$marker}"]);
    modelsDeviceCreate(['is_tablet' => true, 'device' => "tablet-b-{$marker}"]);

    $tabletDevices = Device::where('is_tablet', true)->where('device', 'like', "%{$marker}")->get();

    Assert::assertCount(2, $tabletDevices);
    foreach ($tabletDevices as $tabletDevice) {
        Assert::assertTrue((bool) $tabletDevice->is_tablet);
    }
});

test('can find phone devices', function (): void {
    $marker = uniqid();
    modelsDeviceCreate(['is_phone' => true, 'device' => "phone-a-{$marker}"]);
    modelsDeviceCreate(['is_phone' => false, 'device' => "tablet-{$marker}"]);
    modelsDeviceCreate(['is_phone' => true, 'device' => "phone-b-{$marker}"]);

    $phoneDevices = Device::where('is_phone', true)->where('device', 'like', "%{$marker}")->get();

    Assert::assertCount(2, $phoneDevices);
    foreach ($phoneDevices as $phoneDevice) {
        Assert::assertTrue((bool) $phoneDevice->is_phone);
    }
});

test('can find robot devices', function (): void {
    $marker = uniqid();
    modelsDeviceCreate(['is_robot' => true, 'robot' => "Googlebot-{$marker}", 'device' => "bot-a-{$marker}"]);
    modelsDeviceCreate(['is_robot' => false, 'robot' => null, 'device' => "human-{$marker}"]);
    modelsDeviceCreate(['is_robot' => true, 'robot' => "Bingbot-{$marker}", 'device' => "bot-b-{$marker}"]);

    $robotDevices = Device::where('is_robot', true)->where('device', 'like', "%{$marker}")->get();

    Assert::assertCount(2, $robotDevices);
    foreach ($robotDevices as $robotDevice) {
        Assert::assertTrue((bool) $robotDevice->is_robot);
    }
});

test('can find devices by language', function (): void {
    $marker = uniqid();
    modelsDeviceCreate(['languages' => ['en', "it-{$marker}"], 'device' => "lang-a-{$marker}"]);
    modelsDeviceCreate(['languages' => ['en', "de-{$marker}"], 'device' => "lang-b-{$marker}"]);
    modelsDeviceCreate(['languages' => ['fr', 'es'], 'device' => "lang-c-{$marker}"]);

    $englishDevices = Device::where('device', 'like', "%{$marker}%")->whereJsonContains('languages', 'en')->get();

    Assert::assertCount(2, $englishDevices);
    foreach ($englishDevices as $englishDevice) {
        Assert::assertContains('en', $englishDevice->languages ?? []);
    }
});

test('can find devices by device pattern', function (): void {
    $marker = uniqid();
    modelsDeviceCreate(['device' => "iPhone 13 {$marker}"]);
    modelsDeviceCreate(['device' => "iPhone 14 {$marker}"]);
    modelsDeviceCreate(['device' => "Samsung Galaxy {$marker}"]);

    $iphoneDevices = Device::where('device', 'like', "%iPhone%{$marker}%")->get();

    Assert::assertCount(2, $iphoneDevices);
    foreach ($iphoneDevices as $iphoneDevice) {
        Assert::assertStringContainsString('iPhone', (string) $iphoneDevice->device);
    }
});

test('can update device', function (): void {
    $device = modelsDeviceCreate(['device' => 'Old Device']);
    $device->update(['device' => 'New Device']);

    modelsDeviceAssertInDatabase((string) $device->id, ['device' => 'New Device']);
});

test('can handle null values', function (): void {
    $device = modelsDeviceCreate([
        'device' => 'Test Device',
        'platform' => 'Test Platform',
        'mobile_id' => null,
        'languages' => null,
        'browser' => null,
        'version' => null,
        'robot' => null,
    ]);

    Assert::assertNull($device->mobile_id);
    Assert::assertNull($device->browser);
    Assert::assertNull($device->version);
    Assert::assertNull($device->robot);
});

test('can find devices by multiple criteria', function (): void {
    $marker = uniqid();
    modelsDeviceCreate([
        'platform' => "iOS-{$marker}",
        'is_mobile' => true,
        'browser' => "Safari-{$marker}",
        'device' => "criteria-a-{$marker}",
    ]);

    modelsDeviceCreate([
        'platform' => "Android-{$marker}",
        'is_mobile' => true,
        'browser' => "Chrome-{$marker}",
        'device' => "criteria-b-{$marker}",
    ]);

    $devices = Device::where('device', 'like', "%{$marker}%")
        ->where('is_mobile', true)
        ->where('browser', "Safari-{$marker}")
        ->get();

    Assert::assertCount(1, $devices);
    $first = $devices->first();
    Assert::assertInstanceOf(Device::class, $first);
    Assert::assertSame("iOS-{$marker}", $first->platform);
    Assert::assertTrue((bool) $first->is_mobile);
    Assert::assertSame("Safari-{$marker}", $first->browser);
});

test('device has users relationship', function (): void {
    $device = modelsDeviceCreate();
    Assert::assertInstanceOf(BelongsToMany::class, $device->users());
});

test('device has factory', function (): void {
    $device = modelsDeviceCreate();
    Assert::assertNotNull($device->id);
    Assert::assertInstanceOf(Device::class, $device);
});

test('device has fillable attributes', function (): void {
    $fillable = (new Device)->getFillable();

    foreach ([
        'id', 'uuid', 'mobile_id', 'languages', 'device', 'platform', 'browser', 'version',
        'is_robot', 'robot', 'is_desktop', 'is_mobile', 'is_tablet', 'is_phone',
    ] as $attribute) {
        Assert::assertContains($attribute, $fillable);
    }
});

test('device has casts', function (): void {
    $casts = (new Device)->getCasts();

    Assert::assertSame('array', $casts['languages']);
    Assert::assertSame('boolean', $casts['is_robot']);
    Assert::assertSame('boolean', $casts['is_mobile']);
    Assert::assertSame('string', $casts['id']);
});
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
