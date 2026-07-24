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

    public static function getFormSchema(): array
    {
        return [
            Section::make(__('site_surveys.sections.general'))
                ->columnSpanFull()
                ->components([
                    Grid::make(2)->components([
                        TextInput::make('code')
                            ->label(__('site_surveys.fields.code'))
                            ->maxLength(255),
                        Select::make('status_code')
                            ->label(__('site_surveys.fields.status'))
                            ->options(self::statusOptions())
                            ->required()
                            ->default('planned'),
                    ]),

                    TextInput::make('title')
                        ->label(__('site_surveys.fields.title'))
                        ->columnSpanFull()
                        ->maxLength(255),

                    Grid::make(2)->components([
                        DateTimePicker::make('scheduled_at')
                            ->label(__('site_surveys.fields.scheduled_at'))
                            ->displayFormat('d/m/Y H:i')
                            ->seconds(false),
                        DateTimePicker::make('performed_at')
                            ->label(__('site_surveys.fields.performed_at'))
                            ->displayFormat('d/m/Y H:i')
                            ->seconds(false),
                    ]),
                ])
                ->columns(2),

            Section::make(__('site_surveys.sections.links'))
                ->columnSpanFull()
                ->components([
                    Select::make('customer_id')
                        ->label(__('site_surveys.fields.customer'))
                        ->options(fn (): array => Customer::query()
                            ->orderBy('legal_name')
                            ->pluck('legal_name', 'id')
                            ->all())
                        ->searchable()
                        ->preload()
                        ->live()
                        ->afterStateUpdated(fn (callable $set) => $set('customer_site_id', null))
                        ->required(),

                    Select::make('customer_site_id')
                        ->label(__('site_surveys.fields.customer_site'))
                        ->options(function (callable $get): array {
                            $customerId = (int) ($get('customer_id') ?? 0);

                            if ($customerId <= 0) {
                                return [];
                            }

                            return CustomerSite::query()
                                ->where('customer_id', $customerId)
                                ->whereNotNull('name')
                                ->orderBy('name')
                                ->pluck('name', 'id')
                                ->all();
                        })
                        ->searchable()
                        ->preload()
                        ->disabled(fn (callable $get): bool => blank($get('customer_id'))),

                    Select::make('work_order_id')
                        ->label(__('site_surveys.fields.work_order'))
                        ->options(fn (): array => WorkOrder::query()
                            ->orderByDesc('id')
                            ->limit(200)
                            ->get(['id', 'code', 'title'])
                            ->mapWithKeys(static fn (WorkOrder $workOrder): array => [
                                $workOrder->id => (string) ($workOrder->code ?? $workOrder->title ?? $workOrder->id),
                            ])
                            ->all())
                        ->searchable()
                        ->preload(),

                    Select::make('quotation_id')
                        ->label(__('site_surveys.fields.quotation'))
                        ->options(fn (): array => Quotation::query()
                            ->orderByDesc('id')
                            ->limit(200)
                            ->get(['id', 'code', 'title'])
                            ->mapWithKeys(static fn (Quotation $quotation): array => [
                                $quotation->id => (string) ($quotation->code ?? $quotation->title ?? $quotation->id),
                            ])
                            ->all())
                        ->searchable()
                        ->preload(),

                    Select::make('performed_by_user_id')
                        ->label(__('site_surveys.fields.performed_by'))
                        ->relationship('performedBy', 'name')
                        ->searchable()
                        ->preload(),
                ])
                ->columns(2),

            Section::make(__('site_surveys.sections.outcome'))
                ->columnSpanFull()
                ->components([
                    Textarea::make('notes')
                        ->label(__('site_surveys.fields.notes'))
                        ->rows(5)
                        ->helperText(__('site_surveys.hints.no_attachments'))
                        ->columnSpanFull(),
                ]),
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label(__('site_surveys.fields.code'))
                    ->weight(FontWeight::Bold)
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('title')
                    ->label(__('site_surveys.fields.title'))
                    ->limit(50)
                    ->searchable(),
                Tables\Columns\TextColumn::make('customer.legal_name')
                    ->label(__('site_surveys.fields.customer'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status_code')
                    ->label(__('site_surveys.fields.status'))
                    ->badge()
                    ->color(fn (string $state): string => self::statusColor($state))
                    ->formatStateUsing(fn (string $state): string => __("site_surveys.statuses.{$state}"))
                    ->sortable(),
                Tables\Columns\TextColumn::make('scheduled_at')
                    ->label(__('site_surveys.fields.scheduled_at'))
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('performed_at')
                    ->label(__('site_surveys.fields.performed_at'))
                    ->dateTime('d/m/Y H:i')
                    ->toggleable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('media_count')
                    ->label(__('site_surveys.fields.attachments_count'))
                    ->counts('media')
                    ->badge()
                    ->color(fn (mixed $state): string => (int) $state > 0 ? 'success' : 'warning'),
            ])
            ->defaultSort('scheduled_at', 'desc')
            ->filters([
                SelectFilter::make('status_code')
                    ->label(__('site_surveys.fields.status'))
                    ->options(self::statusOptions()),
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
                RestoreBulkAction::make(),
                ForceDeleteBulkAction::make(),
            ]);
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
