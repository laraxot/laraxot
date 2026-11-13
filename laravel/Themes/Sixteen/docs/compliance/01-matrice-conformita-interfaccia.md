---
title: "Sixteen: matrice di conformita' dell'interfaccia (accessibilita', lingue, design, cookie e privacy sul front-end)"
type: compliance-matrix
status: draft-for-dpo-legal-review
story: dcp-112
owner: theme:Sixteen
created: 2026-10-13
updated: 2026-10-13
tags: [compliance, accessibilita, wcag, multilingua, mobile, design-comuni, cookie, bmad]
related:
  - ../../../../Modules/Fixcity/docs/compliance/00-scopo-del-progetto.md
  - ../../../../Modules/Fixcity/docs/compliance/01-matrice-conformita-normativa.md
  - ../../../../Modules/Fixcity/docs/compliance/03-fonti-cad-accessibilita-verificate.md
  - ../bmad/stories/dcp-112-scopo-e-conformita-normativa.story.md
qmd: "matrice conformita interfaccia accessibilita wcag 2.1 aa en 301 549 dichiarazione accessibilita lang de es footer link 404 google fonts cookie mobile axe prove"
---

# Sixteen: matrice di conformita' dell'interfaccia

## Avvertenza e metodo

Non e' un parere legale e non sostituisce una verifica di accessibilita' firmata. Complementa la [matrice normativa](../../../../Modules/Fixcity/docs/compliance/01-matrice-conformita-normativa.md) (dati, identita', sicurezza) con cio' che si vede e si usa nel browser. Legenda, stati e rischio come nella matrice normativa; in piu' «informativo» indica un criterio fuori dalla baseline normativa, riportato per completezza. Le norme sono lette su fonte primaria nel [registro 03](../../../../Modules/Fixcity/docs/compliance/03-fonti-cad-accessibilita-verificate.md).

Misure del 2026-10-13 su `http://192.168.1.40:8000` (ambiente locale, HTTP, debug bar attiva):
- Playwright (un browser alla volta) su 10 percorsi: `/it`, `/en`, `/de`, `/es`, `/it/tickets`, `/it/tickets/track`, `/it/auth/login`, `/it/auth/register`, `/it/domande-frequenti`, `/it/mappa-sito`; viewport 1440 px per `axe-core` (tag wcag2a, wcag2aa, wcag21a, wcag21aa; sono escluse dal conteggio le violazioni dentro `.phpdebugbar`, strumento di sviluppo), 375 e 768 px per scorrimento orizzontale e dimensione dei bersagli.
- `curl` e `grep` sull'HTML. Comandi nell'appendice A.
- Non misurati: pagine che richiedono il login (wizard, area personale, back office), tastiera e lettori di schermo.

## Conteggio

| Stato | Righe |
|---|---:|
| conforme | 3 |
| parziale | 9 |
| assente | 6 |
| non applicabile | 1 |
| NON VERIFICATO | 7 |
| informativo | 1 |
| Totale | 27 |

Rischio delle righe: alto 5, medio 12, basso 10. Matrice normativa (dati, identita', sicurezza): [39 righe](../../../../Modules/Fixcity/docs/compliance/01-matrice-conformita-normativa.md).

## 1. Accessibilita'

| ID | Riferimento | Cosa impone | Come lo rispettiamo oggi (prova) | Stato | Rischio | Story |
|---|---|---|---|---|---|---|
| I-01 | L. 4/2004 art. 3-quater c. 1-4 (D.Lgs. 106/2018); Dir. (UE) 2016/2102 art. 7; Dec. (UE) 2018/1523 (modello); Linee guida AgID (det. 437/2019, 396/2020, 354/2022) cap. 4.1 | Dichiarazione di accessibilita' pubblicata sul sito, con descrizione e link del meccanismo di feedback e link alla procedura dell'art. 3-quinquies; compilata sulla piattaforma AgID; link nel footer | `/accessibilita`, `/it/accessibilita`: 404. Il componente `components/municipal/accessibility-statement.blade.php` non e' usato e ha valori d'esempio (`accessibilita@comune.example.it`, URL AgID `xxxxxxxx-...`). Nessun meccanismo di feedback sull'accessibilita': l'unico elemento di gradimento e' il widget «Quanto sono chiare le informazioni su questa pagina?» (presente su `/it/tickets` e nelle repliche `tests.*`, assente su `/it` e sulle FAQ), che non raccoglie difetti di accessibilita' ne' rimanda alla procedura dell'art. 3-quinquies | assente | alto | DCP-132 |
| I-02 | DL 18.10.2012 n. 179 art. 9 c. 7; Linee guida AgID cap. 4.1-4.2 | Obiettivi annuali di accessibilita' pubblicati entro il 31 marzo; dichiarazione riesaminata e validata entro il 23 settembre di ogni anno | Nessuna pagina o testo sugli obiettivi (grep «obiettivi di accessibilit» nelle viste, lang e config servite: vuoto). E' un processo dell'ente, il software puo' solo ospitarlo | assente | medio | DCP-132 |
| I-03 | Dir. (UE) 2016/2102 art. 4 e 6; Dec. (UE) 2018/2048 e 2021/1339 (EN 301 549 V3.2.1, dal 12.2.2022); Linee guida AgID cap. 2.2 (livello AA di WCAG 2.1 «equivalente» ai punti 9.1-9.4 della norma) | Siti percepibili, utilizzabili, comprensibili e solidi; presunzione di conformita' con la norma armonizzata | `axe-core` senza violazioni (wcag2a, wcag2aa, wcag21a, wcag21aa) su `/it`, `/en`, `/it/auth/login`, `/it/auth/register`, `/it/tickets/track`, `/it/domande-frequenti`, `/it/mappa-sito` e `/it/tickets`. Per `/it/tickets` la prima misura (17:08) trovava 2 violazioni critical (`aria-allowed-attr` su `button[data-tab-id="map"]` e `.border-transparent`; `aria-required-children` su `#tabDisservizio`); dopo la riscrittura della pagina da parte di un'altra sessione la misura delle 18:12 e' 0. Restano non misurate le pagine con login. Il testo della norma EN 301 549 non e' stato scaricato (registro 03, PARZIALE) | parziale | medio | DCP-143 |
| I-04 | Idem, criteri non automatizzabili (tastiera, focus, lettori di schermo, errori nei moduli, ordine di lettura) | Conformita' a tutti i criteri WCAG 2.1 AA | Non misurato. L'audit statico DCP-49 ha elencato ACC-01..ACC-05 per il wizard (non corretti). Il wizard e l'area personale richiedono il login: non osservati | NON VERIFICATO: serve un audit manuale con tastiera e lettore di schermo | alto | DCP-143 |
| I-05 | L. 4/2004 art. 4 c. 2 (modificato da D.Lgs. 82/2022 art. 25) | Contratti per realizzare o modificare siti e app senza requisiti di accessibilita': nulli | Il contratto tra il Comune e il fornitore e' fuori dal repository | NON VERIFICATO: serve il contratto e la clausola di accessibilita' | medio | nessuna (verifica dell'ente) |
| I-06 | WCAG 2.1 SC 2.4.1, 1.1.1 (EN 301 549 punto 9) | Salto ai contenuti; alternative testuali; punti di riferimento | Sulla home: primo elemento focalizzabile «Vai ai contenuti» (`#main-container`, esiste) e «Vai al footer» (`#footer`, esiste); `header`, `main`, 2 `nav`, `footer`; 0 immagini `<img>` senza `alt` (script di verifica) | conforme (controlli specifici) | basso | nessuna |
| I-07 | Dir. (UE) 2016/2102 art. 1 par. 4 lett. d); L. 4/2004 (nessuna esclusione esplicita per le mappe, registro 03) | Le mappe online possono essere escluse dalla direttiva se le informazioni essenziali sono fornite in modo accessibile | La mappa Leaflet e' accompagnata da un elenco (`/it/tickets` ha schede mappa/elenco; le due violazioni ARIA delle 17:08 stavano proprio nei comandi di questa vista e non risultano piu'). La L. 4/2004 non riporta l'esclusione: NON VERIFICATO come trattarla | NON VERIFICATO: serve la valutazione dell'ente sull'esclusione delle mappe; l'alternativa testuale va comunque provata | medio | DCP-143 |
| I-08 | WCAG 2.2 SC 2.5.8 (24 px): non fa parte della baseline 2.1 del progetto, informativo | Dimensione minima dei bersagli | Elementi interattivi sotto 24 px (esclusa la debug bar): a 375 px su `/it`: link «Nome della Regione» 120x21, «Media policy» 72x20, «Mappa del sito» 85x20, attribuzione «Leaflet» 51x14; su `/it/auth/login` anche «Mostra password» 124x20 e una casella 16x16; i link «Vai ai contenuti» e «Vai al footer» sono 1x1 px finche' non hanno il focus (usuale per i salti) | informativo (criterio fuori baseline) | basso | DCP-143 |

