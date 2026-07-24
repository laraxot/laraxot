<?php

declare(strict_types=1);

uses(\Modules\Quotation\Tests\TestCase::class);

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Modules\Document\Models\Media;
use Modules\Quotation\Actions\AddQuotationAttachmentAction;
use Modules\Quotation\Models\Quotation;

function makeQuotationForAttachment(): Quotation
{
    $customerId = DB::table('customers')->insertGetId([
        'legal_name' => 'Idraulica Rossi S.r.l.',
        'is_active' => 1,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    /** @var Quotation $quotation */
    $quotation = Quotation::query()->create([
        'code' => 'Q-ATT-0001',
        'customer_id' => $customerId,
        'work_order_id' => null,
        'status_code' => 'draft',
        'title' => 'Preventivo con allegati',
        'tax_percent' => 22,
    ]);

    return $quotation;
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
    expect($media->kind)->toBe('document');
    expect($media->disk)->toBe('local');
    expect($media->title)->toBe('Capitolato');
    expect($media->mediable_type)->toBe(Quotation::class);
    expect((int) $media->mediable_id)->toBe((int) $quotation->id);
    expect($media->size_bytes)->toBeGreaterThan(0);

    Storage::disk('local')->assertExists((string) $path);
    expect($quotation->media()->where('kind', 'document')->count())->toBe(1);
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

    expect($media->title)->toBeNull();
    expect($media->kind)->toBe('document');
});
