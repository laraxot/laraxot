<?php

declare(strict_types=1);

namespace Modules\Compliance\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Un allegato della Dichiarazione di Conformita'.
 *
 * @property int $id
 * @property int $declaration_id
 * @property string $kind
 * @property string|null $title
 * @property string|null $file_path
 * @property int|null $compliance_document_id
 * @property string|null $note
 */
final class ConformityAttachment extends BaseModel
{
    // I sei allegati dell'Allegato I.
    public const PROGETTO = 'project';

    public const RELAZIONE_MATERIALI = 'materials_report';

    public const SCHEMA_IMPIANTO = 'plant_diagram';

    public const DICO_PRECEDENTE = 'previous_declaration';

    public const CERTIFICATO_IMPRESA = 'company_certificate';

    public const MATERIALI_NON_NORMALIZZATI = 'non_standard_attestation';

    // Non stanno nell'Allegato I ma sono obblighi lo stesso: le istruzioni d'uso
    // per l'art. 8 comma 2, il rapporto delle verifiche per l'art. 7 comma 1.
    // Compaiono fra gli avvisi, non fra i blocchi.
    public const ISTRUZIONI_USO = 'use_instructions';

    public const RAPPORTO_VERIFICA = 'verification_report';

    /** @var array<string, string> */
    public const ETICHETTE = [
        self::PROGETTO => 'Progetto',
        self::RELAZIONE_MATERIALI => 'Relazione con tipologie dei materiali',
        self::SCHEMA_IMPIANTO => 'Schema dell impianto realizzato',
        self::DICO_PRECEDENTE => 'Dichiarazione di conformita precedente',
        self::CERTIFICATO_IMPRESA => 'Copia del certificato di riconoscimento dei requisiti',
        self::MATERIALI_NON_NORMALIZZATI => 'Attestazione materiali o sistemi non normalizzati',
        self::ISTRUZIONI_USO => 'Istruzioni per l uso e la manutenzione',
        self::RAPPORTO_VERIFICA => 'Rapporto delle verifiche iniziali',
    ];

    protected $table = 'conformity_attachments';

    /** @var array<int, string> */
    protected $guarded = [];

    /**
     * L'allegato conta come presente.
     *
     * Vale il file, ma vale anche una nota che spieghi dove sta: capita spesso
     * che un documento sia gia' stato depositato altrove, e una riga che dice
     * dove vale quanto il documento stesso. Vale anche il rimando a un documento
     * gia' nel fascicolo della commessa, cosi' non si ricarica due volte la
     * stessa cosa.
     *
     * Non vale invece una riga vuota: quella e' un promemoria, non un allegato.
     */
    public function isSatisfied(): bool
    {
        return filled($this->file_path)
            || filled($this->note)
            || $this->compliance_document_id !== null;
    }

    public function label(): string
    {
        return self::ETICHETTE[$this->kind] ?? $this->kind;
    }

    /** @return BelongsTo<ConformityDeclaration, $this> */
    public function declaration(): BelongsTo
    {
        return $this->belongsTo(ConformityDeclaration::class, 'declaration_id');
    }

    /** @return BelongsTo<ComplianceDocument, $this> */
    public function complianceDocument(): BelongsTo
    {
        return $this->belongsTo(ComplianceDocument::class);
    }
}
