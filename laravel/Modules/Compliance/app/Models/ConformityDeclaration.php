<?php

declare(strict_types=1);

namespace Modules\Compliance\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Modules\Customer\Models\Customer;
use Modules\Customer\Models\CustomerSite;
use Modules\User\Models\User;
use Modules\WorkOrder\Models\WorkOrder;

/**
 * La Dichiarazione di Conformita' del DM 37/2008.
 *
 * @property int $id
 * @property int|null $work_order_id
 * @property int|null $customer_id
 * @property int|null $customer_site_id
 * @property int|null $customer_system_id
 * @property int|null $compliance_document_id
 * @property string|null $issued_by_user_id
 * @property string $model
 * @property string $number
 * @property CarbonInterface|null $issued_on
 * @property string $company_name
 * @property string|null $company_address
 * @property string|null $company_vat
 * @property string|null $company_cciaa
 * @property string|null $legal_representative
 * @property string|null $technical_manager
 * @property string $client_name
 * @property string|null $client_tax_code
 * @property string|null $site_address
 * @property string|null $site_city
 * @property string|null $site_province
 * @property string|null $site_postal_code
 * @property string|null $site_unit
 * @property string|null $site_cadastral
 * @property string|null $site_use
 * @property string $plant_type
 * @property string $work_type
 * @property string|null $work_description
 * @property string|null $materials
 * @property string|null $standards
 * @property bool $project_required
 * @property string|null $project_author
 * @property CarbonInterface|null $project_date
 * @property bool $non_standard_materials
 * @property bool $previous_declaration_exists
 * @property string $status
 * @property CarbonInterface|null $issued_at
 * @property string|null $notes
 * @property-read WorkOrder|null $workOrder
 * @property-read Customer|null $customer
 * @property-read CustomerSite|null $site
 */
final class ConformityDeclaration extends BaseModel
{
    use SoftDeletes;

    public const string BOZZA = 'draft';

    public const string EMESSA = 'issued';

    public const string MODELLO_I = 'I';

    public const string MODELLO_II = 'II';

    /** @var array<string, string> */
    public const array MODELLI = [
        self::MODELLO_I => 'Allegato I - Impresa installatrice',
        self::MODELLO_II => 'Allegato II - Ufficio tecnico interno',
    ];

    /**
     * Le lettere dell'art. 1 comma 2 del DM 37/08.
     *
     * ATTENZIONE, QUESTE LETTERE NON SI TOCCANO A ORECCHIO. Sono quelle del
     * decreto, e finiscono stampate su un atto: sbagliarne una vuol dire
     * depositare la dichiarazione sotto la categoria sbagliata. Nella prima
     * versione di questo file le lettere da c a g erano sbagliate — la c univa
     * riscaldamento e idrosanitari, che nel decreto sono due lettere diverse, e
     * da li in poi tutte le altre erano slittate di una posizione. Un
     * termoidraulico abilitato se ne accorge in due secondi, perche' le lettere
     * della sua abilitazione le sa a memoria.
     *
     * Testo di riferimento: art. 1 comma 2 del DM 22 gennaio 2008 n. 37.
     *
     * @var array<string, string>
     */
    public const array IMPIANTI = [
        'a' => 'a) Impianti elettrici: produzione, trasformazione, trasporto, distribuzione e utilizzazione dell energia elettrica, protezione contro le scariche atmosferiche, automazione di porte, cancelli e barriere',
        'b' => 'b) Impianti radiotelevisivi, antenne e impianti elettronici in genere',
        'c' => 'c) Impianti di riscaldamento, climatizzazione, condizionamento e refrigerazione di qualsiasi natura, comprese le opere di evacuazione dei prodotti della combustione e delle condense, e di ventilazione e aerazione dei locali',
        'd' => 'd) Impianti idrici e sanitari di qualsiasi natura o specie',
        'e' => 'e) Impianti per la distribuzione e l utilizzazione di gas di qualsiasi tipo, comprese le opere di evacuazione dei prodotti della combustione e di ventilazione e aerazione dei locali',
        'f' => 'f) Impianti di sollevamento di persone o di cose: ascensori, montacarichi, scale mobili e simili',
        'g' => 'g) Impianti di protezione antincendio',
    ];

    /** @var array<string, string> */
    public const array INTERVENTI = [
        'nuovo_impianto' => 'Nuovo impianto',
        'trasformazione' => 'Trasformazione',
        'ampliamento' => 'Ampliamento',
        'manutenzione_straordinaria' => 'Manutenzione straordinaria',
        'altro' => 'Altro',
    ];

    /**
     * Gli interventi su cui lo schema dell'impianto e' sempre dovuto.
     *
     * La manutenzione straordinaria resta fuori apposta: il decreto li' e' meno
     * netto, e infatti diventa un avviso e non un blocco.
     *
     * @var array<int, string>
     */
    public const INTERVENTI_CON_SCHEMA = ['nuovo_impianto', 'trasformazione', 'ampliamento'];

    protected $table = 'conformity_declarations';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'issued_on' => 'date',
            'project_date' => 'date',
            'issued_at' => 'datetime',
            'project_required' => 'boolean',
            'non_standard_materials' => 'boolean',
            'previous_declaration_exists' => 'boolean',
        ];
    }

    /**
     * Il prossimo numero libero dell'anno.
     *
     * Sta sul modello e non su una delle due azioni perche' i punti da cui nasce
     * una dichiarazione sono due — il pulsante sulla commessa e la creazione a
     * mano — e devono numerare allo stesso modo.
     *
     * Conta anche le cancellate: un numero gia' uscito su un atto non si riusa,
     * anche se quella dichiarazione e' stata poi cestinata.
     */
    public static function nextNumber(): string
    {
        $anno = Carbon::now()->year;

        $quante = self::withTrashed()
            ->where('number', 'like', 'DICO-'.$anno.'-%')
            ->count();

        return sprintf('DICO-%d-%03d', $anno, $quante + 1);
    }

    public function isIssued(): bool
    {
        return $this->status === self::EMESSA;
    }

    /**
     * Sull'Allegato II i requisiti li ha la persona (il responsabile
     * dell'ufficio tecnico), non l'impresa: il certificato CCIAA esce dagli
     * allegati dovuti e cambia chi firma.
     */
    public function isInternalOffice(): bool
    {
        return $this->model === self::MODELLO_II;
    }

    public function workLabel(): string
    {
        return self::INTERVENTI[$this->work_type] ?? $this->work_type;
    }

    public function plantLabel(): string
    {
        return self::IMPIANTI[$this->plant_type] ?? $this->plant_type;
    }

    /** @return HasMany<ConformityAttachment, $this> */
    public function attachments(): HasMany
    {
        return $this->hasMany(ConformityAttachment::class, 'declaration_id');
    }

    /** @return BelongsTo<WorkOrder, $this> */
    public function workOrder(): BelongsTo
    {
        return $this->belongsTo(WorkOrder::class);
    }

    /** @return BelongsTo<Customer, $this> */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /** @return BelongsTo<CustomerSite, $this> */
    public function site(): BelongsTo
    {
        return $this->belongsTo(CustomerSite::class, 'customer_site_id');
    }

    /** @return BelongsTo<CustomerSystem, $this> */
    public function system(): BelongsTo
    {
        return $this->belongsTo(CustomerSystem::class, 'customer_system_id');
    }

    /** @return BelongsTo<ComplianceDocument, $this> */
    public function complianceDocument(): BelongsTo
    {
        return $this->belongsTo(ComplianceDocument::class);
    }

    /** @return BelongsTo<User, $this> */
    public function issuedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'issued_by_user_id');
    }
}
