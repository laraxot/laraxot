<?php

declare(strict_types=1);

namespace Modules\Compliance\Filament\Resources;

use Modules\Compliance\Filament\Resources\WorkOrderChangeOrderResource\Pages;
use Modules\Compliance\Models\WorkOrderChangeOrder;
use Modules\Platform\Filament\Concerns\GatesResourceNavigation;
use Modules\Xot\Filament\Resources\XotBaseResource;

final class WorkOrderChangeOrderResource extends XotBaseResource
{
    use GatesResourceNavigation;

    protected static ?string $model = WorkOrderChangeOrder::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-plus-circle';

    public static function getNavigationGroup(): string
    {
        return __('compliance.navigation');
    }

    public static function getNavigationLabel(): string
    {
        return __('compliance.change_orders');
    }

    public static function getModelLabel(): string
    {
        return __('compliance.change_order');
    }

    public static function getPluralModelLabel(): string
    {
        return __('compliance.change_orders');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWorkOrderChangeOrders::route('/'),
            'create' => Pages\CreateWorkOrderChangeOrder::route('/create'),
            'view' => Pages\ViewWorkOrderChangeOrder::route('/{record}'),
            'edit' => Pages\EditWorkOrderChangeOrder::route('/{record}/edit'),
        ];
    }
}
