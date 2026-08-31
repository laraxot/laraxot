<?php

declare(strict_types=1);

namespace Modules\Wts\Queries;

use Modules\Wts\Models\WtsUnit;
use Illuminate\Database\Eloquent\Collection;

final class GetWtsWorkQueueOverviewQuery
{
    /**
     * @return Collection<int, WtsUnit>
     */
    public function execute(?string $view = null, ?string $search = null, ?string $technicalSearch = null): Collection
    {
        /** @var Collection<int, WtsUnit> $units */
        $units = WtsUnit::query()
            ->with(['inboundLot.inboundRecord.technicalSheetSnapshot', 'technicalSheetSnapshot', 'processingSteps'])
            ->where('status_code', 'available')
            ->latest('updated_at')
            ->get();

        $normalizedView = trim((string) $view);
        $units = $units->filter(static function (WtsUnit $unit) use ($normalizedView): bool {
            return $normalizedView === '' || $unit->current_area_code === $normalizedView;
        });

        $normalizedSearch = trim((string) $search);
        $units = $units->filter(function (WtsUnit $unit) use ($normalizedSearch): bool {
            if ($normalizedSearch === '') {
                return true;
            }

            $sheet = $unit->technicalSheetSnapshot;

            return $this->contains($sheet?->sheet_code, $normalizedSearch)
                || $this->contains($sheet?->waste_code, $normalizedSearch)
                || $this->contains($sheet?->production_description, $normalizedSearch)
                || $this->contains($sheet?->packaging_description, $normalizedSearch);
        });

        $normalizedTechnicalSearch = trim((string) $technicalSearch);

        return $units
            ->filter(function (WtsUnit $unit) use ($normalizedTechnicalSearch): bool {
                if ($normalizedTechnicalSearch === '') {
                    return true;
                }

                return $this->contains($unit->unit_code, $normalizedTechnicalSearch)
                    || $this->contains($unit->qr_code, $normalizedTechnicalSearch)
                    || $this->contains($unit->inboundLot?->lot_code, $normalizedTechnicalSearch);
            })
            ->values();
    }

    private function contains(?string $haystack, string $needle): bool
    {
        return $haystack !== null && str_contains(mb_strtolower($haystack), mb_strtolower($needle));
    }
}
