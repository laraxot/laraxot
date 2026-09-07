<?php

declare(strict_types=1);

namespace Modules\Intervention\Filament\Pages;

use App\Support\AccountFeatures;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;
use Modules\Intervention\Actions\ApplyInterventionReportDraftAction;
use Modules\Intervention\Actions\BuildInterventionReportDraftAction;
use Modules\Intervention\Actions\ResolveInterventionPricingAction;
use Modules\Intervention\Actions\SaveInterventionReportAttachmentsAction;
use Modules\Intervention\Data\DictatedMaterialLine;
use Modules\Intervention\Data\InterventionReportDraft;
use Modules\Intervention\Filament\Resources\InterventionResource;
use Modules\Intervention\Models\Intervention;
use Modules\Intervention\Queries\GetPastInterventionNotesForReportQuery;
use Modules\Intervention\Settings\InterventionTariffSettings;
use Modules\User\Models\User;
use Modules\Xot\Filament\Pages\XotBasePage;

/**
 * Mobile-first page: the operator records a voice note, the AI turns it into a
 * draft report (with catalog-matched extra materials), attaches photos and the
 * customer's finger signature, reviews and confirms. Persisting is done by
 * {@see ApplyInterventionReportDraftAction} and
 * {@see SaveInterventionReportAttachmentsAction}.
 */
final class RecordInterventionReportPage extends XotBasePage
{
    use WithFileUploads;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-microphone';

    protected static ?string $slug = 'voice-report';

    protected string $view = 'intervention::filament.pages.record-intervention-report';

    public ?int $interventionId = null;

    public string $interventionLabel = '';

    public string $transcript = '';

    public bool $hasDraft = false;

    public string $notes = '';

    public ?int $workMinutes = null;

    public ?string $outcomeCode = null;

    /** @var array<int, array{description: string, quantity: float, unit: string, unit_price: float|null, matched_product_id: int|null, is_matched: bool}> */
    public array $materials = [];

    /** @var array<int, string> Faults noticed on site but not fixed; each one becomes an issue report. */
    public array $issues = [];

    /** @var array<int, string> Names of the other people on the job, as dictated. */
    public array $operatorNames = [];

    public ?int $travelKm = null;

    /**
     * Live preview of what the dictated minutes are worth, shown while the
     * operator reviews. The surcharge is spelled out rather than folded into
     * the figure: "60,00 € (+50% weekend)" is the moment the app earns its
     * keep, because that is money the trade routinely forgets to bill.
     */
    public ?float $labourAmount = null;

    public ?string $labourSurchargeCode = null;

    public int $labourSurchargePercent = 0;

    public ?float $calloutFee = null;

    /** True when there are hours to bill but no rate configured to bill them at. */
    public bool $labourNeedsTariff = false;

    /** @var array<int, TemporaryUploadedFile> */
    public array $photos = [];

    public ?string $signature = null;

    public static function shouldRegisterNavigation(): bool
    {
        // Not shown in the menu: this page is always opened from an intervention,
        // via the "Rapportino a voce" button on the intervention detail.
        return false;
    }

    public static function canAccess(): bool
    {
        return self::isEnabledForCurrentPanel();
    }

    private static function isEnabledForCurrentPanel(): bool
    {
        /** @var User|null $user */
        $user = Auth::user();
        $panelId = filament()->getCurrentPanel()?->getId();

        if ($panelId === 'operator') {
            return AccountFeatures::hasFeature($user, 'operator.voice_report', true);
        }

        // Base Laraxot: la pagina vive sui panel per-modulo ({modulo}::admin) e
        // sul panel 'admin' aggregato; entrambi hanno semantica amministrativa.
        return AccountFeatures::currentPanelAllowsFeature(
            $user,
            adminFeature: 'admin.voice_report',
            operatorFeature: 'operator.voice_report',
        );
    }

    public static function getNavigationGroup(): string
    {
        return __('interventions.navigation');
    }

    public static function getNavigationLabel(): string
    {
        return __('interventions.voice_report.title');
    }

