<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Pages;

use App\Support\AccountFeatures;
use Filament\Notifications\Notification;
use Modules\Xot\Filament\Pages\XotBasePage;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use InvalidArgumentException;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;
use Modules\Timber\Actions\RegisterTimberPurchaseAction;
use Modules\Timber\Filament\Pages\Concerns\NormalizesTimberConfigMaps;
use Modules\Timber\Models\TimberPurchaseRecord;
use Modules\Timber\Models\TimberSupplier;
use Modules\Timber\Models\TimberTransporter;
use Modules\Timber\Support\WoodSpeciesCatalog;

final class TimberPurchasesPage extends XotBasePage
{
    use NormalizesTimberConfigMaps;
    use WithFileUploads;

    protected static bool $isDiscovered = false;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-document-plus';

    protected static ?string $navigationLabel = 'TB-01 - Registrazione acquisto';

    protected static ?string $title = 'TB-01 - Registrazione acquisto legname';

    protected static ?string $slug = 'purchases';

    protected static ?int $navigationSort = 1;

    protected string $view = 'timber::filament.pages.purchases';

    public ?int $supplierId = null;

    public ?int $transporterId = null;

    public string $originForest = '';

    public string $ddtNumber = '';

    public string $ddtDate = '';

    public string $woodSpecies = '';

    public string $quantity = '';

    public string $unitOfMeasure = 'quintali';

    public string $grossWeight = '';

    public string $netWeight = '';

    public string $unitPurchasePrice = '';

    public string $purchasePriceUnit = 'quintale';

    public string $certifications = '';

    public string $notes = '';

    public ?TemporaryUploadedFile $photo = null;

    public static function canAccess(): bool
    {
        return AccountFeatures::canAccessPanel(Auth::user(), 'timber');
    }

    public function mount(): void
    {
        $this->ddtDate = now()->toDateString();

        // Default to the first species actually in the catalog, so the option
        // shown as selected always equals what gets submitted. A hardcoded
        // default could be absent from the catalog: the browser would then show
        // the first option while the stale default was silently saved.
        $speciesOptions = WoodSpeciesCatalog::options();
        if (! array_key_exists($this->woodSpecies, $speciesOptions)) {
            $this->woodSpecies = (string) (array_key_first($speciesOptions) ?? '');
        }
    }

    public function registerPurchase(): void
    {
        $supplier = TimberSupplier::query()->find($this->supplierId);
        if (! $supplier instanceof TimberSupplier) {
            Notification::make()->title('Seleziona un fornitore valido')->warning()->send();

            return;
        }

        $transporter = $this->transporterId !== null ? TimberTransporter::query()->find($this->transporterId) : null;

        $photoPath = null;
        if ($this->photo !== null) {
            $storedPath = $this->photo->store('timber/purchases', 'public');
            $photoPath = $storedPath !== false ? $storedPath : null;
        }

        try {
            $authId = Auth::id();

            app(RegisterTimberPurchaseAction::class)->execute(
                supplier: $supplier,
                transporter: $transporter,
                originForest: $this->originForest,
                ddtNumber: $this->ddtNumber,
                ddtDate: $this->ddtDate !== '' ? $this->ddtDate : null,
                woodSpecies: $this->woodSpecies,
                quantity: $this->floatValue($this->quantity),
                unitOfMeasure: $this->unitOfMeasure,
                grossWeight: $this->nullableFloatValue($this->grossWeight),
                netWeight: $this->nullableFloatValue($this->netWeight),
                unitPurchasePrice: $this->nullableFloatValue($this->unitPurchasePrice),
                certifications: $this->certifications,
                photoPath: $photoPath,
                notes: $this->notes,
                userId: is_int($authId) ? $authId : null,
                purchasePriceUnit: $this->purchasePriceUnit,
            );
        } catch (InvalidArgumentException $exception) {
            Notification::make()->title($exception->getMessage())->warning()->send();

            return;
        }

        $this->reset(['originForest', 'ddtNumber', 'quantity', 'grossWeight', 'netWeight', 'unitPurchasePrice', 'certifications', 'notes', 'photo']);
        $this->ddtDate = now()->toDateString();

        Notification::make()->title('Acquisto registrato e lotto generato')->success()->send();
    }

    /**
     * @return array{
     *     purchaseRecords: Collection<int, TimberPurchaseRecord>,
     *     suppliers: Collection<int, TimberSupplier>,
     *     transporters: Collection<int, TimberTransporter>,
     *     woodSpeciesOptions: array<string, string>,
     *     unitsOfMeasure: array<string, string>,
     *     purchasePriceUnits: array<string, string>
     * }
     */
    protected function getViewData(): array
    {
        return [
            'purchaseRecords' => TimberPurchaseRecord::query()->with(['supplier', 'transporter', 'lot'])->latest()->limit(20)->get(),
            'suppliers' => TimberSupplier::query()->where('is_active', true)->orderBy('legal_name')->get(),
            'transporters' => TimberTransporter::query()->where('is_active', true)->orderBy('legal_name')->get(),
            'woodSpeciesOptions' => WoodSpeciesCatalog::options(),
            'unitsOfMeasure' => $this->configMap('timber.units_of_measure'),
            'purchasePriceUnits' => $this->configMap('timber.purchase_price_units'),
        ];
    }

    private function floatValue(string $value): float
    {
        return (float) str_replace(',', '.', trim($value));
    }

    private function nullableFloatValue(string $value): ?float
    {
        $normalized = trim($value);

        return $normalized === '' ? null : $this->floatValue($normalized);
    }
}
