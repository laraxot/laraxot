<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Queries;

use Illuminate\Support\Collection;
use Modules\TimberBilling\Models\TimberDerivedMaterial;
use Modules\TimberBilling\Models\TimberLot;
use Modules\TimberBilling\Models\TimberPackagedUnit;

final class GetTimberInventoryOverviewQuery
{
    /**
     * @return array{lots: Collection<int, TimberLot>, materials: Collection<int, TimberDerivedMaterial>, packagedUnits: Collection<int, TimberPackagedUnit>, stats: array<string, int|float>}
     */
    public function execute(?string $woodSpecies = null, ?string $statusCode = null): array
    {
        $normalizedSpecies = trim((string) $woodSpecies);
        $normalizedStatus = trim((string) $statusCode);

        $lots = TimberLot::query()
            ->when($normalizedSpecies !== '', fn ($query) => $query->where('wood_species', $normalizedSpecies))
            ->when($normalizedStatus !== '', fn ($query) => $query->where('status_code', $normalizedStatus))
            ->get();

        $materials = TimberDerivedMaterial::query()
            ->when($normalizedSpecies !== '', fn ($query) => $query->where('wood_species', $normalizedSpecies))
            ->when($normalizedStatus !== '', fn ($query) => $query->where('status_code', $normalizedStatus))
            ->get();

        $packagedUnits = TimberPackagedUnit::query()
            ->with('derivedMaterial')
            ->when($normalizedStatus !== '', fn ($query) => $query->where('status_code', $normalizedStatus))
            ->get();

        $stats = [
            'lots_available' => $lots->where('status_code', 'available')->count(),
            'materials_to_process' => $materials->where('status_code', 'da_lavorare')->count(),
            'materials_drying' => $materials->where('status_code', 'in_essiccazione')->count(),
            'materials_ready_to_package' => $materials->where('status_code', 'pronto_da_confezionare')->count(),
            'materials_ready_to_sell' => $materials->where('status_code', 'pronto_per_vendita')->count(),
            'packaged_units_ready' => $packagedUnits->where('status_code', 'pronto_per_vendita')->count(),
            'total_waste_quantity' => $materials->where('is_waste', true)->sum(static fn (TimberDerivedMaterial $material): float => (float) $material->quantity),
        ];

        return [
            'lots' => $lots,
            'materials' => $materials,
            'packagedUnits' => $packagedUnits,
            'stats' => $stats,
        ];
    }
}
