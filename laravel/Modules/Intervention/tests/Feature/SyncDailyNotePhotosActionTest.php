<?php

declare(strict_types=1);

use Modules\Intervention\Tests\TestCase;

uses(TestCase::class);

use Illuminate\Support\Facades\Storage;
use Modules\Intervention\Actions\SyncDailyNotePhotosAction;
use Modules\Intervention\Models\DailyNote;
use Modules\User\Database\Factories\UserFactory;

it('syncs daily note photos by creating and removing related media', function (): void {
    Storage::fake('public');

    $user = UserFactory::new()->createOne();

    $dailyNote = DailyNote::query()->create([
        'user_id' => $user->id,
        'note_date' => '2026-03-26',
        'body' => 'Nota di prova',
        'body_html' => '<p>Nota di prova</p>',
    ]);

    $keepPath = 'media/daily-notes/'.$user->id.'/2026-03-26/photos/keep.jpg';
    $removePath = 'media/daily-notes/'.$user->id.'/2026-03-26/photos/remove.jpg';
    $newPath = 'media/daily-notes/'.$user->id.'/2026-03-26/photos/new.jpg';

    Storage::disk('public')->put($keepPath, 'keep');
    Storage::disk('public')->put($removePath, 'remove');
    Storage::disk('public')->put($newPath, 'new');

    app(SyncDailyNotePhotosAction::class)->execute(
        dailyNote: $dailyNote,
        paths: [$keepPath, $removePath],
        uploadedByUserId: $user->id,
    );

    expect($dailyNote->media()->where('kind', 'photo')->count())->toBe(2);

    app(SyncDailyNotePhotosAction::class)->execute(
        dailyNote: $dailyNote->fresh() ?? $dailyNote,
        paths: [$keepPath, $newPath],
        uploadedByUserId: $user->id,
    );

    $dailyNote->refresh();

    expect($dailyNote->media()->where('kind', 'photo')->pluck('path')->all())
        ->toBe([$keepPath, $newPath]);
    expect(Storage::disk('public')->exists($removePath))->toBeFalse();
});
