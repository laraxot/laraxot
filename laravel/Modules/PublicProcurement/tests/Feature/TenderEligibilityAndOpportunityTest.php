<?php

declare(strict_types=1);

use Carbon\CarbonImmutable;
use Modules\Compliance\Models\ComplianceDocument;
use Modules\PublicProcurement\Actions\ApplyTenderOpportunityDraftAction;
use Modules\PublicProcurement\Actions\BuildTenderOpportunityDraftFromTextAction;
use Modules\PublicProcurement\Actions\EvaluateTenderEligibilityAction;
use Modules\PublicProcurement\Models\PublicProcurementTenderOpportunity;
use Modules\PublicProcurement\Models\PublicProcurementTenderSource;
use Modules\PublicProcurement\Tests\TestCase;

/*
|--------------------------------------------------------------------------
| Migrato da gestionale_commesse (tests/Feature/PublicProcurement) — 2026-07-24
|--------------------------------------------------------------------------
|
| Adattato ai namespace correnti (Models\/Actions\ invece di
| Infrastructure\Models\/Application\Actions\). Portate solo le parti
| autosufficienti (eleggibilità, draft AI testuale → opportunità); la
| generazione SAL/contratto e l'esecuzione da AiActionProposal restano
| fuori scopo per lo smoke pass di un modulo dormiente.
*/

uses(TestCase::class);

function makePublicProcurementOpportunity(?CarbonImmutable $deadline = null): PublicProcurementTenderOpportunity
{
    $source = PublicProcurementTenderSource::query()->create([
        'name' => 'Portale demo',
        'source_type' => 'manual',
        'is_active' => true,
    ]);

    return PublicProcurementTenderOpportunity::query()->create([
        'tender_source_id' => $source->id,
        'status' => 'new',
        'contracting_authority' => 'Comune Demo',
        'title' => 'Impianti elettrici scuola comunale',
        'cig' => 'A123456789',
        'cup' => 'H11B26000010004',
        'category' => 'OS30',
        'estimated_amount' => 120000,
        'submission_deadline_at' => ($deadline ?? CarbonImmutable::now()->addDays(20))->toDateTimeString(),
        'ai_summary' => [
            'required_documents' => [
                'durc' => 'DURC valido',
                'insurance' => 'Polizza assicurativa valida',
            ],
        ],
    ]);
}

it('marks a tender as eligible when required documents are valid', function (): void {
    ComplianceDocument::query()->create([
        'type' => 'durc',
        'title' => 'DURC valido',
        'status' => 'valid',
        'expires_on' => now()->addDays(80)->toDateString(),
    ]);
    ComplianceDocument::query()->create([
        'type' => 'insurance',
        'title' => 'Polizza valida',
        'status' => 'valid',
        'expires_on' => now()->addMonths(8)->toDateString(),
    ]);
    $opportunity = makePublicProcurementOpportunity();

    $check = app(EvaluateTenderEligibilityAction::class)->execute($opportunity);

    expect($check->status)->toBe('eligible')
        ->and($check->score)->toBe(100)
        ->and($opportunity->refresh()->status)->toBe('interesting');
});

it('blocks tender participation when required documents are missing or expired', function (): void {
    ComplianceDocument::query()->create([
        'type' => 'durc',
        'title' => 'DURC scaduto',
        'status' => 'valid',
        'expires_on' => now()->subDay()->toDateString(),
    ]);
    $opportunity = makePublicProcurementOpportunity();

    $check = app(EvaluateTenderEligibilityAction::class)->execute($opportunity);

    expect($check->status)->toBe('not_eligible')
        ->and($check->expired_documents)->toHaveCount(1)
        ->and($check->missing_documents)->toHaveCount(1)
        ->and($opportunity->refresh()->status)->toBe('blocked');
});

it('builds and applies an AI tender draft without saving final data before confirmation', function (): void {
    $draft = app(BuildTenderOpportunityDraftFromTextAction::class)->execute(
        'bando-scuola.pdf',
        "Stazione appaltante: Comune Demo\nBando impianti elettrici scuola\nCIG: A111222333\nCUP: H11B26000010004\nImporto: 145.000,00\nScadenza: 30/06/2026\nRichiesta SOA OS30 e cauzione provvisoria.",
    );

    expect(PublicProcurementTenderOpportunity::query()->count())->toBe(0)
        ->and($draft->document_type)->toBe('tender_notice');

    $opportunity = app(ApplyTenderOpportunityDraftAction::class)->execute($draft);

    expect($opportunity->cig)->toBe('A111222333')
        ->and($opportunity->category)->toBe('OS30')
        ->and($draft->refresh()->status)->toBe('applied');
});
