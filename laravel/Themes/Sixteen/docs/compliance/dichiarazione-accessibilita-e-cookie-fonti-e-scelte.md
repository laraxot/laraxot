---
title: "Dichiarazione di accessibilità e cookie policy: fonti, evidenze e scelte"
type: compliance
status: draft-for-accessibility-officer-review
module: Sixteen
created: 2026-10-26
updated: 2026-10-26
tags: [bmad, accessibilita, cookie, garante, agid, dcp-132, dcp-143, dcp-153]
qmd: "dichiarazione accessibilita decisione 2018/1523 modello stato conformita parzialmente conforme evidenze audit cookie policy inventario remember_web garante linee guida 2021 art 122"
related:
  - ../bmad/stories/dcp-132-dichiarazione-accessibilita-e-meccanismo-feedback.story.md
  - ../bmad/stories/dcp-143-accessibilita-difetti-aria-e-audit-manuale-wcag-21-aa.story.md
  - ../../../../Modules/Gdpr/docs/stories/dcp-153-cookiebar-integrata-nel-modulo-gdpr.story.md
  - ../../../../Modules/Fixcity/docs/bmad/completion-audit/front-office-5-accessibilita-e-responsive.md
  - ../../../../Modules/Fixcity/docs/compliance/03-fonti-cad-accessibilita-verificate.md
---

# Dichiarazione di accessibilità e cookie policy: fonti, evidenze e scelte

Fase BMAD: sviluppo e verifica. Data di lavoro: 2026-10-26. Questo documento è una **bozza** che il responsabile dell'accessibilità dell'ente deve convalidare: nessuna parte è un parere legale.

## 1. Che cosa è cambiato

| File | Cambio |
|---|---|
| `config/local/fixcity/database/content/pages/accessibilita.index.json` e la copia `Modules/Fixcity/config/database/content/pages/accessibilita.index.json` (identiche, md5 uguale) | Chiave `declaration` delle 4 lingue: stato, valutazione, 5 parti non accessibili con motivazione, elenchi `verified` e `not_verified`, segnaposto `[[...]]` per i fatti dell'ente |
| `Themes/Sixteen/lang/{it,en,de,es}/accessibility.php` | Nuove chiavi `scope`, `status.reference`, `drafting.verified`, `drafting.not_verified`, `feedback.officer`; frasi di stato allineate al modello UE ("a causa dei casi di non conformità elencati di seguito") |
| `Themes/Sixteen/resources/views/components/blocks/accessibility-statement/design-comuni.blade.php` | Sezione "Ambito" (denominazione dell'ente da `GetEnteDataAction`, indirizzo del sito), norma di riferimento, elenchi "verificato" e "non verificato", responsabile dell'accessibilità |
| `Modules/Fixcity/app/Actions/AssessAccessibilityStatementAction.php` | Due controlli nuovi: `entity` (denominazione dell'ente, da `config/<tenant>/ente.php`, chiave `name`) e `officer` (responsabile); un segnaposto in una voce già segnalata non si ripete come "valori d'esempio" |
| `Modules/Fixcity/lang/{it,en,de,es}/accessibility.php` | Titolo e indicazione per `entity` e `officer` |
| `Modules/Fixcity/tests/Unit/AssessAccessibilityStatementActionTest.php`, `Feature/Pages/AccessibilityStatementPageTest.php` | Test aggiornati e nuovi (vedi sezione 7) |
| `Modules/Gdpr/app/Actions/Cookie/GetCookieInventoryAction.php` | Il cookie «Ricordami» del login entra nell'inventario (vedi sezione 5). Testi `purpose_remember` in `Modules/Gdpr/lang/cookie-consent/*/texts.php` scritti da un altro agente in parallelo; qui integrati senza duplicarli |
| `Modules/Fixcity/tests/Unit/Cookie/RememberCookieTest.php` (nuovo), `Themes/Sixteen/tests/browser/cookie-banner.spec.mjs` | Prova sul cookie accodato da un login vero; la prova da browser esclude `remember_*` dal confronto con il giro dell'ospite |

Non toccati: informativa privacy, note legali, media policy, `config/local/fixcity/ente.php` (vuoto per scelta di DCP-289: i valori sono dell'ente).

## 2. Che cosa chiede il modello (Decisione di esecuzione (UE) 2018/1523, allegato)

Letto direttamente il 2026-10-26 dal testo italiano (archivio Cellar, vedi fonti). Sezione 1, contenuto obbligatorio (art. 4 par. 1):

| Voce del modello | Dove sta ora |
|---|---|
| «[Denominazione dell'ente pubblico] si impegna a rendere [...] accessibile/i, conformemente al[la] [normativa nazionale]» e ambito della dichiarazione | Sezione "Ambito della dichiarazione": denominazione da `ente.php`, indirizzo del sito dalla richiesta |
| Stato di conformità a), b) o c), con la norma di riferimento | Sezione "Stato di conformità" + riga "Riferimento tecnico" (UNI CEI EN 301 549 V3.2.1, WCAG 2.1 AA) |
| Contenuti non accessibili: a) inosservanza, b) onere sproporzionato, c) fuori ambito, con alternative accessibili | Liste `non_accessible`, `disproportionate_burden`, `out_of_scope`; l'alternativa è nel testo della motivazione |
| Redazione: data, metodo (art. 3), ultimo riesame | `evaluation.method`, `evaluation.date`, `published_at`, `updated_at` |
| Feedback: meccanismo, link e recapiti dell'unità o della persona responsabile | Modulo Livewire + `feedback_email` + `officer` |
| Procedura di attuazione: descrizione, link, recapito dell'organismo | Testo fisso + `procedure_url` (AgID) |

