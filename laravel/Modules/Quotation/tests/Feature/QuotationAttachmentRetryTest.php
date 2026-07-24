<?php

declare(strict_types=1);

uses(\Modules\Quotation\Tests\TestCase::class);

use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Modules\Document\Models\Media;
use Modules\Quotation\Actions\QueueQuotationAttachmentRewriteAction;
use Modules\Quotation\Jobs\RewriteQuotationAttachmentsJob;
use Modules\Quotation\Models\Quotation;

function sourceWithOds(): Quotation
{
    $customerId = DB::table('customers')->insertGetId(['legal_name' => 'Pico Pallino', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()]);
    $source = Quotation::query()->create(['customer_id' => $customerId, 'status_code' => 'draft', 'title' => 'Caldaia']);
    $path = 'quotation-attachments/'.$source->id.'/doc.ods';
    Storage::disk('local')->put($path, 'x');
    Media::query()->create(['mediable_type' => Quotation::class, 'mediable_id' => $source->id, 'kind' => 'document', 'disk' => 'local', 'path' => $path, 'title' => 'Preventivo']);

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
    $placeholder = $target->media()->where('kind', 'document')->first();
    expect($placeholder?->title)->toBe(__('quotations.voice.attachment_preparing_title'));
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
    $placeholder = Media::query()->create(['mediable_type' => Quotation::class, 'mediable_id' => $target->id, 'kind' => 'document', 'disk' => 'local', 'path' => $path, 'title' => __('quotations.voice.attachment_preparing_title')]);

    $job = new RewriteQuotationAttachmentsJob((int) $source->id, (int) $target->id, '', null, (int) $placeholder->id);
    $job->failed(new RuntimeException('cURL 28 timeout'));

    expect($placeholder->fresh()->title)->toBe(__('quotations.voice.attachment_failed_title'));
});

it('replaces a stale/failed placeholder on retry instead of piling up', function (): void {
    Bus::fake();
    $source = sourceWithOds();
    $target = targetFor($source);

    // A previous run left a "not generated" placeholder.
    $stalePath = 'quotation-attachments/'.$target->id.'/old.txt';
    Storage::disk('local')->put($stalePath, 'x');
    Media::query()->create(['mediable_type' => Quotation::class, 'mediable_id' => $target->id, 'kind' => 'document', 'disk' => 'local', 'path' => $stalePath, 'title' => __('quotations.voice.attachment_failed_title')]);

    app(QueueQuotationAttachmentRewriteAction::class)->execute($target);

    // Only the fresh "being prepared" placeholder remains.
    $docs = $target->media()->where('kind', 'document')->get();
    expect($docs)->toHaveCount(1);
    expect($docs->first()->title)->toBe(__('quotations.voice.attachment_preparing_title'));
});
