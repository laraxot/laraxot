<?php

declare(strict_types=1);

namespace Modules\Timber\Queries;

use Illuminate\Support\Collection;
use Modules\Timber\Models\TimberDerivedMaterial;
use Modules\Timber\Models\TimberLot;

final class GetTimberYardOverviewQuery
{
    /**
     * @return array{lots: Collection<int, TimberLot>, materials: Collection<int, TimberDerivedMaterial>}
     */
    public function execute(?string $areaCode = null, ?string $search = null): array
    {
        $normalizedArea = trim((string) $areaCode);
        $normalizedSearch = trim((string) $search);

        $lots = TimberLot::query()
            ->with('purchaseRecord.supplier')
            ->where('status_code', 'available')
            ->when($normalizedArea !== '', fn ($query) => $query->where('current_area_code', $normalizedArea))
            ->when($normalizedSearch !== '', function ($query) use ($normalizedSearch): void {
                $query->where(function ($sub) use ($normalizedSearch): void {
                    $sub->where('lot_code', 'like', "%{$normalizedSearch}%")
                        ->orWhere('wood_species', 'like', "%{$normalizedSearch}%");
                });
            })
            ->latest('updated_at')
            ->get();

        $materials = TimberDerivedMaterial::query()
            ->with('processingStep')
            ->where('quantity', '>', 0)
            ->where('is_waste', false)
            ->when($normalizedArea !== '', fn ($query) => $query->where('current_area_code', $normalizedArea))
            ->when($normalizedSearch !== '', function ($query) use ($normalizedSearch): void {
                $query->where(function ($sub) use ($normalizedSearch): void {
                    $sub->where('output_code', 'like', "%{$normalizedSearch}%")
                        ->orWhere('wood_species', 'like', "%{$normalizedSearch}%");
                });
            })
            ->latest('updated_at')
            ->get();

        return [
            'lots' => $lots,
            'materials' => $materials,
        ];
    }
}
