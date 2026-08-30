<?php

declare(strict_types=1);

namespace Modules\MetalWork\Filament\Pages;

use Filament\Pages\Dashboard;
use Illuminate\Support\Collection;
use Modules\MetalWork\Models\MetalDrawing;
use Modules\MetalWork\Models\MetalPartEstimate;
use Modules\MetalWork\Models\MetalRawMaterial;
use Modules\MetalWork\Models\MetalSupplierPrice;

/**
 * Landing page of the panel: what has been quoted lately, and whether the price
 * list behind those quotations is still fresh — a material last invoiced months
 * ago is quoting at a price nobody would honour today.
 *
 * Extends Filament's Dashboard, like the other panels' home pages do, so it is
 * mounted on the panel root. A panel with no page on its root falls back to
 * Filament's redirect controller, which breaks once Livewire has swapped the
 * `redirect` binding — a 500 on /metalworks with no page of its own to blame.
 */
final class MetalWorkDashboard extends Dashboard
{
    protected static bool $isDiscovered = false;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-home';

    protected static ?int $navigationSort = 1;

    protected string $view = 'metalwork::filament.pages.dashboard';

    public static function getNavigationGroup(): string
    {
        return (string) __('metalwork::metalwork.navigation');
    }

    public static function getNavigationLabel(): string
    {
        return (string) __('metalwork::metalwork.panel.dashboard');
    }

    public function getTitle(): string
    {
        return (string) __('metalwork::metalwork.panel.dashboard');
    }

    /**
     * @return array{
     *     stats: array<string, int|string>,
     *     estimates: Collection<int, MetalPartEstimate>,
     *     drawings: Collection<int, MetalDrawing>,
     *     lastPriceUpdate: ?string
     * }
     */
    protected function getViewData(): array
    {
        $lastPrice = MetalSupplierPrice::query()->orderByDesc('recorded_on')->first();

        return [
            'stats' => [
                'drawings' => MetalDrawing::query()->count(),
                'estimates' => MetalPartEstimate::query()->count(),
                'materials' => MetalRawMaterial::query()->count(),
                'prices' => MetalSupplierPrice::query()->count(),
            ],
            'estimates' => MetalPartEstimate::query()->with('drawing')->latest('id')->limit(8)->get(),
            'drawings' => MetalDrawing::query()->latest('id')->limit(8)->get(),
            'lastPriceUpdate' => $lastPrice?->recorded_on?->format('d/m/Y'),
        ];
    }
}
