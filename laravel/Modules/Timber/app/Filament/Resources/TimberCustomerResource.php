<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources;

use App\Support\AccountFeatures;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Modules\Timber\Actions\GenerateTimberCustomerLabelPdfAction;
use Modules\Timber\Actions\GenerateTimberCustomerSheetPdfAction;
use Modules\Timber\Filament\Resources\TimberCustomerResource\Pages;
use Modules\Timber\Models\TimberAgent;
use Modules\Timber\Models\TimberCustomer;
use Modules\Xot\Filament\Resources\XotBaseResource;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class TimberCustomerResource extends XotBaseResource
{
    protected static ?string $model = TimberCustomer::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationLabel = 'Clienti';

    protected static ?int $navigationSort = 85;

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
        return 'Anagrafiche';
    }

    private static function isAvailableInCurrentPanel(): bool
    {
        return AccountFeatures::currentPanelAllowsFeature(
            Auth::user(),
            adminFeature: 'registries',
            operatorFeature: 'registries',
        );
    }

    public static function getModelLabel(): string
    {
        return 'Cliente';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Clienti';
    }

    /** @return array<int|string, Component> */
    public static function getFormSchema(): array
    {
        return [
            Tabs::make('customer')
                ->columnSpanFull()
                ->tabs([
                    Tab::make('Anagrafica')
                        ->schema(self::registryFields()),
                    Tab::make('Rapporti commerciali')
                        ->schema(self::commercialFields()),
                    Tab::make('Varie')
                        ->schema(self::extraFields()),
                ]),
        ];
    }

    /** @return array<int, Component> */
    private static function registryFields(): array
    {
        return [
            Grid::make(3)->components([
                TextInput::make('code')
                    ->maxLength(255),
                TextInput::make('tax_code')
                    ->maxLength(255),
                TextInput::make('vat_number')
                    ->maxLength(255),
            ]),

            Grid::make(2)->components([
                Toggle::make('is_customer')
                    ->default(true),
                Toggle::make('is_supplier')
                    ->default(false),
            ]),

            Section::make('Sede operativa')
                ->columns(2)
                ->components([
                    TextInput::make('business_name')
                        ->required()
                        ->maxLength(255)
                        ->columnSpanFull(),
                    TextInput::make('address_line1')
                        ->maxLength(255)
                        ->columnSpanFull(),
                    TextInput::make('postal_code')
                        ->maxLength(255),
                    TextInput::make('city')
                        ->maxLength(255),
                    TextInput::make('province')
                        ->maxLength(255),
                    TextInput::make('country')
                        ->default('Italia')
                        ->maxLength(255),
                ]),

            Section::make('Fattura elettronica')
                ->columns(3)
                ->components([
                    TextInput::make('sdi_recipient_code')
                        ->maxLength(7),
                    TextInput::make('sdi_admin_ref')
                        ->maxLength(255),
                    TextInput::make('pec')
                        ->email()
                        ->maxLength(255),
                ]),

            Section::make('Contatti')
                ->columns(2)
                ->components([
                    TextInput::make('contact_person')
                        ->maxLength(255),
                    TextInput::make('phone')
                        ->tel()
                        ->maxLength(255),
                    TextInput::make('mobile')
                        ->tel()
                        ->maxLength(255),
                    TextInput::make('fax')
                        ->maxLength(255),
                    TextInput::make('email')
                        ->email()
                        ->maxLength(255)
                        ->columnSpanFull(),
                ]),
        ];
    }

    /** @return array<int, Component> */
    private static function commercialFields(): array
    {
        return [
            Grid::make(2)->components([
                TextInput::make('default_discount_percent')
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(100),
                TextInput::make('price_list_code')
                    ->maxLength(255),
                TextInput::make('credit_limit')
                    ->numeric()
                    ->minValue(0),
                Select::make('agent_id')
                    ->options(fn (): array => TimberAgent::query()
                        ->where('is_active', true)
                        ->orderBy('full_name')
                        ->pluck('full_name', 'id')
                        ->all())
                    ->searchable()
                    ->preload(),
                TextInput::make('payment_terms')
                    ->maxLength(255),
                TextInput::make('vat_rate_code')
                    ->maxLength(255),
                TextInput::make('carriage_code')
                    ->maxLength(255),
            ]),

            Section::make('Coordinate bancarie')
                ->columns(2)
                ->components([
                    TextInput::make('iban')
                        ->maxLength(34),
                    TextInput::make('our_bank')
                        ->maxLength(255),
                ]),

            Section::make('Dichiarazione di intento')
                ->columns(2)
                ->components([
                    TextInput::make('intent_declaration_number')
                        ->maxLength(255),
                    DatePicker::make('intent_declaration_date'),
                ]),

            Grid::make(2)->components([
                Toggle::make('send_documents_by_email'),
                Toggle::make('invoice_with_withholding')
                    ->label("Fattura con Rit. d'acconto"),
            ]),

            Section::make('In creazione documenti')
                ->components([
                    Textarea::make('document_notice')
                        ->rows(2),
                    Textarea::make('document_note')
                        ->rows(2),
                ]),
        ];
    }

    /** @return array<int, Component> */
    private static function extraFields(): array
    {
        return [
            Textarea::make('notes')
                ->rows(4)
                ->columnSpanFull(),

            Section::make('Foglio appunti')
                ->description('Annotazioni datate sul cliente')
                ->components([
                    Repeater::make('contactNotes')
                        ->relationship()
                        ->hiddenLabel()
                        ->schema([
                            Textarea::make('body')
                                ->required()
                                ->rows(2),
                        ])
                        ->addActionLabel('Aggiungi appunto')
                        ->defaultItems(0),
                ]),

            Toggle::make('is_active')
                ->default(true),
        ];
    }

    public static function printSheetAction(): Action
    {
        return Action::make('printSheet')
            ->icon('heroicon-o-document-text')
            ->action(function (TimberCustomer $record): StreamedResponse {
                $pdf = app(GenerateTimberCustomerSheetPdfAction::class)->execute($record);

                return response()->streamDownload(static function () use ($pdf): void {
                    echo $pdf;
                }, 'scheda-cliente-'.$record->id.'.pdf', ['Content-Type' => 'application/pdf']);
            });
    }

    public static function printLabelAction(): Action
    {
        return Action::make('printLabel')
            ->icon('heroicon-o-tag')
            ->action(function (TimberCustomer $record): StreamedResponse {
                $pdf = app(GenerateTimberCustomerLabelPdfAction::class)->execute($record);

                return response()->streamDownload(static function () use ($pdf): void {
                    echo $pdf;
                }, 'etichetta-cliente-'.$record->id.'.pdf', ['Content-Type' => 'application/pdf']);
            });
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTimberCustomers::route('/'),
            'create' => Pages\CreateTimberCustomer::route('/create'),
            'view' => Pages\ViewTimberCustomer::route('/{record}'),
            'edit' => Pages\EditTimberCustomer::route('/{record}/edit'),
        ];
    }
}
