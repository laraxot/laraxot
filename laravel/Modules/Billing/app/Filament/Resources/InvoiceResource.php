<?php

declare(strict_types=1);

namespace Modules\Billing\Filament\Resources;

use App\Support\AccountFeatures;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Billing\Filament\Resources\InvoiceResource\Pages;
use Modules\Billing\Filament\Resources\InvoiceResource\RelationManagers\WorkOrdersRelationManager;
use Modules\Billing\Models\Invoice;
use Modules\Platform\Filament\Resources\GatedXotBaseResource;
use Modules\User\Models\User;

final class InvoiceResource extends GatedXotBaseResource
{
    protected static ?string $model = Invoice::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-receipt-percent';

    public static function shouldRegisterNavigation(): bool
    {
        return self::isAvailableInCurrentPanel();
    }

    public static function canViewAny(): bool
    {
        return self::isAvailableInCurrentPanel() && parent::canViewAny();
    }

    public static function getNavigationGroup(): string
    {
        return __('billing::billing.navigation');
    }

    public static function getModelLabel(): string
    {
        return __('billing::billing.invoice');
    }

    public static function getPluralModelLabel(): string
    {
        return __('billing::billing.invoices');
    }

    public static function canCreate(): bool
    {
        if (! self::isAvailableInCurrentPanel()) {
            return false;
        }

        if (request()->is('customer/*')) {
            return false;
        }

        return parent::canCreate();
    }

    public static function canEdit(Model $record): bool
    {
        if (! self::isAvailableInCurrentPanel()) {
            return false;
        }

        if (request()->is('customer/*')) {
            return false;
        }

        return parent::canEdit($record);
    }

    public static function canView(Model $record): bool
    {
        return self::isAvailableInCurrentPanel() && parent::canView($record);
    }

    /**
     * @return array<int, Component>
     */
    public static function getFormSchema(): array
    {
        return [
            Section::make()
                ->columnSpanFull()
                ->components([
                    Grid::make(2)->components([
                        TextInput::make('number')
                            ->label(__('billing::billing.fields.number'))
                            ->maxLength(255),
                        DatePicker::make('issued_on')
                            ->label(__('billing::billing.fields.issued_on')),
                    ]),

                    Select::make('customer_id')
                        ->label(__('billing::billing.fields.customer'))
                        ->options(fn (): array => DB::table('customers')->orderBy('legal_name')->pluck('legal_name', 'id')->all())
                        ->searchable()
                        ->preload()
                        ->live()
                        ->afterStateUpdated(fn (callable $set) => $set('work_order_id', null))
                        ->required(),

                    Select::make('work_order_id')
                        ->label(__('billing::billing.fields.work_order'))
                        ->options(function (callable $get): array {
                            $q = DB::table('work_orders')->orderByDesc('id');
                            $customerId = $get('customer_id');
                            if (! blank($customerId)) {
                                $q->where('customer_id', $customerId);
                            }

                            return $q
                                ->limit(200)
                                ->get(['id', 'code', 'title'])
                                ->mapWithKeys(function ($row): array {
                                    $id = is_numeric($row->id) ? (int) $row->id : 0;
                                    $code = is_string($row->code) ? $row->code : '';
                                    $title = is_string($row->title) ? $row->title : '';
                                    $label = $code ?: ($title ?: 'Commessa senza codice');

                                    return [$id => $label];
                                })
                                ->all();
                        })
                        ->searchable()
                        ->preload(),

                    TextInput::make('total')
                        ->label(__('billing::billing.fields.total'))
                        ->numeric()
                        ->default(0),

                    Textarea::make('notes')
                        ->label(__('billing::billing.fields.notes'))
                        ->columnSpanFull(),
                ])
                ->columns(2),
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('number')
                    ->label(__('billing::billing.fields.number'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('customer_id')
                    ->label(__('billing::billing.fields.customer'))
                    ->formatStateUsing(function ($state): string {
                        if (is_numeric($state)) {
                            $name = DB::table('customers')->where('id', $state)->value('legal_name');
                            return is_string($name) ? $name : 'Cliente non disponibile';
                        }

                        return 'Cliente non disponibile';
                    })
                    ->toggleable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('issued_on')
                    ->label(__('billing::billing.fields.issued_on'))
                    ->date()
                    ->toggleable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total')
                    ->label(__('billing::billing.fields.total'))
                    ->money('EUR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('electronic_invoice_sent_at')
                    ->label(__('billing::billing.fields.einvoice_sent_at'))
                    ->dateTime()
                    ->toggleable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('electronic_invoice_external_id')
                    ->label(__('billing::billing.fields.einvoice_external_id'))
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make()
                    ->visible(fn (): bool => ! request()->is('customer/*')),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
                RestoreBulkAction::make(),
                ForceDeleteBulkAction::make(),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if (request()->is('customer/*')) {
            /** @var User|null $user */
            $user = Auth::user();
            if (is_object($user)) {
                $id = $user->customer_id ?? 0;
                $customerId = is_numeric($id) ? (int) $id : 0;
            } else {
                $customerId = 0;
            }

            if ($customerId > 0) {
                $query->where('customer_id', $customerId);
            } else {
                $query->whereRaw('1=0');
            }
        }

        return $query;
    }

    public static function getRelations(): array
    {
        return [
            WorkOrdersRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInvoices::route('/'),
            'create' => Pages\CreateInvoice::route('/create'),
            'view' => Pages\ViewInvoice::route('/{record}'),
            'edit' => Pages\EditInvoice::route('/{record}/edit'),
        ];
    }

    private static function isAvailableInCurrentPanel(): bool
    {
        return AccountFeatures::currentPanelAllowsFeature(
            Auth::user(),
            adminFeature: 'admin.billing',
            operatorFeature: null,
            adminDefault: true,
            operatorDefault: false,
            fallback: false,
        );
    }
}