## 2. Lingue

| ID | Riferimento | Cosa impone | Come lo rispettiamo oggi (prova) | Stato | Rischio | Story |
|---|---|---|---|---|---|---|
| I-09 | WCAG 2.1 SC 3.1.1 | La lingua della pagina e' dichiarata | `<html lang>` uguale al prefisso: `it`, `en`, `de`, `es` (curl `/it`, `/en`, `/de`, `/es`) | conforme | basso | nessuna |
| I-10 | WCAG 2.1 SC 3.1.2 (AA) | Le parti in altra lingua sono marcate | Sotto `lang="de"` e `lang="es"` il corpo della home e' interamente in italiano: 27 righe su 27 del `<main>` identiche all'italiano (confronto testuale di `/de` e `/es` con `/it`); nessun `lang="it"` sul contenuto. Header e footer sono tradotti (6-7 righe su 57 uguali all'italiano, per lo piu' nomi di social) | assente | medio | DCP-123 |
| I-11 | Scopo dichiarato: «IT/EN minimo» (`bashscripts/ai/wiki/concepts/fixcity-scopo-e-target.md`); CAD art. 3 c. 1 | Percorso del cittadino comprensibile nelle lingue servite | Il percorso critico e' in italiano anche in inglese: `/en/tickets/create` risponde 302 a `/en/auth/login` con titolo e `<h1>` «Accedi a FixCity», e `/en/auth/register` «Crea il tuo account» (curl); `/de/auth/login` e `/es/auth/register` idem. `/en/tickets`, `/en/tickets/track` e le versioni de ed es di elenco e tracciamento sono tradotte. Il salto «Vai ai contenuti» e' in italiano in tutte le lingue (Playwright, primo link). DCP-120 ha misurato la copertura per pagina | parziale | medio | DCP-120, DCP-123 |
| I-12 | L. 15.12.1999 n. 482; CAD art. 3 c. 1 («fermi restando i diritti delle minoranze linguistiche riconosciute») | Uso della lingua della minoranza storica nei Comuni delimitati | Il Comune non e' definito: non si sa se sia delimitato (art. 3 della legge). Nessuna lingua minoritaria prevista | NON VERIFICATO: serve il Comune e la sua delimitazione | basso | nessuna |
| I-13 | Reg. (UE) 2018/1724 art. 6, 13 e Allegato II | Procedure dell'Allegato II offerte in linea e istruzioni in una lingua UE ampiamente compresa | Il testo dell'Allegato II (eventi della vita: nascita, residenza, studio, lavoro, trasferimento, pensione, impresa) non contiene procedure di segnalazione di disservizi (ricognizione del registro 03) | non applicabile (dal testo dell'allegato; la qualificazione resta del legale) | basso | nessuna |

## 3. Design e contenuti istituzionali

| ID | Riferimento | Cosa impone | Come lo rispettiamo oggi (prova) | Stato | Rischio | Story |
|---|---|---|---|---|---|---|
| I-14 | CAD art. 53 c. 1 e 1-ter, art. 71; AgID, Linee guida di design per i siti internet e i servizi digitali della PA (luglio 2022) | I siti istituzionali rispettano principi di accessibilita', usabilita', completezza, chiarezza; modalita' nelle Linee guida di design | Parity con il kit Design Comuni misurata dall'epic DCP-00 (pixel 99,4-99,9% su decine di pagine, HTML rigorosa >= 90 su 79 pagine su 81). Cio' prova la copia del riferimento, non la conformita' alle Linee guida di design: queste usano «DEVE» per i requisiti obbligatori, non fissano un termine di adeguamento (0 occorrenze di «entro il» e «a decorrere») e l'atto di adozione (numero, data, GU) non e' stato trovato; il cap. 4.8 chiede di inserire nei contratti l'obbligo di rispettarle (registro 03, sez. 15) | parziale | medio | DCP-133 |
| I-15 | GDPR art. 13 par. 1 lett. a); L. 150/2000 art. 8 (registro 03); CAD art. 53-54 | Il sito dice chi e' l'ente e come contattarlo | Dati segnaposto nell'HTML: «Nome del Comune», «Nome della Regione», «Via Roma 123 - 00100 Comune», «Numero verde: 800 016 123», «SMS e WhatsApp: +39 320 1234567»; social a `https://twitter.com` e simili | assente | alto | DCP-133 |
| I-16 | CAD art. 53, art. 54 (rinvio al D.Lgs. 33/2013); GDPR art. 12 par. 1 | Link a informativa, note legali, trasparenza, accessibilita' funzionanti | Footer (alle 17:01 senza prefisso lingua, dalle 18:12 con `/it/` dopo DCP-123): `/it/privacy`, `/it/note-legali`, `/it/accessibilita`, `/it/amministrazione-trasparente`, `/it/media-policy`, `/it/rss.xml`, `/it/mappa-del-sito`, `/it/assistenza`, `/it/prenota-appuntamento`: 404; `/it/mappa-sito` esiste (200) ma il footer ha lo slug sbagliato; 28 voci del footer e 18 dell'header con `href="#"` | assente | alto | DCP-131, DCP-123 |
| I-17 | WCAG 2.1 SC 2.4.2 (AA) | Titoli di pagina che descrivono scopo | `<title>` della home: `/it` «FixCity - La piattaforma civic tech per la manutenzione urbana», `/en` equivalente; `/de` e `/es` solo «FixCity». Pagine reali con titolo proprio (`Elenco segnalazioni`, `Traccia la segnalazione`) | parziale | basso | DCP-123 |
| I-18 | Qualita' del servizio (CAD art. 7 c. 3: soddisfazione dell'utente) | Gli utenti possono esprimere soddisfazione e i dati sono pubblicati | La valutazione del ticket risolto esiste (`GetCitizenRatingAggregateAction`, PRD FR-005) e il widget di gradimento della pagina compare su `/it/tickets`; non e' stata verificata la pubblicazione di statistiche aggregate | parziale | basso | nessuna |
| I-19 | Buona pratica (SEO e accessibilita'), DCP-106 | Una URL senza contenuto non risponde 200 | `/it/tests/privacy` risponde 200 con `<main>` vuoto (0 caratteri di testo): pagina dinamica senza JSON. Causa e rimedio in DCP-106 | parziale | basso | DCP-106 |

## 4. Cookie, privacy e terze parti sul front-end

| ID | Riferimento | Cosa impone | Come lo rispettiamo oggi (prova) | Stato | Rischio | Story |
|---|---|---|---|---|---|---|
| I-20 | D.Lgs. 196/2003 art. 122; Garante provv. 231/2021 par. 7.1 | Con soli cookie tecnici: informazione, nessun banner | Cookie: `XSRF-TOKEN` e `fixcity_session` (7200 s) e, solo locale, `PHPDEBUGBAR_STACK_DATA`. Nessun banner. Nessuna informazione sui cookie in pagina. Pacchetto banner registrato ma inattivo | parziale | medio | DCP-136 |
| I-21 | GDPR art. 12 par. 1, art. 13 | Informativa raggiungibile dai punti di raccolta | Wizard passo 1, registrazione e footer: il link all'informativa porta a 404 o manca (N-04 della matrice normativa) | assente | alto | DCP-130, DCP-131, DCP-135 |
| I-22 | GDPR art. 5, art. 13 par. 1 lett. e)-f) | Risorse di terze parti dichiarate | `fonts.googleapis.com` e `fonts.gstatic.com` su tutte le 10 pagine; `*.tile.openstreetmap.org` su `/it`, `/en`, `/de`, `/es`, `/it/tickets` (Playwright, richieste verso host diversi dall'origine). Le copie locali dei font esistono ma il CSS compilato importa quelle di Google | parziale | medio | DCP-137 |
| I-23 | Prassi di produzione | Nessuno strumento di sviluppo visibile in produzione | La debug bar compare in locale (`DEBUGBAR_ENABLED=true`) e produce 1 violazione di contrasto nei risultati axe, che non e' del prodotto; `.env.production.example` la disattiva | NON VERIFICATO in produzione: serve la lista di controllo di DCP-139 | basso | DCP-139 |

## 5. Mobile e tablet

| ID | Riferimento | Cosa impone | Come lo rispettiamo oggi (prova) | Stato | Rischio | Story |
|---|---|---|---|---|---|---|
| I-24 | WCAG 2.1 SC 1.4.10 (riflusso), 1.3.4; scopo «mobile first» | Pagine usabili a 320-375 px senza scorrimento orizzontale; `viewport` dichiarato | `<meta name="viewport">` presente su tutte le 10 pagine; nessuno scorrimento orizzontale a 375 px (`/it`, `/it/tickets`, `/it/auth/login`) e a 768 px (`/it`): `scrollWidth` uguale a `clientWidth`. Parity visiva a 375 px misurata dall'epic DCP-00 (minimo 99,4% sulle pagine reali) | conforme (pagine misurate) | basso | nessuna |
| I-25 | Scopo: segnalare dal luogo del disservizio | Geolocalizzazione del dispositivo | La geolocalizzazione richiede un contesto sicuro (HTTPS): in locale su HTTP il browser la blocca; il proxy TLS non ha risposto. In piu' `SecurityHeaders::geolocation=()` (se registrato come e') la disattiverebbe | NON VERIFICATO: serve una prova su ambiente con TLS e intestazioni finali | medio | DCP-139 |
| I-26 | Scopo: flusso in due minuti | Il wizard e' usabile da telefono | Non osservato da ospite (redirect al login). Misure visive del wizard da autenticato (DCP-98): 99,4-99,85% a 375-1920 px; usabilita' (tocco, tastiera mobile, messaggi) non misurata | NON VERIFICATO: serve una prova su dispositivo reale con l'account di prova | medio | DCP-143 |

