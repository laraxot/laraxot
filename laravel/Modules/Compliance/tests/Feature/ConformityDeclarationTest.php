<?php

declare(strict_types=1);

use Modules\Compliance\Actions\DetermineRequiredAttachmentsAction;
use Modules\Compliance\Actions\IssueConformityDeclarationAction;
use Modules\Compliance\Actions\PrepareConformityDeclarationForWorkOrderAction;
use Modules\Compliance\Models\ComplianceDocument;
use Modules\Compliance\Models\ConformityAttachment;
use Modules\Compliance\Models\ConformityDeclaration;
use Modules\Compliance\Tests\TestCase;
use Modules\Customer\Models\Customer;
use Modules\Customer\Models\CustomerSite;
use Modules\WorkOrder\Models\WorkOrder;
use Modules\WorkOrder\Models\WorkOrderMaterial;
use Modules\WorkOrder\Models\WorkOrderStatus;

uses(TestCase::class);

/*
|--------------------------------------------------------------------------
| Portato da gestionale_commesse (tests/Feature/Compliance) — 2026-09-11
|--------------------------------------------------------------------------
|
| Un adattamento vero, non di soli namespace: in questa base ne' `customers`
| ne' `customer_sites` hanno una colonna di indirizzo, quindi la via
| dell'immobile non si puo' precompilare. Il test che nel sorgente verificava
| `site_address` qui verifica comune e provincia, e uno nuovo mette nero su
| bianco che la via resta vuota apposta.
*/

function makeDicoWorkOrder(): WorkOrder
{
    $customer = Customer::query()->create([
        'legal_name' => 'Rossi Mario',
        'tax_code' => 'RSSMRA80A01L736X',
        'city' => 'Mestre',
        'province' => 'VE',
    ]);

    $site = CustomerSite::query()->create([
        'customer_id' => $customer->id,
        'name' => 'Appartamento',
        'city' => 'Noale',
        'province' => 'VE',
        'postal_code' => '30033',
    ]);

    $status = WorkOrderStatus::query()->create(['code' => 'open', 'name' => 'Aperta']);

    $workOrder = WorkOrder::query()->create([
        'customer_id' => $customer->id,
        'customer_site_id' => $site->id,
        'status_id' => $status->id,
        'title' => 'Sostituzione caldaia e adeguamento gas',
    ]);

    WorkOrderMaterial::query()->create([
        'work_order_id' => $workOrder->id,
        'description' => 'Caldaia a condensazione 24 kW',
        'unit_code' => 'pz',
        'quantity' => 1,
    ]);

    return $workOrder;
}

/**
 * @param  array<string, mixed>  $extra
 */
function makeDico(array $extra = []): ConformityDeclaration
{
    /** @var int $n */
    static $n = 0;
    $n = $n + 1;

    $dico = ConformityDeclaration::query()->create(array_merge([
        'number' => 'DICO-TEST-'.$n,
        'company_name' => 'Gestionali Cavallini',
        'client_name' => 'Mario Rossi',
        'site_address' => 'Via Roma 1',
        'plant_type' => 'c',
        'work_type' => 'nuovo_impianto',
        'standards' => 'UNI 7129',
        'status' => ConformityDeclaration::BOZZA,
    ], $extra));

    return $dico->load('attachments');
}

it('prende dalla commessa i dati che la commessa gia conosce', function (): void {
    // E' il motivo per cui questa funzione sta nel gestionale commesse:
    // l'installatore non deve ribattere niente di tutto questo.
    $workOrder = makeDicoWorkOrder();

    $dico = app(PrepareConformityDeclarationForWorkOrderAction::class)->execute($workOrder);

    expect($dico->client_name)->toBe('Rossi Mario')
        ->and($dico->client_tax_code)->toBe('RSSMRA80A01L736X')
        // L'immobile e' il cantiere, non la sede del cliente: Noale, non Mestre.
        ->and($dico->site_city)->toBe('Noale')
        ->and($dico->site_province)->toBe('VE')
        ->and($dico->site_postal_code)->toBe('30033')
        ->and($dico->materials)->toContain('Caldaia a condensazione');
});

