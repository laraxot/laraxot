<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Filament\Resources;

use App\Support\AccountFeatures;
use Filament\Actions\Action;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Modules\TimberBilling\Actions\GenerateTimberCustomerLabelPdfAction;
use Modules\TimberBilling\Actions\GenerateTimberCustomerSheetPdfAction;
use Modules\TimberBilling\Filament\Resources\TimberCustomerResource\Pages;
use Modules\TimberBilling\Models\TimberAgent;
use Modules\TimberBilling\Models\TimberCustomer;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class TimberCustomerResource extends Resource
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

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
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
        ]);
    }

    /** @return array<int, Component> */
    private static function registryFields(): array
    {
        return [
            Grid::make(3)->components([
                TextInput::make('code')
                    ->label('Codice')
                    ->maxLength(255),
                TextInput::make('tax_code')
                    ->label('Codice fiscale')
                    ->maxLength(255),
                TextInput::make('vat_number')
                    ->label('Partita IVA')
                    ->maxLength(255),
            ]),

            Grid::make(2)->components([
                Toggle::make('is_customer')
                    ->label('Cliente')
                    ->default(true),
                Toggle::make('is_supplier')
                    ->label('Fornitore')
                    ->default(false),
            ]),

            Section::make('Sede operativa')
                ->columns(2)
                ->components([
                    TextInput::make('business_name')
                        ->label('Denominazione')
                        ->required()
                        ->maxLength(255)
                        ->columnSpanFull(),
                    TextInput::make('address_line1')
                        ->label('Indirizzo')
                        ->maxLength(255)
                        ->columnSpanFull(),
                    TextInput::make('postal_code')
                        ->label('CAP')
                        ->maxLength(255),
                    TextInput::make('city')
                        ->label('Citta')
                        ->maxLength(255),
                    TextInput::make('province')
                        ->label('Provincia')
                        ->maxLength(255),
                    TextInput::make('country')
                        ->label('Nazione')
                        ->default('Italia')
                        ->maxLength(255),
                ]),

            Section::make('Fattura elettronica')
                ->columns(3)
                ->components([
                    TextInput::make('sdi_recipient_code')
                        ->label('Recapito (Codice destinatario)')
                        ->maxLength(7),
                    TextInput::make('sdi_admin_ref')
                        ->label('Rif. amministrazione')
                        ->maxLength(255),
                    TextInput::make('pec')
                        ->label('PEC')
                        ->email()
                        ->maxLength(255),
                ]),

            Section::make('Contatti')
                ->columns(2)
                ->components([
                    TextInput::make('contact_person')
                        ->label('Referente')
                        ->maxLength(255),
                    TextInput::make('phone')
                        ->label('Telefono')
                        ->tel()
                        ->maxLength(255),
                    TextInput::make('mobile')
                        ->label('Cellulare')
                        ->tel()
                        ->maxLength(255),
                    TextInput::make('fax')
                        ->label('Fax')
                        ->maxLength(255),
                    TextInput::make('email')
                        ->label('Email')
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
                    ->label('Sconto (%)')
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(100),
                TextInput::make('price_list_code')
                    ->label('Listino')
                    ->maxLength(255),
                TextInput::make('credit_limit')
                    ->label('Fido')
                    ->numeric()
                    ->minValue(0),
                Select::make('agent_id')
                    ->label('Agente')
                    ->options(fn (): array => TimberAgent::query()
                        ->where('is_active', true)
                        ->orderBy('full_name')
                        ->pluck('full_name', 'id')
                        ->all())
                    ->searchable()
                    ->preload(),
                TextInput::make('payment_terms')
                    ->label('Pagamento')
                    ->maxLength(255),
                TextInput::make('vat_rate_code')
                    ->label('Aliquota IVA')
                    ->maxLength(255),
                TextInput::make('carriage_code')
                    ->label('Porto')
                    ->maxLength(255),
            ]),

            Section::make('Coordinate bancarie')
                ->columns(2)
                ->components([
                    TextInput::make('iban')
                        ->label('Coord. bancarie (IBAN)')
                        ->maxLength(34),
                    TextInput::make('our_bank')
                        ->label('Ns. banca')
                        ->maxLength(255),
                ]),

            Section::make('Dichiarazione di intento')
                ->columns(2)
                ->components([
                    TextInput::make('intent_declaration_number')
                        ->label('Dich. intento')
                        ->maxLength(255),
                    DatePicker::make('intent_declaration_date')
                        ->label('Del'),
                ]),

            Grid::make(2)->components([
                Toggle::make('send_documents_by_email')
                    ->label('Invia documenti tramite email'),
                Toggle::make('invoice_with_withholding')
                    ->label("Fattura con Rit. d'acconto"),
            ]),

            Section::make('In creazione documenti')
                ->components([
                    Textarea::make('document_notice')
                        ->label('Mostra avviso')
                        ->rows(2),
                    Textarea::make('document_note')
                        ->label('Inserisci nota')
                        ->rows(2),
                ]),
        ];
    }

    /** @return array<int, Component> */
    private static function extraFields(): array
    {
        return [
            Textarea::make('notes')
                ->label('Note')
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
                                ->label('Appunto')
                                ->required()
                                ->rows(2),
                        ])
                        ->addActionLabel('Aggiungi appunto')
                        ->defaultItems(0),
                ]),

            Toggle::make('is_active')
                ->label('Attivo')
                ->default(true),
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('Codice')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('business_name')
                    ->label('Denominazione')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('vat_number')
                    ->label('Partita IVA')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('city')
                    ->label('Citta')
                    ->toggleable(),
                Tables\Columns\IconColumn::make('is_customer')
                    ->label('Cliente')
                    ->boolean()
                    ->toggleable(),
                Tables\Columns\IconColumn::make('is_supplier')
                    ->label('Fornitore')
                    ->boolean()
                    ->toggleable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Attivo')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_customer')->label('Cliente'),
                Tables\Filters\TernaryFilter::make('is_supplier')->label('Fornitore'),
            ])
            ->actions([
                self::printSheetAction(),
                self::printLabelAction(),
                ViewAction::make(),
                EditAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
                RestoreBulkAction::make(),
                ForceDeleteBulkAction::make(),
            ]);
    }

    public static function printSheetAction(): Action
    {
        return Action::make('printSheet')
            ->label('Stampa scheda')
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
            ->label('Etichetta')
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
