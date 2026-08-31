# Allegato 1 - Trascrizione GiveBack Platform

## Fonte
- File: `/home/davide/Scaricati/allegato 1.pdf`
- Modalita: trascrizione manuale da pagine renderizzate immagine.
- Nota: il PDF non contiene testo estraibile. Le note manoscritte leggibili sono riportate in fondo come annotazioni.

## 01 - Il prodotto

### Cos'e GiveBack

GiveBack e una piattaforma SaaS che consente ad aziende e gruppi di integrare un sistema di donazione, sconto e fidelizzazione direttamente nei propri touchpoint digitali.

Con un solo click l'utente dona 1 euro, riceve uno sconto immediato, accumula punti e partecipa a estrazioni mensili con premi reali.

| Step | Cosa succede | Chi beneficia |
| --- | --- | --- |
| 1 - Donazione | L'utente clicca il widget e dona 1 euro via carta | Piattaforma GiveBack |
| 2 - Sconto immediato | Riceve un codice sconto personalizzato del merchant | Utente donatore |
| 3 - Punti loyalty | Accumula punti nel suo profilo GiveBack | Utente donatore |
| 4 - Estrazione mensile | Ogni donazione = 1 ticket per il premio del mese | Utente donatore |
| 5 - GiveBack del merchant | Il merchant mette in palio un premio mensile | Community del merchant |

### Il meccanismo di valore

- Per l'utente: dona, si sente bene, risparmia con lo sconto, partecipa a premi mensili.
- Per il merchant: fidelizza i clienti, raccoglie dati di prima parte, differenzia il brand.
- Per GiveBack: fee sulle transazioni e canone SaaS mensile dai merchant.

## 02 - Scope dell'MVP

### Cosa viene consegnato

L'MVP e una versione funzionante e testabile con 3-5 merchant pilota. Non e il prodotto finale: e la versione che serve per validare il meccanismo con utenti reali prima di investire in funzionalita avanzate.

| Incluso nell'MVP | Escluso - Fase 2 |
| --- | --- |
| Widget JavaScript embed iframe | Stripe Connect - split automatico |
| Dashboard merchant completa | App mobile nativa |
| Sistema coupon con pool e CSV upload | Integrazione API CRM enterprise |
| Lotteria mensile con estrazione automatica | Shopify / WooCommerce plugin ufficiale |
| Onboarding merchant self-service | Multi-lingua e multi-valuta |
| Profilo utente con storico e punti | Dashboard analytics avanzata |
| Email transazionali automatiche | SSO e identity enterprise |
| Admin panel per gestione bonifici | Stripe Billing per canoni automatici |
| Deploy su Vercel + Supabase | White label completo |
| Supporto 3 mesi post-lancio, bug critici | Marketplace premi |

## 03 - Architettura tecnica

### Stack e tecnologie

| Area | Tecnologia | Motivazione |
| --- | --- | --- |
| Frontend | Next.js + Tailwind CSS | Velocita di sviluppo, deploy su Vercel |
| Widget embed | JavaScript vanilla + iframe | Zero dipendenze, isolamento CSS, universale |
| Backend | Next.js API Routes | Unico stack, semplicita operativa |
| Database | Supabase PostgreSQL | Auth inclusa, real-time, piano gratuito generoso |
| Pagamenti | Stripe Checkout | Documentazione eccellente, compliance inclusa |
| Email | Resend | Semplice, affidabile, piano gratuito MVP |
| Deploy | Vercel | Zero config, CDN globale, staging automatico |
| Monitoring | Sentry free tier | Error tracking, alert automatici |

### Schema database essenziale

Le quattro entita principali del sistema:

- `USER`: email da Stripe, fingerprint device, profilo opzionale, `created_at`.
- `DONATION`: `user_id`, `merchant_id`, `stripe_payment_intent_id` per idempotenza, amount, points_awarded.
- `COUPON`: code, `user_id`, `merchant_id`, `donation_id`, status, expires_at.
- `LOTTERY_TICKET`: `user_id`, `merchant_id`, `donation_id`, month, is_winner.

