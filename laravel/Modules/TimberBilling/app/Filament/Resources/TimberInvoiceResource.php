<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Filament\Resources;

use App\Support\AccountFeatures;
use Closure;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Modules\Catalog\Models\Product;
use Modules\TimberBilling\Actions\GenerateTimberInvoicePdfAction;
use Modules\TimberBilling\Actions\IssueTimberInvoiceAction;
use Modules\TimberBilling\Actions\PrepareTimberEInvoiceAction;
use Modules\TimberBilling\Actions\SendTimberInvoiceByEmailAction;
use Modules\TimberBilling\Filament\Resources\TimberInvoiceResource\Pages;
use Modules\TimberBilling\Models\TimberInvoice;
use Modules\TimberBilling\Support\WhatsAppMessageLink;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class TimberInvoiceResource extends Resource
{
    protected static ?string $model = TimberInvoice::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-receipt-percent';

    protected static ?string $navigationLabel = 'Fatture e documenti fiscali';

    protected static ?int $navigationSort = 20;

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

    public static function getNavigationGroup(): string
    {
        return 'Fatturazione';
    }

    private static function isAvailableInCurrentPanel(): bool
    {
        return AccountFeatures::currentPanelAllowsFeature(
            Auth::user(),
            adminFeature: 'access',
            operatorFeature: 'access',
        );
    }

    public static function getModelLabel(): string
    {
        return 'Documento fiscale';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Fatture e documenti fiscali';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Testata')
                ->columns(3)
                ->components([
                    Select::make('kind')
                        ->label('Tipo')
                        ->options(self::configMap('timberbilling.invoice_kinds'))
                        ->default('fattura')
                        ->required()
                        ->disabledOn('edit'),
                    TextInput::make('number')
                        ->label('Numero')
                        ->placeholder('Assegnato all\'emissione')
                        ->maxLength(255),
                    DatePicker::make('invoice_date')
                        ->label('Data')
                        ->default(now())
                        ->required(),
                    Select::make('customer_id')
                        ->label('Cliente')
                        ->relationship('customer', 'business_name')
                        ->searchable()
                        ->preload(),
                    Select::make('payment_method_code')
                        ->label('Metodo pagamento')
                        ->options(self::configMap('timberbilling.payment_methods')),
                    TextInput::make('payment_terms')
                        ->label('Termini pagamento')
                        ->maxLength(255),
                ]),

            Section::make('Righe')
                ->components([
                    Repeater::make('lines')
                        ->relationship()
                        ->hiddenLabel()
                        ->schema([
                            Select::make('product_id')
                                ->label('Prodotto')
                                ->options(fn (): array => Product::query()
                                    ->where('is_active', true)
                                    ->orderBy('description')
                                    ->pluck('description', 'id')
                                    ->all())
                                ->searchable()
                                ->preload()
                                ->live()
                                ->afterStateUpdated(function (Set $set, mixed $state): void {
                                    $product = is_numeric($state) ? Product::query()->find($state) : null;
                                    if ($product instanceof Product) {
                                        $description = $product->getAttribute('description');
                                        if (is_string($description) && $description !== '') {
                                            $set('description', $description);
                                        }
                                        $unitPrice = $product->getAttribute('unit_price');
                                        if (is_numeric($unitPrice)) {
                                            $set('unit_price', (float) $unitPrice);
                                        }
                                    }
                                })
                                ->columnSpan(2),
                            TextInput::make('description')
                                ->label('Descrizione')
                                ->required()
                                ->columnSpan(2),
                            TextInput::make('quantity')
                                ->label('Quantita')
                                ->numeric()
                                ->minValue(0)
                                ->default(1)
                                ->required()
                                ->rule(static function (): Closure {
                                    return static function (string $attribute, mixed $value, Closure $fail): void {
                                        if (! is_numeric($value) || (float) $value <= 0) {
                                            $fail('La quantita deve essere positiva.');
                                        }
                                    };
                                }),
                            Select::make('unit_of_measure')
                                ->label('U.m.')
                                ->options(self::configMap('timber.units_of_measure'))
                                ->default('quintali')
                                ->required(),
                            TextInput::make('unit_price')
                                ->label('Prezzo unit.')
                                ->numeric()
                                ->minValue(0)
                                ->default(0),
                            TextInput::make('discount_percent')
                                ->label('Sconto %')
                                ->numeric()
                                ->minValue(0)
                                ->maxValue(100)
                                ->default(0),
                            Select::make('vat_rate')
                                ->label('IVA %')
                                ->options(self::configMap('timberbilling.vat_rates'))
                                ->default('22')
                                ->required(),
                            TextInput::make('lot_code')
                                ->label('Lotto')
                                ->maxLength(255),
                        ])
                        ->columns(4)
                        ->addActionLabel('Aggiungi riga')
                        ->defaultItems(0),
                ]),

            Textarea::make('notes')
                ->label('Note')
                ->rows(3)
                ->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('invoice_date', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('kind')
                    ->label('Tipo')
                    ->formatStateUsing(fn (?string $state): string => self::configMap('timberbilling.invoice_kinds')[$state] ?? (string) $state)
                    ->toggleable(),
                Tables\Columns\TextColumn::make('number')
                    ->label('Numero')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('invoice_date')
                    ->label('Data')
                    ->date('d/m/Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('customer.business_name')
                    ->label('Cliente')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('status_code')
                    ->label('Stato')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => self::configMap('timberbilling.invoice_statuses')[$state] ?? (string) $state),
                Tables\Columns\TextColumn::make('taxable_amount')
                    ->label('Imponibile')
                    ->money('EUR')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('vat_amount')
                    ->label('IVA')
                    ->money('EUR')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('total_amount')
                    ->label('Totale')
                    ->money('EUR')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('kind')
                    ->label('Tipo')
                    ->options(self::configMap('timberbilling.invoice_kinds')),
                Tables\Filters\SelectFilter::make('status_code')
                    ->label('Stato')
                    ->options(self::configMap('timberbilling.invoice_statuses')),
            ])
            ->actions([
                self::downloadPdfAction(),
                self::sendEmailAction(),
                self::whatsAppAction(),
                self::issueAction(),
                self::prepareEInvoiceAction(),
                ViewAction::make(),
                EditAction::make(),
            ]);
    }

    public static function downloadPdfAction(): Action
    {
        return Action::make('downloadPdf')
            ->label('Anteprima PDF')
            ->icon('heroicon-o-document-arrow-down')
            ->action(function (TimberInvoice $record): StreamedResponse {
                $pdf = app(GenerateTimberInvoicePdfAction::class)->execute($record);
                $name = 'documento-'.($record->number ?? (string) $record->id).'.pdf';

                return response()->streamDownload(static function () use ($pdf): void {
                    echo $pdf;
                }, $name, ['Content-Type' => 'application/pdf']);
            });
    }

    public static function sendEmailAction(): Action
    {
        return Action::make('sendEmail')
            ->label('Invia email')
            ->icon('heroicon-o-envelope')
            ->requiresConfirmation()
            ->action(function (TimberInvoice $record): void {
                try {
                    $to = app(SendTimberInvoiceByEmailAction::class)->execute($record);
                    Notification::make()->title('Email inviata a '.$to)->success()->send();
                } catch (\RuntimeException $exception) {
                    Notification::make()->title($exception->getMessage())->warning()->send();
                }
            });
    }

    public static function whatsAppAction(): Action
    {
        return Action::make('whatsApp')
            ->label('WhatsApp')
            ->icon('heroicon-o-chat-bubble-left-right')
            ->color('success')
            ->url(function (TimberInvoice $record): ?string {
                $record->loadMissing('customer');
                $customer = $record->customer;
                $phone = $customer !== null ? ($customer->mobile ?? $customer->phone) : null;
                $message = sprintf(
                    'Salve, in allegato inviamo il documento %s per un totale di %s €.',
                    $record->number ?? '',
                    number_format((float) $record->total_amount, 2, ',', '.'),
                );

                return WhatsAppMessageLink::build(is_string($phone) ? $phone : null, $message);
            }, shouldOpenInNewTab: true)
            ->visible(function (TimberInvoice $record): bool {
                $record->loadMissing('customer');
                $customer = $record->customer;
                $phone = $customer !== null ? ($customer->mobile ?? $customer->phone) : null;

                return WhatsAppMessageLink::build(is_string($phone) ? $phone : null, 'x') !== null;
            });
    }

    public static function prepareEInvoiceAction(): Action
    {
        return Action::make('prepareEInvoice')
            ->label('Genera fattura elettronica')
            ->icon('heroicon-o-paper-airplane')
            ->color('info')
            ->requiresConfirmation()
            ->visible(fn (TimberInvoice $record): bool => $record->kind === 'fattura' && $record->issued_at !== null)
            ->action(function (TimberInvoice $record): void {
                try {
                    app(PrepareTimberEInvoiceAction::class)->execute($record);
                    Notification::make()->title('Fattura elettronica generata (da inviare a SdI)')->success()->send();
                } catch (\RuntimeException $exception) {
                    Notification::make()->title($exception->getMessage())->warning()->send();
                }
            });
    }

    public static function issueAction(): Action
    {
        return Action::make('issue')
            ->label('Emetti')
            ->icon('heroicon-o-check-badge')
            ->color('success')
            ->requiresConfirmation()
            ->visible(fn (TimberInvoice $record): bool => $record->issued_at === null)
            ->action(function (TimberInvoice $record): void {
                try {
                    app(IssueTimberInvoiceAction::class)->execute($record, (int) Auth::id());
                    Notification::make()->title('Documento emesso: '.$record->refresh()->number)->success()->send();
                } catch (\RuntimeException $exception) {
                    Notification::make()->title($exception->getMessage())->warning()->send();
                }
            });
    }

    /**
     * @return array<string, string>
     */
    private static function configMap(string $key): array
    {
        $value = config($key, []);

        if (! is_array($value)) {
            return [];
        }

        /** @var array<string, string> $map */
        $map = [];
        foreach ($value as $code => $label) {
            $map[(string) $code] = is_string($label) ? $label : (string) $code;
        }

        return $map;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTimberInvoices::route('/'),
            'create' => Pages\CreateTimberInvoice::route('/create'),
            'view' => Pages\ViewTimberInvoice::route('/{record}'),
            'edit' => Pages\EditTimberInvoice::route('/{record}/edit'),
        ];
    }
}
