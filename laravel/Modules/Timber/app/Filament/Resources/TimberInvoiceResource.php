<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources;

use App\Support\AccountFeatures;
use Closure;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Modules\Catalog\Models\Product;
use Modules\Timber\Actions\GenerateTimberInvoicePdfAction;
use Modules\Timber\Actions\IssueTimberInvoiceAction;
use Modules\Timber\Actions\PrepareTimberEInvoiceAction;
use Modules\Timber\Actions\SendTimberInvoiceByEmailAction;
use Modules\Timber\Filament\Resources\TimberInvoiceResource\Pages;
use Modules\Timber\Models\TimberInvoice;
use Modules\Timber\Support\TimberConfigMap;
use Modules\Timber\Support\WhatsAppMessageLink;
use Modules\Xot\Filament\Resources\XotBaseResource;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class TimberInvoiceResource extends XotBaseResource
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

    /** @return array<int|string, Component> */
    public function getFormSchema(): array
    {
        return [
            Section::make('Testata')
                ->columns(3)
                ->components([
                    Select::make('kind')
                        ->options(TimberConfigMap::get('timber.invoice_kinds'))
                        ->default('fattura')
                        ->required()
                        ->disabledOn('edit'),
                    TextInput::make('number')
                        ->placeholder('Assegnato all\'emissione')
                        ->maxLength(255),
                    DatePicker::make('invoice_date')
                        ->default(now())
                        ->required(),
                    Select::make('customer_id')
                        ->relationship('customer', 'business_name')
                        ->searchable()
                        ->preload(),
                    Select::make('payment_method_code')
                        ->options(TimberConfigMap::get('timber.payment_methods')),
                    TextInput::make('payment_terms')
                        ->maxLength(255),
                ]),

            Section::make('Righe')
                ->components([
                    Repeater::make('lines')
                        ->relationship()
                        ->hiddenLabel()
                        ->schema([
                            Select::make('product_id')
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
                                ->required()
                                ->columnSpan(2),
                            TextInput::make('quantity')
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
                                ->options(TimberConfigMap::get('timber.units_of_measure'))
                                ->default('quintali')
                                ->required(),
                            TextInput::make('unit_price')
                                ->numeric()
                                ->minValue(0)
                                ->default(0),
                            TextInput::make('discount_percent')
                                ->numeric()
                                ->minValue(0)
                                ->maxValue(100)
                                ->default(0),
                            Select::make('vat_rate')
                                ->options(TimberConfigMap::get('timber.vat_rates'))
                                ->default('22')
                                ->required(),
                            TextInput::make('lot_code')
                                ->maxLength(255),
                        ])
                        ->columns(4)
                        ->addActionLabel('Aggiungi riga')
                        ->defaultItems(0),
                ]),

            Textarea::make('notes')
                ->rows(3)
                ->columnSpanFull(),
        ];
    }

    public static function downloadPdfAction(): Action
    {
        return Action::make('downloadPdf')
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
            ->icon('heroicon-o-check-badge')
            ->color('success')
            ->requiresConfirmation()
            ->visible(fn (TimberInvoice $record): bool => $record->issued_at === null)
            ->action(function (TimberInvoice $record): void {
                try {
                    app(IssueTimberInvoiceAction::class)->execute($record, Auth::id());
                    Notification::make()->title('Documento emesso: '.$record->refresh()->number)->success()->send();
                } catch (\RuntimeException $exception) {
                    Notification::make()->title($exception->getMessage())->warning()->send();
                }
            });
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
