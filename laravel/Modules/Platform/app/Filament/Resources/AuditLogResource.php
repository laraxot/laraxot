<?php

declare(strict_types=1);

namespace Modules\Platform\Filament\Resources;

use App\Support\AccountFeatures;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Modules\Platform\Filament\Resources\AuditLogResource\Pages\ListAuditLogs;
use Modules\Platform\Filament\Resources\AuditLogResource\Pages\ViewAuditLog;
use Modules\Platform\Models\AuditLog;
use Modules\Xot\Filament\Resources\XotBaseResource;

final class AuditLogResource extends XotBaseResource
{
    protected static ?string $model = AuditLog::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-shield-check';

    protected static ?int $navigationSort = 5;

    public static function shouldRegisterNavigation(): bool
    {
        return self::isAvailableInCurrentPanel();
    }

    public static function canViewAny(): bool
    {
        return self::isAvailableInCurrentPanel() && parent::canViewAny();
    }

    public static function canView(Model $record): bool
    {
        return self::isAvailableInCurrentPanel() && parent::canView($record);
    }

    public static function getNavigationGroup(): string
    {
        return __('platform.settings');
    }

    public static function getNavigationLabel(): string
    {
        return __('platform.audit_log.navigation');
    }

    public static function getModelLabel(): string
    {
        return __('platform.audit_log.model');
    }

    public static function getPluralModelLabel(): string
    {
        return __('platform.audit_log.model_plural');
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with('actor');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAuditLogs::route('/'),
            'view' => ViewAuditLog::route('/{record}'),
        ];
    }

    private static function isAvailableInCurrentPanel(): bool
    {
        return AccountFeatures::currentPanelAllowsFeature(
            Auth::user(),
            adminFeature: 'admin.audit_log',
            operatorFeature: null,
            adminDefault: true,
            operatorDefault: false,
            fallback: false,
        );
    }
}
