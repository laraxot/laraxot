<?php

declare(strict_types=1);

uses(TestCase::class);

use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Modules\Intervention\Actions\ConfirmInterventionReportPaymentAction;
use Modules\Intervention\Actions\CreateInterventionReportCheckoutAction;
use Modules\Intervention\Actions\CreateInterventionReportShareLinkAction;
use Modules\Intervention\Contracts\ReportPaymentGatewayContract;
use Modules\Intervention\Exceptions\ReportPaymentNotAvailableException;
use Modules\Intervention\Models\Intervention;
use Modules\Intervention\Tests\TestCase;
use Modules\Signature\Actions\StoreSignatureForModelAction;
use Modules\User\Models\User;

const PAY_TINY_PNG = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII=';

/**
 * In-memory payment gateway that records the checkout call and returns a
 * scripted settlement status.
 */
function fakeGateway(): object
{
    return new class() implements ReportPaymentGatewayContract
    {
        /** @var array<string, mixed> */
        public array $lastCheckout = [];

        public string $sessionId = 'cs_test_123';

        public string $checkoutUrl = 'https://checkout.stripe.test/session';

        public bool $paid = true;

        public ?string $reference = null;

        public function createCheckout(int $amountCents, string $currency, string $description, string $successUrl, string $cancelUrl, array $metadata): array
        {
            $this->lastCheckout = compact('amountCents', 'currency', 'description', 'successUrl', 'cancelUrl', 'metadata');

            return ['id' => $this->sessionId, 'url' => $this->checkoutUrl];
        }

        public function fetchStatus(string $sessionId): array
        {
            return ['paid' => $this->paid, 'reference' => $this->reference];
        }
    };
}

function payIntervention(array $overrides = []): Intervention
{
    $attributes = array_merge([
        'work_order_id' => null,
        'customer_id' => null,
        'performed_by_user_id' => User::factory()->create()->id,
        'scheduled_at' => CarbonImmutable::now(),
        'started_at' => CarbonImmutable::now(),
        'work_minutes' => 60,
        'notes' => 'Lavoro',
        'outcome_code' => 'completed',
        'materials_total' => 0,
        'total_amount' => 150,
        'created_at' => now(),
        'updated_at' => now(),
    ], $overrides);

    $id = DB::table('interventions')->insertGetId($attributes);

    return Intervention::query()->findOrFail($id);
}

function signPayIntervention(Intervention $intervention): void
{
    app(StoreSignatureForModelAction::class)->execute(
        dataUrl: PAY_TINY_PNG,
        signableType: Intervention::class,
        signableId: (int) $intervention->id,
        userId: (int) $intervention->performed_by_user_id,
    );
}

beforeEach(fn () => Storage::fake('public'));

// ── CreateInterventionReportCheckoutAction ────────────────────────────────────

it('creates a checkout for a signed, unpaid report and stores the session id', function (): void {
    $gateway = fakeGateway();
    app()->instance(ReportPaymentGatewayContract::class, $gateway);

    $intervention = payIntervention(['total_amount' => 150]);
    signPayIntervention($intervention);

    $url = app(CreateInterventionReportCheckoutAction::class)
        ->execute($intervention, 'https://app.test/return', 'https://app.test/cancel');

    expect($url)->toBe('https://checkout.stripe.test/session')
        ->and($gateway->lastCheckout['amountCents'])->toBe(15000)
        ->and($gateway->lastCheckout['currency'])->toBe('eur')
        ->and($gateway->lastCheckout['metadata'])->toBe(['intervention_id' => (string) $intervention->id])
        ->and($intervention->fresh()->report_payment_session_id)->toBe('cs_test_123');
});

it('refuses checkout when there is no amount due', function (): void {
    app()->instance(ReportPaymentGatewayContract::class, fakeGateway());
    $intervention = payIntervention(['total_amount' => 0]);
    signPayIntervention($intervention);

    app(CreateInterventionReportCheckoutAction::class)
        ->execute($intervention, 'https://app.test/return', 'https://app.test/cancel');
})->throws(ReportPaymentNotAvailableException::class);

it('refuses checkout when the report is already paid', function (): void {
    app()->instance(ReportPaymentGatewayContract::class, fakeGateway());
    $intervention = payIntervention(['report_paid_at' => CarbonImmutable::now()]);
    signPayIntervention($intervention);

    app(CreateInterventionReportCheckoutAction::class)
        ->execute($intervention, 'https://app.test/return', 'https://app.test/cancel');
})->throws(ReportPaymentNotAvailableException::class);

it('refuses checkout when the report is not signed', function (): void {
    app()->instance(ReportPaymentGatewayContract::class, fakeGateway());
    $intervention = payIntervention();

    app(CreateInterventionReportCheckoutAction::class)
        ->execute($intervention, 'https://app.test/return', 'https://app.test/cancel');
})->throws(ReportPaymentNotAvailableException::class);

