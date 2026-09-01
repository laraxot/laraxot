<?php

declare(strict_types=1);

namespace Modules\Wts\Filament\Pages;

use App\Support\AccountFeatures;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Modules\Platform\Filament\Pages\GatedXotBasePage;
use Modules\Wts\Actions\GenerateWtsOutputLabelPdfAction;
use Modules\Wts\Models\WtsDerivedMaterial;
use Modules\Wts\Models\WtsTechnicalSheetSnapshot;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class WtsOutputsPage extends GatedXotBasePage
{
    protected static bool $isDiscovered = false;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-squares-plus';

    protected static ?string $navigationLabel = 'UI-08 - Materiali prodotti';

    protected static ?string $title = 'UI-08 - Generazione materiali derivati';

    protected static ?string $slug = 'outputs';

    protected static ?int $navigationSort = 8;

    protected string $view = 'wts::filament.pages.outputs';

    public static function canAccess(): bool
    {
        return AccountFeatures::canAccessWtsPage(Auth::user(), self::class);
    }

    public function updateMaterialTechnicalSheet(int|string $materialId, string $snapshotId): void
    {
        $material = WtsDerivedMaterial::query()->find($materialId);
        if ($material === null) {
            return;
        }

        $material->forceFill([
            'technical_sheet_snapshot_id' => $snapshotId !== '' ? (int) $snapshotId : null,
        ])->save();

        Notification::make()
            ->title('Scheda tecnica output aggiornata')
            ->success()
            ->send();
    }

    public function reprintOutputLabel(int|string $materialId): ?StreamedResponse
    {
        $material = WtsDerivedMaterial::query()->with('labelPrints')->find($materialId);
        if ($material === null) {
            return null;
        }

        $authId = Auth::id();
        $material->labelPrints()->create([
            'label_code' => $material->output_code.'-R'.str_pad((string) ($material->labelPrints->count() + 1), 2, '0', STR_PAD_LEFT),
            'qr_payload' => $material->output_code,
            'print_reason_code' => 'output_reprint',
            'printed_by_user_id' => is_int($authId) || is_string($authId) ? $authId : null,
            'printed_at' => now(),
        ]);

        Notification::make()
            ->title('Etichetta output ristampata')
            ->success()
            ->send();

        $pdf = app(GenerateWtsOutputLabelPdfAction::class)->execute($material);

        return response()->streamDownload(
            static function () use ($pdf): void {
                echo $pdf;
            },
            sprintf('etichetta-output-%s.pdf', $material->output_code),
            ['Content-Type' => 'application/pdf'],
        );
    }

    /**
     * @return array{
     *     outputs: Collection<int, WtsDerivedMaterial>,
     *     snapshots: Collection<int, WtsTechnicalSheetSnapshot>
     * }
     */
    protected function getViewData(): array
    {
        return [
            'outputs' => WtsDerivedMaterial::query()->with(['technicalSheetSnapshot', 'processingStep', 'labelPrints'])->latest()->get(),
            'snapshots' => WtsTechnicalSheetSnapshot::query()->orderBy('sheet_code')->get(),
        ];
    }
}
