<?php

declare(strict_types=1);

namespace Modules\Document\Tests\Feature;

use Illuminate\Support\Facades\Storage;
use Modules\Document\Actions\AttachPhotosToInterventionAction;
use Modules\Document\Actions\CreateMediaFromStoredFileAction;
use Modules\Document\Tests\TestCase;
use Modules\User\Database\Factories\UserFactory;

uses(\Modules\Document\Tests\TestCase::class);

it('attaches a stored document to an intervention', function (): void {
    Storage::fake('public');

    $intervention = documentCreateIntervention();

    $path = 'media/interventions/'.$intervention->id.'/docs/test.txt';
    Storage::disk('public')->put($path, 'hello');

    $media = app(CreateMediaFromStoredFileAction::class)->execute(
        mediable: $intervention,
        kind: 'document',
        disk: 'public',
        path: $path,
        uploadedByUserId: null,
        title: 'Test doc',
        meta: [],
    );

    expect($media->kind)->toBe('document');
    expect($media->mime_type)->not->toBeNull();
    expect((int) $media->size_bytes)->toBe(5);
    expect($intervention->media()->count())->toBe(1);
});

it('keeps the uploader id intact when it is a uuid', function (): void {
    // L'id utente e' un UUID: se la colonna resta intera, chi ha caricato il file
    // sparisce (o peggio, diventa un altro utente) senza che nessuno se ne accorga.
    Storage::fake('public');

    $intervention = documentCreateIntervention();
    $uploader = UserFactory::new()->createOne(['id' => 'cccccccc-3333-4333-8333-cccccccccccc']);

    $path = 'media/interventions/'.$intervention->id.'/docs/owner.txt';
    Storage::disk('public')->put($path, 'hello');

    $media = app(CreateMediaFromStoredFileAction::class)->execute(
        mediable: $intervention,
        kind: 'document',
        disk: 'public',
        path: $path,
        uploadedByUserId: $uploader->id,
        title: 'Doc con proprietario',
        meta: [],
    );

    expect($media->refresh()->uploaded_by_user_id)->toBe($uploader->id);
});

it('attaches multiple uploaded photos to an intervention', function (): void {
    Storage::fake('public');

    $intervention = documentCreateIntervention();

    Storage::disk('public')->put('media/interventions/'.$intervention->id.'/photos/a.jpg', 'a');
    Storage::disk('public')->put('media/interventions/'.$intervention->id.'/photos/b.jpg', 'bb');

    $created = app(AttachPhotosToInterventionAction::class)->execute(
        intervention: $intervention,
        paths: [
            'media/interventions/'.$intervention->id.'/photos/a.jpg',
            'media/interventions/'.$intervention->id.'/photos/b.jpg',
        ],
        uploadedByUserId: null,
    );

    expect($created)->toHaveCount(2);

    $intervention->refresh();
    expect($intervention->media()->count())->toBe(2);
    expect($intervention->media()->pluck('kind')->unique()->all())->toBe(['photo']);
});

it('fills mime type and size for stored media files', function (): void {
    Storage::fake('public');

    $intervention = documentCreateIntervention();

    $path = 'media/test.txt';
    Storage::disk('public')->put($path, 'hello');

    $media = $intervention->media()->create([
        'kind' => 'document',
        'title' => 'Test file',
        'disk' => 'public',
        'path' => $path,
        'mime_type' => null,
        'size_bytes' => null,
        'meta' => null,
    ]);

    expect($media->mime_type)->not->toBeNull();
    expect($media->size_bytes)->toBe(5);
});