Art. 3 par. 1: le informazioni sulla conformità devono essere "esatte e basate" su una valutazione effettiva: "un'autovalutazione effettuata dall'ente pubblico" oppure "una valutazione effettuata da terzi, ad esempio una certificazione". Note del modello: scegliere a) solo se sono rispettate tutte le prescrizioni senza eccezioni; b) se è rispettata la maggior parte, con qualche eccezione; c) se la maggior parte non è rispettata. La nota xiii raccomanda un riesame almeno annuale.

## 3. Scelte sulla dichiarazione

| Campo | Valore | Perché |
|---|---|---|
| `status` | `partially_compliant` | Esistono non conformità misurate e un audit incompleto: "conforme" non è dimostrabile. La nota v del modello (maggior parte rispettata con qualche eccezione) è coerente con axe a zero su 24 pagine e 5 difetti noti, ma resta un giudizio da convalidare. `compliant` resta vietato dal codice senza metodo e data (`AssessAccessibilityStatementAction`) e un test nuovo impedisce che la dichiarazione consegnata lo dica |
| `evaluation.method` | `self` | L'audit è del team di progetto, non di un ente terzo né di una certificazione. Vale come autovalutazione solo se l'ente lo adotta: domanda aperta 1 |
| `evaluation.date` | `2026-10-26` | Data dell'audit tecnico FO-5 e del controllo di questa sessione |
| `published_at`, `updated_at`, `officer`, `feedback_email` | `[[segnaposto]]` identici in tutte le lingue | Fatti dell'ente. Il segnaposto non viene mai mostrato: la pagina scrive "Non ancora indicato" e il callout elenca la voce |
| `agid_declaration_url` | `null` | Si ottiene compilando la dichiarazione su form.agid.gov.it (vedi sezione 6) |
| `procedure_url` | pagina AgID "procedura di attuazione" | Verificata 200 il 2026-10-13 (DCP-132). **Non riverificabile il 2026-10-26**: `www.agid.gov.it` risponde con reindirizzamento a `manutenzione.agid.gov.it` |
| `disproportionate_burden`, `out_of_scope` | vuoti | Sono dichiarazioni dell'ente (art. 5 Dir. 2016/2102). Non si inventano esenzioni. In particolare l'esenzione per le mappe online dell'art. 1 par. 4 lett. d della direttiva non è riportata nella L. 4/2004 (registro fonti 03, sezione 2): non si invoca |

### Parti non accessibili dichiarate e come sono state verificate

