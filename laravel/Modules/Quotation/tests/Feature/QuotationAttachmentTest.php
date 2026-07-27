<?php

declare(strict_types=1);

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Modules\Quotation\Actions\AddQuotationAttachmentAction;
use Modules\Quotation\Models\Quotation;
use Modules\Quotation\Tests\TestCase;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

uses(TestCase::class);

function makeQuotationForAttachment(): Quotation
{
    $customerId = DB::table('customers')->insertGetId([
        'legal_name' => 'Idraulica Rossi S.r.l.',
        'is_active' => 1,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return Quotation::query()->create([
        'code' => 'Q-ATT-0001',
        'customer_id' => $customerId,
        'work_order_id' => null,
        'status_code' => 'draft',
        'title' => 'Preventivo con allegati',
        'tax_percent' => 22,
    ]);
}

it('attaches a stored file to a quotation as a document media record', function (): void {
    Storage::fake('local');
    $quotation = makeQuotationForAttachment();

    $path = UploadedFile::fake()
        ->createWithContent('capitolato.csv', "voce;prezzo\ntubo;10")
        ->store('quotation-attachments/'.$quotation->id, 'local');

    $media = app(AddQuotationAttachmentAction::class)->execute(
        quotation: $quotation,
        path: (string) $path,
        disk: 'local',
        uploadedByUserId: null,
        title: 'Capitolato',
    );

    expect($media)->toBeInstanceOf(Media::class);
    expect($media->collection_name)->toBe('documents');
    expect($media->disk)->toBe('local');
    expect($media->name)->toBe('Capitolato');
    expect($media->model_type)->toBe(Quotation::class);
    expect((int) $media->model_id)->toBe((int) $quotation->id);
    expect($media->size)->toBeGreaterThan(0);

    Storage::disk('local')->assertExists((string) $path);
    expect($quotation->getMedia('documents')->count())->toBe(1);
});

it('falls back to a null title when a blank title is given', function (): void {
    Storage::fake('local');
    $quotation = makeQuotationForAttachment();

    $path = UploadedFile::fake()
        ->create('scheda.odt', 4)
        ->store('quotation-attachments/'.$quotation->id, 'local');

    $media = app(AddQuotationAttachmentAction::class)->execute(
        quotation: $quotation,
        path: (string) $path,
        title: '   ',
    );

    expect($media->name)->toBe('scheda');
    expect($media->collection_name)->toBe('documents');
});