    public function getTitle(): string
    {
        return __('interventions.voice_report.title');
    }

    public function getHeading(): string
    {
        return __('interventions.voice_report.title');
    }

    public function mount(): void
    {
        $interventionParam = request()->query('intervention');
        if (! is_numeric($interventionParam)) {
            return;
        }

        $intervention = Intervention::query()
            ->with(['workOrder.customer', 'customer'])
            ->find((int) $interventionParam);

        if ($intervention === null) {
            return;
        }

        $user = Auth::user();
        if (! $user instanceof User || $user->cannot('update', $intervention)) {
            abort(403);
        }

        $this->interventionId = (int) $intervention->id;
        $this->interventionLabel = $this->buildInterventionLabel($intervention);
    }

    public function generateDraft(): void
    {
        $intervention = $this->requireIntervention();
        $transcript = trim($this->transcript);

        if ($transcript === '') {
            Notification::make()
                ->title(__('interventions.voice_report.empty_transcript'))
                ->warning()
                ->send();

            return;
        }

        $examples = app(GetPastInterventionNotesForReportQuery::class)->execute($intervention);
        $draft = app(BuildInterventionReportDraftAction::class)->execute($transcript, $examples);

        // When the AI could not extract anything (e.g. no LLM key configured or
        // the call failed), keep the raw transcript as the notes so the operator
        // never loses what they said, and warn them to review manually.
        $aiEmpty = $draft->notes === ''
            && $draft->workMinutes === null
            && $draft->outcomeCode === null
            && $draft->materials === [];

        $this->notes = $draft->notes !== '' ? $draft->notes : $transcript;
        $this->workMinutes = $draft->workMinutes;
        $this->outcomeCode = $draft->outcomeCode;
        $this->materials = array_map(
            static fn (DictatedMaterialLine $line): array => [
                'description' => $line->description,
                'quantity' => $line->quantity,
                'unit' => $line->unit,
                'unit_price' => $line->unitPrice,
                'matched_product_id' => $line->matchedProductId,
                'is_matched' => $line->isMatched(),
            ],
            $draft->materials,
        );
        $this->issues = $draft->issues;
        $this->operatorNames = $draft->operatorNames;
        $this->travelKm = $draft->travelKm;
        $this->refreshLabourPreview($intervention, $draft);
        $this->hasDraft = true;

        if ($aiEmpty) {
            Notification::make()
                ->title(__('interventions.voice_report.ai_unavailable'))
                ->warning()
                ->send();
        }
    }

    public function addMaterialRow(): void
    {
        $this->materials[] = [
            'description' => '',
            'quantity' => 1.0,
            'unit' => DictatedMaterialLine::DEFAULT_UNIT,
            'unit_price' => null,
            'matched_product_id' => null,
            'is_matched' => false,
        ];
    }

    public function removeMaterial(int $index): void
    {
        unset($this->materials[$index]);
        $this->materials = array_values($this->materials);
    }

    /**
     * Recomputes the labour preview with the same action the save path uses, so
     * what the operator approves is exactly what gets written.
     */
    private function refreshLabourPreview(Intervention $intervention, InterventionReportDraft $draft): void
    {
        $pricing = app(ResolveInterventionPricingAction::class)->execute(
            currentAmount: is_numeric($intervention->amount) ? (float) $intervention->amount : null,
            currentCalloutFee: is_numeric($intervention->callout_fee) ? (float) $intervention->callout_fee : null,
            currentTravelKm: $intervention->travel_km !== null ? (int) $intervention->travel_km : null,
            draft: $draft,
            tariff: app(InterventionTariffSettings::class)->current(),
            performedAt: $intervention->started_at ?? $intervention->scheduled_at,
            fallbackMinutes: (int) ($intervention->work_minutes ?? 0),
        );

        $this->labourAmount = $pricing->amount;
        $this->labourSurchargeCode = $pricing->surchargeCode;
        $this->labourSurchargePercent = $pricing->surchargePercent;
        $this->calloutFee = $pricing->calloutFee;
        $this->labourNeedsTariff = $pricing->labourNeedsTariff;
    }

