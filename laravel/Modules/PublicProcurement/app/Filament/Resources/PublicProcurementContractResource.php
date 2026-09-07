<?php

declare(strict_types=1);

namespace Modules\PublicProcurement\Filament\Resources;

use Carbon\CarbonImmutable;
use Filament\Actions\Action;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Modules\Customer\Models\Customer;
use Modules\Platform\Filament\Concerns\GatesResourceNavigation;
use Modules\PublicProcurement\Actions\GenerateProgressReportFromMeasurementsAction;
use Modules\PublicProcurement\Filament\Resources\PublicProcurementContractResource\Pages\CreatePublicProcurementContract;
use Modules\PublicProcurement\Filament\Resources\PublicProcurementContractResource\Pages\EditPublicProcurementContract;
use Modules\PublicProcurement\Filament\Resources\PublicProcurementContractResource\Pages\ListPublicProcurementContracts;
use Modules\PublicProcurement\Filament\Resources\PublicProcurementContractResource\Pages\ViewPublicProcurementContract;
use Modules\PublicProcurement\Filament\Resources\PublicProcurementContractResource\RelationManagers\DeadlinesRelationManager;
use Modules\PublicProcurement\Filament\Resources\PublicProcurementContractResource\RelationManagers\DocumentRequirementsRelationManager;
use Modules\PublicProcurement\Filament\Resources\PublicProcurementContractResource\RelationManagers\GuaranteesRelationManager;
use Modules\PublicProcurement\Filament\Resources\PublicProcurementContractResource\RelationManagers\MeasurementsRelationManager;
use Modules\PublicProcurement\Filament\Resources\PublicProcurementContractResource\RelationManagers\OfficialRecordsRelationManager;
use Modules\PublicProcurement\Filament\Resources\PublicProcurementContractResource\RelationManagers\PriceItemsRelationManager;
use Modules\PublicProcurement\Filament\Resources\PublicProcurementContractResource\RelationManagers\ProgressReportsRelationManager;
use Modules\PublicProcurement\Models\PublicProcurementContract;
use Modules\WorkOrder\Models\WorkOrder;
use Modules\Xot\Filament\Resources\XotBaseResource;

final class PublicProcurementContractResource extends XotBaseResource
{
    use GatesResourceNavigation;

    protected static ?string $model = PublicProcurementContract::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-building-office-2';

    public static function getNavigationGroup(): string
    {
        return __('public_procurement.navigation');
    }

    public static function getModelLabel(): string
    {
        return __('public_procurement.contract');
    }

    public static function getPluralModelLabel(): string
    {
        return __('public_procurement.contracts');
    }

    /**
     * @return array<string, Component>
     */
    public static function getFormSchema(): array
    {
        return [
            'section' => Section::make()->columnSpanFull()->components([
                'grid' => Grid::make(3)->components([
                    'code' => TextInput::make('code')->maxLength(255),
                    'status' => Select::make('status')->options(self::statuses())->default('draft')->required(),
                    'work_order_id' => Select::make('work_order_id')

                        ->options(fn (): array => WorkOrder::query()->orderByDesc('id')->limit(300)->pluck('title', 'id')->all())
                        ->searchable(),
                ]),
                'title' => TextInput::make('title')->required()->maxLength(255)->columnSpanFull(),
                'grid2' => Grid::make(3)->components([
                    'contracting_authority' => TextInput::make('contracting_authority')->required()->maxLength(255),
                    'customer_id' => Select::make('customer_id')

                        ->options(fn (): array => Customer::query()->orderBy('legal_name')->pluck('legal_name', 'id')->all())
                        ->searchable()
                        ->preload(),
                    'procedure_type' => TextInput::make('procedure_type')->maxLength(255),
                    'cig' => TextInput::make('cig')->maxLength(255),
                    'cup' => TextInput::make('cup')->maxLength(255),
                    'rup_name' => TextInput::make('rup_name')->maxLength(255),
                    'works_director_name' => TextInput::make('works_director_name')->maxLength(255),
                    'safety_coordinator_name' => TextInput::make('safety_coordinator_name')->maxLength(255),
                ]),
                'grid3' => Grid::make(4)->components([
                    'contract_amount' => TextInput::make('contract_amount')->numeric()->default(0),
                    'security_costs' => TextInput::make('security_costs')->numeric()->default(0),
                    'vat_rate' => TextInput::make('vat_rate')->numeric()->default(22),
                    'retention_rate' => TextInput::make('retention_rate')->numeric()->default(0),
                ]),
                'grid4' => Grid::make(3)->components([
                    'awarded_on' => DatePicker::make('awarded_on'),
                    'signed_on' => DatePicker::make('signed_on'),
                    'site_handover_on' => DatePicker::make('site_handover_on'),
                    'works_start_on' => DatePicker::make('works_start_on'),
                    'works_due_on' => DatePicker::make('works_due_on'),
                    'works_completed_on' => DatePicker::make('works_completed_on'),
                ]),
                'description' => Textarea::make('description')->columnSpanFull(),
                'notes' => Textarea::make('notes')->columnSpanFull(),
            ]),
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('status')->badge()->formatStateUsing(fn (string $state): string => self::statuses()[$state] ?? $state)->sortable(),
                Tables\Columns\TextColumn::make('code')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('title')->searchable()->limit(50),
                Tables\Columns\TextColumn::make('contracting_authority')->searchable()->limit(34),
                Tables\Columns\TextColumn::make('cig')->searchable()->toggleable(),
                Tables\Columns\TextColumn::make('cup')->searchable()->toggleable(),
                Tables\Columns\TextColumn::make('contract_amount')->money('EUR')->sortable(),
                Tables\Columns\TextColumn::make('works_due_on')->date()->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')->options(self::statuses()),
            ])
            ->recordActions([
                Action::make('generateSal')

                    ->icon('heroicon-o-document-chart-bar')
                    ->color('success')
                    ->action(function (PublicProcurementContract $record): void {
                        $next = (string) ($record->progressReports()->count() + 1);
                        app(GenerateProgressReportFromMeasurementsAction::class)->execute(
                            $record,
                            $next,
                            CarbonImmutable::now()->startOfMonth(),
                            CarbonImmutable::now()->endOfMonth(),
                        );
                        Notification::make()->title(__('public_procurement.messages.sal_created'))->success()->send();
                    }),
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([DeleteBulkAction::make()])
            ->defaultSort('id', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            DocumentRequirementsRelationManager::class,
            PriceItemsRelationManager::class,
            MeasurementsRelationManager::class,
            ProgressReportsRelationManager::class,
            DeadlinesRelationManager::class,
            OfficialRecordsRelationManager::class,
            GuaranteesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPublicProcurementContracts::route('/'),
            'create' => CreatePublicProcurementContract::route('/create'),
            'view' => ViewPublicProcurementContract::route('/{record}'),
            'edit' => EditPublicProcurementContract::route('/{record}/edit'),
        ];
    }

    /** @return array<string, string> */
    public static function statuses(): array
    {
        return [
            'draft' => __('public_procurement.statuses.draft'),
            'tender_participation' => __('public_procurement.statuses.tender_participation'),
            'active' => __('public_procurement.statuses.active'),
            'completed' => __('public_procurement.statuses.completed'),
            'lost' => __('public_procurement.statuses.lost'),
        ];
    }
}
