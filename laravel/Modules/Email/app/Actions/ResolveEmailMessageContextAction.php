<?php

declare(strict_types=1);

namespace Modules\Email\Actions;

use Modules\Customer\Models\Contact;
use Modules\Customer\Models\Customer;
use Modules\Customer\Models\CustomerSite;
use Modules\Email\Models\EmailContactIdentity;
use Modules\Email\Models\EmailConversation;
use Modules\Intervention\Models\Intervention;
use Modules\WorkOrder\Models\WorkOrder;

final readonly class ResolveEmailMessageContextAction
{
    /**
     * @return array<string, mixed>
     */
    public function execute(EmailConversation $conversation): array
    {
        $email = strtolower(trim((string) $conversation->email));
        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['ok' => false, 'reason' => 'email_missing'];
        }

        $identity = $this->findOrCreateIdentity($email);
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

        return [
            'ok' => true,
            'email' => $email,
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

    private function findOrCreateIdentity(string $email): ?EmailContactIdentity
    {
        /** @var EmailContactIdentity|null $existing */
        $existing = EmailContactIdentity::query()->where('email', $email)->first();
        if ($existing instanceof EmailContactIdentity) {
            return $existing;
        }

        $contact = $this->findContactByEmail($email);
        $site = $this->findSiteByEmail($email);
        $customer = $this->findCustomerByEmail($email);
        $customerId = $contact?->customer_id ?? $site?->customer_id ?? $customer?->id;

        if ($customerId === null && $contact === null && $site === null) {
            return null;
        }

        /** @var EmailContactIdentity $identity */
        $identity = EmailContactIdentity::query()->create([
            'email' => $email,
            'customer_id' => $customerId,
            'customer_site_id' => $contact?->customer_site_id ?? $site?->id,
            'contact_id' => $contact?->id,
            'meta' => ['source' => 'auto_resolved'],
        ]);

        return $identity;
    }

    private function syncConversation(EmailConversation $conversation, ?EmailContactIdentity $identity): void
    {
        if (! ($identity instanceof EmailContactIdentity)) {
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

    private function findContactByEmail(string $email): ?Contact
    {
        /** @var Contact|null $match */
        $match = Contact::query()->whereRaw('LOWER(email) = ?', [$email])->first();

        return $match;
    }

    private function findSiteByEmail(string $email): ?CustomerSite
    {
        /** @var CustomerSite|null $match */
        $match = CustomerSite::query()->whereRaw('LOWER(email) = ?', [$email])->first();

        return $match;
    }

    private function findCustomerByEmail(string $email): ?Customer
    {
        /** @var Customer|null $match */
        $match = Customer::query()->whereRaw('LOWER(email) = ?', [$email])->first();

        return $match;
    }

    private function customerLabel(EmailConversation $conversation): string
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
