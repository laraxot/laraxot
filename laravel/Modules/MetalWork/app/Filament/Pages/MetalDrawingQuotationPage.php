<?php

declare(strict_types=1);

namespace Modules\MetalWork\Filament\Pages;

use Filament\Notifications\Notification;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Modules\Customer\Models\Customer;
use Modules\MetalWork\Actions\CreateQuotationFromMetalPartEstimateAction;
use Modules\MetalWork\Actions\EstimateFromBillOfMaterialsAction;
use Modules\MetalWork\Actions\EstimateMetalPartCostAction;
use Modules\MetalWork\Actions\ImportStepDrawingAction;
use Modules\MetalWork\Actions\StoreMetalPartEstimateAction;
use Modules\MetalWork\Datas\MetalPartCosting;
use Modules\MetalWork\Models\MetalDrawing;
use Modules\MetalWork\Models\MetalDrawingComponent;
use Modules\MetalWork\Models\MetalRawMaterial;
use Modules\Platform\Filament\Concerns\GatesPageNavigation;
use Modules\Xot\Filament\Pages\XotBasePage;
use Modules\Quotation\Filament\Resources\QuotationResource;
use Throwable;

use function Safe\file_get_contents;

/**
 * The whole flow on one screen: drop the STEP file, check what was read out of
 * it, correct the few numbers the file cannot give (which steel, how full the
 * bounding box really is, how much offcut), then turn it into a quotation.
 *
 * The costing is recomputed on every render rather than stored in the component:
 * the operator changes one input and wants to watch the price move. Only the
 * "save" step freezes it.
 */
final class MetalDrawingQuotationPage extends XotBasePage
{
    use GatesPageNavigation;

    use WithFileUploads;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-document-arrow-up';

    protected static ?string $slug = 'drawing-quotation';

    protected static ?int $navigationSort = 5;

    protected string $view = 'metalwork::filament.pages.drawing-quotation';

    /** @var list<string> */
    private const array ACCEPTED_EXTENSIONS = ['stp', 'step'];

    /** @var string */
    private const string DRAWING_DISK = 'local';

    /** @var string */
    private const string DRAWING_DIRECTORY = 'metalworks-drawings';

    /** @var mixed */
    public $drawingFile = null;

    public ?int $drawingId = null;

    public ?int $rawMaterialId = null;

    public string $quantity = '1';

    public string $thicknessMm = '';

    public string $occupancyPercent = '';

    public string $scrapPercent = '';

    public string $markupPercent = '';

    public string $manualSalePrice = '';

    public ?int $customerId = null;

    public static function getNavigationGroup(): string
    {
        return (string) __('metalwork::metalwork.navigation');
    }

    public static function getNavigationLabel(): string
    {
        return (string) __('metalwork::metalwork.quotation.title');
    }

    public function getTitle(): string
    {
        return (string) __('metalwork::metalwork.quotation.title');
    }

    public function mount(): void
    {
        $this->occupancyPercent = $this->configString('default_occupancy_percent');
        $this->scrapPercent = $this->configString('default_scrap_percent');
    }

    public function parseDrawing(): void
    {
        $this->validate([
            'drawingFile' => ['required', 'file', 'max:51200'],
        ]);

        $originalName = $this->uploadedFileName();

        if (! in_array(mb_strtolower(pathinfo($originalName, PATHINFO_EXTENSION)), self::ACCEPTED_EXTENSIONS, true)) {
            $this->addError('drawingFile', (string) __('metalwork::metalwork.quotation.errors.extension'));

            return;
        }

        // The file is kept, not just read: the operator checks the measured
        // geometry against it, and the technical file stays in the archive.
        $storedPath = $this->storeUpload($originalName);

        if ($storedPath === null) {
            $this->addError('drawingFile', (string) __('metalwork::metalwork.quotation.errors.unreadable'));

            return;
        }

        try {
            $userId = Auth::id();
            $drawing = app(ImportStepDrawingAction::class)->execute(
                Storage::disk(self::DRAWING_DISK)->path($storedPath),
                $storedPath,
                is_int($userId) ? $userId : null,
            );
        } catch (Throwable $exception) {
            Storage::disk(self::DRAWING_DISK)->delete($storedPath);
            $this->addError('drawingFile', $exception->getMessage());

            Notification::make()
                ->title(__('metalwork::metalwork.quotation.errors.parse_failed'))
                ->body($exception->getMessage())
                ->danger()
                ->send();

            return;
        }

        $this->openDrawing($drawing->id);
        $this->drawingFile = null;

        Notification::make()
            ->title(__('metalwork::metalwork.quotation.notifications.parsed'))
            ->body(__('metalwork::metalwork.quotation.notifications.parsed_body', [
                'code' => (string) $drawing->part_code,
                'bbox' => sprintf('%s × %s × %s', $drawing->bbox_length_mm, $drawing->bbox_width_mm, $drawing->bbox_thickness_mm),
            ]))
            ->success()
            ->send();
    }

