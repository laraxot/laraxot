<?php

declare(strict_types=1);

namespace Modules\WhatsApp\Actions;

use Modules\Customer\Models\Contact;
use Modules\Customer\Models\Customer;
use Modules\Customer\Models\CustomerSite;
use Modules\Intervention\Models\Intervention;
use Modules\WhatsApp\Support\PhoneNumberNormalizer;
use Modules\WhatsApp\Models\WhatsAppContactIdentity;
use Modules\WhatsApp\Models\WhatsAppConversation;
use Modules\WhatsApp\Models\WhatsAppMessage;
use Modules\WorkOrder\Models\WorkOrder;

final readonly class ResolveWhatsAppMessageContextAction
{
    public function __construct(private PhoneNumberNormalizer $normalizer) {}

    /**
     * @return array<string, mixed>
     */
    public function execute(WhatsAppConversation $conversation): array
    {
        $phone = $this->normalizer->normalize((string) $conversation->phone_e164);
        if ($phone === null) {
            return ['ok' => false, 'reason' => 'phone_missing'];
        }

        $identity = $this->findOrCreateIdentity($phone, $conversation);
        $this->syncConversation($conversation, $identity);

        $customerId = (int) ($conversation->customer_id ?? 0);
        $contactId = (int) ($conversation->contact_id ?? 0);

        $workOrders = $customerId > 0
            ? WorkOrder::query()
                ->with(['customer', 'site', 'status'])
                ->where('customer_id', $customerId)
                ->orderByDesc('updated_at')
                ->limit(5)
                ->get()
            : collect();

        $interventions = $workOrders->isNotEmpty()
            ? Intervention::query()
                ->with(['workOrder.customer', 'workOrder.site'])
                ->whereIn('work_order_id', $workOrders->pluck('id')->all())
                ->orderByDesc('scheduled_at')
                ->orderByDesc('updated_at')
                ->limit(5)
                ->get()
            : collect();

        $conversationHistory = $conversation->messages()
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->limit(20)
            ->get()
            ->reverse()
            ->map(fn (WhatsAppMessage $historyMessage): array => [
                'from' => $historyMessage->direction === 'inbound' ? 'cliente' : 'noi',
                'text' => mb_substr(trim((string) $historyMessage->body), 0, 500),
                'at' => $historyMessage->created_at?->toIso8601String(),
            ])
            ->values()
            ->all();

        $approvedUpToAt = null;
        if ($conversation->approved_up_to_message_id !== null) {
            $checkpoint = WhatsAppMessage::query()->find($conversation->approved_up_to_message_id);
            $approvedUpToAt = $checkpoint?->created_at?->toIso8601String();
        }

        return [
            'ok' => true,
            'today' => now()->toDateString(),
            'approved_up_to_at' => $approvedUpToAt,
            'conversation_history' => $conversationHistory,
            'phone_e164' => $phone,
            'identity_id' => $identity?->id,
            'customer_id' => $customerId > 0 ? $customerId : null,
            'contact_id' => $contactId > 0 ? $contactId : null,
            'customer_label' => $this->customerLabel($conversation),
            'work_orders' => $workOrders->map(fn (WorkOrder $workOrder): array => [
                'id' => (int) $workOrder->id,
                'code' => (string) ($workOrder->code ?? ''),
                'title' => (string) $workOrder->title,
                'status' => (string) data_get($workOrder, 'status.name', ''),
                'site' => (string) data_get($workOrder, 'site.name', ''),
            ])->all(),
            'interventions' => $interventions->map(fn (Intervention $intervention): array => [
                'id' => (int) $intervention->id,
                'work_order_id' => (int) $intervention->work_order_id,
                'scheduled_at' => $intervention->scheduled_at?->toIso8601String(),
                'started_at' => $intervention->started_at?->toIso8601String(),
                'ended_at' => $intervention->ended_at?->toIso8601String(),
                'notes' => mb_substr((string) ($intervention->notes ?? ''), 0, 500),
            ])->all(),
        ];
    }

    private function findOrCreateIdentity(string $phone, WhatsAppConversation $conversation): ?WhatsAppContactIdentity
    {
        /** @var WhatsAppContactIdentity|null $existing */
        $existing = WhatsAppContactIdentity::query()->where('phone_e164', $phone)->first();
        if ($existing instanceof WhatsAppContactIdentity) {
            return $existing;
        }

        $contact = $this->findContactByPhone($phone);
        $site = $this->findSiteByPhone($phone);
        $customer = $this->findCustomerByPhone($phone);

        $customerId = $contact?->customer_id ?? $site?->customer_id ?? $customer?->id;

        if ($customerId === null && $contact === null && $site === null) {
            return null;
        }

        /** @var WhatsAppContactIdentity $identity */
        $identity = WhatsAppContactIdentity::query()->create([
            'phone_e164' => $phone,
            'wa_id' => $conversation->wa_id,
            'customer_id' => $customerId,
            'customer_site_id' => $contact?->customer_site_id ?? $site?->id,
            'contact_id' => $contact?->id,
            'meta' => ['source' => 'auto_resolved'],
        ]);

        return $identity;
    }

    private function syncConversation(WhatsAppConversation $conversation, ?WhatsAppContactIdentity $identity): void
    {
        if (! ($identity instanceof WhatsAppContactIdentity)) {
            return;
        }

        $conversation->forceFill([
            'identity_id' => $identity->id,
            'customer_id' => $identity->customer_id,
            'customer_site_id' => $identity->customer_site_id,
            'contact_id' => $identity->contact_id,
            'user_id' => $identity->user_id,
        ])->save();
    }

    private function findContactByPhone(string $phone): ?Contact
    {
        /** @var Contact|null $match */
        $match = Contact::query()->whereNotNull('phone')->get()
            ->first(fn (Contact $contact): bool => $this->normalizer->normalize((string) $contact->phone) === $phone);

        return $match;
    }

    private function findSiteByPhone(string $phone): ?CustomerSite
    {
        /** @var CustomerSite|null $match */
        $match = CustomerSite::query()->whereNotNull('phone')->get()
            ->first(fn (CustomerSite $site): bool => $this->normalizer->normalize((string) $site->phone) === $phone);

        return $match;
    }

    private function findCustomerByPhone(string $phone): ?Customer
    {
        /** @var Customer|null $match */
        $match = Customer::query()->whereNotNull('phone')->get()
            ->first(fn (Customer $customer): bool => $this->normalizer->normalize((string) $customer->phone) === $phone);

        return $match;
    }

    private function customerLabel(WhatsAppConversation $conversation): string
    {
        $conversation->loadMissing(['customer', 'site', 'contact']);

        $parts = array_filter([
            (string) data_get($conversation, 'customer.legal_name', ''),
            (string) data_get($conversation, 'site.name', ''),
            (string) data_get($conversation, 'contact.full_name', ''),
        ]);

        return implode(' - ', $parts);
    }
}
