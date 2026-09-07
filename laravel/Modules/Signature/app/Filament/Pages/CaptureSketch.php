<?php

declare(strict_types=1);

namespace Modules\Signature\Filament\Pages;

use App\Filament\Forms\Components\CanvasPad;
use App\Support\AccountFeatures;
use Filament\Actions\Action;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\Intervention\Filament\Resources\InterventionResource;
use Modules\Intervention\Models\Intervention;
use Modules\Signature\Actions\StoreSketchForModelAction;
use Modules\Signature\Filament\Pages\Concerns\InteractsWithCanvasFormData;
use Modules\Xot\Filament\Pages\XotBasePage;

/**
 * @property Schema $form
 */
final class CaptureSketch extends XotBasePage
{
    use InteractsWithCanvasFormData;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-pencil-square';

    protected static ?string $title = 'Disegno';

    protected string $view = 'signatures::filament.pages.capture-sketch';

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $slug = 'interventions/{interventionId}/capture-sketch';

    public ?Intervention $intervention = null;

    /** @var array<string, mixed> */
    public array $data = [];

    public static function canAccess(): bool
    {
        return AccountFeatures::currentPanelAllowsFeature(
            Auth::user(),
            adminFeature: 'admin.interventions',
            operatorFeature: 'operator.capture_sketch',
            adminDefault: true,
            operatorDefault: true,
            fallback: false,
        );
    }

    public static function getNavigationLabel(): string
    {
        return 'Disegno';
    }

    public function getTitle(): string
    {
        return 'Disegno';
    }

    public function getHeading(): string
    {
        return 'Disegno';
    }

    public function mount(int $interventionId): void
    {
        /** @var Intervention $intervention */
        $intervention = Intervention::query()->findOrFail($interventionId);
        Gate::authorize('update', $intervention);
        $this->intervention = $intervention;

        $this->form->fill([
            'intervention_id' => $intervention->id,
            'user_id' => Auth::id(),
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components([
                Hidden::make('intervention_id')->default(fn (): ?int => $this->intervention?->id),
                Select::make('user_id')
                    ->label('Utente')
                    ->options(fn (): array => $this->resolveAllowedUserOptions())
                    ->searchable()
                    ->preload()
                    ->disabled(fn (): bool => request()->is('operator/*'))
                    ->required(),
                CanvasPad::make('payload.image')
                    ->label('Disegno')
                    ->required()
                    ->height(320)
                    ->columnSpanFull(),
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('save')
                ->label('Salva')
                ->action(function (): void {
                    $data = $this->getValidatedFormState();

                    $resolvedInterventionId = $this->intervention->id ?? 0;
                    $userId = $this->coerceToInt($data['user_id'] ?? Auth::id());
                    $interventionId = $this->coerceToInt($data['intervention_id'] ?? $resolvedInterventionId);

                    $payload = $this->extractStringKeyedArray($data['payload'] ?? null);
                    $imageDataUrl = isset($payload['image']) ? $this->coerceToString($payload['image']) : '';
                    unset($payload['image']);

                    app(StoreSketchForModelAction::class)->execute(
                        dataUrl: $imageDataUrl,
                        sketchableType: Intervention::class,
                        sketchableId: $interventionId,
                        userId: $userId,
                        payload: $payload,
                    );

                    $this->redirect(InterventionResource::getUrl('view', ['record' => $interventionId]));
                }),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function getValidatedFormState(): array
    {
        return $this->extractStringKeyedArray($this->form->getState());
    }
}
