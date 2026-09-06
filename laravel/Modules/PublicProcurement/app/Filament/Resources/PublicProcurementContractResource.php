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
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
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

final class PublicProcurementContractResource extends Resource
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

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()->columnSpanFull()->components([
                Grid::make(3)->components([
                    TextInput::make('code')->label(__('public_procurement.fields.code'))->maxLength(255),
                    Select::make('status')->label(__('public_procurement.fields.status'))->options(self::statuses())->default('draft')->required(),
                    Select::make('work_order_id')
                        ->label(__('public_procurement.fields.work_order'))
                        ->options(fn (): array => WorkOrder::query()->orderByDesc('id')->limit(300)->pluck('title', 'id')->all())
                        ->searchable(),
                ]),
                TextInput::make('title')->label(__('public_procurement.fields.title'))->required()->maxLength(255)->columnSpanFull(),
                Grid::make(3)->components([
                    TextInput::make('contracting_authority')->label(__('public_procurement.fields.contracting_authority'))->required()->maxLength(255),
                    Select::make('customer_id')
                        ->label(__('public_procurement.fields.customer'))
                        ->options(fn (): array => Customer::query()->orderBy('legal_name')->pluck('legal_name', 'id')->all())
                        ->searchable()
                        ->preload(),
                    TextInput::make('procedure_type')->label(__('public_procurement.fields.procedure_type'))->maxLength(255),
                    TextInput::make('cig')->label(__('public_procurement.fields.cig'))->maxLength(255),
                    TextInput::make('cup')->label(__('public_procurement.fields.cup'))->maxLength(255),
                    TextInput::make('rup_name')->label(__('public_procurement.fields.rup_name'))->maxLength(255),
                    TextInput::make('works_director_name')->label(__('public_procurement.fields.works_director_name'))->maxLength(255),
                    TextInput::make('safety_coordinator_name')->label(__('public_procurement.fields.safety_coordinator_name'))->maxLength(255),
                ]),
                Grid::make(4)->components([
                    TextInput::make('contract_amount')->label(__('public_procurement.fields.contract_amount'))->numeric()->default(0),
                    TextInput::make('security_costs')->label(__('public_procurement.fields.security_costs'))->numeric()->default(0),
                    TextInput::make('vat_rate')->label(__('public_procurement.fields.vat_rate'))->numeric()->default(22),
                    TextInput::make('retention_rate')->label(__('public_procurement.fields.retention_rate'))->numeric()->default(0),
                ]),
                Grid::make(3)->components([
                    DatePicker::make('awarded_on'),
                    DatePicker::make('signed_on'),
                    DatePicker::make('site_handover_on'),
                    DatePicker::make('works_start_on'),
                    DatePicker::make('works_due_on')->label(__('public_procurement.fields.works_due_on')),
                    DatePicker::make('works_completed_on'),
                ]),
                Textarea::make('description')->columnSpanFull(),
                Textarea::make('notes')->label(__('public_procurement.fields.notes'))->columnSpanFull(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('status')->label(__('public_procurement.fields.status'))->badge()->formatStateUsing(fn (string $state): string => self::statuses()[$state] ?? $state)->sortable(),
                Tables\Columns\TextColumn::make('code')->label(__('public_procurement.fields.code'))->searchable()->sortable(),
                Tables\Columns\TextColumn::make('title')->label(__('public_procurement.fields.title'))->searchable()->limit(50),
                Tables\Columns\TextColumn::make('contracting_authority')->label(__('public_procurement.fields.contracting_authority'))->searchable()->limit(34),
                Tables\Columns\TextColumn::make('cig')->label(__('public_procurement.fields.cig'))->searchable()->toggleable(),
                Tables\Columns\TextColumn::make('cup')->label(__('public_procurement.fields.cup'))->searchable()->toggleable(),
                Tables\Columns\TextColumn::make('contract_amount')->label(__('public_procurement.fields.contract_amount'))->money('EUR')->sortable(),
                Tables\Columns\TextColumn::make('works_due_on')->label(__('public_procurement.fields.works_due_on'))->date()->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')->label(__('public_procurement.fields.status'))->options(self::statuses()),
            ])
            ->recordActions([
                Action::make('generateSal')
                    ->label(__('public_procurement.actions.generate_sal'))
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
