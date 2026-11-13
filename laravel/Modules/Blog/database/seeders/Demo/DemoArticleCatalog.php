<?php

declare(strict_types=1);

namespace Modules\Blog\Database\Seeders\Demo;

/**
 * Contenuti demo del front office (Novita' ed Eventi): testi civici credibili in it/en, immagini Design Comuni del tema.
 * Ogni voce: slug, titolo, sintesi, paragrafi, immagine; gli eventi hanno anche date relative a oggi (giorni).
 */
final class DemoArticleCatalog
{
    private const string IMAGES = '/themes/Sixteen/design-comuni/assets/images/';

    /**
     * @return list<array{slug: string, image: string, featured: bool, days_ago: int, title: array{it: string, en: string}, summary: string, body: array{it: list<string>, en: list<string>}}>
     */
    public static function posts(): array
    {
        return [
            [
                'slug' => 'piano-asfaltature-autunno', 'image' => 'notizia-asfaltatura.png', 'featured' => true, 'days_ago' => 2,
                'title' => ['it' => 'Piano asfaltature d\'autunno: 14 strade rinnovate', 'en' => 'Autumn resurfacing plan: 14 streets renewed'],
                'summary' => 'Da lunedì partono i lavori su 14 strade indicate dalle segnalazioni dei cittadini, con chiusure a fasce orarie.',
                'body' => [
                    'it' => ['Il piano nasce dalle segnalazioni arrivate su FixCity: le buche più segnalate sono state verificate dagli uffici tecnici e inserite in un calendario unico.', 'I cantieri lavoreranno dalle 9 alle 16 per ridurre i disagi nelle ore di punta. Le strade interessate e le date sono pubblicate nella sezione Segnalazioni.', 'Chi ha inviato una segnalazione riceverà una notifica quando il tratto sarà chiuso.'],
                    'en' => ['The plan comes from reports sent through FixCity: the most reported potholes were checked by the technical office and put in a single schedule.', 'Work runs from 9am to 4pm to limit rush-hour disruption. Streets and dates are listed in the Reports section.', 'Everyone who sent a report will be notified when their stretch is closed.'],
                ],
            ],
            [
                'slug' => 'biblioteca-dialoghi-dautore', 'image' => 'notizia-dialoghi-biblioteca.png', 'featured' => true, 'days_ago' => 5,
                'title' => ['it' => 'In biblioteca tornano i Dialoghi d\'autore', 'en' => 'Author talks return to the library'],
                'summary' => 'Sei incontri gratuiti con scrittori e scrittrici, il giovedì sera, con prenotazione online.',
                'body' => [
                    'it' => ['La biblioteca civica riapre la rassegna con sei appuntamenti tra novembre e febbraio, dedicati a narrativa, saggistica e libri per ragazzi.', 'L\'ingresso è gratuito fino a esaurimento posti; la sala è accessibile e gli incontri sono sottotitolati.'],
                    'en' => ['The public library reopens the series with six meetings between November and February on fiction, non-fiction and books for young readers.', 'Entry is free while seats last; the room is accessible and talks are subtitled.'],
                ],
            ],
            [
                'slug' => 'nuova-ludoteca-quartiere-nord', 'image' => 'notizia-ludoteca.png', 'featured' => false, 'days_ago' => 9,
                'title' => ['it' => 'Apre la nuova ludoteca del quartiere Nord', 'en' => 'New play centre opens in the North district'],
                'summary' => 'Uno spazio gratuito per bambine e bambini da 3 a 10 anni, aperto anche il sabato mattina.',
                'body' => [
                    'it' => ['La ludoteca occupa i locali ristrutturati dell\'ex scuola di via dei Tigli: giochi, laboratori e un angolo lettura.', 'L\'iscrizione si fa online dall\'area personale; per le famiglie con ISEE basso i laboratori sono gratuiti.'],
                    'en' => ['The play centre uses the renovated rooms of the former school in via dei Tigli: games, workshops and a reading corner.', 'Sign up online from your personal area; workshops are free for low-income families.'],
                ],
            ],
            [
                'slug' => 'sgombero-neve-come-segnalare', 'image' => 'notizia-sgombero.png', 'featured' => false, 'days_ago' => 12,
                'title' => ['it' => 'Neve e ghiaccio: come segnalare una strada da liberare', 'en' => 'Snow and ice: how to report a street to clear'],
                'summary' => 'Il piano neve è attivo: scegli la categoria "Strade e marciapiedi" e indica il punto sulla mappa.',
                'body' => [
                    'it' => ['Durante le nevicate le squadre danno priorità a scuole, ospedali e linee del trasporto pubblico. Le altre strade vengono liberate in base alle segnalazioni.', 'Una segnalazione con la posizione precisa e una foto aiuta a intervenire prima. Puoi seguirne lo stato dalla pagina Traccia segnalazione.'],
                    'en' => ['During snowfall crews give priority to schools, hospitals and public transport routes. Other streets are cleared based on reports.', 'A report with an exact location and a photo helps crews act sooner. You can follow its status from the Track report page.'],
                ],
            ],
            [
                'slug' => 'sicurezza-attraversamenti-scuole', 'image' => 'notizia-sicurezza.png', 'featured' => true, 'days_ago' => 15,
                'title' => ['it' => 'Attraversamenti più sicuri davanti alle scuole', 'en' => 'Safer crossings outside schools'],
                'summary' => 'Nuove strisce rialzate e lampeggianti in 9 plessi, scelti con i comitati dei genitori.',
                'body' => [
                    'it' => ['Gli interventi riguardano i punti dove le segnalazioni indicavano auto troppo veloci all\'entrata e all\'uscita delle lezioni.', 'Nei prossimi mesi la Polizia locale verificherà i risultati con rilevazioni della velocità.'],
                    'en' => ['The work covers spots where reports pointed to cars driving too fast at school start and end times.', 'In the coming months the local police will check the results with speed surveys.'],
                ],
            ],
            [
                'slug' => 'sport-nel-verde-parchi', 'image' => 'notizia-sport-nel-verde.png', 'featured' => false, 'days_ago' => 20,
                'title' => ['it' => 'Sport nel verde: attrezzi nuovi in 5 parchi', 'en' => 'Sport in the green: new equipment in 5 parks'],
                'summary' => 'Percorsi fitness e aree calisthenics gratuite, con istruzioni accessibili tramite QR code.',
                'body' => [
                    'it' => ['Le nuove attrezzature sostituiscono quelle segnalate come danneggiate negli ultimi due anni.', 'Se un attrezzo si rompe, segnalalo con la categoria "Verde pubblico": la manutenzione è prevista entro 10 giorni lavorativi.'],
                    'en' => ['The new equipment replaces items reported as damaged over the last two years.', 'If something breaks, report it under "Public green": repairs are due within 10 working days.'],
                ],
            ],
            [
                'slug' => 'torneo-bici-ciclabili', 'image' => 'notizia-torneo-bici.png', 'featured' => false, 'days_ago' => 26,
                'title' => ['it' => 'Ciclabili: completato l\'anello che collega i quartieri', 'en' => 'Cycle lanes: the ring linking districts is complete'],
                'summary' => 'Otto chilometri continui e protetti, con nuove rastrelliere vicino a scuole e stazione.',
                'body' => [
                    'it' => ['L\'anello unisce le piste esistenti eliminando i tratti interrotti che i ciclisti segnalavano da tempo.', 'Le rastrelliere sono state posizionate dove le segnalazioni indicavano più bici legate ai pali.'],
                    'en' => ['The ring joins the existing lanes and removes the gaps cyclists had long been reporting.', 'Bike racks were placed where reports showed the most bikes chained to poles.'],
                ],
            ],
            [
                'slug' => 'fixcity-un-anno-di-segnalazioni', 'image' => 'image-disservizio.png', 'featured' => false, 'days_ago' => 33,
                'title' => ['it' => 'FixCity, un anno di segnalazioni: cosa abbiamo risolto', 'en' => 'FixCity, one year of reports: what we fixed'],
                'summary' => 'Oltre 500 segnalazioni gestite, tempi medi di presa in carico scesi a 3 giorni.',
                'body' => [
                    'it' => ['In dodici mesi i cittadini hanno inviato segnalazioni su strade, illuminazione, rifiuti e verde pubblico. Ogni segnalazione ha un codice e uno stato consultabile.', 'Il prossimo passo è pubblicare i dati aperti mese per mese, così chiunque potrà verificare i tempi di risposta.'],
                    'en' => ['Over twelve months citizens sent reports about streets, lighting, waste and public green. Every report has a code and a status anyone can check.', 'Next we will publish open data month by month, so anyone can check response times.'],
                ],
            ],
        ];
    }