| N. | Contenuto | Criterio WCAG 2.1 | Stato della prova |
|---|---|---|---|
| 1 | Domande frequenti: pulsanti senza segno di focus | 2.4.7 | Rimisurato il 2026-10-26: contorno `solid 3px rgb(255,255,255)`, ombra trasparente, immagine del pulsante identica con e senza focus |
| 2 | Home: testo tagliato con testo al 200% a 375 px | 1.4.4 | Rimisurato il 2026-10-26 con `font-size:200%` forzato in Chromium (simulazione), screenshot letto |
| 3 | Procedura guidata (area riservata): focus, errori, «Usa la tua posizione», mappa senza nome | 2.4.3, 3.3.1, 4.1.3, 2.1.1, 4.1.2 | Da DCP-143 (2026-10-13) e FO-5 (2026-10-26). **Non riverificata**: richiede il login. Dichiarata per prudenza |
| 4 | Mappa: finestra dei dettagli non completa da tastiera | 2.1.1, 2.4.3 | Da FO-5-15. Nel controllo del 2026-10-26 Invio su un marcatore non apre la finestra (11 marcatori su `/it/tickets`, 6 su `/it`): non riprodotto come in FO-5, quindi dichiarato "da riverificare" |
| 5 | Intestazione: contrasto in hover | 1.4.3 | Pulsante rimisurato il 2026-10-26: bianco su `rgb(0,150,106)` = 3,77:1 a 14 px. Voci di menu 4,13:1 da FO-5-22, non rimisurate |

Difetti di FO-5 che **non** compaiono più e quindi non sono dichiarati (rimisurati il 2026-10-26): campo del codice di traccia largo 16 px a 375 (ora 343 px); pagina "Password dimenticata" stock in inglese senza `main` (ora h1 e testi in italiano); salto ai contenuti senza bersaglio sulle pagine di traccia e `id="main-container"` duplicato (ora 1 per pagina, bersaglio presente su 24 pagine); errori della registrazione senza `aria-invalid` (ora `id` e `aria-invalid` su 7 campi); modale di ricerca senza gestione del focus (il focus entra nel campo e torna al pulsante con Esc); contrasto del badge di stato (axe a zero su `/it/tickets/track/DEMO-PUB-01`). Non rimisurati perché non riproducibili senza login o senza lettore di schermo: FO-5-12, 13, 14, 17, 20, 21.

## 4. Evidenze usate (con data e strumento)

| Evidenza | Data | Strumento | Esito |
|---|---|---|---|
| axe-core su 24 pagine pubbliche a 1280 e 375 px (it: 15 pagine; en, de, es: home, accessibilità, cookie policy) | 2026-10-26 | Chromium 156.0.8078.4, Playwright 1.64.1, axe-core 4.14.0 (`@axe-core/playwright` 4.14.0), tag wcag2a, wcag2aa, wcag21a, wcag21aa, debugbar di sviluppo esclusa | 0 violazioni in 48 misure su 48. Rifatto dopo le modifiche su `/{it,en,de,es}/accessibilita` e `/cookie-policy` a due larghezze: 0 su 16 |
| Controlli mirati: lingua, un `h1`, un `main`, bersaglio del salto, errori della registrazione, ricerca, selettore lingua | 2026-10-26 | Playwright | vedi sezione 3 |
| Audit FO-5 (39 pagine, tastiera, focus a pixel, contrasto, reflow, moduli) | 2026-10-26 | Playwright, axe, Chromium | 33 finding; rapporto in `Modules/Fixcity/docs/bmad/completion-audit/front-office-5-accessibilita-e-responsive.md`. Le prove citate nel rapporto stanno in una cartella temporanea e vanno rifatte prima di correggere |
| Audit DCP-143 (15 pagine con login, checklist WCAG 2.1 AA) | 2026-10-13 | Playwright, axe-core 4.13.0, tastiera | axe a zero su 30 misure; checklist con molte righe "non verificato" |

## 5. Cookie: inventario reale e verifica

### Cookie del sito (verificati con HTTP e con un browser vero, 2026-10-26)

| Nome | Scopo | Durata | Parte | Tipo | Prova |
|---|---|---|---|---|---|
| `fixcity_session` | sessione, protezione dell'accesso | Max-Age 7200 s in sviluppo (`session.lifetime` 120); in produzione 60 minuti salvo `SESSION_LIFETIME`, e cookie di sessione se `expire_on_close` | prima, HttpOnly, SameSite=Lax | tecnico | `Set-Cookie` su 18 URL (it, en, de, es, tickets, traccia, login, registrazione, privacy, note legali, blog, eventi, FAQ, mappa del sito, cookie policy, accessibilità) |
| `XSRF-TOKEN` | protezione dei moduli | come la sessione | prima, leggibile da JS, SameSite=Lax | tecnico | idem |
| `__cookie_consent` | memoria della scelta sul banner | 180 giorni, SameSite=Lax | prima | tecnico | non compare nelle intestazioni `Set-Cookie`; lo scrive il JS del banner dopo "Ho capito": 180 giorni misurati nel browser |
| `remember_web_59ba36addc2b2f9401580f014c7f58ea4e30989d` | resta collegato su questo dispositivo | 400 giorni | prima, HttpOnly | tecnico | **era assente dalla cookie policy.** Si imposta solo con la casella «Ricordami» del login (`LoginWidget`: `Auth::attempt($credentials, $remember)`). Provato con `RememberCookieTest`: un login con remember accoda un solo cookie `remember_*`, uguale al nome dell'inventario, con scadenza a 400 giorni |

