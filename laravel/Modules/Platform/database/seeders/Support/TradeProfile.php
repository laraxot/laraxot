<?php

declare(strict_types=1);

namespace Modules\Platform\Database\Seeders\Support;

/**
 * A trade-specific theme for the demo data (products, job titles, notes, ...), so
 * the same synthetic-data engine can populate a coherent dataset for a plumber /
 * heating engineer, an electrician, or a metalworker — pick the profile that
 * matches the client before showing the demo.
 *
 * @phpstan-type ProductRow array{name: string, sku: string, unit_price: float, description: string}
 */
final readonly class TradeProfile
{
    /**
     * @param  list<string>  $catalogPartTypes
     * @param  list<string>  $catalogModels
     * @param  list<string>  $resellerPrefixes
     * @param  list<string>  $resellerNotes
     * @param  list<ProductRow>  $products
     * @param  list<string>  $randomProductNames
     * @param  list<string>  $randomProductSuffixes
     * @param  list<string>  $randomProductDescriptions
     * @param  list<string>  $jobTypes
     * @param  list<string>  $jobSubjects
     * @param  list<string>  $jobContexts
     * @param  list<string>  $jobDescriptions
     * @param  list<string>  $jobNotes
     * @param  list<string>  $interventionNotes
     * @param  list<string>  $issueReports
     * @param  list<string>  $dailyNotes
     * @param  list<string>  $activityNotes
     */
    public function __construct(
        public string $key,
        public string $label,
        public array $catalogPartTypes,
        public array $catalogModels,
        public array $resellerPrefixes,
        public array $resellerNotes,
        public array $products,
        public array $randomProductNames,
        public array $randomProductSuffixes,
        public array $randomProductDescriptions,
        public array $jobTypes,
        public array $jobSubjects,
        public array $jobContexts,
        public string $jobTitleFormat,
        public array $jobDescriptions,
        public array $jobNotes,
        public array $interventionNotes,
        public array $issueReports,
        public array $dailyNotes,
        public array $activityNotes,
    ) {}

    public static function carpenteria(): self
    {
        return new self(
            key: 'carpenteria',
            label: 'Carpenteria metallica',
            catalogPartTypes: ['Materiali', 'Lavorazioni', 'Finiture', 'Servizi', 'Carpenteria'],
            catalogModels: ['Linea Industrial', 'Linea Minimal', 'Linea Classica', 'Contract'],
            resellerPrefixes: ['Ferramenta', 'Showroom', 'Studio Interior', 'Contract'],
            resellerNotes: ['Partner rivenditore (demo).', 'Canale contract: richieste su commessa.', 'Referente showroom per campionature finiture.'],
            products: [
                ['name' => 'Tubolare quadro 40x40x2 S235', 'sku' => 'MAT-TQ-4040-2', 'unit_price' => 12.50, 'description' => 'Fornitura tubolare quadro in acciaio al carbonio S235.'],
                ['name' => 'Lamiera S235 3 mm (mq)', 'sku' => 'MAT-LAM-S235-3', 'unit_price' => 42.00, 'description' => 'Lamiera in acciaio al carbonio S235, spessore 3 mm.'],
                ['name' => 'Lamiera inox AISI 304 2 mm (mq)', 'sku' => 'MAT-LAM-304-2', 'unit_price' => 135.00, 'description' => 'Lamiera inox AISI 304, spessore 2 mm.'],
                ['name' => 'Taglio laser', 'sku' => 'LAV-TAGLIO-LASER', 'unit_price' => 65.00, 'description' => 'Servizio di taglio laser su disegno.'],
                ['name' => 'Saldatura MIG/MAG', 'sku' => 'LAV-SALDATURA-MIG', 'unit_price' => 45.00, 'description' => 'Saldatura MIG/MAG su carpenteria leggera.'],
                ['name' => 'Verniciatura a polvere RAL (mq)', 'sku' => 'FIN-VERN-POWDER', 'unit_price' => 32.00, 'description' => 'Verniciatura a polvere, colore RAL a scelta.'],
                ['name' => 'Zincatura a caldo (kg)', 'sku' => 'FIN-ZINC-CALDO', 'unit_price' => 3.40, 'description' => 'Zincatura a caldo per esterni.'],
                ['name' => 'Rilievo misure in cantiere', 'sku' => 'SERV-RILIEVO', 'unit_price' => 95.00, 'description' => 'Uscita tecnica per rilievo misure e verifica quote.'],
                ['name' => 'Posa in opera e fissaggi', 'sku' => 'SERV-POSA', 'unit_price' => 65.00, 'description' => 'Installazione in loco, fissaggi e allineamenti.'],
            ],
            randomProductNames: ['Ringhiera su misura', 'Scala interna', 'Cancello carrabile', 'Porta in ferro e vetro', 'Parapetto balcone', 'Pensilina ingresso', 'Griglia di aerazione'],
            randomProductSuffixes: ['S235', 'Inox', 'Verniciato', 'Zincato'],
            randomProductDescriptions: ['Lavorazione su disegno con finitura a campione.', 'Carpenteria leggera per arredo e contract.', 'Include taglio, saldatura e preparazione per finitura.'],
            jobTypes: ['Realizzazione', 'Fornitura e posa', 'Ripresa e adeguamento', 'Sopralluogo e rilievo'],
            jobSubjects: ['ringhiera', 'parapetto', 'cancello', 'scala', 'porta in ferro e vetro', 'struttura arredo', 'pensilina'],
            jobContexts: ['verniciato RAL', 'zincato a caldo', 'inox spazzolato', 'grezzo (da definire)'],
            jobTitleFormat: '%s %s su misura (%s)',
            jobDescriptions: [
                'Lavorazione su disegno del cliente con verifica quote in loco, produzione e posa in opera.',
                'Carpenteria leggera per arredo: taglio, piegatura, saldatura e finitura secondo campione.',
                'Intervento su commessa: rilievo misure, proposta tecnica, produzione e installazione.',
            ],
            jobNotes: ['Richiesta finitura opaca; campione RAL da confermare.', 'Accesso in cantiere su appuntamento.', 'Consegna preferita in fascia mattutina.'],
            interventionNotes: ['Rilievo misure e verifica quote in loco.', 'Posa in opera e regolazioni finali.', 'Sopralluogo per valutazione variante richiesta.'],
            issueReports: ['Quota non conforme al disegno: serve conferma ufficio tecnico.', 'Foro di fissaggio da riposizionare (interferenza).', 'Richiesta variante finitura: in attesa conferma RAL.'],
            dailyNotes: ['Completata una posa con regolazioni finali.', 'Giornata dedicata a rilievi e sopralluoghi.', 'Avanzamento in officina: taglio e preparazione per saldatura.'],
            activityNotes: ['Inviata richiesta campione finitura (RAL / texture).', 'Pronta distinta lavorazioni: taglio, saldatura, finitura.', 'Programmata posa in opera; accesso su appuntamento.'],
        );
    }

    public static function idraulicoTermo(): self
    {
        return new self(
            key: 'idraulico_termo',
            label: 'Idraulico / Termoidraulico',
            catalogPartTypes: ['Materiali idraulici', 'Termoidraulica', 'Sanitari', 'Manodopera', 'Servizi'],
            catalogModels: ['Riscaldamento', 'Idrosanitario', 'Climatizzazione', 'Trattamento acqua'],
            resellerPrefixes: ['Idroterm', 'Termoidraulica', 'Comet', 'Grossisti Idro'],
            resellerNotes: ['Grossista termoidraulico (demo).', 'Fornitore caldaie e climatizzatori.', 'Referente listini METEL e disponibilità.'],
            products: [
                ['name' => 'Caldaia a condensazione 24 kW', 'sku' => 'IDR-CALD-24', 'unit_price' => 1158.00, 'description' => 'Caldaia murale a condensazione 24 kW con produzione ACS.'],
                ['name' => 'Scaldabagno elettrico 80 lt', 'sku' => 'IDR-SCALD-80', 'unit_price' => 210.00, 'description' => 'Scaldacqua elettrico verticale 80 litri.'],
                ['name' => 'Radiatore alluminio (elemento)', 'sku' => 'IDR-RAD-AL', 'unit_price' => 11.50, 'description' => 'Elemento radiatore in alluminio pressofuso.'],
                ['name' => 'Tubo multistrato 16x2 (mt)', 'sku' => 'IDR-MULTI-16', 'unit_price' => 1.85, 'description' => 'Tubo multistrato PEX-AL-PEX per impianti.'],
                ['name' => 'Valvola termostatica', 'sku' => 'IDR-VALV-TERM', 'unit_price' => 12.50, 'description' => 'Valvola termostatica con testa a cera.'],
                ['name' => 'Miscelatore monocomando lavabo', 'sku' => 'IDR-MISC-LAV', 'unit_price' => 58.00, 'description' => 'Miscelatore lavabo cromato con scarico.'],
                ['name' => 'Cronotermostato WiFi', 'sku' => 'IDR-CRONO', 'unit_price' => 79.00, 'description' => 'Cronotermostato programmabile WiFi.'],
                ['name' => 'Filtro defangatore magnetico', 'sku' => 'IDR-DEFANG', 'unit_price' => 125.00, 'description' => 'Defangatore magnetico a protezione dello scambiatore.'],
                ['name' => 'Manodopera idraulico (h)', 'sku' => 'MO-IDRAULICO', 'unit_price' => 35.00, 'description' => 'Ora di manodopera idraulica specializzata.'],
                ['name' => 'Sopralluogo tecnico', 'sku' => 'SERV-SOPRALLUOGO', 'unit_price' => 60.00, 'description' => 'Uscita per sopralluogo e preventivo in loco.'],
            ],
            randomProductNames: ['Collettore di distribuzione', 'Addolcitore acqua', 'Pompa di circolazione', 'Vaso di espansione 8 lt', 'Piatto doccia in resina', 'Sanitario sospeso WC', 'Condizionatore mono split 9000 BTU', 'Boiler a pompa di calore'],
            randomProductSuffixes: ['Ariston', 'Vaillant', 'Baxi', 'Immergas', 'Standard'],
            randomProductDescriptions: ['Fornitura e posa a regola d\'arte.', 'Materiale idrotermosanitario di primaria marca.', 'Installazione con collaudo e messa in pressione.'],
            jobTypes: ['Installazione', 'Sostituzione', 'Manutenzione', 'Ristrutturazione', 'Riparazione'],
            jobSubjects: ['caldaia a condensazione', 'impianto di riscaldamento', 'bagno completo', 'scaldabagno', 'impianto idrico', 'climatizzatore', 'radiatori', 'addolcitore acqua'],
            jobContexts: ['appartamento', 'villetta', 'condominio', 'ufficio', 'locale commerciale'],
            jobTitleFormat: '%s %s — %s',
            jobDescriptions: [
                'Sopralluogo, fornitura e posa in opera con collaudo e messa in pressione.',
                'Sostituzione generatore di calore con smaltimento vecchio apparecchio e avviamento.',
                'Rifacimento impianto idrosanitario del bagno con nuovi sanitari e rubinetteria.',
            ],
            jobNotes: ['Verificare posizione contatore gas e scarico condensa.', 'Cliente presente su appuntamento.', 'Da rilasciare dichiarazione di conformità.'],
            interventionNotes: ['Sostituzione caldaia e avviamento con analisi combustione.', 'Ricerca perdita e ripristino tenuta impianto.', 'Manutenzione ordinaria e controllo pressione.'],
            issueReports: ['Scarico condensa da adeguare: necessario sopralluogo.', 'Pressione impianto instabile: verificare vaso espansione.', 'Attacchi non compatibili: serve raccorderia aggiuntiva.'],
            dailyNotes: ['Sostituita una caldaia con avviamento e prova fumi.', 'Giornata su ricerca perdite e manutenzioni.', 'Montaggio sanitari e collaudo bagno completato.'],
            activityNotes: ['Ordinata caldaia dal grossista.', 'Programmato avviamento con analisi combustione.', 'Preparata dichiarazione di conformità impianto.'],
        );
    }

    public static function elettricista(): self
    {
        return new self(
            key: 'elettricista',
            label: 'Elettricista',
            catalogPartTypes: ['Materiali elettrici', 'Quadristica', 'Illuminazione', 'Manodopera', 'Servizi'],
            catalogModels: ['Civile', 'Industriale', 'Domotica', 'Fotovoltaico'],
            resellerPrefixes: ['Elettroforniture', 'Comet', 'Elettro', 'Grossisti Elettrici'],
            resellerNotes: ['Grossista materiale elettrico (demo).', 'Fornitore quadristica e illuminazione.', 'Referente per domotica e fotovoltaico.'],
            products: [
                ['name' => 'Cavo FS17 1x2,5 mmq (mt)', 'sku' => 'ELE-FS17-25', 'unit_price' => 0.65, 'description' => 'Cavo unipolare FS17 sezione 2,5 mmq.'],
                ['name' => 'Cavo FG16 3x2,5 mmq (mt)', 'sku' => 'ELE-FG16-25', 'unit_price' => 1.90, 'description' => 'Cavo multipolare FG16OR16 antifiamma.'],
                ['name' => 'Interruttore magnetotermico 16A', 'sku' => 'ELE-MT-16', 'unit_price' => 9.80, 'description' => 'Magnetotermico modulare 1P+N 16A.'],
                ['name' => 'Differenziale 25A 30mA', 'sku' => 'ELE-DIFF-25', 'unit_price' => 34.00, 'description' => 'Interruttore differenziale salvavita 2P 25A 30mA.'],
                ['name' => 'Quadro elettrico 12 moduli', 'sku' => 'ELE-QUADRO-12', 'unit_price' => 22.00, 'description' => 'Centralino da incasso 12 moduli con portello.'],
                ['name' => 'Presa Schuko con placca', 'sku' => 'ELE-PRESA-SCH', 'unit_price' => 6.50, 'description' => 'Presa bipasso/Schuko con supporto e placca.'],
                ['name' => 'Faretto LED da incasso 7W', 'sku' => 'ELE-LED-7', 'unit_price' => 8.90, 'description' => 'Faretto LED incasso 7W luce naturale.'],
                ['name' => 'Punto luce/presa (cad)', 'sku' => 'ELE-PUNTO', 'unit_price' => 28.00, 'description' => 'Realizzazione punto luce o presa comprensivo di materiale.'],
                ['name' => 'Manodopera elettricista (h)', 'sku' => 'MO-ELETTRICISTA', 'unit_price' => 35.00, 'description' => 'Ora di manodopera elettrica specializzata.'],
                ['name' => 'Dichiarazione di conformità (DiCo)', 'sku' => 'SERV-DICO', 'unit_price' => 120.00, 'description' => 'Rilascio dichiarazione di conformità (DM 37/08).'],
            ],
            randomProductNames: ['Videocitofono', 'Termostato WiFi', 'Colonnina di ricarica EV', 'Inverter fotovoltaico', 'Modulo domotica KNX', 'Plafoniera LED', 'Rilevatore di movimento', 'Gruppo di continuità UPS'],
            randomProductSuffixes: ['Bticino', 'Gewiss', 'Vimar', 'Finder', 'Standard'],
            randomProductDescriptions: ['Fornitura e posa a regola d\'arte.', 'Componente per impianto elettrico civile.', 'Installazione con collaudo e verifica funzionale.'],
            jobTypes: ['Installazione', 'Rifacimento', 'Manutenzione', 'Adeguamento a norma', 'Riparazione'],
            jobSubjects: ['impianto elettrico', 'quadro elettrico', 'punti luce e prese', 'videocitofono', 'impianto fotovoltaico', 'colonnina di ricarica', 'illuminazione LED', 'impianto domotico'],
            jobContexts: ['appartamento', 'villetta', 'condominio', 'ufficio', 'capannone'],
            jobTitleFormat: '%s %s — %s',
            jobDescriptions: [
                'Cablaggio, quadro, punti luce e prese con dichiarazione di conformità.',
                'Adeguamento impianto a norma CEI 64-8 con nuovo quadro e differenziali.',
                'Installazione illuminazione LED e automazioni con collaudo funzionale.',
            ],
            jobNotes: ['Verificare portata contatore e messa a terra.', 'Cliente presente su appuntamento.', 'Da rilasciare dichiarazione di conformità DM 37/08.'],
            interventionNotes: ['Sostituzione quadro e differenziali con prova di intervento.', 'Realizzazione nuovi punti luce e prese.', 'Ricerca guasto e ripristino linea.'],
            issueReports: ['Messa a terra da verificare: valore non conforme.', 'Portata linea insufficiente: prevedere sezione maggiore.', 'Quadro esistente non a norma: necessario adeguamento.'],
            dailyNotes: ['Rifatto un quadro con nuovi differenziali.', 'Giornata su nuovi punti luce e prese.', 'Collaudo impianto e prova differenziali completati.'],
            activityNotes: ['Ordinato materiale elettrico dal grossista.', 'Programmato collaudo e prova di intervento differenziali.', 'Preparata dichiarazione di conformità.'],
        );
    }
}
