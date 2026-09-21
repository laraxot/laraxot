<?php

declare(strict_types=1);

namespace Modules\Compliance\Actions;

use Modules\Compliance\Models\ComplianceDocument;
use Modules\Compliance\Models\ConformityAttachment;
use Modules\Compliance\Models\ConformityDeclaration;
use Modules\Customer\Models\CustomerSite;
use Modules\Platform\Settings\SettingsRepository;
use Modules\WorkOrder\Models\WorkOrder;
use Modules\WorkOrder\Models\WorkOrderMaterial;
use Spatie\QueueableAction\QueueableAction;

/**
 * Crea la bozza di dichiarazione prendendo dalla commessa tutto quello che la
 * commessa gia' sa.
 *
 * E' il motivo per cui questa funzione vive nel gestionale commesse e non
 * altrove. Il committente, l'immobile e i materiali usati sono gia' scritti da
 * qualche parte nel momento in cui l'installatore chiude il lavoro: chiedergli
 * di ribatterli per fare la dichiarazione e' il tipo di doppio lavoro che un
 * gestionale dovrebbe togliere, non aggiungere.
 *
 * Quello che NON si precompila e' altrettanto importante: tipo di impianto, tipo
 * di intervento e norme tecniche restano vuoti. Sono scelte che cambiano quali
 * allegati sono dovuti, e indovinarle al posto dell'installatore significa
 * produrre un fascicolo sbagliato che sembra giusto.
 *
 * Per lo stesso motivo resta vuota anche la via dell'immobile: in questa base
 * ne' `customers` ne' `customer_sites` hanno una colonna di indirizzo (gli
 * indirizzi vivono nel modulo Geo, e questi due non ci sono agganciati). Si
 * precompilano comune, provincia e CAP, che ci sono; la via la scrive
 * l'installatore. Un campo vuoto si vede, un indirizzo inventato su un atto no.
 */
final class PrepareConformityDeclarationForWorkOrderAction
{
    use QueueableAction;

    /**
     * Quello che serve al pulsante sulla commessa: la bozza, nuova o quella che
     * c'era gia'.
     *
     * Due dichiarazioni in bozza per lo stesso impianto non sono una comodita',
     * sono un modo per emettere quella sbagliata. Se una c'e' gia' si riapre
     * quella, e chi ha premuto va avvisato che sta continuando un lavoro
     * iniziato, non cominciandone uno nuovo.
     *
     * @return array{declaration: ConformityDeclaration, reused: bool}
     */
    public function executeOrReuseDraft(WorkOrder $workOrder): array
    {
        $bozza = ConformityDeclaration::query()
            ->where('work_order_id', $workOrder->getKey())
            ->where('status', ConformityDeclaration::BOZZA)
            ->latest('id')
            ->first();

        if ($bozza instanceof ConformityDeclaration) {
            return ['declaration' => $bozza, 'reused' => true];
        }

        return ['declaration' => $this->execute($workOrder), 'reused' => false];
    }

    /**
     * @param  array<string, mixed>  $overrides
     */
    public function execute(WorkOrder $workOrder, array $overrides = []): ConformityDeclaration
    {
        $workOrder->loadMissing(['customer', 'site', 'materials']);

        $customer = $workOrder->customer;
        $site = $workOrder->site;

        // BelongsTo nullable (customer_site_id puo' essere null): $site puo'
        // davvero non esserci, ma Larastan non capisce l'unione nullable su
        // una magic property che condivide il nome con la relation (bug noto
        // di ModelRelationsExtension). Restringere il tipo con instanceof
        // invece di ?-> tiene la sicurezza a runtime senza inseguire un
        // falso positivo che ?-> non risolverebbe comunque.
        $siteCity = $site instanceof CustomerSite ? $site->city : null;
        $siteProvince = $site instanceof CustomerSite ? $site->province : null;
        $sitePostalCode = $site instanceof CustomerSite ? $site->postal_code : null;

        $declaration = ConformityDeclaration::query()->create(array_merge($this->impresa(), [
            'work_order_id' => $workOrder->id,
            'customer_id' => $workOrder->customer_id,
            'customer_site_id' => $workOrder->customer_site_id,
            'number' => ConformityDeclaration::nextNumber(),
            'model' => ConformityDeclaration::MODELLO_I,
            'status' => ConformityDeclaration::BOZZA,

            'client_name' => (string) ($customer->legal_name ?? ''),
            'client_tax_code' => ($customer->tax_code ?? $customer->vat_number) ?? '',

            // L'immobile e' il cantiere della commessa. Se il cantiere non c'e',
            // si ripiega sul comune del cliente, ma resta un ripiego: sono due
            // cose diverse, e su una dichiarazione confonderle e' un errore.
            'site_city' => ($siteCity ?? $customer->city) ?? '',
            'site_province' => ($siteProvince ?? $customer->province) ?? '',
            'site_postal_code' => ($sitePostalCode ?? $customer->postal_code) ?? '',

            'work_description' => $workOrder->title,
            'materials' => $this->materials($workOrder),

            // Volutamente NON precompilati: cambiano gli allegati dovuti.
            'plant_type' => '',
            'work_type' => '',
        ], $overrides));

        $this->prepareAttachmentSlots($declaration);

        return $declaration->refresh();
    }