    public function openDrawing(int $drawingId): void
    {
        $drawing = MetalDrawing::query()->find($drawingId);

        if ($drawing === null) {
            return;
        }

        $this->drawingId = $drawing->id;
        $this->thicknessMm = '';
        $this->manualSalePrice = '';
    }

    /**
     * Confirms the stock a bill-of-materials row is cut from. The proposal made on
     * import is only a match on section; the operator owns the decision, and once
     * taken it survives a re-import of the same drawing.
     */
    public function setComponentMaterial(int $componentId, string $materialId): void
    {
        $drawing = $this->currentDrawing();

        if ($drawing === null) {
            return;
        }

        $component = $drawing->components()->whereKey($componentId)->first();

        if ($component === null) {
            return;
        }

        $component->forceFill([
            'raw_material_id' => $materialId === '' ? null : (int) $materialId,
            'is_material_confirmed' => $materialId !== '',
        ])->save();
    }

    public function clearDrawing(): void
    {
        $this->drawingId = null;
        $this->rawMaterialId = null;
        $this->manualSalePrice = '';
    }

    public function saveEstimate(): void
    {
        $drawing = $this->currentDrawing();
        $costing = $this->costing();

        if ($drawing === null || $costing === null) {
            return;
        }

        $userId = Auth::id();

        app(StoreMetalPartEstimateAction::class)->execute(
            $costing,
            $drawing,
            $this->currentMaterial(),
            $this->pricingOptions(),
            is_int($userId) ? $userId : null,
        );

        Notification::make()
            ->title(__('metalwork::metalwork.quotation.notifications.saved'))
            ->success()
            ->send();
    }

    public function createQuotation(): mixed
    {
        $drawing = $this->currentDrawing();
        $costing = $this->costing();
        $customer = $this->customerId !== null ? Customer::query()->find($this->customerId) : null;

        if ($drawing === null || $costing === null) {
            return null;
        }

        if ($customer === null) {
            Notification::make()
                ->title(__('metalwork::metalwork.quotation.errors.customer_required'))
                ->warning()
                ->send();

            return null;
        }

        $userId = Auth::id();

        // The estimate is saved first: the quotation must point at the numbers it
        // was built from, not at whatever the screen shows later.
        $estimate = app(StoreMetalPartEstimateAction::class)->execute(
            $costing,
            $drawing,
            $this->currentMaterial(),
            $this->pricingOptions(),
            is_int($userId) ? $userId : null,
        );

        $quotation = app(CreateQuotationFromMetalPartEstimateAction::class)->execute($estimate, $customer);

        Notification::make()
            ->title(__('metalwork::metalwork.quotation.notifications.quotation_created'))
            ->success()
            ->send();

        return $this->redirect(
            QuotationResource::getUrl('edit', ['record' => $quotation->id], panel: 'admin'),
            navigate: false,
        );
    }

