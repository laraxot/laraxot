<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Pages;

use App\Support\AccountFeatures;
use Filament\Pages\Page;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Modules\Timber\Actions\BuildTimberQrPayloadAction;
use Modules\Timber\Actions\GenerateTimberLabelPdfAction;
use Modules\Timber\Actions\RenderTimberQrSvgAction;
use Modules\Timber\Models\TimberDerivedMaterial;
use Modules\Timber\Models\TimberLabelPrint;
use Modules\Timber\Models\TimberLot;
use Modules\Timber\Models\TimberPackagedUnit;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class TimberLabelsPage extends Page
{
    protected static bool $isDiscovered = false;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-qr-code';

    protected static ?string $navigationLabel = 'TB-07 - Etichette QR';

    protected static ?string $title = 'TB-07 - Generazione ed etichette QR';

    protected static ?string $slug = 'labels';

    protected static ?int $navigationSort = 7;

    protected string $view = 'filament.timber.pages.labels';

    public static function canAccess(): bool
    {
        return AccountFeatures::canAccessPanel(Auth::user(), 'timber');
    }

    public function downloadLotLabel(int $lotId): ?StreamedResponse
    {
        $lot = TimberLot::query()->find($lotId);

        return $lot instanceof TimberLot ? $this->downloadLabel($lot, $lot->lot_code) : null;
    }

    public function downloadMaterialLabel(int $materialId): ?StreamedResponse
    {
        $material = TimberDerivedMaterial::query()->find($materialId);

        return $material instanceof TimberDerivedMaterial ? $this->downloadLabel($material, $material->output_code) : null;
    }

    public function downloadPackagedUnitLabel(int $packagedUnitId): ?StreamedResponse
    {
        $unit = TimberPackagedUnit::query()->find($packagedUnitId);

        return $unit instanceof TimberPackagedUnit ? $this->downloadLabel($unit, $unit->package_code) : null;
    }

    /**
     * @return array{
     *     lots: Collection<int, TimberLot>,
     *     materials: Collection<int, TimberDerivedMaterial>,
     *     packagedUnits: Collection<int, TimberPackagedUnit>,
     *     qrPreview: string
     * }
     */
    protected function getViewData(): array
    {
        $qrSvgAction = app(RenderTimberQrSvgAction::class);

        return [
            'lots' => TimberLot::query()->latest()->limit(15)->get(),
            'materials' => TimberDerivedMaterial::query()->latest()->limit(15)->get(),
            'packagedUnits' => TimberPackagedUnit::query()->with('derivedMaterial')->latest()->limit(15)->get(),
            'qrPreview' => $qrSvgAction->dataUri('TMB|LOTTO=LOT-2026-0001|SPECIE=abete|DATA='.now()->toDateString(), 220),
        ];
    }

    private function downloadLabel(Model $labelable, string $code): StreamedResponse
    {
        $payloadAction = app(BuildTimberQrPayloadAction::class);
        $qrSvgAction = app(RenderTimberQrSvgAction::class);

        $labelable->morphMany(TimberLabelPrint::class, 'labelable')->create([
            'label_code' => $code,
            'qr_payload' => $payloadAction->execute($labelable),
            'print_reason_code' => 'reprint',
            'printed_by_user_id' => Auth::id(),
            'printed_at' => now(),
        ]);

        $pdf = app(GenerateTimberLabelPdfAction::class)->execute($labelable);

        return response()->streamDownload(
            static function () use ($pdf): void {
                echo $pdf;
            },
            sprintf('etichetta-%s.pdf', $code),
            ['Content-Type' => 'application/pdf'],
        );
    }
}
