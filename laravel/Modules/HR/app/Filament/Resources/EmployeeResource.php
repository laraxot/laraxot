<?php

declare(strict_types=1);

namespace Modules\HR\Filament\Resources;

use App\Support\AccountFeatures;
use App\Support\Concerns\HasAccountFeatureVisibility;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Modules\HR\Filament\Resources\EmployeeResource\Pages;
use Modules\HR\Filament\Resources\EmployeeResource\RelationManagers\DocumentsRelationManager;
use Modules\HR\Filament\Resources\EmployeeResource\RelationManagers\MedicalVisitsRelationManager;
use Modules\HR\Filament\Resources\EmployeeResource\RelationManagers\TrainingsRelationManager;
use Modules\HR\Models\Employee;
use Modules\User\Models\User;
use Modules\Xot\Filament\Resources\XotBaseResource;

final class EmployeeResource extends XotBaseResource
{
    use HasAccountFeatureVisibility;

    protected static ?string $model = Employee::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-user-group';

    /** @var string */
    private const ACCOUNT_FEATURE_ADMIN = 'admin.hr';

    /** @var string */
    private const ACCOUNT_FEATURE_OPERATOR = 'operator.hr';

    public static function getNavigationGroup(): string
    {
        return __('hr.navigation');
    }

    protected static function isAvailableInCurrentPanel(): bool
    {
        return AccountFeatures::currentPanelAllowsFeature(
            Auth::user(),
            adminFeature: self::ACCOUNT_FEATURE_ADMIN,
            operatorFeature: self::ACCOUNT_FEATURE_OPERATOR,
        );
    }

    public static function getModelLabel(): string
    {
        return __('hr.employee');
    }

    public static function getPluralModelLabel(): string
    {
        return __('hr.employees');
    }

    public static function getRelations(): array
    {
        return [
            MedicalVisitsRelationManager::class,
            TrainingsRelationManager::class,
            DocumentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEmployees::route('/'),
            'create' => Pages\CreateEmployee::route('/create'),
            'view' => Pages\ViewEmployee::route('/{record}'),
            'edit' => Pages\EditEmployee::route('/{record}/edit'),
        ];
    }
}
