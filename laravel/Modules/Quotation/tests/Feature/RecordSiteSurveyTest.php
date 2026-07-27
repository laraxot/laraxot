<?php

declare(strict_types=1);

use Illuminate\Support\Facades\DB;
use Modules\Quotation\Actions\RecordSiteSurveyAction;
use Modules\Quotation\DTO\SiteSurveyAttachment;
use Modules\Quotation\Models\SiteSurvey;
use Modules\Quotation\Tests\TestCase;

uses(TestCase::class);

function surveyForCustomer(): SiteSurvey
{
    $customerId = (int) DB::table('customers')->insertGetId([
        'legal_name' => 'Botti Termoidraulica Srl',
        'is_active' => true,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return SiteSurvey::query()->create([
        'customer_id' => $customerId,
        'title' => 'Sopralluogo caldaia',
        'status_code' => 'planned',
    ]);
}

it('records the survey with photos, videos and voice notes', function (): void {
    $survey = surveyForCustomer();

    $recorded = app(RecordSiteSurveyAction::class)->execute(
        survey: $survey,
        attachments: [
            new SiteSurveyAttachment(kind: 'photo', path: 'surveys/1/caldaia.jpg'),
            new SiteSurveyAttachment(kind: 'video', path: 'surveys/1/vano.mp4'),
            new SiteSurveyAttachment(kind: 'audio', path: 'surveys/1/nota.m4a', title: 'Nota vocale'),
        ],
        notes: 'Vano tecnico stretto, serve caldaia compatta.',
    );

    expect($recorded->status_code)->toBe('done')
        ->and($recorded->performed_at)->not->toBeNull()
        ->and($recorded->notes)->toBe('Vano tecnico stretto, serve caldaia compatta.')
        ->and($recorded->media()->count())->toBe(3)
        ->and($recorded->photos()->count())->toBe(1)
        ->and($recorded->videos()->count())->toBe(1)
        ->and($recorded->voiceNotes()->count())->toBe(1)
        ->and($recorded->voiceNotes()->firstOrFail()->name)->toBe('Nota vocale');
});

it('records a survey without attachments', function (): void {
    $survey = surveyForCustomer();

    $recorded = app(RecordSiteSurveyAction::class)->execute($survey);

    expect($recorded->status_code)->toBe('done')
        ->and($recorded->media()->count())->toBe(0);
});

it('keeps the original performed_at when already set', function (): void {
    $survey = surveyForCustomer();
    $survey->forceFill(['performed_at' => now()->subDays(3)])->save();

    $recorded = app(RecordSiteSurveyAction::class)->execute($survey);

    expect($recorded->performed_at?->toDateString())->toBe(now()->subDays(3)->toDateString());
});

it('keeps existing notes when no new notes are given', function (): void {
    $survey = surveyForCustomer();
    $survey->forceFill(['notes' => 'Nota iniziale'])->save();

    expect(app(RecordSiteSurveyAction::class)->execute($survey)->notes)->toBe('Nota iniziale');
});

it('rejects attachment kinds outside photo, video and audio', function (): void {
    expect(fn (): SiteSurveyAttachment => new SiteSurveyAttachment(kind: 'pdf', path: 'x.pdf'))
        ->toThrow(InvalidArgumentException::class);
});

it('rejects attachments without a path', function (): void {
    expect(fn (): SiteSurveyAttachment => new SiteSurveyAttachment(kind: 'photo', path: '   '))
        ->toThrow(InvalidArgumentException::class);
});

it('links the survey to the quotation it generated', function (): void {
    $survey = surveyForCustomer();

    $quotationId = (int) DB::table('quotations')->insertGetId([
        'customer_id' => $survey->customer_id,
        'status_code' => 'draft',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $survey->forceFill(['quotation_id' => $quotationId, 'status_code' => 'quoted'])->save();

    expect($survey->refresh()->quotation?->getKey())->toBe($quotationId)
        ->and($survey->status_code)->toBe('quoted');
});
