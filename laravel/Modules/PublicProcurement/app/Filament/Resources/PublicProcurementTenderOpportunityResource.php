<?php

declare(strict_types=1);

namespace Modules\PublicProcurement\Filament\Resources;

use Filament\Actions\Action;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DateTimePicker;
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
use Illuminate\Support\Facades\Auth;
use Modules\Customer\Models\Customer;
use Modules\Platform\Filament\Concerns\GatesResourceNavigation;
use Modules\PublicProcurement\Actions\CreateContractFromOpportunityAction;
use Modules\PublicProcurement\Actions\EvaluateTenderEligibilityAction;
use Modules\PublicProcurement\Filament\Resources\PublicProcurementTenderOpportunityResource\Pages\CreatePublicProcurementTenderOpportunity;
use Modules\PublicProcurement\Filament\Resources\PublicProcurementTenderOpportunityResource\Pages\EditPublicProcurementTenderOpportunity;
use Modules\PublicProcurement\Filament\Resources\PublicProcurementTenderOpportunityResource\Pages\ListPublicProcurementTenderOpportunities;
use Modules\PublicProcurement\Filament\Resources\PublicProcurementTenderOpportunityResource\Pages\ViewPublicProcurementTenderOpportunity;
use Modules\PublicProcurement\Filament\Resources\PublicProcurementTenderOpportunityResource\RelationManagers\EligibilityChecksRelationManager;
use Modules\PublicProcurement\Models\PublicProcurementTenderOpportunity;
use Modules\PublicProcurement\Models\PublicProcurementTenderSource;
use Modules\Xot\Filament\Resources\XotBaseResource;

final class PublicProcurementTenderOpportunityResource extends XotBaseResource
{
    use GatesResourceNavigation;

    protected static ?string $model = PublicProcurementTenderOpportunity::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-magnifying-glass-circle';

    public static function getNavigationGroup(): string
    {
        return __('public_procurement.navigation');
    }

    public static function getModelLabel(): string
    {
        return __('public_procurement.tender_opportunity');
    }

    public static function getPluralModelLabel(): string
    {
        return __('public_procurement.tender_opportunities');
    }

    /**
     * @return array<string, Component>
     */
    public function getFormSchema(): array
    {
        return [
            'section' => Section::make()->columnSpanFull()->components([
                'grid' => Grid::make(3)->components([
                    'status' => Select::make('status')->options(self::statuses())->default('new')->required(),
                    'tender_source_id' => Select::make('tender_source_id')

                        ->options(fn (): array => PublicProcurementTenderSource::query()->orderBy('name')->pluck('name', 'id')->all())
                        ->searchable()
                        ->preload(),
                    'customer_id' => Select::make('customer_id')

                        ->options(fn (): array => Customer::query()->orderBy('legal_name')->pluck('legal_name', 'id')->all())
                        ->searchable()
                        ->preload(),
                ]),
                'title' => TextInput::make('title')->required()->maxLength(255)->columnSpanFull(),
                'grid2' => Grid::make(3)->components([
                    'contracting_authority' => TextInput::make('contracting_authority')->required()->maxLength(255),
                    'cig' => TextInput::make('cig')->maxLength(255),
                    'cup' => TextInput::make('cup')->maxLength(255),
                    'cpv_code' => TextInput::make('cpv_code')->maxLength(255),
                    'category' => TextInput::make('category')->maxLength(255),
                    'territory' => TextInput::make('territory')->maxLength(255),
                ]),
                'grid3' => Grid::make(3)->components([
                    'estimated_amount' => TextInput::make('estimated_amount')->numeric(),
                    'published_at' => DateTimePicker::make('published_at'),
                    'submission_deadline_at' => DateTimePicker::make('submission_deadline_at'),
                    'site_visit_due_at' => DateTimePicker::make('site_visit_due_at'),
                    'source_url' => TextInput::make('source_url')->url()->columnSpan(2),
                ]),
                'raw_excerpt' => Textarea::make('raw_excerpt')->columnSpanFull(),
                'notes' => Textarea::make('notes')->columnSpanFull(),
            ]),
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('status')->badge()->formatStateUsing(fn (string $state): string => self::statuses()[$state] ?? $state)->sortable(),
                Tables\Columns\TextColumn::make('title')->searchable()->limit(50),
                Tables\Columns\TextColumn::make('contracting_authority')->searchable()->limit(34),
                Tables\Columns\TextColumn::make('cig')->searchable()->toggleable(),
                Tables\Columns\TextColumn::make('category')->searchable()->toggleable(),
                Tables\Columns\TextColumn::make('territory')->searchable()->toggleable(),
                Tables\Columns\TextColumn::make('estimated_amount')->money('EUR')->sortable(),
                Tables\Columns\TextColumn::make('submission_deadline_at')->dateTime()->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')->options(self::statuses()),
                SelectFilter::make('category')->options(fn (): array => PublicProcurementTenderOpportunity::query()->whereNotNull('category')->distinct()->pluck('category', 'category')->all()),
            ])
            ->recordActions([
                Action::make('evaluateEligibility')

                    ->icon('heroicon-o-shield-check')
                    ->color('warning')
                    ->action(function (PublicProcurementTenderOpportunity $record): void {
                        $userId = Auth::id();
                        app(EvaluateTenderEligibilityAction::class)->execute($record, is_int($userId) ? $userId : null);
                        Notification::make()->title(__('public_procurement.messages.eligibility_completed'))->success()->send();
                    }),
                Action::make('createContract')

                    ->icon('heroicon-o-briefcase')
                    ->color('success')
                    ->action(function (PublicProcurementTenderOpportunity $record): void {
                        app(CreateContractFromOpportunityAction::class)->execute($record);
                        Notification::make()->title(__('public_procurement.messages.contract_created'))->success()->send();
                    }),
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([DeleteBulkAction::make()])
            ->defaultSort('submission_deadline_at');
    }

    public static function getRelations(): array
    {
        return [
            EligibilityChecksRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPublicProcurementTenderOpportunities::route('/'),
            'create' => CreatePublicProcurementTenderOpportunity::route('/create'),
            'view' => ViewPublicProcurementTenderOpportunity::route('/{record}'),
            'edit' => EditPublicProcurementTenderOpportunity::route('/{record}/edit'),
        ];
    }

    /** @return array<string, string> */
    public static function statuses(): array
    {
        return [
            'new' => __('public_procurement.statuses.new'),
            'to_evaluate' => __('public_procurement.statuses.to_evaluate'),
            'interesting' => __('public_procurement.statuses.interesting'),
            'needs_documents' => __('public_procurement.statuses.needs_documents'),
            'blocked' => __('public_procurement.statuses.blocked'),
            'discarded' => __('public_procurement.statuses.discarded'),
            'participation_started' => __('public_procurement.statuses.participation_started'),
            'awarded' => __('public_procurement.statuses.awarded'),
            'lost' => __('public_procurement.statuses.lost'),
        ];
    }
}