## 04 - Gestione pagamenti

### Stripe Checkout + split manuale

Decisione MVP: Stripe Checkout semplice. Tutti i pagamenti vanno sul conto Stripe della piattaforma. Lo split tra GiveBack e merchant viene gestito manualmente a fine mese tramite la dashboard admin.

Stripe Connect, split automatico, viene introdotto nella Fase 2 quando il volume di merchant lo rende necessario, indicativamente oltre 15 merchant attivi.

### Flusso pagamento

- Utente clicca widget e paga 1 euro via Stripe Checkout.
- Stripe invia webhook a GiveBack, evento `payment_intent.succeeded`.
- GiveBack verifica idempotenza tramite `stripe_payment_intent_id`.
- Genera codice coupon univoco e lo assegna all'utente.
- Accredita punti loyalty e ticket lotteria.
- Invia email di conferma con codice sconto.

### Dashboard contabile per i bonifici

La dashboard admin mostra per ogni merchant: totale donazioni del mese, importo ricevuto, quota spettante secondo la percentuale concordata, stato del bonifico da fare o fatto.

Il gestore vede esattamente quanto deve a chi ogni mese e segna i bonifici come completati. Due minuti al mese per merchant.

## 05 - Widget embed

### Integrazione sui siti merchant

Il merchant colla una sola riga di codice nel suo sito. Il widget si carica automaticamente come iframe isolato: zero impatto su CSS e JavaScript del sito host.

### Due modalita di posizionamento

Il merchant sceglie dove posizionare il widget in base alla propria strategia. Entrambe le opzioni usano lo stesso codice embed e lo stesso flusso tecnico.

| Opzione | Dove | Contesto |
| --- | --- | --- |
| A - Post-acquisto | Thank you page, subito dopo un acquisto completato | Massima motivazione. Utente soddisfatto, momento ideale. Il coupon vale per il prossimo ordine. |
| B - Standalone | Qualsiasi pagina: homepage, prodotto, footer, pagina dedicata | Piu flessibile. Adatto a chi vuole promuovere il widget anche fuori dal flusso di acquisto. |

### Nota per i merchant

In entrambe le opzioni il widget richiede che l'utente inserisca la propria email prima di procedere al pagamento. Questo garantisce il funzionamento su qualsiasi piattaforma senza dipendenze tecniche.

La scelta del posizionamento ottimale dipende dalla strategia del merchant. La thank you page garantisce generalmente tassi di conversione piu alti grazie al contesto post-acquisto.

### Aspetti tecnici

| Aspetto tecnico | Soluzione adottata |
| --- | --- |
| Isolamento CSS | iframe: il widget vive in una bolla separata dal sito host |
| CORS | Whitelist domini: ogni merchant registra il suo dominio in onboarding |
| CSP | Documentazione per merchant con istruzioni di configurazione |
| Comunicazione widget-sito | `window.postMessage` notifica donazione completata |
| Verifica installazione | GiveBack controlla automaticamente se il widget e presente sul sito |

## 06 - Onboarding merchant

### Autonomo in meno di 15 minuti

L'obiettivo primario dell'onboarding e che il merchant completi tutto senza assistenza. Ogni step richiede al massimo 2-3 minuti.

| Step | Azione | Tempo stimato |
| --- | --- | --- |
| 1 | Registrazione: email, password, dati base merchant | 2 min |
| 2 | Dati bancari: completamento su Stripe, gestito da Stripe | 3 min |
| 3 | Configurazione sconto: valore percentuale, scadenza giorni, valori default preimpostati | 1 min |
| 4 | Caricamento coupon: CSV upload con template scaricabile | 3 min |
| 5 | Premio lotteria: nome, descrizione, valore, data estrazione | 2 min |
| 6 | Codice embed: copia e incolla sul sito con guida per WordPress/Shopify/altro | 2 min |

### Alert automatici post-onboarding

- Pool coupon sotto soglia: email automatica al merchant.
- Premio lotteria non configurato 7 giorni prima della fine mese.
- Nessuna donazione nei primi 7 giorni: email di supporto proattivo.

