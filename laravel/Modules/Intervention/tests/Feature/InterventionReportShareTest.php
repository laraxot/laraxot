<?php

declare(strict_types=1);

use Modules\Intervention\Tests\TestCase;

uses(\Modules\Intervention\Tests\TestCase::class);

use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;
use Modules\Intervention\Actions\CreateInterventionReportShareLinkAction;
use Modules\Intervention\Models\Intervention;
use Modules\Intervention\Models\InterventionReportShare;
use Modules\User\Database\Factories\UserFactory;

function makeShareIntervention(): Intervention
{
    $customerId = DB::table('customers')->insertGetId([
        'legal_name' => 'Cliente Share', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
    ]);

    $id = DB::table('interventions')->insertGetId([
        'customer_id' => $customerId,
        'performed_by_user_id' => UserFactory::new()->createOne()->id,
        'scheduled_at' => CarbonImmutable::now(),
        'started_at' => CarbonImmutable::now(),
        'work_minutes' => 30,
        'notes' => 'Lavoro svolto',
        'outcome_code' => 'completed',
        'materials_total' => 0,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return Intervention::query()->findOrFail($id);
}

// ── CreateInterventionReportShareLinkAction ───────────────────────────────────

it('creates a share link with a token and public url', function (): void {
    $intervention = makeShareIntervention();
    $user = UserFactory::new()->createOne();

    $share = app(CreateInterventionReportShareLinkAction::class)->execute($intervention, $user->id);

    expect(strlen($share->token))->toBe(48)
        ->and($share->intervention_id)->toBe($intervention->id)
        ->and($share->created_by_user_id)->toBe($user->id)
        ->and($share->isExpired())->toBeFalse()
        ->and($share->publicUrl())->toContain($share->token);
});

it('reuses an existing non-expired share instead of creating a new one', function (): void {
    $intervention = makeShareIntervention();

    $first = app(CreateInterventionReportShareLinkAction::class)->execute($intervention);
    $second = app(CreateInterventionReportShareLinkAction::class)->execute($intervention);

    expect($second->id)->toBe($first->id)
        ->and(InterventionReportShare::query()->count())->toBe(1);
});

it('creates a new share when the existing one is expired', function (): void {
    $intervention = makeShareIntervention();

    $expired = InterventionReportShare::query()->create([
        'intervention_id' => $intervention->id,
        'token' => 'expired-token-value',
        'expires_at' => CarbonImmutable::now()->subDay(),
    ]);

    $fresh = app(CreateInterventionReportShareLinkAction::class)->execute($intervention);

    expect($fresh->id)->not->toBe($expired->id)
        ->and($fresh->isExpired())->toBeFalse()
        ->and(InterventionReportShare::query()->count())->toBe(2);
});

// ── Public route ──────────────────────────────────────────────────────────────

it('serves the client-facing report page over the public share link', function (): void {
    $intervention = makeShareIntervention();
    $share = app(CreateInterventionReportShareLinkAction::class)->execute($intervention);

    $response = $this->get($share->publicUrl());

    $response->assertOk()
        ->assertSee('Rapportino intervento')
        ->assertSee('Cliente Share')
        ->assertSee('Lavoro svolto')
        ->assertSee(route('interventions.report.public.pdf', ['token' => $share->token]));
    expect($response->headers->get('content-type'))->toContain('text/html');
});

it('serves the report PDF over the dedicated pdf link', function (): void {
    $intervention = makeShareIntervention();
    $share = app(CreateInterventionReportShareLinkAction::class)->execute($intervention);

    $response = $this->get(route('interventions.report.public.pdf', ['token' => $share->token]));

    $response->assertOk();
    expect($response->headers->get('content-type'))->toContain('application/pdf')
        ->and(substr((string) $response->getContent(), 0, 4))->toBe('%PDF');
});

it('returns 404 for the pdf link with an unknown token', function (): void {
    $this->get(route('interventions.report.public.pdf', ['token' => 'nope']))
        ->assertNotFound();
});

it('returns 404 for an unknown token', function (): void {
    $this->get(route('interventions.report.public', ['token' => 'does-not-exist']))
        ->assertNotFound();
});

it('returns 404 for an expired share link', function (): void {
    $intervention = makeShareIntervention();
    $share = InterventionReportShare::query()->create([
        'intervention_id' => $intervention->id,
        'token' => 'expired-public-token',
        'expires_at' => CarbonImmutable::now()->subHour(),
    ]);

    $this->get($share->publicUrl())->assertNotFound();
});
