<?php

declare(strict_types=1);

namespace Modules\EnergyBroker\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Gate;
use Modules\EnergyBroker\Actions\CompleteEnergyBrokerLargeUploadAction;
use Modules\EnergyBroker\Actions\StartEnergyBrokerLargeUploadAction;
use Modules\EnergyBroker\Actions\StoreEnergyBrokerLargeUploadChunkAction;
use Modules\EnergyBroker\Jobs\ImportEnergyBrokerLargeUploadJob;
use Modules\EnergyBroker\Models\EnergyBrokerDocument;
use Modules\EnergyBroker\Models\EnergyBrokerLargeUpload;

final class EnergyBrokerLargeUploadController extends Controller
{
    public function start(Request $request, StartEnergyBrokerLargeUploadAction $action): JsonResponse
    {
        Gate::authorize('create', EnergyBrokerDocument::class);

        $validated = $request->validate([
            'filename' => ['required', 'string', 'max:255'],
            'total_size' => ['required', 'integer', 'min:1'],
            'chunk_size' => ['required', 'integer', 'min:1', 'max:104857600'],
            'mime_type' => ['nullable', 'string', 'max:120'],
        ]);

        $user = $request->user();
        $upload = $action->execute(
            originalName: (string) $validated['filename'],
            totalSize: (int) $validated['total_size'],
            chunkSize: (int) $validated['chunk_size'],
            uploadedByUserId: $user ? (int) $user->getAuthIdentifier() : null,
            mimeType: isset($validated['mime_type']) ? (string) $validated['mime_type'] : null,
        );

        return response()->json([
            'ok' => true,
            'upload' => $this->serializeUpload($upload),
        ]);
    }

    public function chunk(Request $request, string $publicId, StoreEnergyBrokerLargeUploadChunkAction $action): JsonResponse
    {
        Gate::authorize('create', EnergyBrokerDocument::class);

        $upload = $this->findUpload($publicId);

        $validated = $request->validate([
            'chunk_index' => ['required', 'integer', 'min:0'],
            'chunk' => ['required', 'file'],
        ]);

        /** @var \Illuminate\Http\UploadedFile $chunk */
        $chunk = $request->file('chunk');
        $upload = $action->execute($upload, (int) $validated['chunk_index'], $chunk);

        return response()->json([
            'ok' => true,
            'upload' => $this->serializeUpload($upload),
        ]);
    }

    public function complete(string $publicId, CompleteEnergyBrokerLargeUploadAction $action): JsonResponse
    {
        Gate::authorize('create', EnergyBrokerDocument::class);

        $upload = $action->execute($this->findUpload($publicId));

        return response()->json([
            'ok' => true,
            'upload' => $this->serializeUpload($upload),
        ]);
    }

    public function status(string $publicId): JsonResponse
    {
        Gate::authorize('viewAny', EnergyBrokerDocument::class);

        return response()->json([
            'ok' => true,
            'upload' => $this->serializeUpload($this->findUpload($publicId)),
        ]);
    }

    public function recent(): JsonResponse
    {
        Gate::authorize('viewAny', EnergyBrokerDocument::class);

        $uploads = EnergyBrokerLargeUpload::query()
            ->with(['batch'])
            ->latest()
            ->limit(10)
            ->get()
            ->map(fn (EnergyBrokerLargeUpload $upload): array => $this->serializeUpload($upload))
            ->all();

        return response()->json([
            'ok' => true,
            'uploads' => $uploads,
        ]);
    }

    public function retry(string $publicId): JsonResponse
    {
        Gate::authorize('create', EnergyBrokerDocument::class);

        $upload = $this->findUpload($publicId);
        if (! in_array($upload->status, ['failed', 'needs_review'], true)) {
            return response()->json(['ok' => false, 'error' => 'Upload non riavviabile.'], 422);
        }

        $upload->forceFill(['status' => 'queued'])->save();
        ImportEnergyBrokerLargeUploadJob::dispatch($upload->id);

        return response()->json([
            'ok' => true,
            'upload' => $this->serializeUpload($upload->refresh()),
        ]);
    }

    private function findUpload(string $publicId): EnergyBrokerLargeUpload
    {
        /** @var EnergyBrokerLargeUpload $upload */
        $upload = EnergyBrokerLargeUpload::query()->where('public_id', $publicId)->firstOrFail();

        return $upload;
    }

    /**
     * @return array<string, mixed>
     */
    private function serializeUpload(EnergyBrokerLargeUpload $upload): array
    {
        $upload->loadMissing(['batch']);
        $batch = $upload->batch;
        $documents = $batch
            ? $batch->documents()
                ->latest()
                ->limit(20)
                ->get()
                ->map(fn (EnergyBrokerDocument $document): array => [
                    'source_file_name' => (string) $document->source_file_name,
                    'analysis_status' => (string) $document->analysis_status,
                    'document_type' => (string) $document->document_type,
                    'service_type' => (string) $document->service_type,
                    'supplier_name' => (string) $document->supplier_name,
                    'failure_reason' => (string) (data_get($document->analysis_payload, 'error') ?? ''),
                ])
                ->all()
            : [];

        return [
            'public_id' => (string) $upload->public_id,
            'original_name' => (string) $upload->original_name,
            'status' => (string) $upload->status,
            'total_size' => (int) $upload->total_size,
            'chunk_size' => (int) $upload->chunk_size,
            'total_chunks' => (int) $upload->total_chunks,
            'received_chunks' => (int) $upload->received_chunks,
            'progress' => $upload->total_chunks > 0 ? round(($upload->received_chunks / $upload->total_chunks) * 100, 2) : 0,
            'batch' => $batch ? [
                'status' => (string) $batch->status,
                'total_files' => (int) $batch->total_files,
                'processed_files' => (int) $batch->processed_files,
                'progress' => $batch->total_files > 0 ? round(($batch->processed_files / $batch->total_files) * 100, 2) : 0,
                'documents' => $documents,
                'meta' => is_array($batch->meta) ? $batch->meta : [],
            ] : null,
            'meta' => is_array($upload->meta) ? $upload->meta : [],
            'created_at' => $upload->created_at?->toDateTimeString(),
            'updated_at' => $upload->updated_at?->toDateTimeString(),
        ];
    }
}