    public function addIssueRow(): void
    {
        $this->issues[] = '';
    }

    public function removeIssue(int $index): void
    {
        unset($this->issues[$index]);
        $this->issues = array_values($this->issues);
    }

    public function removeOperator(int $index): void
    {
        unset($this->operatorNames[$index]);
        $this->operatorNames = array_values($this->operatorNames);
    }

    public function confirm(): void
    {
        $intervention = $this->requireIntervention();

        $draft = new InterventionReportDraft(
            notes: trim($this->notes),
            workMinutes: $this->workMinutes,
            outcomeCode: $this->outcomeCode,
            materials: $this->materialsFromState(),
            issues: $this->issuesFromState(),
            operatorNames: array_values($this->operatorNames),
            travelKm: $this->travelKm,
            labourAmount: $this->labourAmount,
            calloutFee: $this->calloutFee,
        );

        app(ApplyInterventionReportDraftAction::class)->execute($intervention, $draft, (int) Auth::id());

        app(SaveInterventionReportAttachmentsAction::class)->execute(
            intervention: $intervention,
            photoPaths: $this->storePhotos($intervention),
            signatureDataUrl: $this->signature,
            userId: (int) Auth::id(),
        );

        Notification::make()
            ->title(__('interventions.voice_report.saved'))
            ->success()
            ->send();

        $this->redirect(InterventionResource::getUrl('view', ['record' => $intervention->id]));
    }

    /**
     * Moves the uploaded photos to the public disk and returns their paths.
     *
     * @return list<string>
     */
    private function storePhotos(Intervention $intervention): array
    {
        $paths = [];
        foreach ($this->photos as $photo) {
            if (! $photo instanceof TemporaryUploadedFile) {
                continue;
            }

            $path = $photo->store('media/interventions/'.$intervention->id, 'public');
            if (is_string($path) && $path !== '') {
                $paths[] = $path;
            }
        }

        return $paths;
    }

    /**
     * @return list<DictatedMaterialLine>
     */
    private function materialsFromState(): array
    {
        $lines = [];
        foreach ($this->materials as $material) {
            $description = trim((string) ($material['description'] ?? ''));
            if ($description === '') {
                continue;
            }

            $quantity = (float) ($material['quantity'] ?? 0);
            if ($quantity <= 0) {
                $quantity = 1.0;
            }

            $rawPrice = $material['unit_price'];
            $unitPrice = is_numeric($rawPrice) ? (float) $rawPrice : null;

            $rawProductId = $material['matched_product_id'];
            $productId = is_numeric($rawProductId) ? (int) $rawProductId : null;

            $unit = trim((string) ($material['unit'] ?? ''));

            $lines[] = new DictatedMaterialLine(
                description: $description,
                quantity: $quantity,
                unitPrice: $unitPrice,
                matchedProductId: $productId,
                unit: $unit !== '' ? $unit : DictatedMaterialLine::DEFAULT_UNIT,
            );
        }

        return $lines;
    }

    /**
     * @return list<string>
     */
    private function issuesFromState(): array
    {
        $issues = [];
        foreach ($this->issues as $issue) {
            $text = trim((string) $issue);
            if ($text !== '') {
                $issues[] = $text;
            }
        }

        return $issues;
    }

    private function requireIntervention(): Intervention
    {
        if ($this->interventionId === null) {
            abort(404);
        }

        $intervention = Intervention::query()->find($this->interventionId);
        $user = Auth::user();

        if ($intervention === null || ! $user instanceof User || $user->cannot('update', $intervention)) {
            abort(403);
        }

        return $intervention;
    }

    private function buildInterventionLabel(Intervention $intervention): string
    {
        $workOrder = $intervention->workOrder;
        $customer = $intervention->customer;
        $customerName = ($workOrder !== null && $workOrder->customer !== null ? $workOrder->customer->legal_name : null)
            ?? ($customer !== null ? $customer->legal_name : null)
            ?? '—';

        return sprintf('#%d · %s', $intervention->id, $customerName);
    }
}
