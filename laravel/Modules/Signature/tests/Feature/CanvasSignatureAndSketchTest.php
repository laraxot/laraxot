<?php

declare(strict_types=1);

uses(\Modules\Signature\Tests\TestCase::class);

use Modules\User\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Modules\Intervention\Models\Intervention;
use Modules\Signature\Actions\StoreSignatureForModelAction;
use Modules\Signature\Actions\StoreSketchForModelAction;
use Modules\Signature\Models\Signature;
use Modules\Signature\Models\Sketch;

it('stores signature and sketch from canvas data urls', function (): void {
    // Arrange
    Storage::fake('public');

    $user = User::factory()->create();

    $customerId = DB::table('customers')->insertGetId([
        'legal_name' => 'Acme S.p.A.',
        'is_active' => 1,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $workOrderId = DB::table('work_orders')->insertGetId([
        'code' => 'WO-TEST-0001',
        'customer_id' => $customerId,
        'title' => 'Test work order',
        'description' => null,
        'notes' => null,
        'opened_at' => null,
        'scheduled_at' => null,
        'started_at' => null,
        'completed_at' => null,
        'closed_at' => null,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $intervention = Intervention::query()->create([
        'work_order_id' => $workOrderId,
        'performed_by_user_id' => $user->id,
        'started_at' => '2026-02-16 09:00:00',
        'ended_at' => '2026-02-16 10:00:00',
        'travel_km' => null,
        'work_minutes' => 60,
        'notes' => null,
    ]);

    // 1x1 PNG
    $dataUrl = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMB/6XvV2cAAAAASUVORK5CYII=';

    // Act
    $signature = app(StoreSignatureForModelAction::class)->execute(
        dataUrl: $dataUrl,
        signableType: Intervention::class,
        signableId: (int) $intervention->getKey(),
        userId: (int) $user->id,
        signedAt: null,
        payload: ['image' => $dataUrl],
    );

    $sketch = app(StoreSketchForModelAction::class)->execute(
        dataUrl: $dataUrl,
        sketchableType: Intervention::class,
        sketchableId: (int) $intervention->getKey(),
        userId: (int) $user->id,
        payload: ['image' => $dataUrl],
    );

    // Assert
    expect($signature)->toBeInstanceOf(Signature::class);
    expect($signature->media)->not()->toBeNull();
    expect($signature->media->kind)->toBe('signature');
    expect(Storage::disk('public')->exists($signature->media->path))->toBeTrue();

    expect($sketch)->toBeInstanceOf(Sketch::class);
    expect($sketch->media)->not()->toBeNull();
    expect($sketch->media->kind)->toBe('sketch');
    expect(Storage::disk('public')->exists($sketch->media->path))->toBeTrue();
});

