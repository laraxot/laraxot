<?php

declare(strict_types=1);

namespace Modules\Compliance\Actions;

use Modules\Compliance\Models\ConformityAttachment;
use Modules\Compliance\Models\ConformityDeclaration;
use Spatie\QueueableAction\QueueableAction;

/**
 * Quali allegati servono a QUESTA dichiarazione, e perche'.
 *
 * E' il cuore della funzione. Stampare il modulo dell'Allegato I e' la parte
 * facile; quella che serve davvero e' sapere che il fascicolo non e' completo, e
 * saperlo prima di consegnarlo al cliente invece che quando lo contesta il
 * Comune.
 *
 * I sei allegati dell'Allegato I non sono sempre tutti dovuti: dipende dal tipo
 * di intervento e da com'e' fatto l'impianto. Ogni regola qui sotto porta
 * scritto il motivo, cosi' quando una va cambiata si sa cosa si sta cambiando.
 *
 * ATTENZIONE, e va detto in chiaro: questa e' una lettura del decreto, non un
 * parere legale. Il decreto lascia margini, in particolare sulla manutenzione
 * straordinaria, e alcune Camere di Commercio e alcuni Comuni chiedono piu' di
 * cosi'. Il servizio segnala, non certifica, e la firma resta di chi la mette.
 */
final class DetermineRequiredAttachmentsAction
{
    use QueueableAction;

    /**
     * @return array<string, string> allegato => perche' e' dovuto
     */
    public function execute(ConformityDeclaration $declaration): array
    {
        $required = [];

        // Prova che l'impresa e' abilitata a rilasciare la dichiarazione: senza,
        // la firma la mette qualcuno che non poteva metterla.
        //
        // Non vale per l'Allegato II: sull'ufficio tecnico interno di un'impresa
        // non installatrice i requisiti li ha la persona, non l'impresa, e il
        // certificato CCIAA esce dagli allegati dovuti.
        if (! $declaration->isInternalOffice()) {
            $required[ConformityAttachment::CERTIFICATO_IMPRESA] =
                'Va allegato il certificato di riconoscimento dei requisiti: e la prova che l impresa e abilitata a rilasciare la dichiarazione.';
        }

        // Lo schema di cio' che e' stato realizzato serve quando l'impianto nasce
        // o cambia consistenza. Sulla manutenzione straordinaria il decreto e'
        // meno netto, e infatti sotto diventa un avviso.
        if (in_array($declaration->work_type, ConformityDeclaration::INTERVENTI_CON_SCHEMA, true)) {
            $required[ConformityAttachment::SCHEMA_IMPIANTO] =
                'Intervento di tipo "'.$declaration->workLabel().'": lo schema dell impianto realizzato e sempre dovuto.';
        }

        // La relazione dice con che materiali e' stato fatto l'impianto. Quando
        // c'e' un progetto firmato da un professionista, il progetto gia'
        // contiene quelle informazioni.
        if (! $declaration->project_required) {
            $required[ConformityAttachment::RELAZIONE_MATERIALI] =
                'Serve la relazione con le tipologie dei materiali: non c e un progetto che la contenga.';
        } else {
            $required[ConformityAttachment::PROGETTO] =
                'Il progetto e obbligatorio per questo impianto (art. 5 DM 37/08) e va allegato.';
        }

        // Su un impianto che esisteva gia', la dichiarazione precedente e' il
        // pezzo che collega questo intervento a quello di prima.
        if ($declaration->previous_declaration_exists) {
            $required[ConformityAttachment::DICO_PRECEDENTE] =
                'L impianto ha gia una dichiarazione: va richiamata in allegato.';
        }

        if ($declaration->non_standard_materials) {
            $required[ConformityAttachment::MATERIALI_NON_NORMALIZZATI] =
                'Sono stati usati materiali o sistemi non normalizzati: serve la relativa attestazione di conformita.';
        }

        return $required;
    }

    /**
     * Cosa manca perche' il fascicolo sia completo.
     *
     * @return array<string, string>
     */
    public function missing(ConformityDeclaration $declaration): array
    {
        /** @var array<int, string> $present */
        $present = $declaration->attachments
            ->filter(static fn (ConformityAttachment $a): bool => $a->isSatisfied())
            ->map(static fn (ConformityAttachment $a): string => $a->kind)
            ->values()
            ->all();

        return array_diff_key($this->execute($declaration), array_flip($present));
    }

    /**
     * Cose da sapere che non bloccano l'emissione.
     *
     * @return array<int, string>
     */
    public function warnings(ConformityDeclaration $declaration): array
    {
        $out = [];

        // Il caso grigio del decreto: su una manutenzione straordinaria lo schema
        // non e' sempre richiesto, ma se l'intervento ha toccato la consistenza
        // dell'impianto molti Comuni lo pretendono lo stesso.
        if ($declaration->work_type === 'manutenzione_straordinaria'
            && ! $this->has($declaration, ConformityAttachment::SCHEMA_IMPIANTO)) {
            $out[] = 'Manutenzione straordinaria senza schema allegato: il decreto non lo impone sempre, ma se hai toccato la consistenza dell impianto va messo.';
        }

        if ($declaration->work_type === 'altro' && trim((string) $declaration->work_description) === '') {
            $out[] = 'Hai scelto "Altro" come tipo di intervento senza descriverlo: e proprio la casella che va spiegata.';
        }

        if (! $declaration->previous_declaration_exists && $declaration->work_type !== 'nuovo_impianto') {
            $out[] = 'Non e un impianto nuovo e non risulta una dichiarazione precedente: se davvero non esiste, scrivilo nelle note invece di lasciare il vuoto.';
        }

        if (trim((string) $declaration->standards) === '') {
            $out[] = 'Non hai indicato le norme tecniche applicate.';
        }

        // L'art. 8 comma 2 obbliga a consegnare al committente le istruzioni
        // d'uso e manutenzione, e quelle diventano allegato della dichiarazione.
        // Non e' fra gli allegati dell'Allegato I, ma e' un obbligo lo stesso.
        if (! $this->has($declaration, ConformityAttachment::ISTRUZIONI_USO)) {
            $out[] = 'Mancano le istruzioni per l uso e la manutenzione: l art. 8 comma 2 obbliga a consegnarle al committente, e vanno allegate.';
        }

        // L'art. 7 comma 1 impone le verifiche iniziali prima della consegna: il
        // rapporto non e' obbligatorio da allegare, ma e' la prova che le
        // verifiche sono state fatte.
        if (! $this->has($declaration, ConformityAttachment::RAPPORTO_VERIFICA)) {
            $out[] = 'Non c e il rapporto delle verifiche iniziali (art. 7 comma 1): allegarlo e il modo piu semplice di dimostrare che sono state fatte.';
        }

        if (trim((string) $declaration->site_address) === '') {
            $out[] = 'Manca l indirizzo dell immobile: e l unita immobiliare dove sta l impianto, non la sede del cliente.';
        }

        return $out;
    }

    private function has(ConformityDeclaration $declaration, string $kind): bool
    {
        return $declaration->attachments
            ->contains(static fn (ConformityAttachment $a): bool => $a->kind === $kind && $a->isSatisfied());
    }
}