## 07 - Riconoscimento utente

### Identificazione e anti-abuso

L'utente non deve registrarsi per donare. Il sistema lo riconosce automaticamente e costruisce il profilo progressivamente.

| Layer | Dato | Scopo |
| --- | --- | --- |
| Primario | Email da Stripe | Identificatore principale, reale perche usata per ricevuta |
| Secondario | Device fingerprint | Anti-abuso: rileva stessa persona con email diverse |
| Opzionale | Profilo volontario | Utente sceglie di registrarsi per accedere alla dashboard completa |

### Regola anti-abuso MVP

Stessa email sullo stesso merchant nelle ultime 24 ore: coupon bloccato.

Stesso device fingerprint con email diverse sullo stesso merchant: flaggato per review manuale.

Non infallibile, ma sufficiente per i volumi del pilot.

## 08 - Sistema lotteria

### Estrazione mensile automatica

Ogni donazione equivale a 1 ticket per l'estrazione del mese. Chi dona piu volte ha piu probabilita di vincere.

Il merchant configura il premio dalla dashboard. L'estrazione avviene automaticamente il primo giorno di ogni mese.

| Elemento | Funzionamento |
| --- | --- |
| Ticket | 1 donazione = 1 ticket. Accumulabili nello stesso mese. |
| Premio | Configurato dal merchant: viaggio, prodotto, voucher, esperienza. |
| Estrazione | Cron job automatico il primo del mese. Algoritmo random trasparente e loggato. |
| Notifica vincitore | Email automatica GiveBack al vincitore e al merchant. |
| Gestione premio fisico | Il merchant contatta il vincitore direttamente. GiveBack non gestisce la logistica. |
| Storico | Tutte le estrazioni passate visibili in dashboard con vincitore anonimizzato. |

### Nota legale importante

In Italia le estrazioni a premi con corrispettivo economico sono soggette a normativa specifica, D.P.R. 430/2001.

Prima del lancio pubblico e necessaria una consulenza legale per verificare la classificazione del meccanismo e gli eventuali obblighi di notifica.

Questa verifica e responsabilita del committente e non e inclusa nello scope tecnico dell'MVP.

## 09 - Merchant pilota

### Primo anno gratuito - accordo pilota

I merchant selezionati per la fase pilota non pagano il canone SaaS nel primo anno. In cambio si impegnano formalmente a partecipare attivamente al programma.

| GiveBack offre | Il merchant si impegna a |
| --- | --- |
| Accesso gratuito alla piattaforma per 12 mesi | Comunicare il widget alla propria base clienti |
| Supporto tecnico prioritario durante il pilot | Caricare i coupon con regolarita, non lasciare il pool vuoto |
| Possibilita di influenzare le funzionalita future | Configurare un premio reale ogni mese |
| Case study co-sviluppato se i risultati sono positivi | Condividere i dati aggregati per migliorare il prodotto |

### Criteri di selezione merchant pilota

Non qualsiasi merchant. Vengono selezionati merchant con una community attiva e coinvolta, motivati a promuovere il widget ai propri clienti, e con un premio mensile credibile per la loro audience.

Un merchant che installa il widget senza promuoverlo non produce dati utili per validare il prodotto.

## 10 - Modello commerciale

### Pricing e fasi di sviluppo

| Fase | Scope | Budget | Quando |
| --- | --- | --- | --- |
| MVP | Tutto quanto descritto in questo documento | Da validare | Ora, circa 5 mesi |
| Fase 2 | Stripe Connect, split automatico, Stripe Billing per canoni | Da validare | Quando si superano 15 merchant attivi |
| Fase 3 | Shopify/WooCommerce plugin, API pubblica, analytics avanzata | Da quotare | Dopo validazione del mercato |

### Canone SaaS merchant - valori indicativi da validare con il mercato

