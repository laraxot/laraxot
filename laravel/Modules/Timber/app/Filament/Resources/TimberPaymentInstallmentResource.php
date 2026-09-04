<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources;

use App\Support\AccountFeatures;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Section;
use Illuminate\Support\Facades\Auth;
use Modules\Timber\Actions\MarkTimberInstallmentPaidAction;
use Modules\Timber\Actions\SendTimberPaymentRemindersAction;
use Modules\Timber\Filament\Resources\TimberPaymentInstallmentResource\Pages;
use Modules\Timber\Models\TimberPaymentInstallment;
use Modules\Timber\Support\TimberConfigMap;
use Modules\Xot\Filament\Resources\XotBaseResource;

final class TimberPaymentInstallmentResource extends XotBaseResource
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

    /** @return array<int|string, Component> */
    public static function getFormSchema(): array
    {
        return [
            Section::make()
                ->columns(2)
                ->components([
                    Select::make('invoice_id')
                        ->relationship('invoice', 'number')
                        ->searchable()
                        ->preload(),
                    Select::make('customer_id')
                        ->relationship('customer', 'business_name')
                        ->searchable()
                        ->preload(),
                    Select::make('direction')
                        ->options(['in' => 'Entrata (incasso)', 'out' => 'Uscita (pagamento)'])
                        ->default('in')
                        ->required(),
                    DatePicker::make('due_date')
                        ->required(),
                    TextInput::make('amount')
                        ->numeric()
                        ->minValue(0)
                        ->required(),
                    Select::make('payment_method_code')
                        ->options(TimberConfigMap::get('timber.payment_methods')),
                    Select::make('status_code')
                        ->options(TimberConfigMap::get('timber.payment_statuses'))
                        ->default('da_saldare')
                        ->required(),
                    Textarea::make('notes')
                        ->rows(2)
                        ->columnSpanFull(),
                ]),
        ];
    }

    public static function markPaidAction(): Action
    {
        return Action::make('markPaid')
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
            ->icon('heroicon-o-bell-alert')
            ->color('warning')
            ->requiresConfirmation()
            ->action(function (): void {
                $count = app(SendTimberPaymentRemindersAction::class)->execute();
                Notification::make()->title($count.' solleciti inviati')->success()->send();
            });
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
