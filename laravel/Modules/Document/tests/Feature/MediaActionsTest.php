<?php

declare(strict_types=1);

namespace Modules\Document\Tests\Feature;

use Illuminate\Support\Facades\Storage;
use Modules\Document\Actions\AttachPhotosToInterventionAction;
use Modules\Document\Actions\CreateMediaFromStoredFileAction;
use Modules\Document\Tests\TestCase;

uses(TestCase::class);

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
