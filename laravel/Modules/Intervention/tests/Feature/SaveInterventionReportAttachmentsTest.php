<?php

declare(strict_types=1);

uses(\Modules\Intervention\Tests\TestCase::class);

use Modules\User\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Modules\Intervention\Actions\SaveInterventionReportAttachmentsAction;
use Modules\Intervention\Models\Intervention;
use Modules\Signature\Models\Signature;

// A minimal valid 1x1 transparent PNG.
const TINY_PNG_DATA_URL = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII=';

function makeAttachmentIntervention(): Intervention
{
    $userId = User::factory()->create()->id;
    $customerId = DB::table('customers')->insertGetId([
        'legal_name' => 'Cliente Allegati',
        'is_active' => 1,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $id = DB::table('interventions')->insertGetId([
        'customer_id' => $customerId,
        'performed_by_user_id' => $userId,
        'scheduled_at' => CarbonImmutable::now(),
        'started_at' => CarbonImmutable::now(),
        'work_minutes' => 0,
        'materials_total' => 0,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return Intervention::query()->findOrFail($id);
}

beforeEach(function (): void {
    Storage::fake('public');
});

it('stores photos as media and the signature on the intervention', function (): void {
    $intervention = makeAttachmentIntervention();
    Storage::disk('public')->put('media/interventions/a.jpg', 'fake-a');
    Storage::disk('public')->put('media/interventions/b.jpg', 'fake-b');

    $result = app(SaveInterventionReportAttachmentsAction::class)->execute(
        $intervention,
        ['media/interventions/a.jpg', 'media/interventions/b.jpg'],
        TINY_PNG_DATA_URL,
        userId: (int) $intervention->performed_by_user_id,
    );

    expect($result)->toBe(['photos' => 2, 'signed' => true]);

    $photos = $intervention->media()->where('kind', 'photo')->where('meta->scope', 'voice_report')->get();
    expect($photos)->toHaveCount(2);

    $signature = Signature::query()
        ->where('signable_type', Intervention::class)
        ->where('signable_id', $intervention->id)
        ->first();
    expect($signature)->not->toBeNull();
});

it('skips empty and missing photo paths', function (): void {
    $intervention = makeAttachmentIntervention();
    Storage::disk('public')->put('media/interventions/real.jpg', 'x');

    $result = app(SaveInterventionReportAttachmentsAction::class)->execute(
        $intervention,
        ['media/interventions/real.jpg', '   ', 'media/interventions/missing.jpg'],
        null,
        userId: (int) $intervention->performed_by_user_id,
    );

    expect($result)->toBe(['photos' => 1, 'signed' => false])
        ->and($intervention->media()->where('kind', 'photo')->count())->toBe(1);
});

it('does not create a signature when the data url is blank', function (): void {
    $intervention = makeAttachmentIntervention();

    $result = app(SaveInterventionReportAttachmentsAction::class)->execute(
        $intervention,
        [],
        '   ',
        userId: (int) $intervention->performed_by_user_id,
    );

    expect($result)->toBe(['photos' => 0, 'signed' => false])
        ->and(Signature::query()->count())->toBe(0);
});
