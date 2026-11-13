---
title: "Matrice di conformita' alle Linee guida di design della PA, capitolo 4 «Requisiti»"
type: compliance-matrix
status: active
created: 2026-10-14
updated: 2026-10-14
owner: theme:Sixteen
story: dcp-163
tags: [compliance, linee-guida-design, agid, designers-italia, matrice]
qmd: "matrice linee guida design pa capitolo 4 requisiti 4.1 4.2 4.3 4.4 4.5 4.6 4.7 4.8 lg-design-servizi-web conforme parziale assente docs italia designers italia superata"
related: [01-matrice-conformita-interfaccia.md, ../bmad/stories/dcp-163-matrice-linee-guida-design-in-vigore.story.md, ../../../../../bmad-output/stories/8.1.design-guideline-baseline.story.md]
---

# Matrice di conformita' alle Linee guida di design, capitolo 4

## Quale testo e' in vigore
- **In vigore**: «Linee guida di design per i siti internet e i servizi digitali della PA», capitolo 4 «Requisiti» (4.1-4.8), `https://docs.italia.it/italia/design/lg-design-servizi-web/it/versione-corrente/requisiti.html`, letto il 2026-10-14. Il frontespizio le colloca nell'art. 53 c. 1-ter del CAD; l'introduzione le dice emesse ai sensi dell'art. 71 CAD e della det. AgID 160/2018, con riferimento sostanziale all'art. 53, e destinate alle PA dell'art. 2 c. 2 lett. a) CAD. Le pagine lette **non riportano** data di adozione, scadenze di adeguamento ne' sanzioni (la matrice di interfaccia I-14 cita «luglio 2022»: non riverificato).
- **Superata**: `.../design-linee-guida-docs/it/stabile/doc/introduzione-linee-guida-design.html` si apre con «Queste linee guida sono superate» e indica come riferimento in vigore il testo sopra e il Manuale operativo di design (v. 2025.1). Chi cita «le linee guida» deve dire quale versione.
- Il **Manuale operativo di design** (`.../manuale-operativo-design-docs/`) e' uno strumento di supporto: la pagina letta non dice quali parti siano vincolanti.

## Come leggere la tabella
Verbi come nel testo: DEVE/DEVONO (obbligo), DOVREBBE (raccomandazione). Gli stati sono quelli delle altre matrici: conforme, parziale, assente, NON VERIFICATO, non applicabile. «Conforme» solo con una prova riproducibile. Misure del 2026-10-14 su `http://127.0.0.1:8081`, 12 pagine pubbliche per 4 lingue (48 combinazioni), script di scansione descritto nella story DCP-163. I requisiti sono parafrasati; il testo normativo e' alla fonte.

## Sintesi (42 requisiti)
- parziale: 13
- assente: 12
- NON VERIFICATO: 11
- conforme: 4
- non applicabile: 2