| Piano | Prezzo mensile | Per chi |
| --- | --- | --- |
| Starter | da 99 euro/mese | Merchant piccoli, fino a 200 donazioni/mese |
| Growth | da 199 euro/mese | Merchant medi, fino a 1.000 donazioni/mese |
| Pro | da 499 euro/mese | Merchant grandi, volumi illimitati |
| Enterprise | Da concordare | Grandi gruppi: booking, generali, ecc. |

### Modalita di pagamento MVP

- 40% alla firma del contratto.
- 30% a meta progetto, consegna prototipo funzionante.
- 30% al delivery finale, sistema live con merchant pilota onboardati.

## 11 - Timeline MVP

### 5 mesi dall'avvio

La timeline di 5 mesi e strutturata per un team part-time che gestisce piu progetti in parallelo. Include il feedback loop con i merchant pilota e il tempo di stabilizzazione in produzione.

Ogni fase ha deliverable chiari e verificabili prima di procedere.

| Periodo | Fase | Deliverable |
| --- | --- | --- |
| Mese 1 - Settimane 1-2 | Setup e Design | Specifiche firmate, Figma completo, ambienti Vercel + Supabase + Stripe configurati |
| Mese 1 - Settimane 3-4 | Core Backend | API donazioni, webhook Stripe con idempotenza, schema DB, logica coupon |
| Mese 2 - Settimane 5-6 | Widget + Dashboard Merchant | Widget iframe su 3 siti test, dashboard merchant completa con CSV upload |
| Mese 2 - Settimane 7-8 | Profilo Utente + Email + Admin | Frontend utente, email transazionali, admin panel con dashboard contabile |
| Mese 3 - Settimane 9-10 | QA e Test | Test end-to-end, test sui siti reali merchant, fix bug emersi |
| Mese 3 - Settimane 11-12 | Onboarding Pilota | Onboarding 3-5 merchant pilota, verifica widget live, training |
| Mese 4 | Go-live e Stabilizzazione | Lancio ufficiale, monitoraggio prime donazioni reali, fix critici rapidi |
| Mese 5 | Raccolta feedback e ottimizzazione | Interviste merchant pilota, analisi dati donazioni, backlog Fase 2 definito e prioritizzato |

## 12 - Roadmap post-MVP

### Il percorso dopo il lancio

L'MVP e il punto di partenza, non di arrivo. Questa roadmap mostra come il prodotto evolve in base ai risultati del pilot e alla crescita del numero di merchant attivi.

### Fase 2 - Automazione pagamenti, mesi 5-8

Trigger: 10-15 merchant attivi. La gestione manuale dei bonifici diventa insostenibile e si automatizza con Stripe Connect.

| Funzionalita | Dettaglio | Budget |
| --- | --- | --- |
| Stripe Connect | Onboarding Express merchant, split automatico su ogni donazione | Da quotare |
| Stripe Billing | Canone SaaS mensile automatico per merchant a pagamento | Da quotare |
| Dashboard finanziaria | Revenue per merchant, MRR, esportazione contabile | Da quotare |
| Totale Fase 2 |  | Da quotare |

### Fase 3 - Integrazioni e-commerce, mesi 9-14

Trigger: validazione del meccanismo con dati reali. Si costruiscono le integrazioni native per eliminare il processo manuale dei coupon CSV.

| Funzionalita | Dettaglio | Budget |
| --- | --- | --- |
| Shopify App | Plugin ufficiale Shopify App Store: coupon automatici via API | Da quotare |
| WooCommerce Plugin | Integrazione nativa con sistema coupon di WordPress | Da quotare |
| API pubblica GiveBack | Per merchant con sistemi custom: webhook per creare coupon | Da quotare |
| Totale Fase 3 |  | Da quotare |

### Fase 4 - Enterprise e scala, mesi 15+

Trigger: interesse concreto da grandi gruppi come hospitality, assicurazioni e travel. Il prodotto si adatta alle esigenze enterprise.

