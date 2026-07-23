<?php

declare(strict_types=1);

namespace Modules\Intervention\Filament\Pages;

use App\Models\User;
use App\Support\AccountFeatures;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;
use Modules\Intervention\Actions\ApplyInterventionReportDraftAction;
use Modules\Intervention\Actions\BuildInterventionReportDraftAction;
use Modules\Intervention\Actions\SaveInterventionReportAttachmentsAction;
use Modules\Intervention\Queries\GetPastInterventionNotesForReportQuery;
use Modules\Intervention\Domain\DTO\DictatedMaterialLine;
use Modules\Intervention\Domain\DTO\InterventionReportDraft;
use Modules\Intervention\Filament\Resources\InterventionResource;
use Modules\Intervention\Models\Intervention;

/**
 * Mobile-first page: the operator records a voice note, the AI turns it into a
 * draft report (with catalog-matched extra materials), attaches photos and the
 * customer's finger signature, reviews and confirms. Persisting is done by
 * {@see ApplyInterventionReportDraftAction} and
 * {@see SaveInterventionReportAttachmentsAction}.
 */
final class RecordInterventionReportPage extends Page
{
    use WithFileUploads;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-microphone';

    protected static ?string $slug = 'voice-report';

    protected string $view = 'interventions::filament.pages.record-intervention-report';

    public ?int $interventionId = null;

    public string $interventionLabel = '';

    public string $transcript = '';

    public bool $hasDraft = false;

    public string $notes = '';

    public ?int $workMinutes = null;

    public ?string $outcomeCode = null;

    /** @var list<array{description: string, quantity: float, unit_price: float|null, matched_product_id: int|null, is_matched: bool}> */
    public array $materials = [];

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

        return match ($panelId) {
            'operator' => AccountFeatures::hasFeature($user, 'operator.voice_report', true),
            'admin' => AccountFeatures::hasFeature($user, 'admin.voice_report', true),
            default => false,
        };
    }

    public static function getNavigationGroup(): ?string
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
                'unit_price' => $line->unitPrice,
                'matched_product_id' => $line->matchedProductId,
                'is_matched' => $line->isMatched(),
            ],
            $draft->materials,
        );
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

    public function confirm(): void
    {
        $intervention = $this->requireIntervention();

        $draft = new InterventionReportDraft(
            notes: trim($this->notes),
            workMinutes: $this->workMinutes,
            outcomeCode: $this->outcomeCode,
            materials: $this->materialsFromState(),
        );

        app(ApplyInterventionReportDraftAction::class)->execute($intervention, $draft);

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

            $rawPrice = $material['unit_price'] ?? null;
            $unitPrice = is_numeric($rawPrice) ? (float) $rawPrice : null;

            $rawProductId = $material['matched_product_id'] ?? null;
            $productId = is_numeric($rawProductId) ? (int) $rawProductId : null;

            $lines[] = new DictatedMaterialLine(
                description: $description,
                quantity: $quantity,
                unitPrice: $unitPrice,
                matchedProductId: $productId,
            );
        }

        return $lines;
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
        $customer = $intervention->workOrder?->customer?->legal_name
            ?? $intervention->customer?->legal_name
            ?? '—';

        return sprintf('#%d · %s', $intervention->id, $customer);
    }
}
