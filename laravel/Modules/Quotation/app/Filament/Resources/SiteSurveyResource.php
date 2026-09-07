<?php

declare(strict_types=1);

namespace Modules\Quotation\Filament\Resources;

use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Modules\Customer\Models\Customer;
use Modules\Customer\Models\CustomerSite;
use Modules\Quotation\Filament\Resources\SiteSurveyResource\Pages;
use Modules\Quotation\Filament\Resources\SiteSurveyResource\RelationManagers\AttachmentsRelationManager;
use Modules\Quotation\Models\Quotation;
use Modules\Quotation\Models\SiteSurvey;
use Modules\WorkOrder\Models\WorkOrder;
use Modules\Xot\Filament\Resources\XotBaseResource;

/**
 * Sopralluoghi: il rilievo in loco da cui nasce il preventivo.
 */
final class SiteSurveyResource extends XotBaseResource
{
    protected static ?string $model = SiteSurvey::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-map-pin';

    public static function getNavigationSort(): int
    {
        return 1;
    }

    public static function getNavigationGroup(): string
    {
        return __('site_surveys.navigation');
    }

    public static function getModelLabel(): string
    {
        return __('site_surveys.site_survey');
    }

    public static function getPluralModelLabel(): string
    {
        return __('site_surveys.site_surveys');
    }

    /**
     * @return array<string, string>
     */
    public static function statusOptions(): array
    {
        return [
            'planned' => __('site_surveys.statuses.planned'),
            'done' => __('site_surveys.statuses.done'),
            'quoted' => __('site_surveys.statuses.quoted'),
        ];
    }

    public static function statusColor(string $status): string
    {
        return match ($status) {
            'quoted' => 'success',
            'done' => 'info',
            default => 'gray',
        };
    }

    public static function getRelations(): array
    {
        return [
            AttachmentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSiteSurveys::route('/'),
            'create' => Pages\CreateSiteSurvey::route('/create'),
            'view' => Pages\ViewSiteSurvey::route('/{record}'),
            'edit' => Pages\EditSiteSurvey::route('/{record}/edit'),
        ];
    }
}