Cookie visti solo in sviluppo, non del sito: `PHPDEBUGBAR_STACK_DATA` (sessione, HttpOnly, SameSite=Lax) e due chiavi `phpdebugbar-*` in `localStorage`. Dipendono da `DEBUGBAR_ENABLED=true` in `.env` di sviluppo; in produzione il flag deve essere falso (lo controlla già `production-checklist.sh`, citato nel test `CookieBannerPagesTest`).

### Terze parti (browser vero, 14 pagine in 4 lingue)

- Richieste verso altri domini: solo `{a,b,c}.tile.openstreetmap.org`, sulle pagine con la mappa (home in it, en, de, es e `/it/tickets`). Senza cookie impostati da quei domini; il servizio riceve l'indirizzo IP. La cookie policy lo dice già ("Mappe e servizi esterni").
- `nominatim.openstreetmap.org` compare nel JS della mappa (ricerca e geocodifica inversa) e viene chiamato solo se la persona cerca un indirizzo: non è stato esercitato in questa sessione.
- Nessun riferimento a Google Fonts, YouTube, analisi o pubblicità nell'HTML, nei CSS e nei JS di `/it`. La CSP in sola segnalazione (`Content-Security-Policy-Report-Only`) consente `fonts.googleapis.com` e `fonts.gstatic.com`, ma nessuna risorsa li usa: chiude la questione aperta di DCP-153 su "verificare i font".
- `localStorage`: nessuna chiave del sito scritta a un primo accesso. Il codice del tema legge `dark_mode`, ma nessun comando presente nelle pagine la scrive.

### Che cosa ho cambiato nella cookie policy e perché

La pagina è generata dall'inventario (`GetCookieInventoryAction`), la stessa fonte del banner, quindi aggiungere il cookie «Ricordami» lì lo porta in pagina e nel banner in 4 lingue. Non ho aggiunto testo alla pagina: il resto era completo rispetto al par. 7.1 delle linee guida del Garante (elenco dei cookie con finalità, durata e tipo; terze parti; come cambiare la scelta; rinvio all'informativa privacy; link nel footer). Restano due punti che non sono lacune del codice: l'identità del titolare e i diritti stanno nell'informativa privacy (altro agente) e i criteri di classificazione tecnico, analitico, profilazione (par. 8.2) sono implicitamente quelli del par. 4.

Pagina servita dopo la modifica: tabella con 4 righe in it, en, de, es; axe a zero a 1280 e 375 px.

## 6. Obblighi che restano all'ente e a chi pubblica

