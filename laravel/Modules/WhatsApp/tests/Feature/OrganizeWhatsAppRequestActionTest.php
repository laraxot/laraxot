<?php

declare(strict_types=1);

namespace Modules\WhatsApp\Tests\Feature;

use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;
use Modules\Customer\Models\Customer;
use Modules\Platform\Models\PlatformSetting;
use Modules\WhatsApp\Actions\OrganizeWhatsAppRequestAction;
use Modules\WhatsApp\Models\WhatsAppConversation;
use Modules\WhatsApp\Tests\TestCase;
use Modules\WorkOrder\Models\WorkOrder;
use Modules\WorkOrder\Models\WorkOrderStatus;

/*
 * Migrato da gestionale_commesse/tests/Feature/WhatsApp/OrganizeWhatsAppRequestActionTest.php.
 * Namespace adattato: Modules\Customers\Infrastructure -> Modules\Customer\Models,
 * Modules\WorkOrders\Infrastructure -> Modules\WorkOrder\Models,
 * Modules\Platform\Infrastructure -> Modules\Platform\Models, stessa API dell'action.
 */
uses(\Modules\WhatsApp\Tests\TestCase::class);

function organizeSetWorkMode(string $mode): void
{
    PlatformSetting::query()->updateOrCreate(
        ['key' => 'interventions.work_mode'],
        ['value_json' => ['value' => $mode]],
    );
}

/**
 * @param  array<string, mixed>  $overrides
 */
function organizeMakeInboundConversation(array $overrides = []): WhatsAppConversation
{
    return WhatsAppConversation::query()->create(array_merge([
        'phone_e164' => '+393331234567',
        'display_name' => 'Rosa Bianchi',
        'status' => 'open',
        'last_message_at' => CarbonImmutable::now(),
    ], $overrides));
}

function organizeOpenStatus(): WorkOrderStatus
{
    return WorkOrderStatus::query()->firstOrCreate(
        ['code' => 'in_progress'],
        ['name' => 'In lavorazione', 'color' => 'amber', 'is_terminal' => 0, 'sort_order' => 10],
    );
}

beforeEach(function (): void {
    $this->userId = 1;
});

it('creates a new customer, work order and intervention in work-order mode', function (): void {
    organizeSetWorkMode('work_order');
    organizeOpenStatus();
    $conversation = organizeMakeInboundConversation();

    $result = app(OrganizeWhatsAppRequestAction::class)->execute($conversation, [
        'customer_name' => 'Rosa Bianchi',
        'address' => 'Via de Rossi 1',
        'city' => 'Mogliano Veneto',
        'problem' => 'Aggiustare il lavandino',
    ], $this->userId);

    expect($result['customer_created'])->toBeTrue()
        ->and($result['work_order_created'])->toBeTrue()
        ->and($result['work_order_id'])->not->toBeNull();

    $customer = Customer::query()->find($result['customer_id']);
    expect($customer?->legal_name)->toBe('Rosa Bianchi')
        ->and($customer?->phone)->toBe('+393331234567');

    $workOrder = WorkOrder::query()->find($result['work_order_id']);
    expect($workOrder?->customer_id)->toBe($result['customer_id'])
        ->and($workOrder?->title)->toBe('Aggiustare il lavandino');

    expect($conversation->refresh()->customer_id)->toBe($result['customer_id']);
});

it('sets the appointment date on the intervention and ignores an invalid one', function (): void {
    organizeSetWorkMode('customer');
    $conversation = organizeMakeInboundConversation();

    $result = app(OrganizeWhatsAppRequestAction::class)->execute($conversation, [
        'customer_name' => 'Antonio',
        'problem' => 'rubinetto rotto',
        'scheduled_at' => '2026-07-11T10:00:00',
    ], $this->userId);

    $scheduled = DB::table('interventions')->where('id', $result['intervention_id'])->value('scheduled_at');
    expect((string) $scheduled)->toContain('2026-07-11 10:00:00');

    $conversation2 = organizeMakeInboundConversation(['phone_e164' => '+393330000000']);
    $result2 = app(OrganizeWhatsAppRequestAction::class)->execute($conversation2, [
        'customer_name' => 'Mario',
        'problem' => 'guasto',
        'scheduled_at' => 'non-una-data',
    ], $this->userId);

    expect(DB::table('interventions')->where('id', $result2['intervention_id'])->value('scheduled_at'))->toBeNull();
});