| Id | Verbo | Requisito | Stato | Prova | Story |
|---|---|---|---|---|---|
| 4.1.1 | DEVE | Conformita' alla L. 4/2004 e alle Linee guida sull'accessibilita' (AgID, det. 396/2020) | parziale | dichiarazione e modulo di feedback (DCP-132); axe sulle pagine pubbliche; audit manuale (tastiera, lettore di schermo) non eseguito | DCP-143, DCP-158, 8.5 |
| 4.2.1 | DEVE | Protezione dei dati fin dalla progettazione e per impostazione predefinita (GDPR art. 25) | parziale | la pagina pubblica di tracciamento non mostra email; log senza rotazione (matrice N-08); nessun controllo automatico di minimizzazione | DCP-141, 8.7 |
| 4.2.2 | DEVE | Almeno il livello base delle Misure minime di sicurezza ICT (AgID) | NON VERIFICATO | nessun modulo di implementazione nel repository (N-20) | DCP-159 |
| 4.2.3 | DEVONO | Misure tecniche e organizzative adeguate al rischio (GDPR art. 32, art. 5 par. 2) | parziale | intestazioni di sicurezza con CSP in sola segnalazione (DCP-139); freno al tracciamento per codice (DCP-149); limite sul login; limite generale (DCP-138) non fatto | DCP-138, DCP-139 |
| 4.2.4 | DEVE | Valutazione d'impatto prima del trattamento a rischio elevato; consultazione del Garante se serve (GDPR artt. 35-36) | NON VERIFICATO | nessuna DPIA trovata; se serva dipende dall'elenco del Garante n. 467/2018 (N-12) | DCP-142 |
| 4.2.5 | DEVE | Informativa pubblicata sul sito; consenso se e' la base giuridica, anche per i cookie | parziale | `/{it,en,de,es}/privacy` risponde 200 con avviso dei campi mancanti finche' l'ente non fornisce il testo; cookie: banner e cookie policy (DCP-153) | DCP-130, DCP-153 |
| 4.2.6 | DEVONO | Informazioni concise, trasparenti, intelligibili, facilmente accessibili, in linguaggio semplice (GDPR art. 12) | NON VERIFICATO | il testo dell'informativa lo scrive l'ente; leggibilita' non misurata | DCP-130, 8.4 |
| 4.2.7 | DOVREBBE | Su ogni pagina un link ben visibile all'informativa, con dicitura d'uso comune (es. «Privacy») | conforme | footer: link a `/privacy` in 48 combinazioni pagina-lingua su 48 (misura 2026-10-14); la dicitura e' «Informativa privacy» | - |
| 4.2.8 | DEVE | Link all'informativa al momento della raccolta dei dati online, o informazioni nella stessa pagina | parziale | registrazione, accesso e tracciamento: link presente; passo 1 del wizard richiede il login e non e' stato osservato da ospite | DCP-130 |
| 4.2.9 | DEVE | Se il servizio e' rivolto a persone con disabilita', l'informativa deve essere fruibile | NON VERIFICATO | non misurato axe sulla sola pagina `/privacy` con testo completo | DCP-143, 8.5 |
| 4.2.10 | DEVE | Se rivolto a minori, informativa comprensibile a un minore | assente | nessuna regola di eta' minima per registrarsi e nessuna menzione dei minori nei file lingua di registrazione; il profilo ha `birth_date` ma non e' obbligatorio nel form | DCP-165 |
| 4.2.11 | DEVONO | Per le app, informazioni specifiche dell'app, negli store e a due «tocchi» dall'avvio | non applicabile | nessuna app negli store trovata (il modulo Mobile non e' un'app: rotte vuote); da riconfermare se se ne pubblica una | - |
| 4.2.12 | DEVONO | Contatti del RPD (DPO) in una sezione riconoscibile e raggiungibile dalla homepage (Garante n. 186/2021) | assente | menzione del DPO su 4 pagine su 48 (le pagine privacy, dove e' un campo da compilare); nessun link dalla home | DCP-164 |
| 4.2.13 | DEVE | Valutare la necessita' di cookie e tracciamento e le garanzie per trasferimenti verso Paesi terzi | parziale | inventario dei cookie (DCP-153); font di Google tolti (DCP-137); restano tessere OpenStreetMap e ricerca indirizzi (Nominatim) non dichiarate nell'informativa | DCP-137 |
| 4.2.14 | DEVONO | Informare su cookie e tracciamento (Linee guida cookie del Garante 10.6.2021); piena fruibilita' anche senza consenso | conforme | banner non bloccante, cookie policy con i cookie realmente impostati, 17 test nel browser e 59 Pest (DCP-153); vale per soli cookie tecnici | DCP-153, 6.3, 6.4 |
| 4.2.15 | DEVE | Fornitori IT nominati responsabili del trattamento (GDPR art. 28) con ripartizione delle responsabilita' | NON VERIFICATO | nessun elenco di fornitori ne' atti di nomina nel repository (N-09) | DCP-142 |
| 4.2.16 | DEVONO | Trattamenti del sito nel Registro dei trattamenti (GDPR art. 30) | assente | `Treatment` ha 0 righe; nessun registro in `docs/` (N-10) | DCP-142 |
| 4.3.1 | DEVE | Approccio orientato alle persone, con gli utenti coinvolti in tutte le fasi | NON VERIFICATO | scopo, attori e ciclo documentati (DCP-112); coinvolgimento di utenti reali non documentato | 8.6, 8.2 |
| 4.3.2 | DEVONO | Definire e valutare obiettivi, destinatari, processi e attori | parziale | scopo e attori in `Fixcity/docs/compliance/00-scopo-del-progetto.md`; obiettivi misurabili non definiti | 8.2 |
| 4.3.3 | DEVONO | Ricerche con gli utenti su caratteristiche e bisogni nel contesto d'uso | NON VERIFICATO | esistono `bmad-output/research-report.md` e `Themes/Sixteen/docs/user-research.md`; sessioni con utenti non trovate | 8.6 |
| 4.3.4 | DEVONO | Mappare scenari e funzionalita' dal punto di vista degli utenti; prototipi per verificare soluzione e usabilita' | parziale | Design Comuni come prototipo di riferimento; flussi del wizard documentati; verifica con utenti assente | 8.6 |
| 4.3.5 | DEVONO | Usare i risultati della ricerca per l'architettura dell'informazione | NON VERIFICATO | IA presa dal modello Design Comuni; nessun legame con ricerche sugli utenti | 8.3 |
| 4.3.6 | DEVONO | Test di usabilita' per verificare che i servizi rispondano alle esigenze | assente | nessun test di usabilita' con utenti nei docs cercati (solo parita' visiva e axe) | 8.6 |
| 4.3.7 | DEVONO | Ontologie e vocabolari controllati standard della PA | assente | i tipi di segnalazione sono un enum interno (`TicketTypeEnum`) senza corrispondenza con vocabolari della PA | DCP-171 |
| 4.3.8 | DEVE | Linguaggio e organizzazione dei contenuti adatti al destinatario | NON VERIFICATO | leggibilita' non misurata; copertura delle 4 lingue verificata (DCP-153: 48 combinazioni, 0 problemi) | 8.4 |
| 4.3.9 | DEVE | Contenuti facilmente trovabili da motori di ricerca esterni e interni | parziale | `robots.txt` 200; `sitemap.xml` 404; `canonical` 0 su 48; `hreflang` 0 su 48; meta description assente su 8 su 48; ricerca interna: il campo «Cerca nel sito» ha `data-bs-autocomplete="[]"` e `/it/search`, `/it/ricerca` rispondono 404 | DCP-166, 8.4 |
| 4.3.10 | DOVREBBE | Su ogni pagina la data dell'ultimo aggiornamento o verifica | assente | assente in 41 combinazioni su 48; nelle 7 che la nominano e' un campo da compilare (dichiarazione di accessibilita', note legali) | DCP-167 |
| 4.4.1 | DEVONO | Raccogliere e analizzare traffico e comportamento degli utenti | assente | nessuno strumento di misura (coerente con soli cookie tecnici, ma il requisito non e' soddisfatto) | DCP-168, 8.2 |
| 4.4.2 | DEVONO | Pubblicare le informazioni di monitoraggio, aggregate e anonimizzate | assente | nessuna pagina di statistiche | DCP-168 |
| 4.4.3 | DOVREBBE | Adottare Web Analytics Italia, informando gli utenti e rispettando le Linee guida cookie | assente | nessuno strumento adottato | DCP-168 |
| 4.4.4 | DEVE | Permettere agli utenti di comunicare facilmente soddisfazione e difficolta' | parziale | valutazione del ticket risolto (modulo Rating); valutazione della pagina o del servizio presente in 4 combinazioni su 48; modulo di feedback sull'accessibilita' (DCP-132) | DCP-169, DCP-157 |
| 4.4.5 | DEVONO | Raccogliere, analizzare e valutare i feedback sulla qualita' percepita | parziale | i dati si raccolgono (valutazioni, feedback di accessibilita'); nessuna analisi periodica | DCP-169, 8.8 |
| 4.4.6 | DOVREBBE | Manutenzione evolutiva con metodologie di test e ricerca quanti-qualitativa | NON VERIFICATO | `parity` e axe come controlli ricorrenti; ricerca con utenti assente | 8.8 |
| 4.5.1 | DEVONO | Usare i modelli di design disponibili per specifiche tipologie di siti e servizi | conforme | tema Sixteen su Design Comuni (modello per i Comuni); parita' misurata ~99,8% sulle pagine principali (2026-10-13) | - |
| 4.5.2 | DEVONO | Interfacce coerenti per stile ed esperienza d'uso nello stesso sito | parziale | pagine native della home e di `/it/tickets` con classi proprie (`fc-*`) accanto alle pagine Design Comuni; coerenza non misurata | 8.3 |
| 4.5.3 | DEVONO | Interfacce adattabili al dispositivo | conforme | `viewport` dichiarato e nessuno scorrimento orizzontale a 320-375 px (matrice I-24) | - |
| 4.6.1 | DEVE | Accesso con i sistemi di autenticazione previsti dal CAD, con attributi minimi | assente | solo email e password; nessuno SPID, CIE (N-24) | DCP-144 |
| 4.6.2 | DEVE | Base giuridica e garanzie per elementi di terze parti incorporati (font, video, social plug-in) | parziale | font di Google serviti in locale dal 2026-10-14 (richieste a Google: da 2 host a 0 su 12 pagine); restano le tessere OpenStreetMap | DCP-137 |
| 4.6.3 | DEVE | Pagamenti online con gli strumenti previsti dal CAD | non applicabile | il ciclo della segnalazione non prevede pagamenti del cittadino (scopo del progetto); da riconfermare se si aggiungono servizi a pagamento | - |
| 4.7.1 | DEVE | Associare ai contenuti una licenza aperta, salvo diversa norma | assente | 45 combinazioni su 48 non nominano alcuna licenza; il modello delle note legali ha il campo (DCP-162) ma l'ente non l'ha scelta | DCP-170 |
| 4.7.2 | DEVE | Inserire il link alla licenza adottata, nella versione aggiornata | assente | nessun link alla licenza nel footer | DCP-170 |
| 4.8.1 | DEVE | Clausola nei contratti pubblici: il fornitore deve rispettare le Linee guida di design | NON VERIFICATO | il contratto tra l'ente e il fornitore non e' nel repository (I-05) | DCP-158 |

## Lacune che nessuna story precedente copriva
4.2.10 (minori), 4.2.12 (contatti del DPO dalla home), 4.3.7 (vocabolari), 4.3.9 (ricerca interna e sitemap), 4.3.10 (data di aggiornamento), 4.4.1-4.4.3 (monitoraggio), 4.4.4-4.4.5 (valutazione della pagina), 4.7 (licenza), 4.8 (clausola): ognuna ha ora una story DCP-164..171 (o una voce in DCP-158). Il resto e' gia' nelle story DCP-130..161 e nella serie 6.x e 8.x di `bmad-output/stories/`.

## Limiti
Non e' un parere legale. I requisiti sulla sicurezza e sulla privacy del capitolo 4.2 rimandano a norme (GDPR, Codice privacy, provvedimenti del Garante) il cui testo e' nei registri fonti 02 e 03 di `Fixcity/docs/compliance/`. Il sito di prova e' un ambiente locale: la conformita' in produzione si decide con il gate DCP-154.