it('lascia vuota la via dell immobile, che in questa base nessuno conosce', function (): void {
    // In base l'indirizzo non sta ne' sul cliente ne' sul sito: gli indirizzi
    // vivono nel modulo Geo e questi due non ci sono agganciati. Meglio un campo
    // vuoto che l'installatore vede e compila, che una via inventata su un atto.
    $dico = app(PrepareConformityDeclarationForWorkOrderAction::class)->execute(makeDicoWorkOrder());

    expect($dico->site_address)->toBeNull();
});

it('non indovina il tipo di impianto e di intervento', function (): void {
    // Sono le due scelte che decidono quali allegati sono dovuti. Indovinarle
    // produrrebbe un fascicolo sbagliato che sembra giusto.
    $dico = app(PrepareConformityDeclarationForWorkOrderAction::class)->execute(makeDicoWorkOrder());

    expect($dico->plant_type)->toBe('')
        ->and($dico->work_type)->toBe('');
});

it('non emette finche manca un allegato obbligatorio', function (): void {
    $dico = makeDico(['work_type' => 'nuovo_impianto']);

    expect(fn () => app(IssueConformityDeclarationAction::class)->execute($dico))
        ->toThrow(RuntimeException::class, 'allegati obbligatori');

    expect($dico->fresh()?->status)->toBe(ConformityDeclaration::BOZZA);
});

it('col fascicolo completo emette e lascia gli avvisi', function (): void {
    $dico = makeDico(['work_type' => 'nuovo_impianto', 'standards' => '']);

    foreach ([
        ConformityAttachment::CERTIFICATO_IMPRESA,
        ConformityAttachment::SCHEMA_IMPIANTO,
        ConformityAttachment::RELAZIONE_MATERIALI,
    ] as $kind) {
        $dico->attachments()->create(['kind' => $kind, 'file_path' => 'dico/'.$kind.'.pdf']);
    }

    $esito = app(IssueConformityDeclarationAction::class)->execute($dico->load('attachments'));

    expect($esito['declaration']->status)->toBe(ConformityDeclaration::EMESSA)
        ->and($esito['declaration']->issued_on)->not->toBeNull()
        // Le norme tecniche mancanti avvisano ma non bloccano.
        ->and(implode(' ', $esito['warnings']))->toContain('norme tecniche');
});

it('emettendo mette la dico nel fascicolo della commessa', function (): void {
    // Senza questo passaggio l'installatore avrebbe la dichiarazione emessa e la
    // commessa ancora segnata in rosso, perche' la prontezza pretende un
    // documento di tipo dico.
    $workOrder = makeDicoWorkOrder();
    $dico = app(PrepareConformityDeclarationForWorkOrderAction::class)->execute($workOrder, [
        'plant_type' => 'd',
        'work_type' => 'nuovo_impianto',
        'standards' => 'UNI 7129',
    ]);

    foreach ([
        ConformityAttachment::CERTIFICATO_IMPRESA,
        ConformityAttachment::SCHEMA_IMPIANTO,
        ConformityAttachment::RELAZIONE_MATERIALI,
    ] as $kind) {
        $dico->attachments()->updateOrCreate(['kind' => $kind], ['file_path' => 'dico/'.$kind.'.pdf']);
    }

    app(IssueConformityDeclarationAction::class)->execute($dico->load('attachments'));

    $documento = ComplianceDocument::query()
        ->where('work_order_id', $workOrder->id)
        ->where('type', 'dico')
        ->first();

    expect($documento)->not->toBeNull()
        ->and($documento?->status)->toBe('valid')
        ->and($dico->fresh()?->compliance_document_id)->toBe($documento?->id);
});

it('sull allegato II il certificato cciaa non e dovuto', function (): void {
    $allegati = app(DetermineRequiredAttachmentsAction::class);

    $secondo = $allegati->execute(makeDico(['model' => ConformityDeclaration::MODELLO_II]));
    $primo = $allegati->execute(makeDico(['model' => ConformityDeclaration::MODELLO_I]));

    expect($secondo)->not->toHaveKey(ConformityAttachment::CERTIFICATO_IMPRESA)
        ->and($primo)->toHaveKey(ConformityAttachment::CERTIFICATO_IMPRESA)
        ->and($secondo)->toHaveKey(ConformityAttachment::SCHEMA_IMPIANTO);
});