| Funzionalita | Dettaglio |
| --- | --- |
| White label completo | Dashboard brandizzata per ogni merchant enterprise, dominio custom |
| SSO e identity | Integrazione sistemi autenticazione enterprise, SAML, OAuth |
| Analytics avanzata | Cohort analysis, retention, LTV per merchant, report esportabili |
| Benefit non monetari | Upgrade servizio, accesso prioritario, contenuti esclusivi, per booking e generali |
| API enterprise | Integrazione con CRM, CDP, sistemi loyalty esistenti |
| Multi-lingua e multi-valuta | Espansione mercati europei |
| App mobile | iOS e Android per utenti donatori: storico, punti, estrazioni |

### Visione a 24 mesi

Con 50+ merchant attivi, Stripe Connect automatizzato e le prime integrazioni e-commerce live, GiveBack diventa un prodotto SaaS scalabile con MRR prevedibile.

E in quel momento che la conversazione con grandi gruppi come Booking o Generali diventa concreta. I numeri del pilot sono il documento di vendita piu potente che esiste.

## 13 - Responsabilita

### Chi fa cosa

Definizione chiara delle responsabilita per evitare ambiguita durante e dopo il progetto.

| Area | Responsabilita del team tecnico | Responsabilita del committente |
| --- | --- | --- |
| Sviluppo | Costruzione e delivery dell'MVP secondo scope | Approvazione specifiche prima dell'avvio |
| Infrastruttura | Setup e configurazione ambienti | Pagamento costi mensili: Vercel, Supabase, Stripe, Resend, stimati 50-100 euro/mese |
| Legale lotteria | Sistema tecnico dell'estrazione: random, loggato, trasparente | Consulenza legale sulla normativa italiana sui concorsi a premi |
| Merchant pilota | Supporto tecnico all'onboarding | Selezione e gestione commerciale dei merchant pilota |
| Bonifici mensili | Dashboard contabile con importi calcolati automaticamente | Esecuzione dei bonifici ai merchant |
| Evoluzione prodotto | Quotazione e sviluppo funzionalita Fase 2 e successive | Definizione priorita e budget per le evoluzioni |
| Supporto post-lancio | Bug critici inclusi per 3 mesi post-delivery | Richieste di nuove funzionalita quotate separatamente |

### Gestione delle richieste extra-scope

Qualsiasi funzionalita non inclusa in questo documento viene raccolta in una lista condivisa e quotata separatamente come progetto a parte.

Non vengono aggiunte funzionalita extra senza preventivo approvato. Questo protegge sia il budget che i tempi di delivery.

## Annotazioni manoscritte leggibili

- Vicino a `SaaS`: chiarimento "software as a service".
- Vicino a "dona 1 euro": nota "aggiungi 1 euro", quindi il linguaggio piu chiaro potrebbe essere add-on volontario sul pagamento o contributo aggiuntivo, non donazione isolata.
- Vicino a `Utente donatore` e ai punti loyalty: "a cosa servono i punti? per ulteriore sconto".
- Vicino a "Per l'utente: dona": nota che una parte andra in associazioni benefiche. Questo cambia il messaggio di valore: non solo premio/sconto, ma anche destinazione solidale trasparente.
- Vicino a "Dashboard merchant completa": domanda "cosa vuol dire?". Serve esplicitare quali funzioni contiene.
- Vicino a "Deploy su Vercel + Supabase": punto interrogativo. Da non assumere come stack definitivo.
- Nota tecnica successiva: lo stack previsto per il progetto non e quello dell'allegato, ma Laravel + Filament, dove il team ha molta esperienza.
- Dashboard bonifici: annotazione che non vale nella configurazione iniziale. Va considerata come area futura o placeholder, non come requisito iniziale.
- Identificazione e anti-abuso: annotazione che va in contrasto con l'opzione standalone del pulsante. Serve validare come riconoscere l'utente se il widget non e nel flusso post-acquisto.
- Sistema lotteria: domanda "ha senso o non ha senso?", probabilmente per i vincoli legali. Nota aggiornata: da confronto con consulente giochi a premi in BNI, il meccanismo puo essere fatto, ma richiede fideiussione della somma premio al Ministero e gestione formale degli adempimenti.
- Nota legale e sottotesto del premio: evidenziati come "importante".
