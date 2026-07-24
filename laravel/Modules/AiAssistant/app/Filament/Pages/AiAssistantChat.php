<?php

declare(strict_types=1);

namespace Modules\AiAssistant\Filament\Pages;

use App\Support\AccountFeatures;
use Carbon\CarbonImmutable;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Modules\AiAssistant\Actions\ExecuteAiActionProposalAction;
use Modules\AiAssistant\Models\AiActionProposal;
use Modules\AiAssistant\Models\AiMessage;
use Modules\AiAssistant\Models\AiThread;
use Modules\Email\Models\EmailMessage;
use Modules\Intervention\Models\Intervention;
use Modules\Inventory\Models\Warehouse;
use Modules\Platform\Settings\SettingsRepository;
use Modules\User\Models\User;
use Modules\WhatsApp\Models\WhatsAppMessage;
use Modules\WorkOrder\Models\WorkOrder;

use function Safe\preg_match;
use function Safe\preg_match_all;
use function Safe\preg_replace;

final class AiAssistantChat extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-cpu-chip';

    protected static ?string $navigationLabel = 'Assistente AI';

    protected static ?string $title = 'Assistente AI';

    protected static ?int $navigationSort = 1;

    protected string $view = 'aiassistant::filament.pages.ai-assistant-chat';

    /**
     * Hide the Filament page header (the big "Assistente AI" title) to reclaim
     * vertical space on mobile; the tab title and nav label stay intact.
     */
    public function getHeading(): string
    {
        return '';
    }

    public ?int $contextInterventionId = null;

    public ?int $contextWarehouseId = null;

    public bool $materialsOnInterventionsEnabled = true;

    public string $panelId = 'operator';

    public ?string $contextInterventionLabel = null;

    public ?string $contextWarehouseLabel = null;

    /** @var array<string, array<int, string>> */
    public array $exampleGroups = [];

    /** @var array<int, array{label: string, prompt: string}> */
    public array $quickPrompts = [];

    public ?string $currentThreadPublicId = null;

    public ?string $currentThreadLoadedLabel = null;

    /** @var array<int, array{role: string, content: string}> */
    public array $initialMessages = [];

    /** @var array<string, int|string|null> */
    public array $proposalWorkOrderIds = [];

    /** @var array<string, string> */
    public array $proposalWorkOrderSearches = [];

    /** @var array<string, int|string|null> */
    public array $proposalInterventionIds = [];

    /** @var array<string, string> */
    public array $proposalScheduledAts = [];

    /** @var array<string, string> */
    public array $proposalBodies = [];

    /** @var array<string, string> */
    public array $proposalMaterialDescriptions = [];

    /** @var array<string, string> */
    public array $proposalMaterialCosts = [];

    /** @var array<string, string> */
    public array $proposalLaborAmounts = [];

    public string $inputPlaceholder = '';

    public bool $canUseInterventions = false;

    public bool $canUseWorkOrders = false;

    public bool $canUseDailyNotes = false;

    public bool $canUseMaterials = false;

    public bool $canUseBilling = false;

    public bool $canUseQuotations = false;

    public bool $canUseCustomers = false;

    public bool $canUseProduction = false;

    public bool $canUseEnergyBrokerDocuments = false;

    public static function shouldRegisterNavigation(): bool
    {
        return AccountFeatures::currentPanelAllowsFeature(
            Auth::user(),
            adminFeature: 'admin.ai_assistant',
            operatorFeature: 'operator.ai_assistant',
            adminDefault: false,
            operatorDefault: true,
            fallback: false,
        );
    }

    public function mount(): void
    {
        $interventionId = request()->query('intervention_id');
        $warehouseId = request()->query('warehouse_id');
        $this->panelId = filament()->getCurrentPanel()?->getId() ?? 'operator';

        $this->contextInterventionId = is_numeric($interventionId) ? (int) $interventionId : null;
        $this->contextWarehouseId = is_numeric($warehouseId) ? (int) $warehouseId : null;
        $this->materialsOnInterventionsEnabled = app(SettingsRepository::class)
            ->getBool('inventory.allow_link_interventions', true);
        $this->bootCapabilities();
        $this->contextInterventionLabel = $this->resolveInterventionLabel($this->contextInterventionId);
        $this->contextWarehouseLabel = $this->resolveWarehouseLabel($this->contextWarehouseId);
        $this->exampleGroups = $this->buildExampleGroups();
        $this->quickPrompts = $this->buildQuickPrompts();
        $this->inputPlaceholder = $this->buildInputPlaceholder();

        $threadParam = request()->query('thread');
        if (request()->query('new') !== null) {
            // Fresh chat: leave everything empty so a new thread is created on send.
        } elseif (is_string($threadParam) && $threadParam !== '') {
            $this->loadThreadByPublicId($threadParam);
        } else {
            $this->loadLatestThreadMessages();
        }
    }

    /**
     * Recent chat threads for the current user/panel (for the history switcher).
     *
     * @return array<int, array{public_id: string, label: string, active: bool}>
     */
    public function chatThreads(): array
    {
        $userId = Auth::id();
        if (! is_numeric($userId)) {
            return [];
        }

        return AiThread::query()
            ->where('created_by_user_id', (int) $userId)
            ->where('panel_id', $this->panelId)
            ->whereHas('messages', fn ($query) => $query->whereIn('role', ['user', 'assistant']))
            ->orderByDesc('last_message_at')
            ->orderByDesc('id')
            ->limit(30)
            ->get()
            ->map(fn (AiThread $thread): array => [
                'public_id' => (string) $thread->public_id,
                'label' => $thread->last_message_at
                    ? $thread->last_message_at->format('d/m/Y H:i')
                    : 'Chat',
                'active' => (string) $thread->public_id === $this->currentThreadPublicId,
            ])
            ->all();
    }

    public function pendingCommunicationCount(): int
    {
        return count($this->pendingCommunicationProposals());
    }

    public static function canAccess(): bool
    {
        return AccountFeatures::currentPanelAllowsFeature(
            Auth::user(),
            adminFeature: 'admin.ai_assistant',
            operatorFeature: 'operator.ai_assistant',
            adminDefault: false,
            operatorDefault: true,
            fallback: false,
        ) && Gate::allows('use-aiassistant');
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function pendingCommunicationProposals(): array
    {
        $userId = Auth::id();
        if (! is_int($userId) && ! is_string($userId)) {
            return [];
        }

        return AiActionProposal::query()
            ->with('thread')
            ->when(! $this->canManageCommunicationProposals(), fn ($query) => $query->where('proposed_by_user_id', $userId))
            ->where('status', 'pending')
            ->whereIn('type', [
                'append_work_order_activity_from_whatsapp',
                'create_intervention_from_whatsapp',
                'update_intervention_schedule_from_whatsapp',
                'append_work_order_activity_from_email',
                'create_intervention_from_email',
                'update_intervention_schedule_from_email',
            ])
            ->orderByDesc('created_at')
            ->limit(10)
            ->get()
            ->map(function (AiActionProposal $proposal): array {
                $payload = is_array($proposal->payload) ? $proposal->payload : [];
                $source = (string) ($payload['source'] ?? data_get($proposal->thread?->meta, 'source', ''));
                $preview = (string) ($proposal->preview ?? '');
                $publicId = (string) $proposal->public_id;
                $this->fillProposalFormState($publicId, $payload);
                $context = $this->proposalContext($proposal);

                return [
                    'public_id' => $publicId,
                    'source' => $source !== '' ? $source : 'messaggio',
                    'type' => (string) $proposal->type,
                    'action_label' => $this->proposalActionLabel((string) $proposal->type),
                    'preview' => $preview !== '' ? $preview : 'Proposta AI da verificare.',
                    'body' => Str::limit((string) ($payload['body'] ?? ''), 240),
                    'full_message' => $this->fullCommunicationMessage($payload),
                    'requires_work_order' => $this->proposalRequiresWorkOrder((string) $proposal->type),
                    'requires_intervention' => $this->proposalRequiresIntervention((string) $proposal->type),
                    'uses_schedule' => $this->proposalUsesSchedule((string) $proposal->type),
                    'supports_materials' => $this->proposalSupportsMaterials((string) $proposal->type),
                    'supports_labor' => $this->proposalSupportsLabor((string) $proposal->type),
                    'work_order_options' => $this->workOrderOptions($context, $payload, $publicId),
                    'intervention_options' => $this->interventionOptions($context, $payload),
                    'work_order_create_url' => $this->workOrderCreateUrl(),
                    'work_order_index_url' => $this->workOrderIndexUrl(),
                    'created_at' => $proposal->created_at?->format('d/m/Y H:i'),
                ];
            })
            ->all();
    }

    public function canViewCommunicationsMonitor(): bool
    {
        return $this->canManageCommunicationProposals();
    }

    public function approveCommunicationProposal(string $proposalPublicId): void
    {
        Gate::authorize('use-aiassistant');

        /** @var AiActionProposal|null $proposal */
        $proposal = AiActionProposal::query()
            ->where('public_id', $proposalPublicId)
            ->where('status', 'pending')
            ->first();

        if (! $proposal instanceof AiActionProposal || ! $this->canHandleCommunicationProposal($proposal)) {
            Notification::make()->title('Proposta non trovata o non autorizzata')->danger()->send();

            return;
        }

        $userId = Auth::id();
        if (! is_numeric($userId)) {
            Notification::make()->title('Utente non valido')->danger()->send();

            return;
        }

        if (! $this->applyEditedPayload($proposal)) {
            return;
        }

        $result = app(ExecuteAiActionProposalAction::class)->execute($proposal, (int) $userId);
        $ok = (bool) ($result['ok'] ?? false);

        $notification = Notification::make()
            ->title($ok ? 'Proposta approvata ed eseguita' : 'Esecuzione proposta fallita')
            ->body($ok ? null : (string) ($result['error'] ?? 'Errore sconosciuto.'));

        ($ok ? $notification->success() : $notification->danger())->send();
    }

    public function cancelCommunicationProposal(string $proposalPublicId): void
    {
        Gate::authorize('use-aiassistant');

        /** @var AiActionProposal|null $proposal */
        $proposal = AiActionProposal::query()
            ->where('public_id', $proposalPublicId)
            ->where('status', 'pending')
            ->first();

        if (! $proposal instanceof AiActionProposal || ! $this->canHandleCommunicationProposal($proposal)) {
            Notification::make()->title('Proposta non trovata o non autorizzata')->danger()->send();

            return;
        }

        $proposal->forceFill([
            'status' => 'cancelled',
            'confirmed_by_user_id' => Auth::id(),
            'confirmed_at' => now(),
        ])->save();

        Notification::make()->title('Proposta scartata')->success()->send();
    }

    private function canManageCommunicationProposals(): bool
    {
        return Gate::allows('manage-email-ai') || Gate::allows('manage-whatsapp-ai');
    }

    private function loadLatestThreadMessages(): void
    {
        $userId = Auth::id();
        if (! is_numeric($userId)) {
            return;
        }

        /** @var AiThread|null $thread */
        $thread = AiThread::query()
            ->where('created_by_user_id', (int) $userId)
            ->where('panel_id', $this->panelId)
            ->whereHas('messages', fn ($query) => $query->whereIn('role', ['user', 'assistant']))
            ->orderByDesc('last_message_at')
            ->orderByDesc('id')
            ->first();

        if (! $thread instanceof AiThread) {
            return;
        }

        $this->hydrateThread($thread);
        // Auto-restored on open → label it as the panel's most recent chat.
        $this->currentThreadLoadedLabel = 'Ultima chat del pannello';
    }

    private function loadThreadByPublicId(string $publicId): void
    {
        $userId = Auth::id();
        if (! is_numeric($userId)) {
            return;
        }

        /** @var AiThread|null $thread */
        $thread = AiThread::query()
            ->where('created_by_user_id', (int) $userId)
            ->where('panel_id', $this->panelId)
            ->where('public_id', $publicId)
            ->first();

        if ($thread instanceof AiThread) {
            $this->hydrateThread($thread);
        }
    }

    private function hydrateThread(AiThread $thread): void
    {
        $this->currentThreadPublicId = (string) $thread->public_id;
        $this->currentThreadLoadedLabel = $thread->last_message_at
            ? 'Chat del '.$thread->last_message_at->format('d/m/Y H:i')
            : 'Chat';
        $this->initialMessages = $thread->messages()
            ->whereIn('role', ['user', 'assistant'])
            ->orderBy('id')
            ->limit(80)
            ->get()
            ->map(fn (AiMessage $message): array => [
                'role' => (string) $message->role,
                'content' => $this->displayableAiMessageContent((string) ($message->content ?? '')),
            ])
            ->filter(fn (array $message): bool => trim($message['content']) !== '')
            ->values()
            ->all();
    }

    private function displayableAiMessageContent(string $content): string
    {
        if (preg_match_all('/(?:^|\R)<user_request>\s*(.*?)\s*<\/user_request>/su', $content, $matches) >= 1) {
            $requests = $matches[1];

            return trim((string) end($requests));
        }

        $withoutContext = preg_replace('/^\[trusted_context[^\]]*\]\s*/u', '', $content);
        $withoutNotice = preg_replace('/\[security_notice\].*?\[\/security_notice\]\s*/su', '', is_string($withoutContext) ? $withoutContext : $content);

        return trim(is_string($withoutNotice) ? $withoutNotice : $content);
    }

    private function canHandleCommunicationProposal(AiActionProposal $proposal): bool
    {
        if ($this->canManageCommunicationProposals()) {
            return true;
        }

        return (int) $proposal->proposed_by_user_id === (int) Auth::id();
    }

    private function applyEditedPayload(AiActionProposal $proposal): bool
    {
        $publicId = (string) $proposal->public_id;
        $payload = is_array($proposal->payload) ? $proposal->payload : [];
        $type = (string) $proposal->type;
        $workOrderId = $this->positiveInt($this->proposalWorkOrderIds[$publicId] ?? null);
        $interventionId = $this->positiveInt($this->proposalInterventionIds[$publicId] ?? null);
        $body = trim((string) ($this->proposalBodies[$publicId] ?? ''));
        $scheduledAt = trim((string) ($this->proposalScheduledAts[$publicId] ?? ''));
        $materialDescription = trim((string) ($this->proposalMaterialDescriptions[$publicId] ?? ''));
        $materialCost = $this->positiveFloat($this->proposalMaterialCosts[$publicId] ?? null);
        $laborAmount = $this->positiveFloat($this->proposalLaborAmounts[$publicId] ?? null);

        if ($this->proposalRequiresWorkOrder($type) && $workOrderId === null) {
            Notification::make()->title('Seleziona o inserisci una commessa valida')->danger()->send();

            return false;
        }

        if ($this->proposalRequiresIntervention($type) && $interventionId === null) {
            Notification::make()->title('Inserisci un intervento valido')->danger()->send();

            return false;
        }

        if ($this->proposalUsesSchedule($type) && $scheduledAt === '') {
            Notification::make()->title('Inserisci data e ora intervento')->danger()->send();

            return false;
        }

        if (str_contains($type, 'append_work_order_activity') && $body === '') {
            Notification::make()->title('Inserisci il testo da archiviare')->danger()->send();

            return false;
        }

        if ($workOrderId !== null) {
            $payload['work_order_id'] = $workOrderId;
        }

        if ($interventionId !== null) {
            $payload['intervention_id'] = $interventionId;
        }

        if ($body !== '') {
            $payload['body'] = $body;
            $payload['notes'] = $body;
        }

        if ($scheduledAt !== '') {
            $payload['scheduled_at'] = $scheduledAt;
        }

        if ($this->proposalSupportsMaterials($type) && $materialCost !== null && $materialCost > 0) {
            $payload['material_lines'] = [[
                'description' => $materialDescription !== '' ? $materialDescription : 'Materiali extra',
                'quantity' => 1,
                'unit_price' => $materialCost,
            ]];
        }

        if ($this->proposalSupportsLabor($type) && $laborAmount !== null && $laborAmount > 0) {
            $payload['labor_amount'] = $laborAmount;
            $payload['amount'] = $laborAmount;
        }

        $proposal->forceFill(['payload' => $payload])->save();

        return true;
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    private function fillProposalFormState(string $publicId, array $payload): void
    {
        $this->proposalWorkOrderIds[$publicId] ??= is_numeric($payload['work_order_id'] ?? null) ? (int) $payload['work_order_id'] : '';
        $this->proposalWorkOrderSearches[$publicId] ??= '';
        $this->proposalInterventionIds[$publicId] ??= is_numeric($payload['intervention_id'] ?? null) ? (int) $payload['intervention_id'] : '';
        $this->proposalBodies[$publicId] ??= (string) ($payload['body'] ?? $payload['notes'] ?? $payload['summary'] ?? '');
        $this->proposalScheduledAts[$publicId] ??= $this->formatScheduleForInput((string) ($payload['scheduled_at'] ?? ''));
        $materialLines = is_array($payload['material_lines'] ?? null) ? $payload['material_lines'] : [];
        $firstMaterialLine = $materialLines[0] ?? null;
        $materialLine = is_array($firstMaterialLine) ? $firstMaterialLine : [];
        $this->proposalMaterialDescriptions[$publicId] ??= (string) ($materialLine['description'] ?? $this->guessMaterialDescription((string) ($payload['body'] ?? $payload['notes'] ?? '')));
        $messageText = (string) ($payload['body'] ?? $payload['notes'] ?? '');
        $materialCost = $this->positiveFloat($materialLine['unit_price'] ?? null) ?? $this->extractMaterialEuroAmount($messageText) ?? $this->extractEuroAmount($messageText);
        $this->proposalMaterialCosts[$publicId] ??= $materialCost !== null ? number_format($materialCost, 2, '.', '') : '';
        $laborAmount = $this->positiveFloat($payload['labor_amount'] ?? $payload['amount'] ?? null) ?? $this->extractLaborEuroAmount($messageText);
        $this->proposalLaborAmounts[$publicId] ??= $laborAmount !== null ? number_format($laborAmount, 2, '.', '') : '';
    }

    private function proposalActionLabel(string $type): string
    {
        return match ($type) {
            'append_work_order_activity_from_whatsapp', 'append_work_order_activity_from_email' => 'Archiviare una nota sulla commessa',
            'create_intervention_from_whatsapp', 'create_intervention_from_email' => 'Creare un nuovo intervento',
            'update_intervention_schedule_from_whatsapp', 'update_intervention_schedule_from_email' => 'Aggiornare data/ora di un intervento',
            default => 'Azione proposta',
        };
    }

    private function proposalRequiresWorkOrder(string $type): bool
    {
        return str_contains($type, 'append_work_order_activity') || str_contains($type, 'create_intervention');
    }

    private function proposalRequiresIntervention(string $type): bool
    {
        return str_contains($type, 'update_intervention_schedule');
    }

    private function proposalUsesSchedule(string $type): bool
    {
        return str_contains($type, 'create_intervention') || str_contains($type, 'update_intervention_schedule');
    }

    private function proposalSupportsMaterials(string $type): bool
    {
        return str_contains($type, 'create_intervention') || str_contains($type, 'update_intervention_schedule');
    }

    private function proposalSupportsLabor(string $type): bool
    {
        return str_contains($type, 'create_intervention') || str_contains($type, 'update_intervention_schedule');
    }

    private function formatScheduleForInput(string $value): string
    {
        if (trim($value) === '') {
            return '';
        }

        try {
            return CarbonImmutable::parse($value)->format('Y-m-d\TH:i');
        } catch (\Throwable) {
            return $value;
        }
    }

    private function positiveInt(mixed $value): ?int
    {
        if (! is_numeric($value)) {
            return null;
        }

        $int = (int) $value;

        return $int > 0 ? $int : null;
    }

    private function positiveFloat(mixed $value): ?float
    {
        if (is_string($value)) {
            $value = str_replace(',', '.', $value);
        }

        if (! is_numeric($value)) {
            return null;
        }

        $float = round((float) $value, 2);

        return $float > 0 ? $float : null;
    }

    private function extractEuroAmount(string $text): ?float
    {
        if (preg_match('/(\d+(?:[,.]\d{1,2})?)\s*(?:euro|eur|€)/i', $text, $matches) !== 1 || ! isset($matches[1])) {
            return null;
        }

        return $this->positiveFloat($matches[1]);
    }

    private function extractMaterialEuroAmount(string $text): ?float
    {
        return $this->extractEuroAmountNearKeywords($text, '(?:materiali?|ricambi?|pezzi|tubi?|cavi?|raccordi?)');
    }

    private function extractLaborEuroAmount(string $text): ?float
    {
        return $this->extractEuroAmountNearKeywords($text, '(?:manodopera|lavoro|ore|operaio|tecnico)');
    }

    private function extractEuroAmountNearKeywords(string $text, string $keywordPattern): ?float
    {
        $amountPattern = '(\d+(?:[,.]\d{1,2})?)\s*(?:euro|eur|€)';
        if (preg_match('/'.$keywordPattern.'[^\d€]{0,40}'.$amountPattern.'/i', $text, $matches) === 1 && isset($matches[1])) {
            return $this->positiveFloat($matches[1]);
        }

        if (preg_match('/'.$amountPattern.'[^\w]{0,20}(?:di|per)?[^\w]{0,10}'.$keywordPattern.'/i', $text, $matches) === 1 && isset($matches[1])) {
            return $this->positiveFloat($matches[1]);
        }

        return null;
    }

    private function guessMaterialDescription(string $text): string
    {
        return preg_match('/material|ricamb|pezzi|tub|cav|raccord/i', $text) === 1 ? 'Materiali extra' : '';
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    private function fullCommunicationMessage(array $payload): string
    {
        $source = (string) ($payload['source'] ?? '');
        if ($source === 'whatsapp' && is_numeric($payload['whatsapp_message_id'] ?? null)) {
            $body = WhatsAppMessage::query()
                ->whereKey((int) $payload['whatsapp_message_id'])
                ->value('body');

            return is_string($body) ? $body : '';
        }

        if ($source === 'email' && is_numeric($payload['email_message_id'] ?? null)) {
            /** @var EmailMessage|null $message */
            $message = EmailMessage::query()
                ->whereKey((int) $payload['email_message_id'])
                ->first(['subject', 'body']);

            if ($message instanceof EmailMessage) {
                return trim(((string) data_get($message, 'subject', ''))."\n\n".((string) data_get($message, 'body', '')));
            }
        }

        return (string) ($payload['body'] ?? '');
    }

    /**
     * @return array<string, mixed>
     */
    private function proposalContext(AiActionProposal $proposal): array
    {
        $message = $proposal->thread?->messages()
            ->whereNotNull('payload')
            ->orderByDesc('id')
            ->first();

        $context = is_array($message?->payload) ? ($message->payload['context'] ?? []) : [];

        return $this->stringKeyArray($context);
    }

    /**
     * @return array<string, mixed>
     */
    private function stringKeyArray(mixed $value): array
    {
        if (! is_array($value)) {
            return [];
        }

        $normalized = [];
        foreach ($value as $key => $item) {
            if (is_string($key)) {
                $normalized[$key] = $item;
            }
        }

        return $normalized;
    }

    /**
     * @param  array<string, mixed>  $context
     * @param  array<string, mixed>  $payload
     * @return array<int, string>
     */
    private function workOrderOptions(array $context, array $payload, string $publicId): array
    {
        $options = [];
        $workOrders = is_array($context['work_orders'] ?? null) ? $context['work_orders'] : [];

        foreach ($workOrders as $workOrder) {
            if (! is_array($workOrder) || ! is_numeric($workOrder['id'] ?? null)) {
                continue;
            }

            $id = (int) $workOrder['id'];
            $parts = array_filter([
                '#'.$id,
                (string) ($workOrder['code'] ?? ''),
                (string) ($workOrder['title'] ?? ''),
                (string) ($workOrder['site'] ?? ''),
                (string) ($workOrder['status'] ?? ''),
            ]);
            $options[$id] = implode(' - ', $parts);
        }

        foreach ($this->searchedWorkOrderOptions((string) ($this->proposalWorkOrderSearches[$publicId] ?? '')) as $id => $label) {
            $options[$id] = $label;
        }

        $selectedId = $this->positiveInt($payload['work_order_id'] ?? null);
        if ($selectedId !== null && ! isset($options[$selectedId])) {
            $options[$selectedId] = 'Commessa #'.$selectedId;
        }

        return $options;
    }

    /**
     * @return array<int, string>
     */
    private function searchedWorkOrderOptions(string $search): array
    {
        $search = trim($search);
        if (mb_strlen($search) < 2) {
            return [];
        }

        return WorkOrder::query()
            ->with(['customer', 'site', 'status'])
            ->where(function ($query) use ($search): void {
                $query
                    ->where('code', 'like', '%'.$search.'%')
                    ->orWhere('title', 'like', '%'.$search.'%')
                    ->orWhere('description', 'like', '%'.$search.'%')
                    ->orWhereHas('customer', fn ($q) => $q->where('legal_name', 'like', '%'.$search.'%'))
                    ->orWhereHas('site', fn ($q) => $q->where('name', 'like', '%'.$search.'%'));
            })
            ->orderByDesc('updated_at')
            ->limit(25)
            ->get()
            ->mapWithKeys(fn (WorkOrder $workOrder): array => [
                (int) $workOrder->id => implode(' - ', array_filter([
                    '#'.(int) $workOrder->id,
                    (string) ($workOrder->code ?? ''),
                    (string) $workOrder->title,
                    (string) data_get($workOrder, 'customer.legal_name', ''),
                    (string) data_get($workOrder, 'site.name', ''),
                    (string) data_get($workOrder, 'status.name', ''),
                ])),
            ])
            ->all();
    }

    /**
     * @param  array<string, mixed>  $context
     * @param  array<string, mixed>  $payload
     * @return array<int, string>
     */
    private function interventionOptions(array $context, array $payload): array
    {
        $options = [];
        $interventions = is_array($context['interventions'] ?? null) ? $context['interventions'] : [];

        foreach ($interventions as $intervention) {
            if (! is_array($intervention) || ! is_numeric($intervention['id'] ?? null)) {
                continue;
            }

            $id = (int) $intervention['id'];
            $parts = array_filter([
                '#'.$id,
                is_numeric($intervention['work_order_id'] ?? null) ? 'commessa #'.(int) $intervention['work_order_id'] : '',
                (string) ($intervention['scheduled_at'] ?? ''),
                (string) ($intervention['notes'] ?? ''),
            ]);
            $options[$id] = implode(' - ', $parts);
        }

        $selectedId = $this->positiveInt($payload['intervention_id'] ?? null);
        if ($selectedId !== null && ! isset($options[$selectedId])) {
            $options[$selectedId] = 'Intervento #'.$selectedId;
        }

        return $options;
    }

    private function workOrderCreateUrl(): string
    {
        return url(($this->panelId === 'operator' ? '/operator' : '/admin').'/work-orders/create');
    }

    private function workOrderIndexUrl(): string
    {
        return url(($this->panelId === 'operator' ? '/operator' : '/admin').'/work-orders');
    }

    /**
     * @return array<string, array<int, string>>
     */
    private function buildExampleGroups(): array
    {
        if ($this->panelId === 'admin') {
            $groups = [];

            if ($this->canUseBilling) {
                $groups['Fatture'] = [
                    'Fammi vedere le ultime fatture del cliente Rossi',
                    'Cerca la fattura 12',
                    'Mostrami le fatture della commessa luci scale',
                ];
            }

            if ($this->canUseQuotations) {
                $groups['Preventivi'] = [
                    'Fammi vedere i preventivi in bozza del cliente Bianchi',
                    'Cerca il preventivo della commessa quadro scala A',
                    'Mostrami gli ultimi preventivi confermati',
                ];
            }

            if ($this->canUseWorkOrders || $this->canUseCustomers) {
                $groups['Clienti e commesse'] = array_values(array_filter([
                    $this->canUseWorkOrders ? 'Apri la commessa del Condominio Parco Verde' : null,
                    $this->canUseWorkOrders ? 'Mostrami le commesse aperte del cliente Rossi' : null,
                    $this->canUseWorkOrders ? 'Qual e il referente della commessa luci scale?' : null,
                    $this->canUseCustomers ? 'Cerca il cliente Bianchi e la sua sede' : null,
                ]));
            }

            if ($this->canUseInterventions) {
                $groups['Interventi'] = [
                    'Mostrami l ultimo intervento del Condominio Aurora',
                    'Cerca gli interventi aperti del cliente Rossi',
                    'Apri l intervento della commessa quadro scala A',
                ];
            }

            if ($this->canUseMaterials) {
                $groups['Magazzino'] = [
                    'Quanta giacenza ho del cavo 3x1.5 nel magazzino centrale?',
                    'Cerca il corrugato da 25 nel deposito',
                    'Mostrami il materiale disponibile nel furgone di Gerardo',
                ];
            }

            if ($this->canUseProduction) {
                $groups['Produzione'] = [
                    'Crea un ordine di produzione per quadro scala A',
                    'Porta in corso la fase taglio del quadro laboratorio',
                ];
            }

            if ($this->canUseEnergyBrokerDocuments) {
                $groups['Energy Broker'] = [
                    'Cerca le bollette Energy Broker ancora in bozza',
                    'Mostrami il riepilogo del contratto Energy Broker appena importato',
                    'Prepara la proposta di conferma import per il documento Energy Broker, ma segnala eventuali dubbi',
                ];
            }

            return $groups;
        }

        $groups = [];

        if ($this->canUseInterventions) {
            $groups['Timbrature e interventi'] = [
                'Avvia la timbratura sull intervento aperto',
                'Avvia la timbratura sull ultimo intervento della commessa luci scale',
                'Ferma la timbratura dell intervento del Condominio Parco Verde',
                'Chiudi l intervento aperto del condominio di via Roma',
                'Mostrami l ultimo intervento della commessa quadro scala A',
            ];
        }

        if ($this->canUseDailyNotes) {
            $groups['Note giornaliere'] = [
                'Aggiungi alle note giornaliere che ho sostituito due plafoniere',
                'Scrivi nelle note di oggi che manca il materiale',
            ];
        }

        if ($this->canUseWorkOrders) {
            $groups['Commesse'] = [
                'Apri la commessa del Condominio Parco Verde',
                'Qual e il referente della commessa luci scale?',
                'Segna come completata la commessa quadro scala A',
            ];
        }

        if ($this->canUseMaterials) {
            $groups['Materiali e magazzino'] = [
                'Scarica 20 metri di cavo 3x1.5 dal furgone',
                'Quanta giacenza ho del magnetotermico 16 ampere?',
                'Cerca il corrugato da 25 nel deposito',
                'Aggiungi 4 prese all intervento aperto',
            ];
        }

        return $groups;
    }

    /**
     * @return array<int, array{label: string, prompt: string}>
     */
    private function buildQuickPrompts(): array
    {
        if ($this->panelId === 'admin') {
            return array_values(array_filter([
                $this->canUseBilling ? ['label' => 'Fatture cliente', 'prompt' => 'Fammi vedere le ultime fatture del cliente Rossi'] : null,
                $this->canUseQuotations ? ['label' => 'Preventivi', 'prompt' => 'Fammi vedere i preventivi in bozza del cliente Bianchi'] : null,
                $this->canUseWorkOrders ? ['label' => 'Commesse aperte', 'prompt' => 'Mostrami le commesse aperte del cliente Rossi'] : null,
                $this->canUseMaterials ? ['label' => 'Giacenza', 'prompt' => 'Quanta giacenza ho del cavo 3x1.5 nel magazzino centrale?'] : null,
                $this->canUseEnergyBrokerDocuments ? ['label' => 'Documenti broker', 'prompt' => 'Cerca i documenti Energy Broker in bozza e dimmi quali posso confermare'] : null,
            ]));
        }

        return array_values(array_filter([
            $this->canUseInterventions ? ['label' => 'Avvia timbratura', 'prompt' => $this->contextInterventionId ? 'Avvia la timbratura dell intervento aperto in questa schermata.' : 'Avvia la timbratura sull intervento aperto.'] : null,
            $this->canUseInterventions ? ['label' => 'Stop timbratura', 'prompt' => $this->contextInterventionId ? 'Ferma la timbratura dell intervento aperto in questa schermata.' : 'Ferma la timbratura dell intervento aperto.'] : null,
            $this->canUseDailyNotes ? ['label' => 'Nota giornaliera', 'prompt' => 'Aggiungi alle note giornaliere di oggi: '] : null,
            $this->canUseInterventions ? ['label' => 'Chiudi intervento', 'prompt' => $this->contextInterventionId ? 'Chiudi l intervento aperto in questa schermata con esito completato. Nota: ' : 'Chiudi l intervento aperto del cliente Rossi con esito completato. Nota: '] : null,
            $this->canUseMaterials ? ['label' => 'Materiali', 'prompt' => 'Aggiungi materiale all intervento aperto: cerca il prodotto e prepara la proposta.'] : null,
            $this->canUseWorkOrders ? ['label' => 'Apri commessa', 'prompt' => 'Apri la commessa del Condominio Parco Verde'] : null,
        ]));
    }

    private function buildInputPlaceholder(): string
    {
        return 'Scrivi qui…';
    }

    private function bootCapabilities(): void
    {
        $user = Auth::user();

        $this->canUseInterventions = $this->panelFeature('admin.interventions', 'operator.interventions', true, true);
        $this->canUseWorkOrders = $this->panelFeature('admin.work_orders', 'operator.work_orders', true, true);
        $this->canUseCustomers = $this->panelFeature('admin.customers', null, true, false);
        $this->canUseBilling = $this->panelFeature('admin.billing', null, true, false);
        $this->canUseQuotations = $this->panelFeature('admin.quotations', 'operator.quotations', true, false);
        $this->canUseProduction = $this->panelFeature('admin.production', null, true, false);
        $this->canUseEnergyBrokerDocuments = $this->panelId === 'admin'
            && $this->panelFeature('energy_broker.documents', null, true, false)
            && $user instanceof User
            && $user->can('ViewAny:EnergyBroker');
        $this->canUseDailyNotes = $this->panelId === 'operator'
            && $this->panelFeature(null, 'operator.daily_notes', false, true)
            && app(SettingsRepository::class)->getBool('modules.operator.daily_notes', true);
        $this->canUseMaterials = $this->panelId === 'admin'
            ? $this->panelFeature('admin.inventory', null, true, false)
            : $this->panelFeature(null, 'operator.materials', false, false) && $this->materialsOnInterventionsEnabled;
    }

    private function panelFeature(?string $adminFeature, ?string $operatorFeature, bool $adminDefault, bool $operatorDefault): bool
    {
        $user = Auth::user();

        return AccountFeatures::currentPanelAllowsFeature(
            $user,
            adminFeature: $adminFeature,
            operatorFeature: $operatorFeature,
            adminDefault: $adminDefault,
            operatorDefault: $operatorDefault,
            fallback: false,
        );
    }

    private function resolveInterventionLabel(?int $interventionId): ?string
    {
        if ($interventionId === null || $interventionId <= 0) {
            return null;
        }

        /** @var Intervention|null $intervention */
        $intervention = Intervention::query()
            ->with(['workOrder.customer', 'workOrder.site'])
            ->find($interventionId);

        if (! $intervention instanceof Intervention) {
            return null;
        }

        $customer = (string) data_get($intervention, 'workOrder.customer.legal_name', '');
        $site = (string) data_get($intervention, 'workOrder.site.name', '');
        $title = (string) data_get($intervention, 'workOrder.title', '');

        return trim(implode(' - ', array_values(array_filter([$customer, $site !== '' ? $site : $title]))));
    }

    private function resolveWarehouseLabel(?int $warehouseId): ?string
    {
        if ($warehouseId === null || $warehouseId <= 0) {
            return null;
        }

        /** @var Warehouse|null $warehouse */
        $warehouse = Warehouse::query()->find($warehouseId);

        return $warehouse instanceof Warehouse ? (string) data_get($warehouse, 'name', '') : null;
    }
}
