<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Filament\Resources;

use App\Support\AccountFeatures;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Modules\TimberBilling\Actions\MarkTimberInstallmentPaidAction;
use Modules\TimberBilling\Actions\SendTimberPaymentRemindersAction;
use Modules\TimberBilling\Filament\Resources\TimberPaymentInstallmentResource\Pages;
use Modules\TimberBilling\Models\TimberPaymentInstallment;

final class TimberPaymentInstallmentResource extends Resource
{
    protected static ?string $model = TimberPaymentInstallment::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $navigationLabel = 'Scadenzario / Pagamenti';

    protected static ?int $navigationSort = 22;

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
        return 'Scadenza';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Scadenzario / Pagamenti';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()
                ->columns(2)
                ->components([
                    Select::make('invoice_id')
                        ->label('Fattura')
                        ->relationship('invoice', 'number')
                        ->searchable()
                        ->preload(),
                    Select::make('customer_id')
                        ->label('Cliente')
                        ->relationship('customer', 'business_name')
                        ->searchable()
                        ->preload(),
                    Select::make('direction')
                        ->label('Tipo')
                        ->options(['in' => 'Entrata (incasso)', 'out' => 'Uscita (pagamento)'])
                        ->default('in')
                        ->required(),
                    DatePicker::make('due_date')
                        ->label('Scadenza')
                        ->required(),
                    TextInput::make('amount')
                        ->label('Importo')
                        ->numeric()
                        ->minValue(0)
                        ->required(),
                    Select::make('payment_method_code')
                        ->label('Metodo')
                        ->options(self::configMap('timberbilling.payment_methods')),
                    Select::make('status_code')
                        ->label('Stato')
                        ->options(self::configMap('timberbilling.payment_statuses'))
                        ->default('da_saldare')
                        ->required(),
                    Textarea::make('notes')
                        ->label('Note')
                        ->rows(2)
                        ->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('due_date', 'asc')
            ->columns([
                Tables\Columns\TextColumn::make('due_date')
                    ->label('Scadenza')
                    ->date('d/m/Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('invoice.number')
                    ->label('Documento')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('customer.business_name')
                    ->label('Cliente')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('direction')
                    ->label('Tipo')
                    ->formatStateUsing(fn (?string $state): string => $state === 'out' ? 'Uscita' : 'Entrata')
                    ->badge()
                    ->color(fn (?string $state): string => $state === 'out' ? 'warning' : 'success'),
                Tables\Columns\TextColumn::make('amount')
                    ->label('Importo')
                    ->money('EUR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('payment_method_code')
                    ->label('Metodo')
                    ->formatStateUsing(fn (?string $state): string => self::configMap('timberbilling.payment_methods')[$state] ?? (string) $state)
                    ->toggleable(),
                Tables\Columns\TextColumn::make('status_code')
                    ->label('Stato')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => self::configMap('timberbilling.payment_statuses')[$state] ?? (string) $state)
                    ->color(fn (?string $state): string => match ($state) {
                        'saldato' => 'success',
                        'sollecitato' => 'warning',
                        default => 'gray',
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status_code')
                    ->label('Stato')
                    ->options(self::configMap('timberbilling.payment_statuses')),
                Tables\Filters\SelectFilter::make('payment_method_code')
                    ->label('Metodo')
                    ->options(self::configMap('timberbilling.payment_methods')),
                Tables\Filters\SelectFilter::make('direction')
                    ->label('Tipo')
                    ->options(['in' => 'Entrate', 'out' => 'Uscite']),
                Tables\Filters\Filter::make('overdue')
                    ->label('Solo scadute da saldare')
                    ->query(fn (Builder $query): Builder => $query->where('status_code', '!=', 'saldato')->whereDate('due_date', '<=', now())),
                Tables\Filters\Filter::make('riba_to_issue')
                    ->label('RIBA da emettere')
                    ->query(fn (Builder $query): Builder => $query->where('payment_method_code', 'riba')->where('status_code', 'da_saldare')),
            ])
            ->actions([
                self::markPaidAction(),
                EditAction::make(),
            ]);
    }

    public static function markPaidAction(): Action
    {
        return Action::make('markPaid')
            ->label('Segna pagato')
            ->icon('heroicon-o-check-circle')
            ->color('success')
            ->requiresConfirmation()
            ->visible(fn (TimberPaymentInstallment $record): bool => ! $record->isSettled())
            ->action(function (TimberPaymentInstallment $record): void {
                app(MarkTimberInstallmentPaidAction::class)->execute($record);
                Notification::make()->title('Rata segnata come saldata')->success()->send();
            });
    }

    public static function sendRemindersAction(): Action
    {
        return Action::make('sendReminders')
            ->label('Invia solleciti')
            ->icon('heroicon-o-bell-alert')
            ->color('warning')
            ->requiresConfirmation()
            ->action(function (): void {
                $count = app(SendTimberPaymentRemindersAction::class)->execute();
                Notification::make()->title($count.' solleciti inviati')->success()->send();
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
            'index' => Pages\ListTimberPaymentInstallments::route('/'),
            'create' => Pages\CreateTimberPaymentInstallment::route('/create'),
            'edit' => Pages\EditTimberPaymentInstallment::route('/{record}/edit'),
        ];
    }
}
