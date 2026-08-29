<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Pages;

use App\Support\AccountFeatures;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use InvalidArgumentException;
use Modules\Timber\Actions\GenerateTimberPackagedUnitsAction;
use Modules\Timber\Filament\Pages\Concerns\NormalizesTimberConfigMaps;
use Modules\Timber\Models\TimberDerivedMaterial;

final class TimberPackagingPage extends Page
{
    use NormalizesTimberConfigMaps;

    protected static bool $isDiscovered = false;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-cube';

    protected static ?string $navigationLabel = 'Confezionamento e pallettizzazione';

    protected static ?string $title = 'Confezionamento e pallettizzazione';

    protected static ?string $slug = 'packaging';

    protected static ?int $navigationSort = 43;

    protected string $view = 'filament.timber.pages.packaging';

    public ?int $materialId = null;

    public string $packageTypeCode = 'bancale';

    public int $unitsCount = 1;

    public string $quantityPerUnit = '';

    public static function canAccess(): bool
    {
        return AccountFeatures::canAccessPanel(Auth::user(), 'timber');
    }

    public function mount(): void
    {
        $requestedMaterialId = (int) request()->query('material', 0);
        $this->materialId = $requestedMaterialId > 0 ? $requestedMaterialId : null;
    }

    public function generatePackagedUnits(): void
    {
        $material = $this->selectedMaterial();
        if (! $material instanceof TimberDerivedMaterial) {
            Notification::make()->title('Nessun materiale selezionato')->warning()->send();

            return;
        }

        try {
            $authId = Auth::id();

            app(GenerateTimberPackagedUnitsAction::class)->execute(
                material: $material,
                packageTypeCode: $this->packageTypeCode,
                unitsCount: $this->unitsCount,
                quantityPerUnit: $this->floatValue($this->quantityPerUnit),
                userId: is_int($authId) ? $authId : null,
            );
        } catch (InvalidArgumentException $exception) {
            Notification::make()->title($exception->getMessage())->warning()->send();

            return;
        }

        $this->unitsCount = 1;
        $this->quantityPerUnit = '';

        Notification::make()->title('Confezioni generate ed etichettate')->success()->send();
    }

    /**
     * @return array{materials: Collection<int, TimberDerivedMaterial>, selectedMaterial: TimberDerivedMaterial|null, packageTypes: array<string, string>}
     */
    protected function getViewData(): array
    {
        $materials = TimberDerivedMaterial::query()
            ->whereIn('status_code', ['pronto_da_confezionare', 'da_lavorare'])
            ->where('quantity', '>', 0)
            ->where('is_waste', false)
            ->latest()
            ->get();

        return [
            'materials' => $materials,
            'selectedMaterial' => $this->selectedMaterial($materials),
            'packageTypes' => $this->configMap('timber.package_types'),
        ];
    }

    /**
     * @param  Collection<int, TimberDerivedMaterial>|null  $materials
     */
    private function selectedMaterial($materials = null): ?TimberDerivedMaterial
    {
        if ($materials instanceof Collection) {
            /** @var TimberDerivedMaterial|null $material */
            $material = $materials->firstWhere('id', $this->materialId);

            return $material;
        }

        if ($this->materialId === null) {
            return null;
        }

        return TimberDerivedMaterial::query()->find($this->materialId);
    }

    private function floatValue(string $value): float
    {
        return (float) str_replace(',', '.', trim($value));
    }
}
