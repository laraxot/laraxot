# GiveBack Platform - spiegazione semplice del mockup

## In poche parole

GiveBack e un servizio online che un negozio puo mettere sul proprio sito.

Nel mockup aggiornato il messaggio non e piu "donazione" ma "fortuna":

1. Il cliente vede il pulsante `Oggi sono fortunato`.
2. Versa solo `1 euro`.
3. Riceve subito una promo nel checkout.
4. Tenta la fortuna per un premio messo in palio dalla piattaforma.
5. Il negozio controlla widget, promo e andamento campagna.

La frase sintetica del mockup e:

> Oggi sono fortunato: con 1 euro tenti la fortuna e ricevi subito una promo.

## Cosa mostra il mockup

Il mockup non e un PDF da leggere. E un esempio del sito/portale da costruire.

Dentro si vedono:

- la schermata cliente con il bottone `Oggi sono fortunato`;
- il modulo con email e importo fisso da 1 euro;
- il widget in pre-acquisto con split payment;
- la schermata di preparazione merchant;
- la dashboard merchant con promo, ticket e alert;
- la pagina con split payment e riconciliazione;
- la pagina premio piattaforma con note legali;
- la roadmap commerciale e di perimetro;
- la documentazione collegata.

I pulsanti e i form sono solo dimostrativi: servono a far capire il percorso, non salvano ancora dati veri.

## Cosa significa per il cliente finale

Il cliente deve capire questo messaggio:

> Oggi sono fortunato. Aggiungo 1 euro, ricevo subito una promo e tento la fortuna.

Da evitare nel MVP:

- buona causa;
- beneficenza;
- punti;
- importi multipli.

Da far capire bene invece:

- importo fisso di 1 euro;
- promo immediata;
- premio della piattaforma;
- pagamento unico con split payment dietro le quinte.

## Cosa significa per il negozio

Il negozio non deve vedere cose tecniche.

Deve poter fare queste azioni:

- inserire nome negozio e sito web;
- scegliere dove mettere il widget;
- decidere la promo da dare al cliente;
- caricare i codici promo da usare;
- copiare il widget da mettere sul sito;
- vedere se il widget e attivo;
- vedere quanti clienti hanno tentato la fortuna;
- ricevere avvisi se manca qualcosa.

Il premio non e a carico del merchant come regola base. Nel mockup il premio e gestito dalla piattaforma.

## Premio piattaforma

Il premio mensile si puo fare, ma non va preso alla leggera.

Da confronto con consulente giochi a premi BNI, il meccanismo e possibile, pero bisogna gestire la parte legale. In particolare serve la fideiussione della somma premio al Ministero e servono gli adempimenti corretti.

Nel mockup quindi la pagina premio mostra chiaramente:

- premio scelto dalla piattaforma;
- valore del premio;
- data di estrazione;
- stato della fideiussione;
- documenti da preparare;
- avviso "non andare online finche la parte legale non e pronta".

## Pagamenti e divisione degli incassi

Nota del 19/05/2026: Giuliano Florian per Giulia Tonello.

La strategia indicata e usare lo **split payment**, cioe la divisione automatica dei pagamenti.

Detto semplice: il cliente paga una volta sola, nel checkout. Poi il sistema divide l incasso tra le parti giuste, per esempio merchant e piattaforma.

Questa scelta e importante perche:

- evita calcoli manuali a fine mese;
- aiuta a certificare meglio le transazioni fiscali;
- rende piu ordinata la gestione quando ci sono piu soggetti che devono ricevere una parte;
- prepara il progetto a funzionare come sistema multi-merchant.

Nel mockup la parte pagamenti resta dimostrativa, ma deve far vedere l idea: un pagamento solo in ingresso, divisione automatica dietro le quinte.

## Widget sul sito

Nel mockup ci sono due possibilita:

- `pre-acquisto con split payment`, consigliato per partire;
- pagina dedicata standalone, solo come opzione secondaria.

La posizione pre-acquisto e la piu chiara per spiegare il valore.

La pagina standalone resta possibile, ma se non c e gia un ordine bisogna chiedere sempre email prima del pagamento.

## Cosa non mettiamo subito

Per partire non serve fare tutto.

Nella prima versione non mettiamo come obbligatori:

- merchant donation;
- app mobile;
- collegamento automatico con Shopify o WooCommerce;
- gestione automatica dei bonifici;
- area enterprise;
- multi-lingua;
- sistema avanzato di statistiche.

Prima si valida se negozi e clienti usano davvero il sistema.

## Tecnologia scelta

Nel documento originale si parlava di altri strumenti. Per questo progetto invece si usa Laravel + Filament, perche e lo stack gia conosciuto e gia usato nel gestionale.

Detto in modo semplice:

- Laravel fa funzionare il sistema;
- Filament crea le schermate di gestione;
- Stripe gestira i pagamenti quando si passera alla versione reale;
- il widget sara il pezzetto da mettere sul sito del negozio.

## Consulenti indicati

Per la parte premi:

- Consulente: Operazioni a Premio.
- Dr. Stefano Notturno, Olojin Srl, San Fior (TV).
- Tel. 0438 409186.
- Cell. 348 8555253.
- Email: `s.notturno@olojin.it`.

Per privacy, GDPR e AI Act:

- Consulenza: GDPR Privacy & L. 132 AI Act.
- Avv. Erika Fischer, TV.
- Tel. 33784843.
- Email: `avvocatofischer@gmail.com`.

Per commercialista e bando Veneto non ci sono ancora dati nel repository: vanno raccolti fuori repo.

## Pagine del mockup

- `Dashboard`: panoramica generale.
- `Meccanismo fortuna e valore`: esempio del percorso cliente.
- `Widget embed e installazione`: esempio del box da mettere sul sito.
- `Onboarding merchant`: form di registrazione negozio.
- `Merchant dashboard`: area riservata del negozio.
- `Admin split payment e controlli`: esempio operativo dei pagamenti.
- `Premio piattaforma, compliance e note legali`: premio mensile e controlli legali.
- `Pricing, timeline e roadmap`: cosa si fa ora e cosa si fara dopo.
- `Documentazione GiveBack`: questa documentazione dentro al mockup.

## Nota importante

Il mockup serve per vedere e discutere i passaggi. Non e ancora il prodotto finito.

Le schermate devono far capire a colpo d occhio:

- cosa fa il cliente;
- cosa fa il negozio;
- cosa resta da validare;
- cosa non va promesso subito.
