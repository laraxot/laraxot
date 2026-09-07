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

    public function getFormSchema(): array
    {
        return [
            Section::make()->components([
                Grid::make(2)->components([
                    TextInput::make('first_name')->label(__('hr.fields.first_name'))->required()->maxLength(255),
                    TextInput::make('last_name')->label(__('hr.fields.last_name'))->required()->maxLength(255),
                ]),
                Grid::make(2)->components([
                    TextInput::make('email')->label(__('hr.fields.email'))->email()->maxLength(255),
                    TextInput::make('phone')->label(__('hr.fields.phone'))->tel()->maxLength(255),
                ]),
                Grid::make(2)->components([
                    DatePicker::make('hired_at')->label(__('hr.fields.hired_at')),
                    DatePicker::make('terminated_at')->label(__('hr.fields.terminated_at')),
                ]),
                Select::make('user_id')
                    ->label(__('hr.fields.user'))
                    ->options(fn (): array => User::query()
                        ->orderBy('name')
                        ->get()
                        ->mapWithKeys(fn (User $user): array => [$user->id => $user->name])
                        ->all())
                    ->searchable()
                    ->preload(),
                Textarea::make('notes')->label(__('hr.fields.notes'))->columnSpanFull(),
                Toggle::make('is_active')->label(__('hr.fields.is_active'))->default(true),
            ])->columnSpanFull()->columns(2),
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('last_name')
                    ->label(__('hr.fields.last_name'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('first_name')
                    ->label(__('hr.fields.first_name'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->label(__('hr.fields.email'))
                    ->toggleable()
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label(__('hr.fields.is_active'))
                    ->boolean()
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                DeleteBulkAction::make(),
                RestoreBulkAction::make(),
                ForceDeleteBulkAction::make(),
            ]);
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