    /**
     * @return array{
     *     drawing: ?MetalDrawing,
     *     components: Collection<int, MetalDrawingComponent>,
     *     costing: ?MetalPartCosting,
     *     recentDrawings: Collection<int, MetalDrawing>,
     *     materials: Collection<int, MetalRawMaterial>,
     *     customers: Collection<int, Customer>
     * }
     */
    protected function getViewData(): array
    {
        $drawing = $this->currentDrawing();

        /** @var Collection<int, MetalDrawingComponent> $components */
        $components = $drawing instanceof MetalDrawing ? $drawing->components : collect();

        return [
            'drawing' => $drawing,
            'components' => $components,
            'costing' => $this->costing(),
            'recentDrawings' => MetalDrawing::query()->latest('id')->limit(10)->get(),
            'materials' => MetalRawMaterial::query()->where('is_active', true)->orderBy('code')->get(),
            'customers' => Customer::query()->orderBy('legal_name')->get(),
        ];
    }

    private function costing(): ?MetalPartCosting
    {
        $drawing = $this->currentDrawing();

        if ($drawing === null) {
            return null;
        }

        // A drawing whose solids could be told apart is costed piece by piece:
        // every row is a real purchase at an invoiced price, instead of one lump
        // of steel whose solid fraction has to be guessed at. The single-part path
        // stays for drawings the extractor could not break down.
        if ($drawing->components()->exists()) {
            return app(EstimateFromBillOfMaterialsAction::class)->execute($drawing, $this->pricingOptions());
        }

        return app(EstimateMetalPartCostAction::class)->execute(
            $drawing->geometry(),
            $this->currentMaterial(),
            $this->pricingOptions(),
        );
    }

    /**
     * @return array{
     *     quantity?: int,
     *     thickness_mm?: float|null,
     *     occupancy_percent?: float|null,
     *     scrap_percent?: float|null,
     *     markup_percent?: float|null,
     *     manual_sale_price?: float|null
     * }
     */
    private function pricingOptions(): array
    {
        return array_filter([
            'quantity' => max(1, (int) $this->numeric($this->quantity, 1.0)),
            'thickness_mm' => $this->nullableNumeric($this->thicknessMm),
            'occupancy_percent' => $this->nullableNumeric($this->occupancyPercent),
            'scrap_percent' => $this->nullableNumeric($this->scrapPercent),
            'markup_percent' => $this->nullableNumeric($this->markupPercent),
            'manual_sale_price' => $this->nullableNumeric($this->manualSalePrice),
        ], static fn (mixed $value): bool => $value !== null);
    }

    private function currentDrawing(): ?MetalDrawing
    {
        return $this->drawingId !== null ? MetalDrawing::query()->find($this->drawingId) : null;
    }

    private function currentMaterial(): ?MetalRawMaterial
    {
        return $this->rawMaterialId !== null ? MetalRawMaterial::query()->find($this->rawMaterialId) : null;
    }

    private function nullableNumeric(string $value): ?float
    {
        $normalized = str_replace(',', '.', trim($value));

        return $normalized !== '' && is_numeric($normalized) ? (float) $normalized : null;
    }

    private function numeric(string $value, float $fallback): float
    {
        return $this->nullableNumeric($value) ?? $fallback;
    }

    private function configString(string $key): string
    {
        $value = config('metalwork.'.$key);

        return is_numeric($value) ? (string) $value : '';
    }

    private function uploadedFileName(): string
    {
        return $this->drawingFile instanceof TemporaryUploadedFile
            ? $this->drawingFile->getClientOriginalName()
            : 'disegno.stp';
    }

    /**
     * @return string|null the path on the drawing disk, or null when the upload
     *                     cannot be read
     */
    private function storeUpload(string $originalName): ?string
    {
        $sourcePath = $this->drawingFile instanceof TemporaryUploadedFile
            ? $this->drawingFile->getRealPath()
            : false;

        if ($sourcePath === false || $sourcePath === '' || ! is_file($sourcePath)) {
            return null;
        }

        $path = self::DRAWING_DIRECTORY.'/'.uniqid('drawing_', true).'/'.basename($originalName);

        return Storage::disk(self::DRAWING_DISK)->put($path, (string) file_get_contents($sourcePath))
            ? $path
            : null;
    }
}
