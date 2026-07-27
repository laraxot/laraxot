<?php

declare(strict_types=1);

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Modules\Intervention\Models\Intervention;
use Modules\Signature\Actions\StoreSignatureForModelAction;
use Modules\Signature\Actions\StoreSketchForModelAction;
use Modules\Signature\Models\Signature;
use Modules\Signature\Models\Sketch;
use Modules\Signature\Tests\TestCase;
use Modules\User\Database\Factories\UserFactory;

uses(TestCase::class);

it('stores signature and sketch from canvas data urls', function (): void {
    // Arrange
    Storage::fake('public');

    $user = UserFactory::new()->createOne();

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
    //
    // Adapted from the source gestionale_commesse test: this module's port stores
    // disk/path/mime_type directly on the Signature/Sketch models instead of going
    // through a Media FK (see docs/gestionale-commesse-port.md), so assertions on
    // `$signature->media` are replaced with direct property assertions.
    expect($signature)->toBeInstanceOf(Signature::class);
    expect($signature->disk)->toBe('public');
    expect($signature->mime_type)->toBe('image/png');
    expect(Storage::disk('public')->exists((string) $signature->path))->toBeTrue();

    expect($sketch)->toBeInstanceOf(Sketch::class);
    expect($sketch->disk)->toBe('public');
    expect($sketch->mime_type)->toBe('image/png');
    expect(Storage::disk('public')->exists((string) $sketch->path))->toBeTrue();
});