## 6. Licenze di cio' che si riusa

| ID | Riferimento | Cosa impone | Come lo rispettiamo oggi (prova) | Stato | Rischio | Story |
|---|---|---|---|---|---|---|
| I-27 | BSD-3-Clause di Bootstrap Italia e di Design Comuni pagine statiche (GitHub `italia/bootstrap-italia` e `italia/design-comuni-pagine-statiche`: LICENSE e AUTHORS, registro 03 sez. 15) | Si conservano avviso di copyright, elenco delle condizioni e disclaimer; per i binari anche nella documentazione o altri materiali; i nomi dei titolari non servono a promuovere i derivati | `resources/css/design-comuni/components.css` (righe 1-8) cita «licenza BSD-3-Clause, (c) Presidenza del Consiglio dei Ministri» e dichiara che il CSS e' generato da `bootstrap-italia.min.css` 2.9.0. L'AUTHORS elenca tre titolari (Team per la Trasformazione Digitale e Dipartimento per la trasformazione digitale della PCM; AgID) e il LICENSE «the respective contributors». Mancano il testo completo con il disclaimer, un NOTICE o una pagina di crediti. Lo sprite `public/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg` e i font locali non hanno avviso; la licenza di font e icone del pacchetto non e' stata verificata. Il README di Bootstrap Italia parla di «CC0» in contrasto con il LICENSE: da chiarire con AgID | parziale | medio | DCP-145 |