    /**
     * @return list<array{slug: string, image: string, featured: bool, starts_in: int, lasts: int, title: array{it: string, en: string}, summary: string, body: array{it: list<string>, en: list<string>}}>
     */
    public static function events(): array
    {
        return [
            [
                'slug' => 'festa-delle-associazioni', 'image' => 'eventi-festa-associazioni.png', 'featured' => true, 'starts_in' => 6, 'lasts' => 1,
                'title' => ['it' => 'Festa delle associazioni', 'en' => 'Community associations day'],
                'summary' => 'Stand, laboratori e musica in piazza: oltre 60 associazioni del territorio si presentano.',
                'body' => ['it' => ['Una giornata per conoscere il volontariato cittadino e iscriversi alle attività.', 'Ingresso libero dalle 10 alle 19; area bimbi e punto ristoro a cura delle associazioni.'], 'en' => ['A day to meet local volunteer groups and sign up for activities.', 'Free entry from 10am to 7pm; kids area and food stalls run by the associations.']],
            ],
            [
                'slug' => 'biblioteca-nel-parco', 'image' => 'eventi-biblioteca-parco.png', 'featured' => false, 'starts_in' => 10, 'lasts' => 3,
                'title' => ['it' => 'La biblioteca nel parco', 'en' => 'The library in the park'],
                'summary' => 'Prestito libri all\'aperto e letture per bambini nel parco centrale.',
                'body' => ['it' => ['Per tre giorni la biblioteca porta i libri all\'aperto, con letture ad alta voce ogni pomeriggio.', 'In caso di pioggia gli incontri si spostano nella sala civica.'], 'en' => ['For three days the library brings books outdoors, with read-aloud sessions every afternoon.', 'If it rains, sessions move to the civic hall.']],
            ],
            [
                'slug' => 'celluloide-festival', 'image' => 'eventi-celluloide-festival.png', 'featured' => true, 'starts_in' => 18, 'lasts' => 4,
                'title' => ['it' => 'Celluloide Festival del cinema indipendente', 'en' => 'Celluloide independent film festival'],
                'summary' => 'Quattro serate di proiezioni gratuite al cinema comunale, con incontri con i registi.',
                'body' => ['it' => ['Il festival presenta corti e lungometraggi di giovani autori, con sottotitoli in italiano e inglese.', 'Le proiezioni sono gratuite; la prenotazione è consigliata.'], 'en' => ['The festival shows shorts and features by young filmmakers, subtitled in Italian and English.', 'Screenings are free; booking is recommended.']],
            ],
            [
                'slug' => 'street-art-sottopassi', 'image' => 'eventi-street-art.png', 'featured' => false, 'starts_in' => 25, 'lasts' => 7,
                'title' => ['it' => 'Street art nei sottopassi', 'en' => 'Street art in the underpasses'],
                'summary' => 'Una settimana di murales che trasformano tre sottopassi segnalati come degradati.',
                'body' => ['it' => ['Artisti locali e studenti dipingono i sottopassi indicati nelle segnalazioni per degrado e scarsa illuminazione.', 'Insieme ai murales arrivano nuove luci a LED.'], 'en' => ['Local artists and students paint the underpasses that reports flagged for decay and poor lighting.', 'New LED lights come with the murals.']],
            ],
            [
                'slug' => 'tram-via-inaugurazione', 'image' => 'eventi-tram-via.png', 'featured' => false, 'starts_in' => 40, 'lasts' => 1,
                'title' => ['it' => 'Inaugurazione della nuova linea tram', 'en' => 'New tram line opening'],
                'summary' => 'Corse gratuite per tutta la giornata e visite guidate al deposito.',
                'body' => ['it' => ['Il giorno dell\'inaugurazione le corse sono gratuite dalle 6 a mezzanotte.', 'Le fermate sono accessibili e dotate di annunci sonori.'], 'en' => ['On opening day rides are free from 6am to midnight.', 'Stops are step-free and have audio announcements.']],
            ],
            [
                'slug' => 'estate-in-citta', 'image' => 'eventi-estate.png', 'featured' => false, 'starts_in' => -60, 'lasts' => 30,
                'title' => ['it' => 'Estate in città: il bilancio', 'en' => 'Summer in the city: the results'],
                'summary' => 'Concerti, cinema all\'aperto e centri estivi: 40 serate e 12.000 presenze.',
                'body' => ['it' => ['La rassegna estiva si è chiusa con un\'ottima partecipazione e nessun problema di sicurezza segnalato.', 'Le proposte dei cittadini per la prossima edizione si raccolgono fino a fine anno.'], 'en' => ['The summer programme closed with strong attendance and no safety issues reported.', 'Ideas for next year are collected until the end of the year.']],
            ],
        ];
    }

    public static function image(string $file): string
    {
        return self::IMAGES.$file;
    }
}