it('su una manutenzione straordinaria lo schema avvisa ma non blocca', function (): void {
    $dico = makeDico(['work_type' => 'manutenzione_straordinaria']);
    $allegati = app(DetermineRequiredAttachmentsAction::class);

    expect($allegati->execute($dico))->not->toHaveKey(ConformityAttachment::SCHEMA_IMPIANTO)
        ->and(implode(' ', $allegati->warnings($dico)))->toContain('consistenza');
});

it('col progetto si allega il progetto e non la relazione', function (): void {
    $allegati = app(DetermineRequiredAttachmentsAction::class);

    $conProgetto = $allegati->execute(makeDico(['project_required' => true]));
    $senzaProgetto = $allegati->execute(makeDico(['project_required' => false]));

    expect($conProgetto)->toHaveKey(ConformityAttachment::PROGETTO)
        ->and($conProgetto)->not->toHaveKey(ConformityAttachment::RELAZIONE_MATERIALI)
        ->and($senzaProgetto)->toHaveKey(ConformityAttachment::RELAZIONE_MATERIALI);
});

it('una riga vuota non e un allegato ma una nota che spiega si', function (): void {
    $allegati = app(DetermineRequiredAttachmentsAction::class);

    $vuoto = makeDico(['work_type' => 'nuovo_impianto']);
    $vuoto->attachments()->create(['kind' => ConformityAttachment::CERTIFICATO_IMPRESA]);
    expect($allegati->missing($vuoto->load('attachments')))
        ->toHaveKey(ConformityAttachment::CERTIFICATO_IMPRESA);

    $spiegato = makeDico(['work_type' => 'nuovo_impianto']);
    $spiegato->attachments()->create([
        'kind' => ConformityAttachment::CERTIFICATO_IMPRESA,
        'note' => 'Visura camerale gia depositata in Comune il 12/03/2026.',
    ]);
    expect($allegati->missing($spiegato->load('attachments')))
        ->not->toHaveKey(ConformityAttachment::CERTIFICATO_IMPRESA);
});

it('le istruzioni d uso e il rapporto di verifica avvisano ma non bloccano', function (): void {
    $dico = makeDico(['work_type' => 'nuovo_impianto']);
    $allegati = app(DetermineRequiredAttachmentsAction::class);
    $avvisi = implode(' ', $allegati->warnings($dico));

    expect($avvisi)->toContain('art. 8 comma 2')
        ->and($avvisi)->toContain('art. 7 comma 1')
        ->and($allegati->missing($dico))->not->toHaveKey(ConformityAttachment::ISTRUZIONI_USO);
});

it('le lettere degli impianti sono quelle del DM 37/08, non a orecchio', function (): void {
    // Nella prima versione erano sbagliate dalla c in poi: la c univa
    // riscaldamento e idrosanitari, che nel decreto sono due lettere diverse, e
    // tutte le successive erano slittate di una posizione. Finiscono stampate su
    // un atto, e un termoidraulico abilitato le sa a memoria.
    $lettere = ConformityDeclaration::IMPIANTI;

    expect(array_keys($lettere))->toBe(['a', 'b', 'c', 'd', 'e', 'f', 'g'])
        ->and($lettere['a'])->toContain('elettric')
        ->and($lettere['b'])->toContain('radiotelevisiv')
        // c = termico. NON deve contenere gli idrosanitari: quelli sono la d.
        ->and($lettere['c'])->toContain('riscaldamento')
        ->and($lettere['c'])->toContain('refrigerazione')
        ->and(mb_strtolower($lettere['c']))->not->toContain('sanitari')
        // d = idrici e sanitari, non gas.
        ->and($lettere['d'])->toContain('idrici')
        ->and($lettere['d'])->toContain('sanitari')
        ->and(mb_strtolower($lettere['d']))->not->toContain('gas')
        // e = gas, non sollevamento.
        ->and($lettere['e'])->toContain('gas')
        // f = sollevamento, non antincendio.
        ->and($lettere['f'])->toContain('sollevamento')
        ->and($lettere['f'])->toContain('ascensori')
        // g = antincendio.
        ->and($lettere['g'])->toContain('antincendio');
});

it('la lettera scelta viene stampata per esteso sulla dichiarazione', function (): void {
    $dico = makeDico(['plant_type' => 'd']);

    expect($dico->plantLabel())->toContain('idrici')
        ->and($dico->plantLabel())->toStartWith('d)');
});