// ── ConfirmInterventionReportPaymentAction ────────────────────────────────────

it('marks the report paid when the session settled', function (): void {
    $gateway = fakeGateway();
    $gateway->paid = true;
    app()->instance(ReportPaymentGatewayContract::class, $gateway);

    $intervention = payIntervention(['report_payment_session_id' => 'cs_test_123']);

    $result = app(ConfirmInterventionReportPaymentAction::class)->execute('cs_test_123');

    expect($result)->toBeTrue()
        ->and($intervention->fresh()->report_paid_at)->not->toBeNull();
});

it('does not mark paid when the session is not settled', function (): void {
    $gateway = fakeGateway();
    $gateway->paid = false;
    app()->instance(ReportPaymentGatewayContract::class, $gateway);

    $intervention = payIntervention(['report_payment_session_id' => 'cs_test_123']);

    $result = app(ConfirmInterventionReportPaymentAction::class)->execute('cs_test_123');

    expect($result)->toBeFalse()
        ->and($intervention->fresh()->report_paid_at)->toBeNull();
});

it('returns false when no intervention matches the session', function (): void {
    $gateway = fakeGateway();
    $gateway->paid = true;
    app()->instance(ReportPaymentGatewayContract::class, $gateway);

    payIntervention(['report_payment_session_id' => 'cs_other']);

    expect(app(ConfirmInterventionReportPaymentAction::class)->execute('cs_test_123'))->toBeFalse();
});

it('is idempotent and keeps the original paid timestamp', function (): void {
    $gateway = fakeGateway();
    $gateway->paid = true;
    app()->instance(ReportPaymentGatewayContract::class, $gateway);

    $paidAt = CarbonImmutable::parse('2026-05-20 08:00');
    $intervention = payIntervention(['report_payment_session_id' => 'cs_test_123', 'report_paid_at' => $paidAt]);

    $result = app(ConfirmInterventionReportPaymentAction::class)->execute('cs_test_123');

    expect($result)->toBeTrue()
        ->and($intervention->fresh()->report_paid_at->format('Y-m-d H:i'))->toBe('2026-05-20 08:00');
});

// ── Public HTTP flow ──────────────────────────────────────────────────────────

it('lets the customer sign the report from the public page', function (): void {
    $intervention = payIntervention();
    $share = app(CreateInterventionReportShareLinkAction::class)->execute($intervention);

    $this->post(route('interventions.report.public.sign', ['token' => $share->token]), [
        'signature' => PAY_TINY_PNG,
    ])->assertRedirect(route('interventions.report.public', ['token' => $share->token]).'?signature=ok');

    expect($intervention->signatures()->count())->toBe(1);
});

it('rejects an invalid signature payload', function (): void {
    $intervention = payIntervention();
    $share = app(CreateInterventionReportShareLinkAction::class)->execute($intervention);

    $this->post(route('interventions.report.public.sign', ['token' => $share->token]), [
        'signature' => 'not-an-image',
    ])->assertRedirect(route('interventions.report.public', ['token' => $share->token]).'?signature=invalid');

    expect($intervention->signatures()->count())->toBe(0);
});

it('starts checkout from the public pay route and redirects to Stripe', function (): void {
    app()->instance(ReportPaymentGatewayContract::class, fakeGateway());
    $intervention = payIntervention(['total_amount' => 150]);
    signPayIntervention($intervention);
    $share = app(CreateInterventionReportShareLinkAction::class)->execute($intervention);

    $this->post(route('interventions.report.public.pay', ['token' => $share->token]))
        ->assertRedirect('https://checkout.stripe.test/session');
});

it('redirects with an error when payment is unavailable', function (): void {
    app()->instance(ReportPaymentGatewayContract::class, fakeGateway());
    $intervention = payIntervention(['total_amount' => 0]);
    $share = app(CreateInterventionReportShareLinkAction::class)->execute($intervention);

    $this->post(route('interventions.report.public.pay', ['token' => $share->token]))
        ->assertRedirect(route('interventions.report.public', ['token' => $share->token]).'?payment=unavailable');
});

it('confirms payment on the return route and marks it paid', function (): void {
    $gateway = fakeGateway();
    $gateway->paid = true;
    app()->instance(ReportPaymentGatewayContract::class, $gateway);
    $intervention = payIntervention(['total_amount' => 150, 'report_payment_session_id' => 'cs_test_123']);
    $share = app(CreateInterventionReportShareLinkAction::class)->execute($intervention);

    $this->get(route('interventions.report.public.return', ['token' => $share->token]).'?session_id=cs_test_123')
        ->assertRedirect(route('interventions.report.public', ['token' => $share->token]).'?payment=success');

    expect($intervention->fresh()->report_paid_at)->not->toBeNull();
});
