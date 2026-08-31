<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Modules\Quotation\Actions\AddQuotationAttachmentAction;
use Modules\Quotation\Actions\QueueQuotationAttachmentRewriteAction;
use Modules\Quotation\Jobs\RewriteQuotationAttachmentsJob;
use Modules\Quotation\Models\Quotation;
use Modules\Quotation\Tests\TestCase;
use PHPUnit\Framework\Assert;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

uses(\Modules\Quotation\Tests\TestCase::class);

function sourceWithOds(): Quotation
{
    $customerId = DB::table('customers')->insertGetId(['legal_name' => 'Pico Pallino', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()]);
    $source = Quotation::query()->create(['customer_id' => $customerId, 'status_code' => 'draft', 'title' => 'Caldaia']);
    $path = 'quotation-attachments/'.$source->id.'/doc.ods';
    Storage::disk('local')->put($path, 'x');
    attachQuotationDocument($source, $path, 'local', 'Preventivo');

    return $source;
}

function targetFor(Quotation $source, bool $withSource = true): Quotation
{
    $customerId = DB::table('customers')->insertGetId(['legal_name' => 'Selmec S.p.A.', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()]);

    return Quotation::query()->create([
        'customer_id' => $customerId, 'status_code' => 'draft', 'title' => 'X',
        'source_quotation_id' => $withSource ? $source->id : null,
    ]);
}

beforeEach(fn () => Storage::fake('local'));

it('queues the rewrite and adds a placeholder when the quotation has a source', function (): void {
    Bus::fake();
    $source = sourceWithOds();
    $target = targetFor($source);

    $queued = app(QueueQuotationAttachmentRewriteAction::class)->execute($target, 'Selmec S.p.A.');

    expect($queued)->toBeTrue();
    $placeholder = $target->getMedia('documents')->first();
    Assert::assertInstanceOf(Media::class, $placeholder);
    expect($placeholder->name)->toBe(__('quotations.voice.attachment_preparing_title'));
    Bus::assertDispatched(RewriteQuotationAttachmentsJob::class, fn (RewriteQuotationAttachmentsJob $j): bool => $j->sourceId === (int) $source->id && $j->targetId === (int) $target->id);
});

it('does nothing when the quotation has no source', function (): void {
    Bus::fake();
    $source = sourceWithOds();
    $target = targetFor($source, withSource: false);

    expect(app(QueueQuotationAttachmentRewriteAction::class)->execute($target))->toBeFalse();
    Bus::assertNothingDispatched();
});

it('marks the placeholder failed when the job times out / fails (never stuck "preparing")', function (): void {
    $source = sourceWithOds();
    $target = targetFor($source);

    $path = 'quotation-attachments/'.$target->id.'/ph.txt';
    Storage::disk('local')->put($path, 'x');
    $placeholder = app(AddQuotationAttachmentAction::class)->execute(
        $target,
        $path,
        'local',
        null,
        __('quotations.voice.attachment_preparing_title'),
    );

    $job = new RewriteQuotationAttachmentsJob((int) $source->id, (int) $target->id, '', null, (int) $placeholder->id);
    $job->failed(new RuntimeException('cURL 28 timeout'));

    $failed = Media::query()->findOrFail($placeholder->id);
expect($failed)->not->toBeNull();
    expect($failed->name)->toBe(__('quotations.voice.attachment_failed_title'));
});

it('replaces a stale/failed placeholder on retry instead of piling up', function (): void {
    Bus::fake();
    $source = sourceWithOds();
    $target = targetFor($source);

    // A previous run left a "not generated" placeholder.
    $stalePath = 'quotation-attachments/'.$target->id.'/old.txt';
    Storage::disk('local')->put($stalePath, 'x');
    attachQuotationDocument($target, $stalePath, 'local', __('quotations.voice.attachment_failed_title'));

    app(QueueQuotationAttachmentRewriteAction::class)->execute($target);

    // Only the fresh "being prepared" placeholder remains.
    $docs = $target->getMedia('documents');
    expect($docs)->toHaveCount(1);
    expect($docs->first()?->name)->toBe(__('quotations.voice.attachment_preparing_title'));
});
