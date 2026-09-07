<?php

declare(strict_types=1);

namespace Modules\Signature\Filament\Pages;

use App\Filament\Forms\Components\CanvasPad;
use App\Support\AccountFeatures;
use Filament\Actions\Action;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\Customer\Actions\UpsertCustomerContactByEmailAction;
use Modules\Intervention\Filament\Resources\InterventionResource;
use Modules\Intervention\Models\Intervention;
use Modules\Signature\Actions\StoreSignatureForModelAction;
use Modules\Signature\Filament\Pages\Concerns\InteractsWithCanvasFormData;
use Modules\WorkOrder\Actions\SetWorkOrderPrimaryContactAction;
use Modules\Xot\Filament\Pages\XotBasePage;

/**
 * @property Schema $form
 */
final class CaptureSignature extends XotBasePage
{
    use InteractsWithCanvasFormData;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-pencil';

    protected static ?string $title = 'Firma';

    protected string $view = 'signatures::filament.pages.capture-signature';

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $slug = 'interventions/{interventionId}/capture-signature';

    public ?Intervention $intervention = null;

    /** @var array<string, mixed> */
    public array $data = [];

    public static function canAccess(): bool
    {
        return AccountFeatures::currentPanelAllowsFeature(
            Auth::user(),
            adminFeature: 'admin.interventions',
            operatorFeature: 'operator.capture_signature',
            adminDefault: true,
            operatorDefault: true,
            fallback: false,
        );
    }

    public static function getNavigationLabel(): string
    {
        return 'Firma';
    }

    public function getTitle(): string
    {
        return 'Firma';
    }

    public function getHeading(): string
    {
        return 'Firma';
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
                TextInput::make('signatory.full_name')
                    ->label('Nominativo firmatario')
                    ->maxLength(255),
                TextInput::make('signatory.email')
                    ->label('Email firmatario')
                    ->email()
                    ->maxLength(255),
                TextInput::make('signatory.phone')
                    ->label('Telefono firmatario')
                    ->tel()
                    ->maxLength(255),
                TextInput::make('signatory.position')
                    ->label('Ruolo/posizione firmatario')
                    ->maxLength(255),
                Textarea::make('signatory.notes')
                    ->label('Note firmatario')
                    ->columnSpanFull(),
                CanvasPad::make('payload.image')
                    ->label('Firma')
                    ->required()
                    ->height(260)
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

                    $signatoryRaw = $data['signatory'] ?? [];
                    $signatory = $this->extractStringKeyedArray($signatoryRaw);
                    $signatoryFullName = isset($signatory['full_name']) ? trim($this->coerceToString($signatory['full_name'])) : null;
                    $signatoryEmail = isset($signatory['email']) ? trim($this->coerceToString($signatory['email'])) : null;

                    if ($this->intervention instanceof Intervention && $signatoryFullName && $signatoryEmail) {
                        $workOrder = $this->intervention->workOrder()->first();
                        if ($workOrder) {
                            $contact = app(UpsertCustomerContactByEmailAction::class)->execute(
                                customerId: (int) $workOrder->customer_id,
                                fullName: $signatoryFullName,
                                email: $signatoryEmail,
                                phone: isset($signatory['phone']) ? $this->coerceToString($signatory['phone']) : null,
                                position: isset($signatory['position']) ? $this->coerceToString($signatory['position']) : null,
                                notes: isset($signatory['notes']) ? $this->coerceToString($signatory['notes']) : null,
                                customerSiteId: $workOrder->customer_site_id ? (int) $workOrder->customer_site_id : null,
                            );

                            app(SetWorkOrderPrimaryContactAction::class)->execute($workOrder, (int) $contact->id);
                        }
                    }

                    $payload = $this->extractStringKeyedArray($data['payload'] ?? null);
                    $imageDataUrl = isset($payload['image']) ? $this->coerceToString($payload['image']) : '';
                    unset($payload['image']);

                    app(StoreSignatureForModelAction::class)->execute(
                        dataUrl: $imageDataUrl,
                        signableType: Intervention::class,
                        signableId: $interventionId,
                        userId: $userId,
                        signedAt: null,
                        payload: [
                            ...$payload,
                            'signatory' => $signatory !== [] ? $signatory : null,
                        ],
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
