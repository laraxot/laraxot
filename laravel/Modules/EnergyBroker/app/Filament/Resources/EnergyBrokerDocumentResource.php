<?php

declare(strict_types=1);

namespace Modules\EnergyBroker\Filament\Resources;

use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Section;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Modules\EnergyBroker\Actions\ConfirmEnergyBrokerDocumentImportAction;
use Modules\EnergyBroker\Actions\DeletePoorlyAnalyzedEnergyBrokerDocumentsAction;
use Modules\EnergyBroker\Actions\ReprocessEnergyBrokerDocumentPdfAction;
use Modules\EnergyBroker\Filament\Resources\EnergyBrokerDocumentResource\Pages;
use Modules\EnergyBroker\Models\EnergyBrokerDocument;
use Modules\Platform\Filament\Concerns\GatesResourceNavigation;
use Modules\Xot\Filament\Resources\XotBaseResource;

final class EnergyBrokerDocumentResource extends XotBaseResource
{
    use GatesResourceNavigation;

    protected static ?string $model = EnergyBrokerDocument::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-document-text';

    public static function getNavigationGroup(): string
    {
        return __('energy_broker.navigation');
    }

    public static function getModelLabel(): string
    {
        return __('energy_broker.document');
    }

    public static function getPluralModelLabel(): string
    {
        return __('energy_broker.documents');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEnergyBrokerDocuments::route('/'),
            'view' => Pages\ViewEnergyBrokerDocument::route('/{record}'),
            'edit' => Pages\EditEnergyBrokerDocument::route('/{record}/edit'),
        ];
    }

    public static function payloadValue(?EnergyBrokerDocument $record, string $path): string
    {
        $value = data_get($record->normalized_payload ?? [], $path);

        if (is_bool($value)) {
            return $value ? __('Yes') : __('No');
        }

        if (! is_scalar($value)) {
            return '-';
        }

        $value = trim((string) $value);

        return $value !== '' ? $value : '-';
    }

    public static function hasDownloadablePdf(EnergyBrokerDocument $record): bool
    {
        $media = $record->media;
        if ($media === null || $media->path === null) {
            return false;
        }

        return Storage::disk((string) ($media->disk ?: 'public'))->exists($media->path);
    }
}
