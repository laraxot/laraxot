<?php

declare(strict_types=1);

namespace Modules\AiAssistant\Filament\Pages;

use Carbon\CarbonImmutable;
use Filament\Notifications\Notification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\AiAssistant\Actions\ExecuteAiActionProposalAction;
use Modules\AiAssistant\Models\AiActionProposal;
use Modules\Email\Models\EmailMessage;
use Modules\Platform\Settings\SettingsRepository;
use Modules\WhatsApp\Models\WhatsAppConversation;
use Modules\WhatsApp\Models\WhatsAppMessage;
use Modules\WorkOrder\Models\WorkOrder;
use Modules\Xot\Filament\Pages\XotBasePage;

use function Safe\preg_match;

final class AiCommunicationsMonitor extends XotBasePage
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    protected static ?string $navigationLabel = 'Messaggi clienti';

    protected static ?string $title = 'Messaggi clienti';

    protected static ?string $slug = 'settings/ai-messages';

    protected static ?int $navigationSort = 2;

    protected string $view = 'aiassistant::filament.pages.ai-communications-monitor';

    public string $channel = 'all';

    public string $status = 'needs_review';

    public int $limit = 100;

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

    /** @var array<string, string> */
    public array $proposalCustomerNames = [];

    /** @var array<string, string> */
    public array $proposalAddresses = [];

    /** @var array<string, string> */
    public array $proposalProblems = [];

    public static function shouldRegisterNavigation(): bool
    {
        return Gate::allows('manage-email-ai') || Gate::allows('manage-whatsapp-ai');
    }

    public static function canAccess(): bool
    {
        return self::shouldRegisterNavigation();
    }

    public static function getNavigationGroup(): ?string
    {
        return null;
    }

    public static function getNavigationBadge(): ?string
    {
        if (! self::canAccess()) {
            return null;
        }

        $count = WhatsAppMessage::query()->where('processing_status', 'needs_review')->count()
            + EmailMessage::query()->where('processing_status', 'needs_review')->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): string
    {
        return 'warning';
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function messages(): array
    {
        $items = [];
        $limit = max(10, min($this->limit, 300));

        if ($this->channel === 'all' || $this->channel === 'email') {
            $items = array_merge($items, $this->emailMessages($limit));
        }

        if ($this->channel === 'all' || $this->channel === 'whatsapp') {
            $items = array_merge($items, $this->whatsAppConversations($limit));
        }

        usort($items, static function (array $a, array $b): int {
            $aSort = is_string($a['sort_at'] ?? null) ? $a['sort_at'] : '';
            $bSort = is_string($b['sort_at'] ?? null) ? $b['sort_at'] : '';

            return strcmp($bSort, $aSort);
        });

        return array_slice($items, 0, $limit);
    }

    public function approveProposal(string $proposalPublicId): void
    {
        if (! self::canAccess()) {
            Notification::make()->title('Non autorizzato')->danger()->send();

            return;
        }

        /** @var AiActionProposal|null $proposal */
        $proposal = AiActionProposal::query()
            ->where('public_id', $proposalPublicId)
            ->where('status', 'pending')
            ->first();

        if (! $proposal instanceof AiActionProposal) {
            Notification::make()->title('Proposta non trovata o gia gestita')->warning()->send();

            return;
        }

        $userId = Auth::id();
        if (! is_int($userId)) {
            Notification::make()->title('Utente non valido')->danger()->send();

            return;
        }

        if (! $this->applyEditedPayload($proposal)) {
            return;
        }

        $result = app(ExecuteAiActionProposalAction::class)->execute($proposal, $userId);
        $ok = (bool) ($result['ok'] ?? false);

        $errorMsg = is_string($result['error'] ?? null) ? $result['error'] : 'Errore sconosciuto.';
        $notification = Notification::make()
            ->title($ok ? 'Proposta approvata ed eseguita' : 'Esecuzione proposta fallita')
            ->body($ok ? null : $errorMsg);

        ($ok ? $notification->success() : $notification->danger())->send();
    }

    public function cancelProposal(string $proposalPublicId): void
    {
        if (! self::canAccess()) {
            Notification::make()->title('Non autorizzato')->danger()->send();

            return;
        }

        /** @var AiActionProposal|null $proposal */
        $proposal = AiActionProposal::query()
            ->where('public_id', $proposalPublicId)
            ->where('status', 'pending')
            ->first();

        if (! $proposal instanceof AiActionProposal) {
            Notification::make()->title('Proposta non trovata o gia gestita')->warning()->send();

            return;
        }

        $proposal->forceFill([
            'status' => 'cancelled',
            'confirmed_by_user_id' => Auth::id(),
            'confirmed_at' => now(),
        ])->save();

        Notification::make()->title('Proposta scartata')->success()->send();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function emailMessages(int $limit): array
    {
        return EmailMessage::query()
            ->with(['conversation', 'proposal'])
            ->when($this->status !== 'all', fn ($query) => $query->where('processing_status', $this->status))
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get()
            ->map(function (EmailMessage $message): array {
                $direction = data_get($message, 'direction', '');
                $directionStr = is_string($direction) ? $direction : '';
                $contactVal = data_get($message, 'conversation.email') ?? data_get($message, 'from_email') ?? data_get($message, 'to_email') ?? '';
                $contactStr = is_string($contactVal) ? $contactVal : '';
                $subject = data_get($message, 'subject') ?? 'Email senza oggetto';
                $titleStr = is_string($subject) ? $subject : 'Email senza oggetto';
                $body = data_get($message, 'body', '');
                $bodyStr = is_string($body) ? $body : '';
                $status = data_get($message, 'status', '');
                $statusStr = is_string($status) ? $status : '';
                $processingStatus = data_get($message, 'processing_status', '');
                $processingStatusStr = is_string($processingStatus) ? $processingStatus : '';
                $error = data_get($message, 'error', '');
                $errorStr = is_string($error) ? $error : '';

                return $this->mapMessage(
                    channel: 'email',
                    id: (int) $message->id,
                    direction: $directionStr,
                    contact: $contactStr,
                    title: $titleStr,
                    contactSub: '',
                    body: $bodyStr,
                    status: $statusStr,
                    processingStatus: $processingStatusStr,
                    error: $errorStr,
                    aiResult: $this->stringKeyArray(data_get($message, 'ai_result')),
                    proposal: $message->proposal,
                    workOrderId: is_numeric(data_get($message, 'work_order_id')) ? (int) data_get($message, 'work_order_id') : null,
                    interventionId: is_numeric(data_get($message, 'intervention_id')) ? (int) data_get($message, 'intervention_id') : null,
                    receivedAt: $this->formatDateTime(data_get($message, 'received_at')),
                    processedAt: $this->formatDateTime(data_get($message, 'processed_at')),
                    sortAt: $this->iso8601(data_get($message, 'created_at')),
                );
            })
            ->all();
    }

    /**
     * One card per conversation (not per message): shows the whole thread and
     * the single "live" proposal that covers the current request.
     *
     * @return array<int, array<string, mixed>>
     */
    private function whatsAppConversations(int $limit): array
    {
        $query = WhatsAppConversation::query()
            ->with('customer')
            ->orderByDesc('last_message_at')
            ->orderByDesc('id')
            ->limit($limit);

        if ($this->status === 'needs_review') {
            $query->whereNotNull('active_proposal_id');
        }

        $items = [];
        foreach ($query->get() as $conversation) {
            $proposal = data_get($conversation, 'active_proposal_id') !== null
                ? AiActionProposal::query()->find(data_get($conversation, 'active_proposal_id'))
                : null;
            $pendingProposal = $proposal instanceof AiActionProposal && $proposal->status === 'pending' ? $proposal : null;

            if ($this->status === 'needs_review' && $pendingProposal === null) {
                continue;
            }

            $thread = $conversation->messages()
                ->orderByDesc('created_at')->orderByDesc('id')->limit(15)->get()
                ->reverse()
                ->map(function (WhatsAppMessage $message): array {
                    $createdAt = data_get($message, 'created_at');
                    $body = data_get($message, 'body', '');
                    $bodyStr = is_string($body) ? $body : '';

                    return [
                        'from' => data_get($message, 'direction') === 'inbound' ? 'cliente' : 'noi',
                        'text' => trim($bodyStr),
                        'at' => $createdAt instanceof Carbon ? $createdAt->format('H:i') : null,
                    ];
                })
                ->map(static function (array $entry): array {
                    $from = is_string($entry['from']) ? $entry['from'] : '';
                    $text = is_string($entry['text']) ? $entry['text'] : '';

                    return [
                        'from' => $from,
                        'text' => $text,
                        'at' => is_string($entry['at']) ? $entry['at'] : null,
                    ];
                })
                ->filter(fn (array $entry): bool => $entry['text'] !== '')
                ->values()
                ->all();

            $thread = $this->normalizeThread($thread);

            $phoneValue = data_get($conversation, 'phone_e164', '');
            $phone = is_string($phoneValue) ? $phoneValue : '';
            $customer = $conversation->customer;
            $linkedNameValue = $customer !== null ? data_get($customer, 'legal_name', '') : '';
            $linkedName = trim(is_string($linkedNameValue) ? $linkedNameValue : '');
            $extractedName = '';
            if ($pendingProposal instanceof AiActionProposal) {
                $payload = $pendingProposal->payload ?? [];
                $customerNameValue = $payload['customer_name'] ?? '';
                $extractedName = trim(is_string($customerNameValue) ? $customerNameValue : '');
            }
            $displayName = $linkedName !== '' ? $linkedName : ($extractedName !== '' ? $extractedName : $phone);

            $statusValue = data_get($conversation, 'status', '');
            $statusStr = is_string($statusValue) ? $statusValue : '';
            $items[] = $this->mapMessage(
                channel: 'whatsapp',
                id: (int) $conversation->id,
                direction: 'inbound',
                contact: $displayName,
                contactSub: $displayName !== $phone ? $phone : '',
                title: 'Conversazione WhatsApp',
                body: '',
                status: $statusStr,
                processingStatus: $pendingProposal !== null ? 'needs_review' : 'processed',
                error: '',
                aiResult: [],
                proposal: $pendingProposal,
                workOrderId: null,
                interventionId: null,
                receivedAt: $this->formatDateTime(data_get($conversation, 'last_message_at')),
                processedAt: null,
                sortAt: $this->iso8601(data_get($conversation, 'last_message_at')),
                thread: $thread,
            );
        }

        return $items;
    }

    /**
     * @param  array<string, mixed>  $aiResult
     * @param  list<array{from: string, text: string, at: string|null}>  $thread
     * @return array<string, mixed>
     */
    private function mapMessage(
        string $channel,
        int $id,
        string $direction,
        string $contact,
        string $title,
        string $contactSub,
        string $body,
        string $status,
        string $processingStatus,
        string $error,
        array $aiResult,
        ?AiActionProposal $proposal,
        ?int $workOrderId,
        ?int $interventionId,
        ?string $receivedAt,
        ?string $processedAt,
        string $sortAt,
        array $thread = [],
    ): array {
        return [
            'channel' => $channel,
            'id' => $id,
            'direction' => $direction,
            'contact' => $contact,
            'contact_sub' => $contactSub,
            'title' => $title,
            'body' => trim($body),
            'thread' => $thread,
            'status' => $status,
            'processing_status' => $processingStatus,
            'status_label' => $this->statusLabel($processingStatus),
            'status_class' => $this->statusClass($processingStatus),
            'error' => $error,
            'ai_summary' => is_string($aiResult['summary'] ?? null) ? $aiResult['summary'] : '',
            'ai_intent' => is_string($aiResult['intent'] ?? null) ? $aiResult['intent'] : '',
            'ai_confidence' => is_numeric($aiResult['confidence'] ?? null) ? (int) $aiResult['confidence'] : null,
            'ai_result' => $aiResult,
            'proposal_public_id' => $proposal?->public_id ? (string) $proposal->public_id : null,
            'proposal_status' => $proposal?->status ? (string) $proposal->status : null,
            'proposal_type' => $proposal?->type ? (string) $proposal->type : null,
            'proposal_action_label' => $proposal?->type ? $this->proposalActionLabel((string) $proposal->type) : null,
            'proposal_preview' => $proposal?->preview ? (string) $proposal->preview : null,
            'proposal_creates_summary' => $proposal instanceof AiActionProposal
                ? $this->proposalCreatesSummary((string) $proposal->type, is_array($proposal->payload) ? $proposal->payload : [])
                : null,
            'proposal_payload' => $this->fillProposalFormState($proposal),
            'proposal_requires_work_order' => $proposal?->type ? $this->proposalRequiresWorkOrder((string) $proposal->type) : false,
            'proposal_requires_intervention' => $proposal?->type ? $this->proposalRequiresIntervention((string) $proposal->type) : false,
            'proposal_uses_schedule' => $proposal?->type ? $this->proposalUsesSchedule((string) $proposal->type) : false,
            'proposal_supports_materials' => $proposal?->type ? $this->proposalSupportsMaterials((string) $proposal->type) : false,
            'proposal_supports_labor' => $proposal?->type ? $this->proposalSupportsLabor((string) $proposal->type) : false,
            'proposal_is_organize' => $proposal?->type === 'organize_whatsapp_request',
            'work_order_options' => ($proposal instanceof AiActionProposal && $this->proposalRequiresWorkOrder((string) $proposal->type)) ? $this->workOrderOptions($this->proposalContext($proposal), is_array($proposal->payload) ? $proposal->payload : [], (string) $proposal->public_id) : [],
            'intervention_options' => ($proposal instanceof AiActionProposal && $this->proposalRequiresIntervention((string) $proposal->type)) ? $this->interventionOptions($this->proposalContext($proposal), is_array($proposal->payload) ? $proposal->payload : []) : [],
            'work_order_create_url' => url('/admin/work-orders/create'),
            'work_order_index_url' => url('/admin/work-orders'),
            'work_order_id' => $workOrderId,
            'intervention_id' => $interventionId,
            'received_at' => $receivedAt,
            'processed_at' => $processedAt,
            'sort_at' => $sortAt,
        ];
    }

    private function statusLabel(string $status): string
    {
        return match ($status) {
            'pending' => 'In coda',
            'processing' => 'In elaborazione',
            'needs_review' => 'Da approvare',
            'processed' => 'Elaborato',
            'failed' => 'Errore',
            default => $status,
        };
    }

    private function statusClass(string $status): string
    {
        return match ($status) {
            'pending' => 'bg-amber-50 text-amber-800 ring-amber-600/20 dark:bg-amber-400/10 dark:text-amber-200',
            'processing' => 'bg-sky-50 text-sky-800 ring-sky-600/20 dark:bg-sky-400/10 dark:text-sky-200',
            'needs_review' => 'bg-purple-50 text-purple-800 ring-purple-600/20 dark:bg-purple-400/10 dark:text-purple-200',
            'processed' => 'bg-emerald-50 text-emerald-800 ring-emerald-600/20 dark:bg-emerald-400/10 dark:text-emerald-200',
            'failed' => 'bg-rose-50 text-rose-800 ring-rose-600/20 dark:bg-rose-400/10 dark:text-rose-200',
            default => 'bg-gray-50 text-gray-700 ring-gray-600/20 dark:bg-white/10 dark:text-gray-200',
        };
    }

    /**
     * @return array<string, mixed>
     */
    private function fillProposalFormState(?AiActionProposal $proposal): array
    {
        if (! $proposal instanceof AiActionProposal || ! is_array($proposal->payload)) {
            return [];
        }

        $publicIdValue = $proposal->public_id;
        $publicId = is_string($publicIdValue) ? $publicIdValue : '';
        $payload = $proposal->payload;
        $this->proposalWorkOrderIds[$publicId] ??= is_numeric($payload['work_order_id'] ?? null) ? (int) $payload['work_order_id'] : '';
        $this->proposalWorkOrderSearches[$publicId] ??= '';
        $this->proposalInterventionIds[$publicId] ??= is_numeric($payload['intervention_id'] ?? null) ? (int) $payload['intervention_id'] : '';
        $bodyValue = $payload['body'] ?? $payload['notes'] ?? $payload['summary'] ?? '';
        $this->proposalBodies[$publicId] ??= is_string($bodyValue) ? $bodyValue : '';
        $scheduledAtValue = $payload['scheduled_at'] ?? '';
        $this->proposalScheduledAts[$publicId] ??= $this->formatScheduleForInput(is_string($scheduledAtValue) ? $scheduledAtValue : '');
        $materialLines = is_array($payload['material_lines'] ?? null) ? $payload['material_lines'] : [];
        $firstMaterialLine = $materialLines[0] ?? null;
        $materialLine = is_array($firstMaterialLine) ? $firstMaterialLine : [];
        $descValue = $materialLine['description'] ?? null;
        $bodyForGuess = $payload['body'] ?? $payload['notes'] ?? '';
        $bodyForGuessStr = is_string($bodyForGuess) ? $bodyForGuess : '';
        $this->proposalMaterialDescriptions[$publicId] ??= is_string($descValue) ? $descValue : $this->guessMaterialDescription($bodyForGuessStr);
        $messageText = is_string($bodyForGuess) ? $bodyForGuess : '';
        $materialCost = $this->positiveFloat($materialLine['unit_price'] ?? null) ?? $this->extractMaterialEuroAmount($messageText) ?? $this->extractEuroAmount($messageText);
        $this->proposalMaterialCosts[$publicId] ??= $materialCost !== null ? number_format($materialCost, 2, '.', '') : '';
        $laborAmount = $this->positiveFloat($payload['labor_amount'] ?? $payload['amount'] ?? null) ?? $this->extractLaborEuroAmount($messageText);
        $this->proposalLaborAmounts[$publicId] ??= $laborAmount !== null ? number_format($laborAmount, 2, '.', '') : '';

        // New service request: prefill the three editable fields.
        $customerNameValue = $payload['customer_name'] ?? '';
        $this->proposalCustomerNames[$publicId] ??= is_string($customerNameValue) ? $customerNameValue : '';
        $addressValue = $payload['address'] ?? '';
        $cityValue = $payload['city'] ?? '';
        $addressStr = is_string($addressValue) ? $addressValue : '';
        $cityStr = is_string($cityValue) ? $cityValue : '';
        $this->proposalAddresses[$publicId] ??= trim($addressStr.' '.$cityStr);
        $problemValue = $payload['problem'] ?? $payload['summary'] ?? $messageText;
        $this->proposalProblems[$publicId] ??= is_string($problemValue) ? $problemValue : '';

        return $this->stringKeyArray($payload);
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

        if ($type === 'organize_whatsapp_request') {
            $customerName = trim((string) ($this->proposalCustomerNames[$publicId] ?? ''));
            if ($customerName === '') {
                Notification::make()->title('Inserisci il nome del cliente')->danger()->send();

                return false;
            }

            $address = trim((string) ($this->proposalAddresses[$publicId] ?? ''));
            $problem = trim((string) ($this->proposalProblems[$publicId] ?? ''));

            $payload['customer_name'] = $customerName;
            $payload['address'] = $address;
            $payload['problem'] = $problem.($address !== '' ? "\nIndirizzo: {$address}" : '');

            $appointment = trim((string) ($this->proposalScheduledAts[$publicId] ?? ''));
            if ($appointment !== '') {
                $payload['scheduled_at'] = $appointment;
            }
        }

        $proposal->forceFill(['payload' => $payload])->save();

        return true;
    }

    private function proposalActionLabel(string $type): string
    {
        return match ($type) {
            'organize_whatsapp_request' => 'Crea cliente e apri il lavoro',
            'append_work_order_activity_from_whatsapp', 'append_work_order_activity_from_email' => 'Archiviare una nota sulla commessa',
            'create_intervention_from_whatsapp', 'create_intervention_from_email' => 'Creare un nuovo intervento',
            'update_intervention_schedule_from_whatsapp', 'update_intervention_schedule_from_email' => 'Aggiornare data/ora di un intervento',
            default => 'Azione proposta',
        };
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    private function proposalCreatesSummary(string $type, array $payload): string
    {
        if ($type === 'organize_whatsapp_request') {
            $customerNameValue = $payload['customer_name'] ?? '';
            $name = trim(is_string($customerNameValue) ? $customerNameValue : '');
            $directMode = app(SettingsRepository::class)
                ->getString('interventions.work_mode', 'work_order') === 'customer';
            $what = $directMode ? 'cliente e intervento' : 'cliente, commessa e intervento';

            $when = '';
            $scheduledValue = $payload['scheduled_at'] ?? '';
            $scheduled = trim(is_string($scheduledValue) ? $scheduledValue : '');
            if ($scheduled !== '') {
                try {
                    $when = ' per '.CarbonImmutable::parse($scheduled)->translatedFormat('l d/m \a\l\l\e H:i');
                } catch (\Throwable) {
                }
            }

            return 'Creo '.$what.($name !== '' ? ' «'.$name.'»' : '').$when;
        }

        return match ($type) {
            'append_work_order_activity_from_whatsapp', 'append_work_order_activity_from_email' => 'Aggiungo una nota alla commessa selezionata',
            'create_intervention_from_whatsapp', 'create_intervention_from_email' => 'Creo un intervento sulla commessa selezionata',
            'update_intervention_schedule_from_whatsapp', 'update_intervention_schedule_from_email' => "Aggiorno la data/ora dell'intervento",
            default => '',
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
     * @param  array<int, array<string, mixed>>  $thread
     * @return list<array{from: string, text: string, at: string|null}>
     */
    private function normalizeThread(array $thread): array
    {
        $normalized = [];
        foreach ($thread as $entry) {
            $fromValue = $entry['from'] ?? '';
            $textValue = $entry['text'] ?? '';
            $normalized[] = [
                'from' => is_string($fromValue) ? $fromValue : '',
                'text' => is_string($textValue) ? $textValue : '',
                'at' => is_string($entry['at'] ?? null) ? $entry['at'] : null,
            ];
        }

        return $normalized;
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

    private function formatDateTime(mixed $value): ?string
    {
        return $value instanceof Carbon ? $value->format('d/m/Y H:i') : null;
    }

    private function iso8601(mixed $value): string
    {
        return $value instanceof Carbon ? $value->toIso8601String() : '';
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
            $code = is_string($workOrder['code'] ?? null) ? $workOrder['code'] : '';
            $title = is_string($workOrder['title'] ?? null) ? $workOrder['title'] : '';
            $site = is_string($workOrder['site'] ?? null) ? $workOrder['site'] : '';
            $status = is_string($workOrder['status'] ?? null) ? $workOrder['status'] : '';
            $options[$id] = implode(' - ', array_filter([
                '#'.$id,
                $code,
                $title,
                $site,
                $status,
            ]));
        }

        $searchValue = $this->proposalWorkOrderSearches[$publicId] ?? '';
        $searchStr = is_string($searchValue) ? $searchValue : '';
        foreach ($this->searchedWorkOrderOptions($searchStr) as $id => $label) {
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
            ->mapWithKeys(function (WorkOrder $workOrder): array {
                $code = is_string($workOrder->code ?? null) ? $workOrder->code : '';
                $title = is_string($workOrder->title) ? $workOrder->title : '';
                $customerLegalName = data_get($workOrder, 'customer.legal_name', '');
                $customerLegalNameStr = is_string($customerLegalName) ? $customerLegalName : '';
                $siteName = data_get($workOrder, 'site.name', '');
                $siteNameStr = is_string($siteName) ? $siteName : '';
                $statusName = data_get($workOrder, 'status.name', '');
                $statusNameStr = is_string($statusName) ? $statusName : '';

                return [
                    (int) $workOrder->id => implode(' - ', array_filter([
                        '#'.(int) $workOrder->id,
                        $code,
                        $title,
                        $customerLegalNameStr,
                        $siteNameStr,
                        $statusNameStr,
                    ])),
                ];
            })
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
            $scheduledAt = is_string($intervention['scheduled_at'] ?? null) ? $intervention['scheduled_at'] : '';
            $notes = is_string($intervention['notes'] ?? null) ? $intervention['notes'] : '';
            $options[$id] = implode(' - ', array_filter([
                '#'.$id,
                is_numeric($intervention['work_order_id'] ?? null) ? 'commessa #'.(int) $intervention['work_order_id'] : '',
                $scheduledAt,
                $notes,
            ]));
        }

        $selectedId = $this->positiveInt($payload['intervention_id'] ?? null);
        if ($selectedId !== null && ! isset($options[$selectedId])) {
            $options[$selectedId] = 'Intervento #'.$selectedId;
        }

        return $options;
    }
}