- La dichiarazione va compilata anche sulla piattaforma AgID `https://form.agid.gov.it/` (Linee guida AgID sull'accessibilità, cap. 4.1, secondo il registro fonti 03; `form.agid.gov.it` risponde 200 il 2026-10-26) e il suo indirizzo va in `agid_declaration_url`. Il testo delle Linee guida non è stato riletto oggi perché il sito AgID è in manutenzione.
- Riesame e validazione ogni anno entro il 23 settembre (stesso cap. 4.1). La L. 4/2004 art. 3-quater non contiene quella data (registro fonti 03).
- Il link "Dichiarazione di accessibilità" sul footer c'è già (DCP-131); il modello UE lo vuole in evidenza sulla home o su tutte le pagine.
- Procedura di attuazione: secondo il regolamento AgID sul difensore civico per il digitale (DT 355/2022, nel risultato di ricerca di oggi, PDF non estratto) la segnalazione si presenta con il modulo raggiungibile dal link nella dichiarazione dell'ente. Il link attuale è quello verificato il 2026-10-13: l'ente deve confermare che sia ancora quello giusto quando AgID torna online.

## 7. Dati che deve fornire l'ente (elenco esatto)

1. Denominazione dell'ente: `config/<tenant>/ente.php`, chiave `name` (tenant `local/fixcity`: oggi vuota). Alimenta anche header e footer.
2. Responsabile dell'accessibilità (nome e ruolo o ufficio): `declaration.officer`, nel blocco di ciascuna lingua dei due JSON.
3. Indirizzo email o PEC del canale di feedback: `declaration.feedback_email`. Finché manca, il modulo di feedback resta spento.
4. Data di pubblicazione scelta dall'ente: `declaration.published_at` (AAAA-MM-GG).
5. Data della dichiarazione o dell'ultimo riesame: `declaration.updated_at` (AAAA-MM-GG).
6. Convalida del responsabile su stato (`partially_compliant`), metodo (`self`), data della valutazione, elenco delle parti non accessibili e dei punti non verificati.
7. Indirizzo della dichiarazione compilata su form.agid.gov.it: `declaration.agid_declaration_url`.
8. Eventuali dichiarazioni di onere sproporzionato (art. 5 Dir. 2016/2102) o contenuti fuori ambito: `disproportionate_burden`, `out_of_scope`.

Prova: con `PUBLICATION_GATE=1` la guardia `AccessibilityDeclarationPublicationGateTest` resta rossa in 4 lingue e chiede esattamente: data di pubblicazione, data dell'ultimo aggiornamento, denominazione dell'ente, responsabile, indirizzo per le segnalazioni.

## 8. Che cosa NON è stato verificato

- Nessun lettore di schermo reale (NVDA, JAWS, VoiceOver, TalkBack); nessuna persona; nessun dispositivo reale; solo Chromium.
- Procedura guidata e area personale: non riverificate oggi (login non usato per non toccare il database di sviluppo).
- Il sito in produzione e il suo contenuto reale; il back office; gli allegati.
- Il testo vigente degli artt. 3-quater e 3-quinquies della L. 4/2004 su Normattiva oggi: la pagina atto restituisce solo l'art. 1 e "ultimo aggiornamento all'atto pubblicato il 01/07/2022". Gli articoli sono quelli letti il 2026-10-13 nel registro fonti 03.
- WP29 parere 04/2012 (WP194), par. 3.2: il PDF è stato scaricato ma non è stato possibile estrarne il testo (nessuno strumento PDF disponibile); il contenuto del par. 3.2 è confermato solo da fonti secondarie.
- I numeri dei criteri WCAG sono quelli dei rapporti esistenti; non ho riletto oggi il testo del W3C.
- Se un cookie, uno script o un'integrazione venga aggiunto dopo la verifica (nessun test lo prova nel tempo: lo prova solo `CookieBannerPagesTest` su 7 URL).

## 9. Fonti

Normativa e atti (testo letto direttamente il 2026-10-26, salvo diversa indicazione):

- Decisione di esecuzione (UE) 2018/1523, modello di dichiarazione di accessibilità, artt. 1 a 4 e allegato: https://eur-lex.europa.eu/eli/dec_impl/2018/1523/oj (testo letto da http://publications.europa.eu/resource/celex/32018D1523, perché EUR-Lex risponde con sfida anti-bot). Vigenza: nessuna modifica registrata nell'interrogazione SPARQL del 2026-10-13 (registro fonti 03).
- Direttiva (UE) 2016/2102, artt. 1, 4, 5, 7: https://eur-lex.europa.eu/eli/dir/2016/2102/oj (registro fonti 03, 2026-10-13).
- Legge 9 gennaio 2004, n. 4, artt. 3-bis, 3-ter, 3-quater, 3-quinquies, 4: https://www.normattiva.it/uri-res/N2Ls?urn:nir:stato:legge:2004-01-09;4 (registro fonti 03, 2026-10-13; modificata dal D.Lgs. 106/2018).
- D.Lgs. 10 agosto 2018, n. 106 (recepimento della direttiva): https://www.normattiva.it/uri-res/N2Ls?urn:nir:stato:decreto.legislativo:2018-08-10;106
- D.Lgs. 27 maggio 2022, n. 82 (Atto europeo sull'accessibilità), art. 25 c. 3: https://www.normattiva.it/uri-res/N2Ls?urn:nir:stato:decreto.legislativo:2022-05-27;82. Non è la base di questa dichiarazione: riguarda prodotti e servizi di mercato dal 28 giugno 2025 e non elenca i servizi pubblici comunali (registro fonti 03, sezione 7).
- Linee guida AgID sull'accessibilità degli strumenti informatici, Det. 437/2019, 396/2020, 354/2022 (testo vigente: riferimento a EN 301 549 V3.2.1; WCAG 2.1 AA come requisito; dichiarazione sulla piattaforma AgID; riesame entro il 23 settembre): https://www.agid.gov.it/it/design-servizi/accessibilita (non raggiungibile oggi) e comunicato GU n. 237 del 24-09-2020. Letto nel registro fonti 03 (2026-10-13).
- Decisione (UE) 2021/1339, riferimento a EN 301 549 V3.2.1: https://eur-lex.europa.eu/eli/dec_impl/2021/1339/oj (registro fonti 03).
- Regolamento AgID sul difensore civico per il digitale, DT 355/2022: https://www.agid.gov.it/sites/agid/files/2025-02/Regolamento_segnalazioni_Difensore_Civico_Digitale_accessibilit%C3%A0.pdf (indicato dalla ricerca, PDF non letto).
- Piattaforma AgID per le dichiarazioni: https://form.agid.gov.it/ (200 il 2026-10-26).
- Garante per la protezione dei dati personali, Linee guida cookie e altri strumenti di tracciamento, 10 giugno 2021, doc. web 9677876, provv. n. 231, GU n. 163 del 9 luglio 2021: https://www.garanteprivacy.it/home/docweb/-/docweb-display/docweb/9677876. Letti i parr. 4 (tecnici e profilazione), 5, 6.1 (scroll e cookie wall), 6.2 e 7.1 (sei mesi prima di riproporre il banner, nessuno strumento non tecnico prima del consenso, link nel footer, elenco di terze parti e cookie), 7.2 (analitici equiparati ai tecnici con minimizzazione dell'IP), 8 (informativa). Il testo non nomina il local storage.
- Codice privacy, D.Lgs. 196/2003, art. 122, c. 1 (consenso salvo archiviazione tecnica "nella misura strettamente necessaria al fornitore di un servizio della società dell'informazione esplicitamente richiesto"): https://www.normattiva.it/uri-res/N2Ls?urn:nir:stato:decreto.legislativo:2003-06-30;196~art122
- Direttiva 2002/58/CE (ePrivacy), art. 5 par. 3, nel testo della direttiva 2009/136/CE: https://eur-lex.europa.eu/legal-content/IT/TXT/?uri=CELEX:02002L0058-20091219
- WP29, parere 04/2012 sull'esenzione dal consenso (WP194), par. 3.2 sui cookie di accesso persistenti ("ricordami"): https://ec.europa.eu/justice/article-29/documentation/opinion-recommendation/files/2012/wp194_en.pdf (par. 3.2 da fonte secondaria: https://www.insideprivacy.com/international/article-29-working-party-publishes-guidance-on-cookie-rule-exemptions/).
- WCAG 2.1 e 2.2: https://www.w3.org/TR/WCAG21/ e https://www.w3.org/TR/WCAG22/ (non riletti oggi).

## 10. Domande aperte

1. Metodo: l'ente adotta l'audit tecnico del team come propria autovalutazione (`self`), oppure vuole una valutazione di terzi (`third_party`)? Cambia una sola chiave nei due JSON.
2. Lo stato `partially_compliant` è convalidato dal responsabile? La nota v del modello lo riserva al caso "maggior parte delle prescrizioni rispettata": l'audit non copre tutti i criteri.
3. Chi esegue la prova con NVDA o VoiceOver (unico modo per chiudere i punti 3 e 4 della sezione 3 e la riga "non verificato" sui lettori di schermo)?
4. Voci di menu in hover a 4,13:1 e pulsante a 3,77:1: l'ente considera il contrasto in hover un requisito AA (criterio 1.4.3) o lo tratta come rifinitura? Qui è dichiarato per prudenza.
5. Link della procedura di attuazione: confermare l'indirizzo quando `www.agid.gov.it` torna raggiungibile.
6. Durata dei cookie di sessione in produzione (60 minuti per default): la pagina la legge dalla configurazione, ma conviene confermare `SESSION_LIFETIME` e `SESSION_EXPIRE_ON_CLOSE` nel `.env` di produzione prima della messa online.
7. Il cookie «Ricordami» dura 400 giorni: è voluto? Il WP29 (par. 3.2) lo ammette se la casella è spuntata dalla persona e la durata è comunicata vicino alla casella; oggi la durata è solo nella cookie policy.
