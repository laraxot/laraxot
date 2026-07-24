<?php

declare(strict_types=1);

namespace Modules\WhatsApp\Actions;

use Carbon\CarbonImmutable;
use Modules\Customer\Models\Customer;
use Modules\Intervention\Models\Intervention;
use Modules\Platform\Settings\SettingsRepository;
use Modules\WhatsApp\Models\WhatsAppConversation;
use Modules\WorkOrder\Models\WorkOrder;
use Modules\WorkOrder\Models\WorkOrderStatus;
use Spatie\QueueableAction\QueueableAction;

use function Safe\preg_replace;

/**
 * Turns a WhatsApp service request into gestionale data: it recognizes an
 * existing customer (by phone, then by name) or creates a new one, then creates
 * a work order + intervention or a direct-customer intervention depending on the
 * configured work mode. An existing open work order for the customer is reused.
 *
 * The proposal is confirmed with editable fields, so the values passed here are
 * the operator-reviewed ones.
 *
 * @phpstan-type RequestData array{customer_name?: string, address?: string, city?: string, problem?: string}
 */
final class OrganizeWhatsAppRequestAction
{
    use QueueableAction;

    /**
     * @param  array{customer_name?: string, address?: string, city?: string, problem?: string, scheduled_at?: string}  $data
     * @return array{customer_id: int, work_order_id: int|null, intervention_id: int, customer_created: bool, work_order_created: bool}
     */
    public function execute(WhatsAppConversation $conversation, array $data, int $userId): array
    {
        $problem = trim((string) ($data['problem'] ?? ''));
        if ($problem === '') {
            $problem = 'Richiesta ricevuta via WhatsApp.';
        }

        $scheduledAt = $this->parseScheduledAt($data['scheduled_at'] ?? null);

        [$customer, $customerCreated] = $this->resolveCustomer($conversation, $data);

        if ($conversation->customer_id !== $customer->id) {
            $conversation->forceFill(['customer_id' => $customer->id])->save();
        }

        $directMode = app(SettingsRepository::class)
            ->getString('interventions.work_mode', 'work_order') === 'customer';

        $workOrder = null;
        $workOrderCreated = false;

        if (! $directMode) {
            $workOrder = $this->openWorkOrderFor($customer);
            if ($workOrder === null) {
                $workOrder = $this->createWorkOrder($customer, $problem);
                $workOrderCreated = true;
            }
        }

        $intervention = Intervention::query()->create([
            'work_order_id' => $workOrder?->id,
            'customer_id' => $workOrder === null ? $customer->id : null,
            'performed_by_user_id' => $userId,
            'scheduled_at' => $scheduledAt,
            'notes' => $problem,
            'outcome_code' => null,
        ]);

        return [
            'customer_id' => (int) $customer->id,
            'work_order_id' => $workOrder?->id !== null ? (int) $workOrder->id : null,
            'intervention_id' => (int) $intervention->id,
            'customer_created' => $customerCreated,
            'work_order_created' => $workOrderCreated,
        ];
    }

    /**
     * @param  array{customer_name?: string, address?: string, city?: string, problem?: string, scheduled_at?: string}  $data
     * @return array{0: Customer, 1: bool}
     */
    private function resolveCustomer(WhatsAppConversation $conversation, array $data): array
    {
        if ($conversation->customer_id !== null) {
            $existing = Customer::query()->find($conversation->customer_id);
            if ($existing !== null) {
                return [$existing, false];
            }
        }

        $byPhone = $this->matchByPhone((string) $conversation->phone_e164);
        if ($byPhone !== null) {
            return [$byPhone, false];
        }

        $name = trim((string) ($data['customer_name'] ?? ''));
        if ($name === '') {
            $name = trim((string) $conversation->display_name);
        }

        if ($name !== '') {
            $byName = Customer::query()->whereRaw('LOWER(legal_name) = ?', [mb_strtolower($name)])->first();
            if ($byName !== null) {
                return [$byName, false];
            }
        }

        $customer = Customer::query()->create([
            'legal_name' => $name !== '' ? $name : ('Contatto '.$conversation->phone_e164),
            'phone' => (string) $conversation->phone_e164,
            'is_active' => true,
        ]);

        return [$customer, true];
    }

    private function parseScheduledAt(mixed $value): ?CarbonImmutable
    {
        if (! is_string($value) || trim($value) === '') {
            return null;
        }

        try {
            return CarbonImmutable::parse($value);
        } catch (\Throwable) {
            return null;
        }
    }

    private function matchByPhone(string $phone): ?Customer
    {
        $digits = preg_replace('/\D/', '', $phone);
        if (mb_strlen($digits) < 6) {
            return null;
        }

        $last = mb_substr($digits, -9);

        return Customer::query()
            ->whereNotNull('phone')
            ->whereRaw("REPLACE(REPLACE(REPLACE(phone, ' ', ''), '-', ''), '+', '') LIKE ?", ['%'.$last])
            ->orderBy('id')
            ->first();
    }

    private function openWorkOrderFor(Customer $customer): ?WorkOrder
    {
        $terminalStatusIds = WorkOrderStatus::query()->where('is_terminal', true)->pluck('id')->all();

        return WorkOrder::query()
            ->where('customer_id', $customer->id)
            ->where(function ($query) use ($terminalStatusIds): void {
                $query->whereNull('status_id')->orWhereNotIn('status_id', $terminalStatusIds);
            })
            ->orderByDesc('id')
            ->first();
    }

    private function createWorkOrder(Customer $customer, string $problem): WorkOrder
    {
        $status = WorkOrderStatus::query()->where('is_terminal', false)->orderBy('sort_order')->first();

        return WorkOrder::query()->create([
            'code' => 'WA-'.now()->format('ymd').'-'.mb_substr((string) $customer->id, -4).'-'.random_int(100, 999),
            'customer_id' => $customer->id,
            'status_id' => $status?->id,
            'title' => mb_substr($problem, 0, 120),
            'description' => $problem,
            'opened_at' => now(),
        ]);
    }
}