    /**
     * I dati dell'impresa che dichiara.
     *
     * Non sono nell'anagrafica: questo gestionale non ha una scheda "la mia
     * azienda". Invece di lasciare un segnaposto che poi finisce stampato su un
     * atto, si prendono, in ordine: da quello che e' stato configurato a mano
     * nelle impostazioni, altrimenti dall'ultima dichiarazione dove erano
     * scritti.
     *
     * Il secondo caso e' quello che conta nell'uso vero: la prima volta
     * l'installatore compila il riquadro dell'impresa, e da li' in poi se lo
     * ritrova gia' fatto. Una correzione fatta su una dichiarazione si porta
     * dietro anche le successive.
     *
     * @return array<string, string|null>
     */
    private function impresa(): array
    {
        $impostazioni = app(SettingsRepository::class);

        $ultima = ConformityDeclaration::query()
            ->whereNotNull('company_name')
            ->where('company_name', '!=', '')
            ->latest('id')
            ->first();

        $campi = [
            'company_name' => 'name',
            'company_address' => 'address',
            'company_vat' => 'vat',
            'company_cciaa' => 'cciaa',
            'legal_representative' => 'legal_representative',
            'technical_manager' => 'technical_manager',
        ];

        $out = [];

        foreach ($campi as $colonna => $chiave) {
            $configurato = $impostazioni->getString('dico.company.'.$chiave);

            $precedente = $ultima instanceof ConformityDeclaration
                ? $ultima->getAttribute($colonna)
                : null;

            $out[$colonna] = filled($configurato)
                ? $configurato
                : (is_string($precedente) && $precedente !== '' ? $precedente : null);
        }

        // Ultimo ripiego per il solo nome: un modulo senza intestazione non si
        // capisce nemmeno di chi sia la bozza.
        if (! filled($out['company_name'])) {
            $appName = config('app.name');
            $out['company_name'] = is_scalar($appName) ? (string) $appName : '';
        }

        return $out;
    }

    /**
     * L'elenco dei materiali, dalle righe della commessa.
     */
    private function materials(WorkOrder $workOrder): ?string
    {
        $righe = $workOrder->materials
            ->map(static function (WorkOrderMaterial $m): string {
                $quantita = rtrim(rtrim(number_format((float) $m->quantity, 3, ',', '.'), '0'), ',');

                return '- '.$m->description.' ('.$quantita.' '.($m->unit_code ?? 'pz').')';
            })
            ->all();

        return $righe === [] ? null : implode("\n", $righe);
    }

    /**
     * Crea le righe degli allegati che la commessa ha gia' nel fascicolo.
     *
     * Se un documento e' gia' stato caricato sulla commessa, l'allegato punta a
     * quello invece di far ricaricare lo stesso file una seconda volta.
     */
    private function prepareAttachmentSlots(ConformityDeclaration $declaration): void
    {
        $workOrder = $declaration->workOrder;
        $documenti = $workOrder instanceof WorkOrder ? $workOrder->complianceDocuments : collect();

        $corrispondenze = [
            'progetto' => ConformityAttachment::PROGETTO,
            'schema' => ConformityAttachment::SCHEMA_IMPIANTO,
            'visura' => ConformityAttachment::CERTIFICATO_IMPRESA,
            'cciaa' => ConformityAttachment::CERTIFICATO_IMPRESA,
        ];

        foreach ($documenti as $documento) {
            if (! $documento instanceof ComplianceDocument) {
                continue;
            }

            $tipo = mb_strtolower($documento->type.' '.$documento->title);

            foreach ($corrispondenze as $indizio => $kind) {
                if (! str_contains($tipo, $indizio)) {
                    continue;
                }

                $declaration->attachments()->firstOrCreate(
                    ['kind' => $kind],
                    [
                        'title' => $documento->title,
                        'compliance_document_id' => $documento->id,
                        'note' => 'Gia nel fascicolo della commessa.',
                    ],
                );

                break;
            }
        }
    }
}
