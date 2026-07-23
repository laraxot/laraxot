<?php

declare(strict_types=1);

namespace Modules\Signature\Filament\Pages;

use App\Filament\Forms\Components\CanvasPad;
use App\Support\AccountFeatures;
use Filament\Actions\Action;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\Customer\Actions\UpsertCustomerContactByEmailAction;
use Modules\Intervention\Models\Intervention;
use Modules\Signature\Actions\StoreSignatureForModelAction;
use Modules\WorkOrder\Actions\SetWorkOrderPrimaryContactAction;

final class CaptureSignature extends Page
{
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
                    $data = $this->form->getState();

                    $resolvedInterventionId = (int) ($this->intervention?->getKey() ?? 0);
                    $userId = (int) ($data['user_id'] ?? Auth::id());
                    $interventionId = (int) ($data['intervention_id'] ?? $resolvedInterventionId);

                    $signatory = $data['signatory'] ?? [];
                    $signatoryFullName = isset($signatory['full_name']) ? trim((string) $signatory['full_name']) : null;
                    $signatoryEmail = isset($signatory['email']) ? trim((string) $signatory['email']) : null;

                    if ($this->intervention instanceof Intervention && $signatoryFullName && $signatoryEmail) {
                        $workOrder = $this->intervention->workOrder()->first();
                        if ($workOrder) {
                            $contact = app(UpsertCustomerContactByEmailAction::class)->execute(
                                customerId: (int) $workOrder->customer_id,
                                fullName: $signatoryFullName,
                                email: $signatoryEmail,
                                phone: isset($signatory['phone']) ? (string) $signatory['phone'] : null,
                                position: isset($signatory['position']) ? (string) $signatory['position'] : null,
                                notes: isset($signatory['notes']) ? (string) $signatory['notes'] : null,
                                customerSiteId: $workOrder->customer_site_id ? (int) $workOrder->customer_site_id : null,
                            );

                            app(SetWorkOrderPrimaryContactAction::class)->execute($workOrder, (int) $contact->id);
                        }
                    }

                    /** @var array<string, mixed> $payload */
                    $payload = is_array($data['payload'] ?? null) ? $data['payload'] : [];
                    unset($payload['image']);

                    app(StoreSignatureForModelAction::class)->execute(
                        dataUrl: (string) ($data['payload']['image'] ?? ''),
                        signableType: Intervention::class,
                        signableId: $interventionId,
                        userId: $userId,
                        signedAt: null,
                        payload: [
                            ...$payload,
                            'signatory' => $data['signatory'] ?? null,
                        ],
                    );

                    $this->redirect(\Modules\Intervention\Filament\Resources\InterventionResource::getUrl('view', ['record' => $interventionId]));
                }),
        ];
    }

    /**
     * @return array<int, string>
     */
    private function resolveAllowedUserOptions(): array
    {
        $currentUser = Auth::user();
        $currentUserId = Auth::id();

        if ($currentUserId === null) {
            return [];
        }

        if (request()->is('operator/*')) {
            return \App\Models\User::query()
                ->whereKey($currentUserId)
                ->pluck('name', 'id')
                ->all();
        }

        $workOrderId = (int) ($this->intervention?->work_order_id ?? 0);

        if ($workOrderId > 0 && AccountFeatures::hasAssignedPerimeter($currentUser)) {
            return \App\Models\User::query()
                ->whereIn('id', function ($query) use ($workOrderId): void {
                    $query->select('user_id')
                        ->from('work_order_assignments')
                        ->where('work_order_id', $workOrderId);
                })
                ->orderBy('name')
                ->pluck('name', 'id')
                ->all();
        }

        return \App\Models\User::query()
            ->orderBy('name')
            ->pluck('name', 'id')
            ->all();
    }
}

