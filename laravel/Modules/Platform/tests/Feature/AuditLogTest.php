<?php

declare(strict_types=1);

use Modules\Customer\Models\Customer;
use Modules\Platform\Models\AuditLog;
use Modules\Platform\Models\PlatformSetting;
use Modules\Platform\Tests\TestCase;
use Modules\User\Models\User;

uses(TestCase::class);

it('writes an audit log entry when creating a customer', function (): void {
    $user = User::factory()->create();

    test()->actingAs($user);

    $customer = Customer::factory()->create();

    /** @var AuditLog $log */
    $log = AuditLog::query()
        ->where('action', 'created')
        ->where('subject_type', Customer::class)
        ->where('subject_id', (string) $customer->id)
        ->latest('created_at')
        ->firstOrFail();

    expect($log->actor_user_id)->toBe($user->id);
});

it('redacts sensitive keys in audit payloads', function (): void {
    $user = User::factory()->create();

    test()->actingAs($user);

    /** @var PlatformSetting $setting */
    $setting = PlatformSetting::query()->create([
        'key' => 'billing.einvoice.api_key',
        'value_json' => ['value' => 'super-secret-token'],
        'updated_by_user_id' => $user->id,
    ]);

    $setting->update([
        'value_json' => ['value' => 'even-more-secret'],
    ]);

    /** @var AuditLog $log */
    $log = AuditLog::query()
        ->where('action', 'updated')
        ->where('subject_type', PlatformSetting::class)
        ->where('subject_id', (string) $setting->id)
        ->latest('created_at')
        ->firstOrFail();

    $after = is_array($log->after) ? $log->after : [];

    // The payload may be nested; assert the secret doesn't appear anywhere.
    $json = json_encode($after, JSON_UNESCAPED_UNICODE);
    expect($json)->not->toContain('super-secret-token');
    expect($json)->not->toContain('even-more-secret');
});
