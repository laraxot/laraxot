<?php

declare(strict_types=1);

uses(TestCase::class);

use Carbon\CarbonImmutable;
use Modules\Intervention\Actions\UpsertDailyNoteForUserAction;
use Modules\Intervention\Models\DailyNote;
use Modules\Intervention\Tests\TestCase;
use Modules\User\Models\User;

it('upserts a daily note for a user and date', function (): void {
    $user = User::factory()->create();
    $date = CarbonImmutable::parse('2026-02-01');

    $note1 = app(UpsertDailyNoteForUserAction::class)->execute(
        userId: (int) $user->id,
        noteDate: $date,
        body: 'First note',
        bodyHtml: '<p><strong>First</strong> note</p>',
    );

    expect($note1)->toBeInstanceOf(DailyNote::class);
    expect($note1->user_id)->toBe((int) $user->id);
    expect($note1->note_date->toDateString())->toBe('2026-02-01');
    expect($note1->body)->toBe('First note');
    expect($note1->body_html)->toBe('<p><strong>First</strong> note</p>');

    $note2 = app(UpsertDailyNoteForUserAction::class)->execute(
        userId: (int) $user->id,
        noteDate: $date,
        body: 'Updated note',
        bodyHtml: '<p><span style="color: #2563eb">Updated</span> note</p>',
    );

    expect($note2->id)->toBe($note1->id);
    expect($note2->body)->toBe('Updated note');
    expect($note2->body_html)->toBe('<p><span style="color: #2563eb">Updated</span> note</p>');

    $note1->refresh();
    expect($note1->body)->toBe('Updated note');
    expect($note1->body_html)->toBe('<p><span style="color: #2563eb">Updated</span> note</p>');
});