it('recognizes an existing customer by phone and does not duplicate it', function (): void {
    organizeSetWorkMode('work_order');
    organizeOpenStatus();
    $existing = Customer::query()->create(['legal_name' => 'Rosa B.', 'phone' => '333 123 4567', 'is_active' => true]);
    $conversation = organizeMakeInboundConversation();

    $result = app(OrganizeWhatsAppRequestAction::class)->execute($conversation, [
        'customer_name' => 'Rosa Bianchi',
        'problem' => 'Perdita',
    ], $this->userId);

    expect($result['customer_created'])->toBeFalse()
        ->and($result['customer_id'])->toBe($existing->id);
});

it('recognizes an existing customer by name when the phone does not match', function (): void {
    organizeSetWorkMode('work_order');
    organizeOpenStatus();
    $existing = Customer::query()->create(['legal_name' => 'Rosa Bianchi', 'phone' => null, 'is_active' => true]);
    $conversation = organizeMakeInboundConversation(['phone_e164' => '+399999999999']);

    $result = app(OrganizeWhatsAppRequestAction::class)->execute($conversation, [
        'customer_name' => 'Rosa Bianchi',
        'problem' => 'Perdita',
    ], $this->userId);

    expect($result['customer_created'])->toBeFalse()
        ->and($result['customer_id'])->toBe($existing->id);
});

it('reuses an existing open work order for the customer', function (): void {
    organizeSetWorkMode('work_order');
    $status = organizeOpenStatus();
    $customer = Customer::query()->create(['legal_name' => 'Rosa Bianchi', 'phone' => '+393331234567', 'is_active' => true]);
    $existingWo = WorkOrder::query()->create([
        'code' => 'WO-OLD', 'customer_id' => $customer->id, 'status_id' => $status->id,
        'title' => 'Commessa aperta', 'opened_at' => now(),
    ]);
    $conversation = organizeMakeInboundConversation(['customer_id' => $customer->id]);

    $result = app(OrganizeWhatsAppRequestAction::class)->execute($conversation, ['problem' => 'Altro guasto'], $this->userId);

    expect($result['work_order_created'])->toBeFalse()
        ->and($result['work_order_id'])->toBe($existingWo->id);
});

it('creates a direct-customer intervention without a work order in customer mode', function (): void {
    organizeSetWorkMode('customer');
    $conversation = organizeMakeInboundConversation();

    $result = app(OrganizeWhatsAppRequestAction::class)->execute($conversation, [
        'customer_name' => 'Rosa Bianchi',
        'problem' => 'Aggiustare il lavandino',
    ], $this->userId);

    expect($result['work_order_id'])->toBeNull()
        ->and($result['work_order_created'])->toBeFalse();

    $interventionCustomerId = DB::table('interventions')->where('id', $result['intervention_id'])->value('customer_id');
    expect((int) $interventionCustomerId)->toBe($result['customer_id']);
});

it('creates a new customer when the phone is too short to match', function (): void {
    organizeSetWorkMode('customer');
    Customer::query()->create(['legal_name' => 'Altro Cliente', 'phone' => '+393339999999', 'is_active' => true]);
    $conversation = organizeMakeInboundConversation(['phone_e164' => '+39', 'display_name' => 'Tizio']);

    $result = app(OrganizeWhatsAppRequestAction::class)->execute($conversation, ['customer_name' => 'Tizio Nuovo'], $this->userId);

    expect($result['customer_created'])->toBeTrue();
    expect(Customer::query()->find($result['customer_id'])?->legal_name)->toBe('Tizio Nuovo');
});

it('falls back to a placeholder problem and the display name when data is sparse', function (): void {
    organizeSetWorkMode('customer');
    $conversation = organizeMakeInboundConversation(['display_name' => 'Mario Verdi']);

    $result = app(OrganizeWhatsAppRequestAction::class)->execute($conversation, [], $this->userId);

    $customer = Customer::query()->find($result['customer_id']);
    expect($customer?->legal_name)->toBe('Mario Verdi');

    $notes = DB::table('interventions')->where('id', $result['intervention_id'])->value('notes');
    expect((string) $notes)->toBe('Richiesta ricevuta via WhatsApp.');
});