## Appendice A: comandi di verifica

```bash
B=http://192.168.1.40:8000
# footer: percorsi e prefisso lingua
curl -s -o /dev/null -w '%{http_code}\n' $B/privacy $B/it/privacy $B/accessibilita $B/it/accessibilita $B/note-legali
# lingua dichiarata e corpo della pagina
for l in it en de es; do curl -s $B/$l | grep -o '<html[^>]*>'; done
# titoli del percorso critico in inglese
curl -s $B/en/auth/login | grep -o '<title>[^<]*</title>'
# axe, cookie, richieste di terze parti, scorrimento orizzontale: sonda Playwright
# scratchpad della sessione: dcp112-probe.mjs (un browser alla volta, axe-core del tema)
node dcp112-probe.mjs '[{"path":"/it","width":1440,"axe":true}]' out.json
```

La sonda e' descritta in `Modules/Fixcity/docs/compliance/01-matrice-conformita-normativa.md` (appendice A) e nella story DCP-112. I file di lavoro stanno nello scratchpad di sessione (non nel repository).

## Aggiornamento 2026-10-13 (sera): DCP-131 e DCP-132

| Riga | Prima | Ora |
|---|---|---|
| I-01 | assente | `/{it,en,de,es}/accessibilita` risponde 200 (blocco `accessibility-statement`, dati dell'ente nel JSON); modulo di feedback che invia email alla casella dichiarata; link alla procedura dell'art. 3-quinquies; link dal footer e dalle pagine di login e registrazione. Stato di conformita', date e casella restano da fornire (guardia `AccessibilityDeclarationPublicationGateTest`, rossa per costruzione) |
| I-16 | assente | footer: tutti i link interni rispondono 200 (o 302 al login per `/tickets/create`) in 4 lingue e nessun `href="#"` sul sito reale (test `FooterLinksResolveTest`); le voci senza pagina sono testo non attivo. Header: NON risolto (9 link 404 per lingua, 12 `href="#"`), fuori perimetro di DCP-131 |

Story: `../bmad/stories/dcp-131-footer-legale-pagine-mancanti-e-link.story.md` e `...dcp-132-dichiarazione-accessibilita-e-meccanismo-feedback.story.md`.

