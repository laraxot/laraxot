<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources\TimberDocumentResource\Pages;

use Modules\Xot\Filament\Resources\Pages\XotBaseCreateRecord;
use Illuminate\Support\Facades\Auth;
use Modules\Timber\Filament\Resources\TimberDocumentResource;
use Modules\Timber\Models\TimberDocument;
use Modules\Timber\Support\TimberDocumentNumberGenerator;

final class CreateTimberDocument extends XotBaseCreateRecord
{
    protected static string $resource = TimberDocumentResource::class;

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $number = $data['number'] ?? null;
        if (! is_string($number) || trim($number) === '') {
            $type = is_string($data['document_type_code'] ?? null) ? $data['document_type_code'] : 'ddt';
            $data['number'] = app(TimberDocumentNumberGenerator::class)->next($type);
        }

        $data['created_by_user_id'] = Auth::id();

        return $data;
    }

    protected function afterCreate(): void
    {
        $record = $this->record;
        if ($record instanceof TimberDocument) {
            $record->recalculateTotals();
        }
    }
}
