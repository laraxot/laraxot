<?php

declare(strict_types=1);

namespace Modules\Quotation\Filament\Resources;

use App\Support\AccountFeatures;
use App\Support\Concerns\HasAccountFeatureVisibility;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Customer\Models\Customer;
use Modules\Quotation\Filament\Resources\QuotationResource\Pages;
use Modules\Quotation\Filament\Resources\QuotationResource\RelationManagers\AttachmentsRelationManager;
use Modules\Quotation\Filament\Resources\QuotationResource\RelationManagers\ItemsRelationManager;
use Modules\Quotation\Models\Quotation;
use Modules\Xot\Filament\Resources\XotBaseResource;

use function Safe\json_encode;

final class QuotationResource extends XotBaseResource
{
    use HasAccountFeatureVisibility;

    protected static ?string $model = Quotation::class;

    /**
     * Ricerca globale: l'operatore cerca un codice o un nome senza sapere in che
     * sezione si trova. I dettagli evitano di dover aprire il record per capire
     * se e' quello giusto.
     */
    protected static ?string $recordTitleAttribute = 'code';

    /**
     * @return array<int, string>
     */
    public static function getGloballySearchableAttributes(): array
    {
        return ['code', 'title', 'customer.legal_name'];
    }

    /**
     * @return array<string, string|null>
     */
    public static function getGlobalSearchResultDetails(Model $record): array
    {
        /** @var Quotation $record */
        $details = [];

        $customerName = $record->customer?->legal_name;
        if (is_string($customerName) && $customerName !== '') {
            $details[__('quotations.fields.customer')] = $customerName;
        }

        $statusCode = $record->status_code;
        if ($statusCode !== '') {
            $details[__('quotations.fields.status')] = (string) __('quotations.statuses.'.$statusCode);
        }

        return $details;
    }

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-document-text';

    /** Read reflectively by HasAccountFeatureVisibility via defined()/constant(). */
    /** @var string */
    private const ACCOUNT_FEATURE_ADMIN = 'admin.quotations';

    /** Read reflectively by HasAccountFeatureVisibility via defined()/constant(). */
    /** @var string */
    private const ACCOUNT_FEATURE_OPERATOR = 'operator.quotations';

    public static function getNavigationGroup(): string
    {
        return __('quotations.navigation');
    }

    public static function getModelLabel(): string
    {
        return __('quotations.quotation');
    }

    public static function getPluralModelLabel(): string
    {
        return __('quotations.quotations');
    }

    public static function getRelations(): array
    {
        return [
            ItemsRelationManager::class,
            AttachmentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListQuotations::route('/'),
            'create' => Pages\CreateQuotation::route('/create'),
            'view' => Pages\ViewQuotation::route('/{record}'),
            'edit' => Pages\EditQuotation::route('/{record}/edit'),
        ];
    }

    protected static function isAvailableInCurrentPanel(): bool
    {
        return AccountFeatures::currentPanelAllowsFeature(
            Auth::user(),
            adminFeature: self::ACCOUNT_FEATURE_ADMIN,
            operatorFeature: self::ACCOUNT_FEATURE_OPERATOR,
            adminDefault: true,
            operatorDefault: true,
            fallback: false,
        );
    }

    /**
     * Margine in euro e percento, leggibile in lista: quanto resta del
     * ricavo dopo i costi. Sconosciuto finché non c'è un costo caricato.
     */
    public static function marginSummary(?Quotation $quotation): string
    {
        if (! $quotation instanceof Quotation || ! $quotation->exists) {
            return (string) __('quotations.margin.unknown');
        }

        $cost = (float) $quotation->cost_total;
        $revenue = (float) $quotation->taxable_amount > 0.0
            ? (float) $quotation->taxable_amount
            : (float) $quotation->subtotal;

        if ($cost <= 0.0) {
            return (string) __('quotations.margin.unknown');
        }

        $margin = $revenue - $cost;
        $percent = $revenue > 0.0 ? ($margin / $revenue) * 100 : 0.0;

        return sprintf(
            '%s € (%s%%)',
            number_format($margin, 2, ',', '.'),
            number_format($percent, 1, ',', '.'),
        );
    }

    /**
     * Ambra sotto il 15%% di margine, rosso in perdita: un lavoro venduto
     * sotto costo deve urlare dalla lista.
     */
    public static function marginColour(Quotation $quotation): string
    {
        $cost = (float) $quotation->cost_total;
        $revenue = (float) $quotation->taxable_amount > 0.0
            ? (float) $quotation->taxable_amount
            : (float) $quotation->subtotal;

        if ($cost <= 0.0 || $revenue <= 0.0) {
            return 'gray';
        }

        $percent = (($revenue - $cost) / $revenue) * 100;

        return match (true) {
            $percent < 0 => 'danger',
            $percent < 15 => 'warning',
            default => 'success',
        };
    }
}
