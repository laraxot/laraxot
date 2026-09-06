<?php

declare(strict_types=1);

namespace Modules\Customer\Filament\Resources;

use App\Support\AccountFeatures;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Modules\Customer\Filament\Resources\CustomerResource\Pages;
use Modules\Customer\Filament\Resources\CustomerResource\RelationManagers\ContactsRelationManager;
use Modules\Customer\Filament\Resources\CustomerResource\RelationManagers\CustomerSitesRelationManager;
use Modules\Customer\Filament\Resources\CustomerResource\RelationManagers\WorkOrdersRelationManager;
use Modules\Customer\Models\Customer;
use Modules\Platform\Filament\Resources\GatedXotBaseResource;
use Modules\User\Models\User;

final class CustomerResource extends GatedXotBaseResource
{
    protected static ?string $model = Customer::class;

    protected static ?string $recordTitleAttribute = 'legal_name';

    public static function getGloballySearchableAttributes(): array
    {
        return ['legal_name', 'vat_number', 'tax_code', 'email', 'phone', 'city'];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        /** @var Customer $record */
        return array_filter([
            (string) __('customers.fields.vat_number') => is_string($record->vat_number) ? $record->vat_number : null,
            (string) __('customers.fields.city') => is_string($record->city) ? $record->city : null,
            (string) __('customers.fields.phone') => is_string($record->phone) ? $record->phone : null,
        ]);
    }

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-building-office-2';

    public static function shouldRegisterNavigation(): bool
    {
        return self::isAvailableInCurrentPanel();
    }

    public static function canViewAny(): bool
    {
        return self::isAvailableInCurrentPanel() && parent::canViewAny();
    }

    public static function canCreate(): bool
    {
        return self::isAvailableInCurrentPanel() && parent::canCreate();
    }

    public static function canEdit(Model $record): bool
    {
        return self::isAvailableInCurrentPanel() && parent::canEdit($record);
    }

    public static function canView(Model $record): bool
    {
        return self::isAvailableInCurrentPanel() && parent::canView($record);
    }

    public static function getNavigationSort(): int
    {
        return 4;
    }

    public static function getNavigationGroup(): string
    {
        return (string) __('customers.navigation');
    }

    public static function getModelLabel(): string
    {
        return __('customers.customer');
    }

    public static function getPluralModelLabel(): string
    {
        return __('customers.customers');
    }

    public static function getFormSchema(): array
    {
        return [
            Section::make()
                ->columnSpanFull()
                ->components([
                    TextInput::make('legal_name')
                        ->label(__('customers.fields.legal_name'))
                        ->required()
                        ->maxLength(255),

                    TextInput::make('vat_number')
                        ->label(__('customers.fields.vat_number'))
                        ->maxLength(255),

                    TextInput::make('tax_code')
                        ->label(__('customers.fields.tax_code'))
                        ->maxLength(255),

                    TextInput::make('email')
                        ->label(__('customers.fields.email'))
                        ->email()
                        ->maxLength(255),

                    TextInput::make('phone')
                        ->label(__('customers.fields.phone'))
                        ->tel()
                        ->maxLength(255),

                    TextInput::make('address_line1')
                        ->label(__('customers.fields.address_line1'))
                        ->maxLength(255),

                    TextInput::make('address_line2')
                        ->label(__('customers.fields.address_line2'))
                        ->maxLength(255),

                    Grid::make(3)->components([
                        TextInput::make('city')
                            ->label(__('customers.fields.city'))
                            ->maxLength(255),
                        TextInput::make('postal_code')
                            ->label(__('customers.fields.postal_code'))
                            ->maxLength(255),
                        TextInput::make('province')
                            ->label(__('customers.fields.province'))
                            ->maxLength(255),
                    ]),

                    Select::make('country_code')
                        ->label(__('customers.fields.country_code'))
                        ->options([
                            'IT' => 'Italia',
                            'SM' => 'San Marino',
                            'CH' => 'Svizzera',
                            'DE' => 'Germania',
                            'FR' => 'Francia',
                            'AT' => 'Austria',
                        ])
                        ->default('IT')
                        ->searchable()
                        ->preload(),

                    Select::make('payment_method')
                        ->label(__('customers.fields.payment_method'))
                        ->options([
                            'bonifico' => __('customers.payment_methods.bonifico'),
                            'sepa' => __('customers.payment_methods.sepa'),
                            'carta' => __('customers.payment_methods.carta'),
                            'contanti' => __('customers.payment_methods.contanti'),
                        ])
                        ->default('bonifico')
                        ->searchable()
                        ->preload()
                        ->live()
                        ->required(),

                    TextInput::make('iban')
                        ->label(__('customers.fields.iban'))
                        ->maxLength(34)
                        ->visible(function (callable $get): bool {
                            $paymentMethod = $get('payment_method');
                            return is_string($paymentMethod) && $paymentMethod === 'bonifico';
                        })
                        ->dehydrateStateUsing(function (?string $state, callable $get): ?string {
                            $paymentMethod = $get('payment_method');
                            return is_string($paymentMethod) && $paymentMethod === 'bonifico' ? $state : null;
                        }),

                    Textarea::make('notes')
                        ->label(__('customers.fields.notes'))
                        ->columnSpanFull(),

                    Toggle::make('is_active')
                        ->label(__('customers.fields.is_active'))
                        ->default(true),
                ])
                ->columns(2),
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('legal_name')
                    ->label(__('customers.fields.legal_name'))
                    ->weight(FontWeight::Bold)
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->label(__('customers.fields.email'))
                    ->icon('heroicon-m-envelope')
                    ->copyable()
                    ->toggleable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label(__('customers.fields.phone'))
                    ->icon('heroicon-m-phone')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('city')
                    ->label(__('customers.fields.city'))
                    ->toggleable()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label(__('customers.fields.is_active'))
                    ->boolean()
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label(__('customers.fields.is_active')),
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
            WorkOrdersRelationManager::class,
            CustomerSitesRelationManager::class,
            ContactsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCustomers::route('/'),
            'create' => Pages\CreateCustomer::route('/create'),
            'view' => Pages\ViewCustomer::route('/{record}'),
            'edit' => Pages\EditCustomer::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        $user = Auth::user();

        if (request()->is('admin/*') && $user instanceof User && AccountFeatures::hasAssignedPerimeter($user)) {
            AccountFeatures::scopeAssignedCustomers($query, $user);
        }

        return $query;
    }

    private static function isAvailableInCurrentPanel(): bool
    {
        return AccountFeatures::currentPanelAllowsFeature(
            Auth::user(),
            adminFeature: 'admin.customers',
            operatorFeature: null,
            adminDefault: true,
            operatorDefault: false,
            fallback: ! request()->is('customer/*') && ! request()->is('supplier/*'),
        );
    }
}
