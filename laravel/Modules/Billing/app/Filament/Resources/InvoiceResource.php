<?php

declare(strict_types=1);

namespace Modules\Billing\Filament\Resources;

use App\Support\AccountFeatures;
use Filament\Actions\Action;
use Filament\Support\Facades\FilamentIcon;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Billing\Models\Invoice;
use Modules\User\Models\User;
use Modules\Xot\Filament\Resources\XotBaseResource;

/**
 * Fatture cliente — ciclo attivo.
 *
 * Schema form → {@see InvoiceResource\Schemas\InvoiceForm}
 * Schema infolist → {@see InvoiceResource\Schemas\InvoiceInfolist}
 * Tabella → {@see InvoiceResource\Tables\InvoicesTable}
 */
final class InvoiceResource extends XotBaseResource
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

    public static function canEdit(mixed $record): bool
    {
        if (! self::isAvailableInCurrentPanel()) {
            return false;
        }

        if (request()->is('customer/*')) {
            return false;
        }

        return parent::canEdit($record);
    }

    public static function canView(mixed $record): bool
    {
        return self::isAvailableInCurrentPanel() && parent::canView($record);
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
