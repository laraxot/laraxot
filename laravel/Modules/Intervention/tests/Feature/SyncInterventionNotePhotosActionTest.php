<?php

declare(strict_types=1);

use Modules\Intervention\Tests\TestCase;

uses(TestCase::class);

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Modules\Document\Actions\AttachPhotosToInterventionAction;
use Modules\Intervention\Actions\SyncInterventionNotePhotosAction;
use Modules\Intervention\Models\Intervention;
use Modules\User\Database\Factories\UserFactory;

it('syncs only intervention note photos without touching other intervention photos', function (): void {
    Storage::fake('public');

    $user = UserFactory::new()->createOne();

    $customerId = DB::table('customers')->insertGetId([
        'legal_name' => 'Cliente test',
        'is_active' => 1,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $workOrderId = DB::table('work_orders')->insertGetId([
        'code' => 'WO-NOTE-PHOTO-0001',
        'customer_id' => $customerId,
        'title' => 'Test note foto intervento',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $intervention = Intervention::query()->create([
        'work_order_id' => $workOrderId,
        'performed_by_user_id' => $user->id,
        'scheduled_at' => now(),
        'notes' => 'Nota di prova',
        'notes_html' => '<p>Nota di prova</p>',
        'work_minutes' => 30,
    ]);

    $genericPhotoPath = 'media/interventions/'.$intervention->id.'/photos/generic.jpg';
    $notePhotoPathOne = 'media/interventions/'.$intervention->id.'/note-photos/note-1.jpg';
    $notePhotoPathTwo = 'media/interventions/'.$intervention->id.'/note-photos/note-2.jpg';

    Storage::disk('public')->put($genericPhotoPath, 'generic');
    Storage::disk('public')->put($notePhotoPathOne, 'note-one');
    Storage::disk('public')->put($notePhotoPathTwo, 'note-two');

    app(AttachPhotosToInterventionAction::class)->execute(
        intervention: $intervention,
        paths: [$genericPhotoPath],
        uploadedByUserId: $user->id,
    );

    app(SyncInterventionNotePhotosAction::class)->execute(
        intervention: $intervention->refresh(),
        paths: [$notePhotoPathOne],
        uploadedByUserId: $user->id,
    );

    app(SyncInterventionNotePhotosAction::class)->execute(
        intervention: $intervention->refresh(),
        paths: [$notePhotoPathTwo],
        uploadedByUserId: $user->id,
    );

    $intervention->refresh();

    expect($intervention->media()->where('kind', 'photo')->count())->toBe(2);
    expect($intervention->media()->where('meta->scope', 'intervention_note')->pluck('path')->all())
        ->toBe([$notePhotoPathTwo]);
    expect($intervention->media()->whereNull('meta->scope')->pluck('path')->all())
        ->toBe([$genericPhotoPath]);
    expect(Storage::disk('public')->exists($notePhotoPathOne))->toBeFalse();
    expect(Storage::disk('public')->exists($genericPhotoPath))->toBeTrue();
});
