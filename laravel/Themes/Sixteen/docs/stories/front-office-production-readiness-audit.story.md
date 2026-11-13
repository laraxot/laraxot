---
title: "Front office production readiness audit (anonymous and logged in)"
type: story
status: in-progress
module: Sixteen
created: 2026-11-11
updated: 2026-11-11
tags: [bmad, story, sixteen, front-office, audit, production, a11y, ux, docs]
qmd: "front office production readiness audit logged anonymous area personale 500 view cache debugbar sitemap a11y axe"
related:
  - auth-components-regression-restore.story.md
  - front-office-completeness-audit.story.md
  - ../../../../Modules/Fixcity/docs/stories/dcp-154-gate-di-messa-in-produzione.story.md
  - ../../../../Modules/Fixcity/docs/stories/dcp-280-completamento-del-progetto-storia-ombrello.story.md
  - ../../../../Modules/Cms/docs/stories/head-sixteen-layout-migration.story.md
issues: []
discussions: ["https://github.com/laraxot/theme_sixteen_fila5/discussions/93", "https://github.com/laraxot/theme_sixteen_fila5/discussions/94", "https://github.com/laraxot/theme_sixteen_fila5/discussions/95"]
---

# Front office production readiness audit

Status: **in-progress**

## Why

Richiesta dell'utente (2026-11-11): controllare **integralmente** il front office, sia da utente non loggato sia da utente
loggato, per andare in produzione; migliorare ciò che è migliorabile, correggere ciò che è rotto, alzare la qualità del
codice e la UI/UX, e riordinare le `docs` di moduli e temi. Metodo: BMAD + ECC + second brain + Filament Blueprint.

Questa story prosegue il punto «ancora aperto» di `auth-components-regression-restore.story.md`: audit dell'area loggata,
revisione UI/UX e docs. Il ripristino delle viste cancellate dal commit `6cb55f0d` è già fatto lì; qui si misura il risultato.
La parte di configurazione e contenuti per la pubblicazione resta di **DCP-154** (`go-live-gate.py`): non si duplica qui, si
esegue a fine lavoro.

## Fasi BMAD

| Fase | Contenuto | Stato |
|------|-----------|-------|
| Analisi | inventario URL, crawl HTTP, sweep browser (console, rete, overflow, axe), log | in corso |
| Soluzione | un fix per causa, test rosso → verde nel modulo/tema owner | in corso |
| Verifica | `view:cache` (2235 viste compilate, pagine vive con `data-dc`), PHPStan e Pint sui file toccati, Pest del tema (18 rossi, tutti `ComuneControllerTest`), spec browser nuove; resta il `go-live-gate.py` completo a macchine libere (lo lancia la sessione 85) | quasi chiusa |
| Docs | `docs/` di moduli e temi toccati, second brain, indice wiki | in corso |

## Metodo e strumenti

- Istanza misurata: `php artisan serve` su `127.0.0.1:8081` (6 worker), DB di sviluppo, `APP_DEBUG=true` (Debugbar attiva).
- Anonimo: crawl HTTP (stato, titolo, canonical, description, H1, alt, chiavi grezze) e sweep browser (Playwright 1.63,
  axe-core WCAG 2.0/2.1/2.2 AA, desktop 1440 e mobile 390).
- Loggato: utente di prova `@example.test` del DB dev, sessione creata dal codice dell'app (mai password nel repo).
- Regola: ogni difetto è **MISURATO** (riprodotto) o **DEDOTTO** (da verificare). Le cause segnalate dagli agenti si
  riproducono prima di correggere.

## Baseline misurata (prima dei fix)

| # | Misura | Esito |
|---|--------|-------|
| B1 | Crawl HTTP anonimo, 220 URL (172 pagine + 48 schede ticket a campione, 4 lingue) | 220/220 a 200, titolo/canonical/description/1 H1 ovunque, 0 chiavi grezze, 0 img senza alt |
| B2 | Home anonima e loggata, 4 lingue | 200 |
| B3 | `/it/area-personale/pratiche` da loggato | **500** `Undefined variable $tickets` (`pages/area-personale/pratiche.blade.php:25`) |
| B4 | Sitemap | 2108 URL; `/tickets`, `/services`, `/lista-categorie`, `/mappa-sito` solo in 2 lingue su 4 (da verificare se voluto) |

## Difetti e correzioni

Stato: **MISURATO** = riprodotto prima e dopo; ogni correzione ha una prova. I difetti dello sweep anonimo, dello sprite
delle icone, di `deploy.sh` e dei gate sono nella sezione «Difetti sessione 85» più sotto.

| ID | Difetto | Causa verificata | Correzione e prova | Stato |
|---|---|---|---|---|
| L-01 | `/it/area-personale/pratiche` da loggato: **500** `Undefined variable $tickets` | `Themes/Sixteen/resources/views/pages/area-personale/pratiche.blade.php`: pagina Folio residua che leggeva `$tickets` da un `@php` mai eseguito sul percorso servito. Una cartella letterale `pages/area-personale/` ha precedenza sul router CMS (Folio abbina prima le cartelle letterali, poi `[container0]/[slug0]`), quindi **ombreggiava** anche le pagine sorelle: `/seguite` rispondeva 404 e `/notifiche` 302→404 | rimossa la pagina Folio e la sua cartella vuota (copia in `scratchpad/removed/`). L'implementazione vera è il blocco CMS `personal-area-practices` dichiarato in `Modules/Fixcity/config/database/content/pages/area-personale/pratiche.json`. Prova da loggato: `pratiche`, `seguite`, `impostazioni`, `notifiche` tutte **200**. Guardia già esistente `NoSemanticFolioPageDirectoriesTest` 2/2 | chiuso |
| L-02 | `/it/pages/<qualsiasi>` rispondeva **200** (soft-404) | `pages/pages/[slug].blade.php`: pagina Volt legacy `name('pages.view')` che accettava ogni slug | rimossa con la cartella (copia in `scratchpad/removed/`). Ora 404. Nessun link vivo la usava (i `/pages/privacy|help|…` stanno solo in `layouts/auth.blade.php` e `guest.blade.php`, usati dal solo `disclaimer.blade.php`) | chiuso; **aperto**: ripulire i link morti di quei due layout legacy |
| L-03 | Sitemap e hreflang senza `de`/`es` per `/tickets`, `/services`, `/lista-categorie`, `/mappa-sito`; `<head>` di quelle lingue `noindex` | Cms `ResolveWrittenLocalesAction`: una lingua è «scritta» solo se titolo **e** blocchi di quella lingua hanno un corpo. Quelle 4 pagine hanno blocchi-vista senza testo (`view`, `header_active`) dichiarati solo per `it`/`en`, quindi `de`/`es` risultavano «non scritte» pur essendo tradotte dai file lang | aggiunti `de` ed `es` (copia semantica di `it`, verificata col parser) a `content_blocks` e `sidebar_blocks` in 9 JSON (4 pagine × tenant `config/local/fixcity` e modulo, più `tickets.index.json` del modulo). Prova: `/sitemap.xml` ora ha it/en/de/es per tutte e 4 le pagine (2116 URL contro 2108), `/de/tickets`, `/es/services`, `/de/lista-categorie`, `/es/mappa-sito` rispondono 200. Guardia: le 4 pagine aggiunte a `$contentParityTargets` di `Modules/Fixcity/tests/Unit/ContentLocaleParityTest.php`: 5/5 verdi; **prova inversa** togliendo de/es da `mappa-sito`: 3 test rossi con messaggio esplicito, file ripristinato | chiuso |
| L-04 | (non è un difetto) 158 pagine del modulo e 77 del tenant mostrano solo `it`/`en` nel JSON | scansione: nel tenant attivo le pagine non-fixture sono 29 e solo 3 non hanno il corpo in 4 lingue, tutte legittime: `segnalazione-dettaglio` (modello `*.view`, non indicizzato), `segnalazioni.index` (alias 301 → `/tickets` in ogni lingua), `tickets/track` (corpo disegnato dal codice, già tradotto). Le altre sono fixture `tests.*` | nessuna azione | chiuso |

| L-05 | 13 PHP del tema **non compilano** (`ThemeServiceProvider`, `SpidAuthAction`, `routes/auth.php`, `SixteenComposer`, `CreateAppointment`, `Appointment`, 7 `Models/Municipal/*`): `phpstan analyse Themes/Sixteen` dava **184 errori di parsing** e `AppointmentEnumsTest` andava in ParseError | MISURATO con `php -l` su 481 file. Sono corrotti dalla sync `6cb55f0d` (riga vecchia e riga nuova incollate sulla stessa riga, `use` doppi, es. `public function scopeUpcoming($query)    public function scopeUpcoming(Builder $query): Builder`): è lo stesso difetto della story `mirror-merge-corruption-php-recovery`, chiusa `done` ma tornata, perché il remoto `laraxot/theme_sixteen_fila5` ha ancora i file rotti e ogni mirror li riporta. Il tema **non è nell'autoload** della radice né in `packages.php`: il sito in esecuzione non carica queste classi, quindi non è un blocco del go-live ma un guasto latente per PHPStan, test e `dump-autoload`. **Attenzione:** `6cb55f0d^` non è una buona sorgente (3 dei 13 non compilano nemmeno lì, `Appointment.php` è identico) | riscritti in avanti dall'**ultima revisione che compila** (metodo della story precedente), file per file: `006ca426` (26 ott.) per 6, `1c4bda3a` (8 ott.) per 5, `bc1ec833` (7 ott.) per 2. Riapplicato il **gate SPID** `digitalIdentityRoutesEnabled` che la revisione vecchia non aveva (P0 del `production-hardening-p0`): `DigitalIdentityRoutesDisabledByDefaultTest` 2/2. Prova: `php -l` 0 rotti su 481; `AppointmentEnumsTest` 3/3; PHPStan sul tema **0 errori di parsing** (da 184). Guardia nuova: `tests/Unit/PhpSourcesCompileTest.php`, `php -l` su `app`, `routes`, `config`, `http`, `src`; prova inversa: reinserito un file rotto, il test diventa rosso con l'errore `Cannot use … already in use` che il solo parser non vede. Copia dei file rotti in `scratchpad/php_broken_backup/` | chiuso; **aperto**: correggere a monte il remoto, e mettere `php -l` prima di ogni mirror |
| L-06 | `Themes/Sixteen/tests/Unit/ComuneControllerTest.php` usava `RefreshDatabase` (vietato: dati sacri) con `Tests\TestCase`, che definisce `refreshDatabase(): void {}`: **fatale** alla costruzione della suite, quindi `pest Themes/Sixteen/tests/Unit` non partiva per intero; in più faceva `artisan migrate --database testing` nel `beforeEach` | MISURATO: `PHP Fatal error: Declaration of …RefreshDatabase::refreshDatabase() must be compatible with Tests\TestCase::refreshDatabase(): void` | tolti il trait e il `migrate`: `Tests\TestCase` usa già `DatabaseTransactions`. Il file si carica. **Non cancellato** (le regole vietano di eliminare test senza approvazione) | chiuso; vedi «Aperto» per i suoi 18 rossi |

**Lezione (L-05).** Una story marcata `done` non protegge da una regressione che arriva da un'altra parte (il remoto): serve una guardia
che si rompa quando il difetto ritorna. Quella di allora aveva ripristinato i file ma non aggiunto alcun test.

**Lezione (L-03).** Ho misurato prima la causa sbagliata: «la sitemap include una lingua se `content_blocks[lingua]` esiste»
sembrava confermata dalle 4 pagine, ma `domande-frequenti` è in sitemap in 4 lingue con un solo `it` nel file del modulo. La regola
vera sta in Cms e legge titolo **e** blocchi; il file che conta è quello del tenant attivo, non quello del modulo. Si legge
l'azione prima di descrivere il comportamento.

**Lezione (L-01).** Una cartella letterale in `pages/` non rompe solo se stessa: toglie al router CMS tutti gli slug fratelli.
Un 500 su una pagina può nascondere un 404 sulle sorelle.

## Difetti del secondo giro (L-07…L-14) e audit F

Stesso metodo: ogni riga ha causa verificata, correzione, **guardia** e **prova inversa** (rosso senza la correzione, verde con). Le sorgenti
ripristinate dal mirror vengono da `<commit>^` verificando che le righe coincidano con le statistiche del commit (`a50c38ec` era la revisione
sbagliata: 206 righe contro 249 cancellate).

| ID | Difetto | Causa verificata | Correzione e prova | Stato |
|---|---|---|---|---|
| L-07 | `#nav4` (menu) con `aria-hidden="true"` anche su desktop: 6 link focusabili dentro un nodo nascosto, axe `aria-hidden-focus` su 14 pagine (WCAG 4.1.2) | `resources/js/theme/header-mobile-nav.js` lo impostava a prescindere dal viewport; sopra 992 px il pannello è la barra sempre visibile | `syncPanelAria()`: `aria-hidden` solo sul drawer mobile e riallineato al cambio di viewport. Spec `tests/browser/header-nav-aria-hidden.spec.mjs` **3/3** (1440: assente; 390: `true`/`false`/`true`; resize 390→1440→390); con il manifest vecchio **rossa** | chiuso |
| L-08 | `npm run build` del tema **non partiva** (import mancanti) e il riepilogo del wizard dava **500** «View not found» a ogni utente loggato | il mirror `6cb55f0d` («.») ha cancellato file ancora importati: 5 JS (`components/{carousel,dropdown,mobile-menu,modal,motion-reveal}`), 9 CSS (`components/{filament-wizard-parity,filament-select-wizard-fix,header-footer-colors,cookiebar-tokens,civic-design-tokens,civic-design-components,fo-filament-form-shell,motion-reveal,design-comuni-shell-a11y}`) e `components/wizard/report-partials/images-preview.blade.php` (anteprime foto, DCP-182), incluso da `summary-sections.blade.php:26` | riscritti in avanti da `6cb55f0d^` (conteggi righe uguali alle statistiche). Gli altri file cancellati (`bootstrap-italia.js`, `camera-upload.js`, `svg/`, `bootstrap-italia-classes.css`, `civic-design-ticket-layer.css`, `design-comuni*.css`) **non sono referenziati** e restano cancellati. Build verde, manifest ricostruito. Guardia `tests/Unit/WizardIncludesResolveTest.php` (ogni `@include` del wizard, anche con prefisso `$variabile.`, punta a un file esistente; prefisso non risolvibile = rosso): **rossa** senza il partial | chiuso |
| L-09 | Barra cookie con **chiavi grezze** (`cookie-consent::texts.…`) e senza il rinvio a «Gestisci cookie» | il mirror `78c4ef9` (Gdpr) ha riportato un `GdprServiceProvider` vecchio (niente namespace lang `cookie-consent`, niente `prependNamespace` della vista, niente `InjectCookieBanner` nel gruppo `web`) e ha ridotto `alert_text_consent` in it/en/de/es | provider e 4 `texts.php` ripristinati da `78c4ef9^`. Guardia `Modules/Gdpr/tests/Unit/Providers/CookieConsentWiringTest.php` (3 test: ogni riga di `texts.php` si risolve via `trans()`, la vista del modulo ha la precedenza, il middleware è nel gruppo `web`): **3 rossi** con il provider regredito. Nota: Pest legge solo `laravel/tests/Pest.php`, quindi ogni file di test deve fare `uses(TestCase::class)` (il commento di `Modules/Gdpr/tests/Pest.php` che dice il contrario è sbagliato nelle esecuzioni dalla radice) | chiuso |
| L-10 | Home in tedesco: `aria-label` «Alle Neuigkeiten anzeigen» su un link che mostra «Alle anzeigen» (WCAG 2.5.3, comando vocale) | `view_all_aria` non conteneva `view_all_label` | «Alle anzeigen: Neuigkeiten» / «Alle anzeigen: Veranstaltungen» (tenant e modulo). Guardia in `ContentLocaleParityTest` (`contentParityAriaMissesVisibleLabel`, controlla che le etichette siano più di 7 per non passare a vuoto): rossa col testo vecchio | chiuso |
| L-11 | **Reset password**: `12345678` e `aaaaaaaa` accettate (la registrazione richiede maiuscole, minuscole, numeri, simboli) | `UserForm::getResetPasswordFormSchema()` e `getPasswordResetConfirmFormSchema()` avevano solo `minLength(8)` | `->rule(Password::default())` (la policy di `PasswordData`, che include `uncompromised`). Guardia `Modules/User/tests/Unit/Filament/Schemas/PasswordResetUsesPasswordPolicyTest.php` (2 schemi, rifiuta `12345678`/`aaaaaaaa`/`abc`, accetta una forte; `Http::fake()` per non dipendere da HIBP): **2 rossi** senza la regola | chiuso |
| L-12 | **403 e 419** davano la pagina nuda di Laravel in inglese («Page Expired», «Forbidden»), senza header, footer né lingua | `resources/views/errors/{403,419}.blade.php` generiche; DCP-151 aveva coperto solo il 404 | nuova vista `Modules/Fixcity/resources/views/errors/access.blade.php` (accesso + home, lingua della richiesta, mai il messaggio dell'eccezione), testi `error_pages.expired|forbidden|login|home` in it/en/de/es, gestore `HttpException` 403/419 in `bootstrap/app.php` con lo stesso filtro del 404 (estratto in una closure, nessun elenco duplicato); API, Livewire, JSON e back office restano standard. Guardia `Modules/Fixcity/tests/Feature/Pages/ThemedAccessErrorPagesTest.php` (8 test): **7 rossi** con la mappa degli stati svuotata (resta verde solo il caso API/JSON, giusto così); `ThemedNotFoundPageTest` 7/7 invariato | chiuso. Prova visiva sul dev: `POST /it|de/auth/logout` senza token dà un **419 vero** con titolo, header e footer del tema, in lingua, a 375 e 1440 px senza overflow e con i pulsanti a 48 px. Axe sulla pagina non eseguito |
| L-13 | FAQ, pratiche della segnalazione e trascrizione video: `aria-expanded="true"` su pulsanti `collapsed` con pannello chiuso (12 su 12 nelle FAQ), stato invertito fino al secondo clic; in più 12 icone con `href="…sprites.svg#"` vuoto (audit F, UX-01 e UX-07) | `content-page/sections/accordion.blade.php:24` (la nota in testa lo prescriveva «per la parity» col riferimento), `report-area/section-practices.blade.php:44`, `event-detail/design-comuni.blade.php:154`. Nessun test imponeva il `true` (`BootstrapItaliaComponentsTest` controlla solo che l'attributo ci sia) | `aria-expanded="false"` nei tre template, icona `it-expand` (presente nello sprite), nota in testa riscritta. Guardie: `tests/Unit/CollapsedButtonsStartCollapsedTest.php` (nessun pulsante `collapsed` con `true` nelle viste; **rossa** con il `true` reintrodotto) e spec `tests/browser/faq-accordion-aria-expanded.spec.mjs` (chiuso = `false`, clic = `true` + `.show`, secondo clic = `false`; **rossa** con 12 pulsanti sbagliati se si reintroduce) | chiuso |
| L-14 | Focus da tastiera sulle stelle di valutazione: anello arancione 2 px = **2,14:1** su bianco (minimo 3:1, WCAG 1.4.11) (audit F, UX-12) | MISURATO: il radio è 1×1 px, l'indicatore visibile è il `box-shadow` della label, scritto da `design-comuni/components.css` (copia minificata del riferimento) con `!important` | regola in `resources/css/app/20-dc-components.css` con `!important` e specificità maggiore: anello bianco + `#1a1a1a`. Rimisurato col browser reale: da `rgb(255,153,0) 2px` a `rgb(255,255,255) 2px, rgb(26,26,26) 5px`. Seconda prova della stessa spec: **rossa** col manifest precedente | chiuso |
| L-15 | Suite Sixteen Unit con **25 rossi** (baseline 26): 18 in `ComuneControllerTest` e 7 altrove | **Triage prima di toccare**. (a) `HeaderAreaPersonaleLinksContractTest` (3 test), `FrontofficeRoutesAndSearchSuggestionsTest`, `AuthLoginStyleContractTest`: puntavano a file spariti con "header v1 unico template" (`e4a7e412`) e col mirror `6cb55f0d` (`header/{authenticated,guest,user-dropdown}`, `sections/header/partials/{nav-primary,nav-secondary,personal-area-guest-parity}`, `sections/search-modal.blade.php`, `pages/auth-login.blade.php` ridotto a un alias di una riga). Verificato che **nessun codice vivo li richiama**: `header/v1.blade.php` include `language-switcher`, `user-dropdown` e `personal-area-login-cta`, tutti presenti, e il modal vivo (`search-modal/design-comuni`) è guidato dal CMS (`$d('suggestions')`). Quindi **non ho ripristinato nulla**: i test ora guardano i file vivi (consumer `v1.blade.php`, `$storedNavHref` → `FrontofficeUrl::fromStoredUrl`, blocco `pages/auth/login.blade.php`) e i divieti sulle chiavi legacy (`FrontofficeUrl::personalArea`, `ui::ui.profile`) valgono per i file vivi. (b) `VoltFragmentNamesAreUniqueTest`: **difetto vero**. Il mirror `6cb55f0d` ha aggiunto `pages/tests/pages-view-reference.blade.php` con `@volt('pages.view')`, lo stesso nome di `pages_old/pages/[slug]` e `pages_old1/pages/[slug]`: Volt scrive il frammento in `storage/framework/views/<md5 del solo nome>` e una compilazione completa mette per ultimo `pages_old`, quindi la pagina viva perde il frammento (`data-dc`) | (a) in `HeaderAreaPersonaleLinksContractTest` 2 test riscritti sul consumer vivo, 1 sostituito (le varianti legacy → v1 e partial vivi, con gli stessi divieti) e **1 tolto**: «legacy header user-dropdown usa named route Folio» leggeva `components/header/user-dropdown.blade.php` (file inesistente) e le sue asserzioni sul partial vivo sono già nel test «user-dropdown usa FrontofficeRoutes…» (il partial vivo non usa `route('notifications')` ma `FrontofficeRoutes::personalAreaNotifiche`); in `FrontofficeRoutesAndSearchSuggestionsTest` e `AuthLoginStyleContractTest` cambia solo il percorso letto. PHPStan zero nei 4 file (due `glob()` non Safe sostituiti da `Finder`). (b) nomi dei due file morti rinominati `pages-old.view` e `pages-old1.view` (stessa convenzione `pages-old.*` di `0b1a4486`, nessun file cancellato). Suite: **18 failed, 4 skipped, 432 passed**, tutti i rossi restanti in `ComuneControllerTest` (`RouteNotFoundException`, cluster morto: decisione se cancellarlo) | chiuso. Restano morti e referenziati solo da test: `app/Presentation/Search/FrontofficeSearchSuggestions.php` e `components/header-comune.blade.php` (nessun consumer vivo) |

**Lezione (L-13).** Un commento nel template che dice «replicato tale e quale per la parity» non è un requisito: prima di toccare si cerca se un test
lo impone (qui nessuno), poi si misura l'effetto per chi usa un lettore di schermo. Un difetto del riferimento copiato non diventa un contratto.

**Lezione (L-12).** Per la prova inversa su un file condiviso (`bootstrap/app.php`) ho lavorato su una copia di backup e l'ho rimessa con `cp` subito dopo la
prova, controllando `php -l` e il conteggio delle righe: è il metodo di DCP-151 («non sostituire il file vivo senza copia»). Il rischio resta con un altro agente
che scrive lo stesso file nello stesso minuto.

**Lezione (L-08/L-09).** I mirror «.» dell'altra macchina sono la fonte ricorrente di queste regressioni: cancellano file ancora importati e riportano provider
vecchi, e nessun test lo dice finché non si fa `npm run build` o si apre la pagina. Le tre guardie nuove (`WizardIncludesResolveTest`,
`CookieConsentWiringTest`, `PhpSourcesCompileTest`) rompono al ritorno del difetto; il remoto a monte è ancora nello stato sbagliato.

### Audit F (UI/UX, accessibilità, responsive, prestazioni): 4 high, 9 medium, 12 low

Rapporto dell'agente, ID `F-nn` rinominati `UX-nn` per non collidere con gli `F-nn` della sessione 85. Le cause segnalate si riproducono prima di correggere
(UX-12 l'ho riprodotta: la causa dell'agente, «focus ring arancione», era giusta ma l'origine era un `!important` nel CSS del riferimento, non nel tema).

| ID | Sev | Cosa | Stato |
|---|---|---|---|
| UX-01 | high | FAQ: `aria-expanded` invertito | **chiuso** (L-13) |
| UX-07 | medium | FAQ: 12 icone con frammento vuoto | **chiuso** (L-13) |
| UX-12 | medium | stelle: focus 2,14:1 | **chiuso** (L-14) |
| UX-02 | high | wizard `/tickets/create` loggato: **CLS 0,44 desktop, 0,49 mobile**. MISURATO da me a 390 px: due shift (0,184 a 0,7 s e 0,308 a 1,4 s). Il primo è la barra cookie in modalità `data-layout="flow"` (`position: static` in cima, 155 px, decisa dallo script `place()` di `Modules/Gdpr/resources/views/cookie-consent/index.blade.php` quando la barra fissa coprirebbe un controllo del primo schermo) che spinge giù header e main; su `/it` è `fixed` e non c'è salto. Il secondo è il contenuto del wizard nascosto con `x-cloak`/`hidden` fino all'idratazione (footer da 584 a 0) | **aperto**: è una scelta di progetto del modulo Gdpr (la barra in flusso evita di coprire i controlli, WCAG 2.4.11) più il rendering lato client del wizard; correggere vuol dire decidere `place()` prima del primo paint (script sincrono) e dare altezza minima/render server allo step attivo. Serve una story propria con la prova a 390 px |
| UX-03 | high | payload: CSS 3,4 MB (`app-*.css` 3,34 MB, 270 KB gzip), JS 1,47 MB (mappa Leaflet e asset Filament anche su `/auth/login`), 7 CSS bloccanti | **aperto**: purge, bundle per rotta, `theme-map` solo dove c'è una mappa |
| UX-04 | high (GDPR) | `www.gravatar.com` caricato prima del consenso (blog, dettaglio ticket, header loggato); l'hash md5 dell'email è nell'HTML | **aperto, decisione CMT-022**: proxy lato server o iniziali locali, oppure citarlo nell'informativa e nella CSP |
| UX-09 | medium (GDPR) | tile `*.tile.openstreetmap.org` prima di ogni scelta: la barra dice «solo cookie tecnici» senza citare i terzi | **aperto, decisione del titolare** |
| UX-05 | medium | «Demo» nella fascia slim dell'header (`config/local/fixcity/database/content/sections/header.json:27`) | **aperto**: serve il nome della regione dell'ente o togliere il blocco (anche D35 della parity) |
| UX-06 | medium | `forced-colors`: logo bianco su Canvas, `.btn` senza bordo (esiste già una regola in `20-dc-components.css`, DCP-291 FO-5-20, «da rimisurare») | **aperto**: va provato con Windows High Contrast reale, non solo con l'emulazione |
| UX-08 | medium | immagini senza `width/height`/`srcset`, PNG ingrandite, `map-placeholder.svg` da 612 KB nel blog, niente `loading="lazy"` in blog/eventi | aperto |
| UX-10 | medium | asset con `max-age=3600`, senza `ETag` né compressione (dev); produzione da verificare | aperto, server |
| UX-11 | medium | target sotto 24 px (`a.category` 21, «Leggi tutto» 21), testo sotto 14 px, pulsante cerca 36×36 | aperto: tocca il layout, da fare con screenshot |
| UX-13 | medium | 632 viste Sixteen cancellate da `483080dcac`, 26 nomi ancora citati dal codice (esempio `<x-banner>` nel Blog) | **aperto**: la guardia sui wizard (L-08) è un primo passo; serve lo stesso controllo per `@include`/`<x-…>` di tutto il tema |
| UX-14…UX-25 | low | `heading-order` del footer, landmark senza nome (`it-header-slim-right-zone`), `aria-label="breadcrumb"` in inglese, `role="group"` sul form del wizard, «Vai ai contenuti» che punta al breadcrumb, **`public_html/favicon.ico` da 0 byte**, `info.php.old` (con `phpinfo()`) e altri `.old` nel docroot, copy «pratiche»/«segnalazioni», refuso `title-snall-semi-bold` (che però è la classe del riferimento: non rinominare senza controllare il CSS) | aperti, elencati nel rapporto grezzo |

Non verificato dall'agente (e quindi non in questa story): tecnologie assistive reali e Windows High Contrast reale, mappe e avatar senza rete, wizard oltre lo
step 1 (scrive ticket nel DB), tempi reali (il dev ha Debugbar e carico alto), compressione e cache in produzione, lingue `en/de/es` (solo `it` auditato).

## Verifica

Aggiornamento a fine secondo giro (2026-11-11, macchina condivisa, dev `:8081`):

| Comando | Esito |
|---------|-------|
| `phpstan analyse` (config unica, senza `--level`) su `bootstrap/app.php`, `UserForm`, `GdprServiceProvider` e i 6 test nuovi o toccati | **0 errori** |
| `pint` sugli stessi file | passed |
| `pest-scratch-db.sh` su `CookieConsentWiringTest`, `PasswordResetUsesPasswordPolicyTest`, `ContentLocaleParityTest`, `ThemedAccessErrorPagesTest`, `ThemedNotFoundPageTest`, `CollapsedButtonsStartCollapsedTest`, `WizardIncludesResolveTest`, `PhpSourcesCompileTest` | **29 passed (332 asserzioni)** |
| Playwright `header-nav-aria-hidden.spec.mjs` + `faq-accordion-aria-expanded.spec.mjs` | 3/3 + 2/2; con il difetto reintrodotto rosse |
| `npm run build` in `Themes/Sixteen` | verde, manifest ricostruito (`app-DKQf7xRw.css` dopo L-14; un build successivo delle 21:45 UTC lo ha portato a `app-C5moBbs-.css`, servito da `/it` e con la regola di L-14 dentro: 1 occorrenza nel sorgente e nel CSS compilato) |
| `pest-scratch-db.sh Themes/Sixteen/tests/Unit` (suite completa, 73 s) | prima del triage L-15: 25 failed, 4 skipped, 426 passed. **Dopo: 18 failed, 4 skipped, 432 passed (2866 asserzioni)**; i 18 rossi sono tutti `ComuneControllerTest` (`RouteNotFoundException`, cluster morto). Baseline iniziale: 26 failed / 447 passed (il totale scende perché `ResolvePageHeadActionTest` è sparito con la migrazione della head a `cms::head`) |

Prima parte (primo giro), a lavori ancora in corso:

| Comando | Esito |
|---------|-------|
| `curl /sitemap.xml` + `grep` delle 4 pagine | it/en/de/es presenti; 2116 `<loc>` |
| `pest-scratch-db.sh Modules/Fixcity/tests/Unit/ContentLocaleParityTest.php` | 5 passed (211 asserzioni); con de/es tolti da `mappa-sito`: 3 failed |
| `pest-scratch-db.sh Themes/Sixteen/tests/Unit/NoSemanticFolioPageDirectoriesTest.php` + `DesignComuniIconSpriteIsDeployableTest.php` | 5 passed (14 asserzioni) |

Da fare: `php artisan view:cache`, PHPStan (`./vendor/bin/phpstan analyse Themes/Sixteen`, senza `--level`), Pint sui PHP
toccati, suite Pest completa del tema, specifiche `Themes/Sixteen/tests/browser`, `go-live-gate.py` (lo lancia la sessione 85 a
macchine libere). Nota: `bashscripts/tools/heavy-slot.sh`, citato nella memoria `pest-scratch-db-needs-config-cache-bypass`, non
esiste più (stessa sync di F-03); `pest-scratch-db.sh` funziona anche senza. (Aggiornamento: `heavy-slot.sh` è stato ripristinato, vedi F-09.)

## Difetti sessione 85 (sweep anonimo 4 lingue, sprite icone, deploy, gate)

Decisioni di questa sezione, alternative scartate e differenze rispetto all'area loggata: discussion [#95](https://github.com/laraxot/theme_sixteen_fila5/discussions/95) (le decisioni dell'area loggata sono nella [#94](https://github.com/laraxot/theme_sixteen_fila5/discussions/94), l'head nella [#93](https://github.com/laraxot/theme_sixteen_fila5/discussions/93)). Story di dettaglio: [icon-sprite-deployability](icon-sprite-deployability.story.md) (F-01/F-02), [home-news-grid-specificity](home-news-grid-specificity.story.md) (F-06).

Sessione Claude `base-fixcity-fila5-85`, claim 2026-11-11 ~20:45, d'accordo con `-fe`: qui sono le misure dello sweep browser
anonimo (128 URL × 1440 e 375 px, `it/en/de/es`), lo sprite delle icone, `deploy.sh` e i gate di messa in produzione. L'area
loggata, B3 e B4 restano di `-fe`. Il filone `<head>` → `cms::head` è **solo documentazione**:
[story del Cms](../../../../Modules/Cms/docs/stories/head-sixteen-layout-migration.story.md). Una prima story di questa sessione
(`front-office-production-audit.story.md`) duplicava questa: i suoi difetti sono qui sotto.

| ID | Difetto | Causa verificata | Correzione e prova | Stato |
|---|---|---|---|---|
| F-01 | `sprites.svg` rispondeva **404 su ogni pagina** (percorso diretto `/themes/Sixteen/design-comuni/…` e rotta `/{lingua}/assets/…`): sparivano le icone di header, footer, ricerca e menu utente, senza alcun errore visibile | lo sprite in `public/` era escluso **due volte** da `Themes/Sixteen/.gitignore`: la riga esplicita e `dist/` (riga 11), che esclude la cartella. `deploy.sh` non esegue il build del tema e serve `public/` com'è: un checkout pulito non aveva il file | tolta la riga; riaperti in `.gitignore` tutti i livelli fino al file (git non rientra in una cartella esclusa). Prova: `git check-ignore -q` exit **1** (non ignorato) e il file compare tra i non tracciati; su una copia con le sole regole di prima exit **0** | chiuso (il file va ancora committato: nessun commit senza richiesta) |
| F-02 | `deploy.sh` verificava solo `manifest.json` del tema: un deploy senza sprite andava online senza icone e senza errori | lettura di `deploy.sh` e di `prepare-static-assets.mjs` (scarica lo sprite da jsdelivr solo se manca) | `test -f` sullo sprite subito dopo quello del manifest, prima di `php artisan down` (il sito non entra in manutenzione per un deploy che poi si ferma). Test `Themes/Sixteen/tests/Unit/DesignComuniIconSpriteIsDeployableTest.php` 3/3; `ModulePublicAssetsLinksTest` (Xot) 3/3 | chiuso |
| F-03 | `bashscripts/tools/release/go-live-gate.py` e `tools/security/production-checklist.sh` **non esistevano più** (DCP-154, 176, 200, 231, 280 e 282 li citano come cancelli) | li ha cancellati il merge di sync `1af871d0c` del repo annidato `bashscripts` (2026-11-11 15:18, «adotta le cancellazioni dell'altro lato»); l'ultima versione buona è nel suo primo genitore `f7a39cc23`. Con loro erano spariti anche `tools/pest-scratch-db.sh` e `tools/compliance/lg-design-scan.py`, che il gate richiama | ripristinati i 4 file da `f7a39cc23` **solo dove mancavano** (nessuna sovrascrittura), identici al sorgente (`cmp`), sintassi valida; `go-live-gate.py --self-test` exit 0 (10 configurazioni sbagliate segnalate). Il gate contro il dev non è ancora stato misurato: sotto carico 40 ha superato i 540 s | ripristinato, **non committato**; il gate completo si lancia a macchine libere. **Decisione dell'utente da confermare su `pest-scratch-db.sh`**: la memoria `sync-commits-markers-and-adopts-other-machine-deletions-1af871d0c` (punto 3) lo dà per tolto di proposito il 2026-10-28 («non gira su Fixcity»); è stato rimesso perché i test Pest di questo audit lo usano per isolare il DB di sviluppo (righe «Verifica» sopra: 5 passed) e perché `go-live-gate.py` lo richiama nello stadio Pest. Se la decisione resta «tolto», va tolto di nuovo (file non tracciato, `cmp` identico a `f7a39cc23`) e il gate va lanciato con `--skip-pest`. Finché non committato, la sync del repo annidato (commit `.`) lo adotterebbe comunque: non lasciarlo a metà |
| F-04 | 8 database scratch residui di fixture precedenti (`scratch-auth-fixture.sh status`) | `status`: «nessuna fixture attiva, DB scratch residui: 8» | nessuna: sono DB, si ripuliscono solo con conferma dell'utente | aperto |
| F-05 | carico macchina 30-40 (altri agenti, ollama, ~70 processi Chrome): le misure di tempo di questo giro non sono affidabili | `uptime` | nessun giudizio sulle prestazioni da questo giro; la misura va rifatta a macchina libera | info |
| F-06 | Home, blocco «Novità»: a 1440 px **2 card per riga** con la terza sola sotto e metà larghezza vuota; a 800 px una sola per riga (UI) | il CSS compilato scopa Bootstrap con `body:has(.page-content[data-dc]) .row` (specificità 0,3,1) e batte le regole del blocco (`display:grid`, `padding:0`, `width:auto`), che non si applicano mai; il suo `gap: 1.5rem` invece sì e **si somma** al gutter `g-4`: 3×440 + 2×24 = 1368 px in una riga da 1320. Misurato con `getComputedStyle` (riga `display: flex`, colonna `padding: 0 12px`) | tolte da `blocks/home/news.blade.php` le due regole e il `gap`: il layout lo dicono le classi del markup (`row g-4`, `col-md-6 col-xl-4`). Spec `tests/browser/home-news-grid.spec.mjs`: **rossa** prima (1440: 2 invece di 3; 800: 1 invece di 2), **verde 3/3** dopo (3, 2 e 1 per riga a 1440, 800 e 375 px); verificato a occhio. Lezione scritta in `docs/architecture/css-scoping-rule.md` | chiuso |
| F-07 | Dettagli blog ed eventi in `en/de/es` (12 pagine misurate): corpo in italiano, **nessun `robots`**, canonical su sé stessi: indicizzabili come duplicati dell'italiano (SEO) e senza `lang="it"` sul testo (WCAG 3.1.2) | l'`<head>` di Sixteen mette `noindex` solo ai percorsi privati; `cms::head` calcola «tradotto» solo per i `Page` del CMS (`Head.php:67-68`), un dettaglio da modello ha `writtenLocales = null` e risulta tradotto: **uguale con o senza la migrazione**, non è una regressione di parità (nota nella [story del Cms](../../../../Modules/Cms/docs/stories/head-sixteen-layout-migration.story.md)) | non corretto: è una decisione di prodotto (tradurre i contenuti, o far valutare «tradotto» anche ai dettagli da modello e dare `noindex`/`lang`) | **aperto, decide il PO** |
| F-08 | Home `/en`: nelle 3 card notizia il titolo è inglese e l'**estratto italiano** (la stessa card mescola due lingue); in `de/es` tutto il contenuto del catalogo è italiano | `Modules/Blog/database/seeders/Demo/DemoArticleCatalog.php`: `title` e `body` hanno `it`/`en`, `summary` è **una stringa sola** (`@return … summary: string`), e non esistono `de`/`es` | proposta: `summary` come `{it,en}` (e `de`/`es` se servono). **Non corretto e seed non rieseguito**: sono dati demo e il DB di sviluppo non si tocca senza richiesta; i contenuti reali del sito li scrive la redazione | aperto, bassa (dati demo) |
| F-09 | `bashscripts/tools/heavy-slot.sh` (limitatore `flock` dei comandi pesanti) sparito: l'ordine permanente degli agenti e `root-script-policy.md` lo indicano come obbligatorio, con carico 35 serve davvero | stessa sync `1af871d0c`; in quel repo il file era già stato cancellato e riaggiunto più volte (`c4451393a`, `3a5b6d7e3`, `f7a39cc23`): rimbalzo fra le due macchine | ripristinato da `f7a39cc23` (32 righe, solo dove mancava, `cmp` identico), `bash -n` OK, prova con un comando dentro lo slot exit 0 | ripristinato, **non committato**; potrebbe essere cancellato di nuovo dalla sync |

**Lezione (F-01).** La prima versione del test confrontava una riga del `.gitignore`: era verde mentre git ignorava ancora il file
(la riga era tolta, ma restava `dist/`). Il test ora chiede a git (`check-ignore -q`) e si rompe su una copia con le sole regole
vecchie. Un test che legge il file di configurazione invece di interrogare lo strumento che lo applica può dare falso verde.

**Lezione (F-03).** Un comando di storia che non trova nulla non prova che il file non sia mai esistito: avevo cercato con un
percorso relativo alla radice nel repo sbagliato. Il file era nel repo annidato, con percorso relativo a quello.

**Lezione (F-06).** «Le immagini mancano» era un'ipotesi sbagliata: nello screenshot le card avevano un buco in cima. La prova
(immagine portata in vista, attesa fino a 25 s) ha mostrato che l'immagine carica in 2,8 s: era il caricamento lazy sotto carico 35, non un
difetto. Il difetto vero era sotto, nel layout. Una cattura che sembra un bug va riprodotta prima di correggerla.

### Esito dello sweep anonimo (128 URL × 1440 e 375 px, `it/en/de/es`, dev `:8081`, 2026-11-11)

| Controllo | Esito |
|---|---|
| Stati HTTP | 120 × 200 e 8 × 404 voluti (pagina e ticket inesistenti, in 4 lingue, a tema, titolo tradotto); **0 × 5xx**, su entrambi i viewport |
| `<title>`, description, canonical, `<h1>` | 0 mancanti o duplicati nella stessa pagina; `<html lang>` sempre coerente con la lingua dell'URL. **Misurato con l'`<head>` inline, prima delle 21:13 UTC**: dopo il passaggio di `main.blade.php` a `<x-cms::head>` canonical e hreflang mancano sulle pagine `noindex` (vedi sotto, «Go-live gate sul dev» e D-7 nella [story del Cms](../../../../Modules/Cms/docs/stories/head-sixteen-layout-migration.story.md)) |
| Immagini senza `alt`, chiavi di traduzione grezze, overflow orizzontale a 375 px | **0 / 0 / 0** |
| Percorsi privati da anonimo (`tickets/create`, `area-personale/*`) | redirect al login **nella stessa lingua**, nessun dato esposto |
| Errori di console e asset in 4xx | tutti spiegati: 33 (desktop) e 32 (mobile) pagine con lo sprite in 404, cioè F-01 **prima** della correzione, più gli 8 404 voluti; **altri: 0**. Dopo la correzione, 95 e 96 pagine consecutive con 0 asset in errore e 0 errori di console non-404 |
| Italiano nelle pagine non `it` | 33 pagine: contenuti editoriali (home, blog, eventi) → F-07 e F-08 |

### Go-live gate sul dev (`go-live-gate.py --base http://127.0.0.1:8081 --skip-pest`, 2026-11-11 ~21:20 UTC, tramite `heavy-slot.sh`)

Esito: **NON pubblicabile** — 11 controlli bloccanti falliti, 5 requisiti LG non soddisfatti, 2 avvisi, 16 superati su 34.
**Secondo giro (~21:45 UTC, dopo il rebuild del tema e i ripristini di `-fe`, carico sceso a 11): identico**, 0 controlli cambiati di esito e stessi dettagli
(`LG-03`/`LG-04` ancora 12 su 48); anche il diff di parità dell'head (128 URL) dà le stesse 4 voci. Quindi il rebuild non ha peggiorato né risolto nulla di
questo elenco, e `LG-03`/`LG-04` dipendono solo da D-7, non dal tema.

| Gruppo | Controlli | Lettura |
|---|---|---|
| Ambiente di destinazione | `ENV-01..06` FAIL (`APP_ENV=local`, `APP_DEBUG=true`, `DEBUGBAR_ENABLED=true`, `APP_URL=http://fixcity.local`, `MAIL_FROM_ADDRESS=noreply@fixcity.local`, `SESSION_SECURE_COOKIE` non impostato); `ENV-08` avviso (sendmail, recapito esterno non provato); `NET-01` FAIL (serve un'origine https) | **attesi sul dev**: il gate legge il `.env` del dev; vanno rilanciati sul `.env` di destinazione (DCP-154/155) |
| Repository | `REPO-02` FAIL: 94 `.md` con data o suffisso numerico nel nome (es. `validation-report-20260127-095021.md`); `REPO-01` PASS (0 marker di merge) | debito reale, DCP-201 |
| Contenuti dell'ente | `LEG-04` FAIL: 12 testi legali su 20 con l'avviso di contenuto incompleto (es. `de/privacy`, `de/note-legali`, `en/accessibilita`); `LG-02` FAIL: contatto del DPO assente in 40 combinazioni su 48 | dati/testi dell'ente (DCP-130, 162, 164) |
| Linee guida | `LG-06` data di ultimo aggiornamento 35/48, `LG-07` valutazione della pagina 40/48, `LG-08` licenza dei contenuti 41/48 FAIL | già tracciati (DCP-167, 169, 170) |
| **`LG-03`/`LG-04`** | canonical e hreflang mancanti in **12 combinazioni su 48** | **nuovo**: regressione dell'`<head>` migrato, non del dev; D-7 (decide il PO, **aperta**: causa `ResolveRobotsPolicyAction` + `Head`, proposta e alternativa in coda a `Modules/Cms/docs/stories/head-sixteen-layout-migration.story.md`, requisito DCP-166 / Linee guida 4.3.9). `Modules/Cms/app` non si tocca finche' il PO non risponde |
| Pest | `LEG-01` FAIL «NON eseguita (--skip-pest): una guardia saltata non è verde» | volutamente saltato qui; va eseguito con `pest-scratch-db.sh` (vedi nota su F-03) |

Da chiudere, per responsabile: sviluppo 7, fornitore 7, titolare 2, ente (testi) 2, ente (dati) 1. Superati, tra gli altri: nessun marker di merge, nessun
file di debug nella radice web, `APP_KEY`, `SECURITY_HEADERS_ENABLED`, `SECURITY_CSP_MODE`, tenant, home 200 in 4 lingue, nessun dato di prova dell'ente nelle home,
nessuna risorsa di terze parti non dichiarata, `robots.txt`, `sitemap.xml`, link all'informativa su ogni pagina, meta description ovunque.
`security.txt` risponde 404 (avviso `SITE-04`, DCP-155).

Limiti dichiarati: solo utente anonimo (il loggato è di `-fe`); i tempi non valgono (F-05); lo sweep non giudica il design, per questo ho guardato gli
screenshot (home `it` a 1440 px, prima e dopo); axe non l'ho rilanciato io. Sul dev restano visibili la Debugbar e la chiamata `POST /_boost/browser-logs`
(`APP_DEBUG=true`): in produzione devono sparire, e lo verifica il controllo `ENV` del gate sul `.env` di destinazione (da lanciare a macchine libere).

## Documenti BMAD/ECC creati da questo audit (2026-11-12)

Richiesta dell'utente: «documenta tutto dentro le cartelle docs dentro i moduli e dentro i temi, crea tutti i documenti per bmad ed ecc (stories, brainstorming, planning)». Tutti portano il link alla discussion [#94](https://github.com/laraxot/theme_sixteen_fila5/discussions/94) nel campo `discussions`.

| Documento | Cosa raccoglie dell'audit | Stato |
|---|---|---|
| [DCP-447](dcp-447-tema-ricostruibile-e-guardie-contro-i-mirror.story.md) | L-05, L-08, L-15 (b): 13 PHP rotti, file importati cancellati, nome Volt doppio, con le 4 guardie | fatta; resta aperto il remoto a monte |
| [DCP-448](dcp-448-accessibilita-del-front-office-aria-faq-focus-e-menu.story.md) | L-07, L-13, L-14, L-10: menu, FAQ, focus delle stelle, `aria-label` tedesco | fatta |
| [DCP-449](dcp-449-salto-di-layout-del-wizard-tickets-create.story.md) | UX-02: CLS del wizard (barra cookie in flusso, contenuto nascosto fino all'idratazione) | aperta |
| [DCP-450](dcp-450-peso-e-cache-degli-asset-del-tema.story.md) | UX-03, UX-10: peso di CSS e JS, cache | aperta |
| [DCP-451](dcp-451-guardia-sui-riferimenti-a-viste-cancellate.story.md) | UX-13: 26 nomi di viste ancora citati dopo `483080dcac` | aperta |
| [DCP-452](dcp-452-residui-di-ux-accessibilita-e-messa-in-produzione-del-tema.story.md) | UX-05, UX-06, UX-08, UX-11, UX-14…25; DEC-71 («Demo»), DEC-72 (favicon e dati dell'ente) | aperta |
| [DCP-453](dcp-453-ritiro-del-codice-morto-del-tema-decisione.story.md) | L-06, L-15: 18 rossi di `ComuneControllerTest`, `pages_old*`, `header-comune`, `FrontofficeSearchSuggestions`; DEC-70 | da decidere |
| [DCP-454 (Gdpr)](../../../../Modules/Gdpr/docs/stories/dcp-454-terze-parti-prima-del-consenso-gravatar-e-openstreetmap.story.md) | UX-04, UX-09: Gravatar e tile OpenStreetMap prima del consenso; DEC-69 | da decidere |
| [Decisione: i test seguono i file vivi](../bmad/decisions/contract-tests-follow-live-files.md) | L-15 (a) | presa dalla sessione del triage, da rivedere |
| [Brainstorming](../bmad/ux-performance-consent-brainstorming.md) | domande, opzioni e rischi dei cinque filoni aperti | bozza |
| [Piano a onde](../plans/front-office-ux-performance-backlog-plan.md) | ordine proposto: decisioni, guardie, misure, modifiche | proposta |

I numeri delle story vengono dal registro DCP-200 (DEC-69…72) e dalla numerazione per ora di creazione del file. Le story DCP-455…457 del modulo Fixcity (gate, passo 1 del wizard) non sono di questo elenco.

## Terzo giro (2026-11-12): ospite e utente loggato con lo strumento versionato

Fase BMAD: **Test/Fix** (verifica reale, poi correzione, poi guardia). Sessione Claude, in parallelo alla passata Codex qui sotto: le due non si sovrappongono, questa misura i **difetti muti** (chiavi grezze, meta mancanti, 4xx/5xx) che un test sul sorgente non vede.

**Strumento:** `bashscripts/tools/visual/fo-sweep.mjs` (versionato, `--self-test`, solo GET). Ricava gli URL dalla `sitemap.xml` della base e apre ogni pagina a 1440 e 375 px; registra status, title/description/canonical/hreflang/robots, h1, alt, chiavi grezze, overflow orizzontale, asset in 4xx ed errori di console. Con `--state` usa la sessione dell'utente di prova isolato (`scratch-auth-fixture.sh`, DB scratch), mai l'utente reale.

| Base | URL distinti | Record (URL x viewport) | Anomalie prima | Dopo (riesecuzione mirata sulle pagine corrette) |
|---|---|---|---|---|
| ospite, dev `:8081` | 140 | 280 | 15: 7 chiavi grezze su `/{lang}/tickets/track`, 1 x 500 e 7 marcatori `\|---LINE:n---\|` | `/tickets/track` in 4 lingue: 0 chiavi grezze; 2 x 429 sul freno anti-abuso (vedi sotto) |
| loggato, fixture `:8091` | 116 | 232 | 14: 5 chiavi grezze su `/{lang}/tickets/track`, 8 x `description` assente su `/{lang}/tickets/create`, 1 x 429 | 0 su `track` e su `create` |

### Difetti trovati e corretti

| Id | Difetto (MISURATO) | Causa | Correzione | Guardia |
|---|---|---|---|---|
| T-01 | `/{lang}/tickets/track` mostra le chiavi grezze `fixcity::ticket.track.my_tickets_title` e altre sei, **in tutte e quattro le lingue**, a ospite e a loggato | la vista `components/pages/ticket-track.blade.php` (lavoro in corso dell'altra sessione) cita 7 chiavi che nessun file lang definiva: `login_hint`, `login_link`, `my_tickets_title`, `my_tickets_intro`, `my_tickets_empty`, `my_tickets_sent_on`, `my_tickets_all` | 7 chiavi aggiunte in `Modules/Fixcity/lang/{it,de,es}/ticket.php` e `en/ticket_track.php` (additive, dopo `not_found_help`) | `Themes/Sixteen/tests/Unit/FixcityTranslationKeysCitedByViewsExistTest.php`: ogni chiave letterale `fixcity::` citata dalle viste esiste in it/en/de/es (`hasForLocale`, senza fallback); i prefissi dinamici sono esclusi. **Prova inversa:** tolta `my_tickets_title` da `de`, rosso con chiave e `file:riga`; ripristinata, verde (5/5) |
| T-02 | da loggato `/{lang}/tickets/create` non ha `<meta name="description">` (8 record su 8) | `tickets/create.json` non aveva il campo `description` localizzato, a differenza di `track.json` | `description` it/en/de/es aggiunta alle **tre** copie del JSON (tenant `config/local/fixcity/.../tickets/create.json`, `Modules/Fixcity/config/database/content/pages/tickets.create.json` e `.../tickets/create.json`) | `TicketCreatePageMiddlewareGuardTest` controllava solo 2 copie su 3 pur dicendo «three copies»: ora le controlla tutte e 3. **Prova inversa:** testo guastato nella terza copia, rosso; ripristinata, verde |
| T-03 | 1 x 500 e 7 marcatori `\|---LINE:n---\|` su pagine che il codice non ha rotto | file compilati in `storage/framework/views` lasciati da un 500 e un `view:clear` di un'altra sessione a metà richiesta (stessa famiglia di DCP-377) | rimossi i 2 file toccati, rigenerati da soli; niente `view:clear` generale | nessuna: e' una gara fra sessioni, non un difetto del sito. Regola nel how-to |

**Non e' un difetto:** `/{lang}/tickets/track` risponde 429 se lo sweep apre 4 lingue x 2 viewport in pochi secondi dallo stesso IP (freno `throttle` di DCP-149/150, voluto). Una pagina in 429 **non e' stata misurata**: lo sweep la segnala come tale (`status 429: freno anti-abuso, pagina NON misurata`), non la dichiara buona. Ripetuta a freno scaduto: 200, nessuna chiave grezza, link «Inicia sesión» tradotto.

**Misura che non ho preso:** nessuna misura di tempo, la macchina era a carico 27-49 (F-05). `ComuneControllerTest` (18 rossi) resta la linea di base nota, non toccata da questo giro.

## Evidenza corrente — passata Codex 2026-11-12

Questa sezione sostituisce ogni risultato storico non riconvalidato. Graphify è stato interrogato prima dell'esplorazione del codice; le pagine Hindsight archiviate sono state verificate contro l'albero corrente.

| Gate | Evidenza | Esito |
|---|---|---|
| Bootstrap | `composer check-platform-reqs`, `php artisan --version`, `route:list --json`, `view:cache` da `laravel/` | pass; PHP 8.4.27, Laravel 13.42.0 |
| Asset tema | `npm run build` da `laravel/Themes/Sixteen` | pass; Vite 8.3.4 |
| Suite Sixteen Unit+Feature | Pest Unit + Feature | **197 failed, 6 skipped, 494 passed, 3539 assertions**; non verde |
| Routing | `folio:list --json` + HTTP su `127.0.0.1:8081` | Folio registra `tickets/create`, `tickets/track` e `area-personale/*`; HTTP guest verificato: home, track, privacy, cookie-policy, ricerca e mappa-sito 200; create e area-personale redirigono al login localizzato. Flussi autenticati e provider reali restano aperti |
| Identità digitale | test sicurezza mirato + route gate | callback SPID/CIE fail-closed senza certificato/JWKS trusted; route disabilitate di default |
| Browser | `a11y-axe.spec.mjs`, guest, 375/768/1440, server `127.0.0.1:8081` | prima passata **23 pass, 12 skip auth, 1 fail** per 429 transitorio sul throttle intenzionale `10/min`; ripetizione isolata di `/it/tickets/track` a 375: pass, 0 violazioni. Fixture autenticata e screenshot non eseguiti |

### Correzioni di questa passata

- I blocchi riusabili di breadcrumb, hero, dettaglio news e conferma segnalazione non emettono più `href="#"` quando manca un URL CMS; i CTA senza destinazione vengono omessi e non vengono inventati valori demo di email, ricevuta o servizi.
- Il CSS del blocco blog è scoped a `.sixteen-blog-index`, non a `main[data-page="lista-risorse"]`, quindi resta riusabile fuori da una pagina specifica.
- I callback SPID/CIE verificano firma trusted e i `return_url` restano sulla stessa origine; sono stati aggiunti test fail-closed.

### Decisione di rilascio

Stato: **NON PRODUCTION READY**. Restano aperti suite rossa, fixture autenticata, screenshot/manual UX, gate dell'ambiente target e verifica di provider/certificati reali.

## Quarta passata — parity responsive header/footer (2026-11-12)

Scope: Sixteen rispetto al markup Design Comuni, a 375/768/1440 px, con utente ospite e stato autenticato rappresentato dal contratto del tema.

- Il caricamento isolato dei JSON CMS durante i test ora si inizializza lazily da `config/<tenant>/database/content`, senza scrivere mai nei contenuti versionati. Questo evita che `x-section` venga verificato senza i blocchi reali di header/footer.
- Il test browser del tracking ora limita il submit al form `.dc-ticket-track__form`; prima selezionava anche i submit della ricerca globale e produceva un falso fallimento.
- Verifica Pest mirata: **21 pass, 218 assertions** (`FooterV1DesignComuniStructureTest`, `HeaderSlimBarContractTest`).
- Verifica browser tracking: **2 pass**, incluse larghezze 320/390/575/576/768/1440 e contrasto a 375/1280. Il `429` multilingua resta il rate limit intenzionale `10/min`, non un difetto responsive.

Resta non certificabile il go-live complessivo: la suite Sixteen completa e i flussi autenticati reali devono ancora essere verdi e il gate va eseguito sull'ambiente di destinazione.

### Evidenza aggiuntiva Codex: parity visiva e responsive

La fixture isolata è stata avviata su `127.0.0.1:8092` con database scratch e storage separato; il login UI reale ha prodotto una
sessione Playwright autenticata. Le pagine del wizard (passo 1, dati/mappa e riepilogo) e dell'area personale (pratiche, seguite,
notifiche, impostazioni) hanno superato axe WCAG 2.1 AA a **375, 768 e 1440 px**: **21/21 pass**, 0 violazioni bloccanti.

Il controllo DOM/screenshot su home ospite e autenticata ha misurato, a tutte le tre larghezze, `scrollWidth === clientWidth` (overflow
orizzontale 0), header e footer presenti, e asset CSS del tema caricati dal manifest Vite. Il menu mobile si apre entro il viewport e
chiude con `Escape`; il menu utente autenticato resta entro il viewport a 375, 768 e 1440 px. Le immagini di controllo sono state
salvate fuori dal repository in `/tmp/fixcity-{guest,auth}-{375,768,1440}.png`.

Durante il controllo è emerso un drift di contratto: «I miei servizi» usava `FrontofficeRoutes::servicesIndex()` mentre il riferimento
Design Comuni e le guardie del tema richiedono la named Folio route `services.categories`. Il partial vivo
`components/sections/header/partials/user-dropdown.blade.php` è stato riallineato alla route canonica; le guardie
`HeaderAreaPersonaleLinksContractTest` e `HeaderV1SlimDropdownContractTest` ora sono **17/17 pass**.

La parità non equivale ancora a certificazione di produzione: restano la suite completa rossa (fallimenti storici da triagiare), il
go-live gate sull'ambiente target, i provider SPID/CIE reali e le prove con tecnologie assistive/rete degradata.

## Quinta passata — parita' chrome e fix /area-personale/servizi (2026-11-19)

Scope: verifica parita' Sixteen ↔ Design Comuni (header/footer/CSS) e responsive 375/768/1440,
piu' chiusura dei 404 residui trovati dal crawl.

### Esiti della verifica

- **Chrome identico su tutte le pagine**: misura DOM computata su 19 URL (home, blog, eventi,
  ticket, track, create, confirmation, privacy, FAQ, ricerca, servizi, lista-categorie,
  mappa-sito, login, register, 404, area personale) — header 222px / Titillium Web / 17 link
  e footer 547px / 17 link ovunque, `data-dc` presente anche sulle pagine `layout: app`
  (ricerca, track, mappa-sito, lista-categorie): nessuna divergenza visiva del chrome.
- **Nessun overflow orizzontale** a 375/768/1440 su 18 screenshot (`scratchpad/fo-audit/shots.js`).
- **Flussi interattivi verdi**: login ok/ko, wizard creazione segnalazione (23 step),
  API tickets/list e geojson 200, track page con input.

### Fix applicato

- **`/it/area-personale/servizi` 404 → 200**: mancava la pagina CMS `area-personale.servizi`
  (le 4 sorelle ce l'hanno). La catch-all `[container0]/[slug0]` del tema risolve via
  `ResolvePageAction` e abortiva 404 prima del blade Volt legacy in `Modules/User`.
  Creato `config/local/fixcity/database/content/pages/area-personale/servizi.json`
  (`design-comuni`, `auth`, it/en/de/es, blocco `content-page` + `cards-simple`).
  Story: `Modules/User/docs/stories/area-personale-servizi-cms-page.story.md`.
  Verifiche: guest 302→login (it/en), auth 200 con H1 "I miei servizi",
  `view:cache` ok, `PersonalAreaPagesTest` 34/34.

### Restano 404 legittimi (rotta non piu' esistente / pagine di test)

`/it/learn`, `/it/genesis/*`, `/it/mobile*`, `/it/prova`, `/it/artisan-commands-manager`,
`/it/dashboard`, `/it/profile/edit`, `/it/notifications`, `/it/errors/password-expired`,
`/it/segnalazione-dettaglio` — nessun link pubblico li raggiunge; sono pagine legacy o di
test non collegate. Nessuna azione: nessun soft-404, il 404 e' genuino.

## Pagina sfuggita: `/it/auth/verify` (2026-11-12)

Segnalata dall'utente: la pagina non e' coerente con Design Comuni. **Causa dell'omissione (MISURATA, non dedotta):**

- La pagina e' del modulo User (`Modules/User/resources/views/pages/auth/verify.blade.php`), non ha override in `Themes/Sixteen/.../pages/auth/` (li' ci sono solo `login`, `register`, `logout`, `password/reset`) e usa `<x-layouts.main>` con markup Tailwind/Breeze (`sm:max-w-md`, `dark:`), senza componenti Design Comuni.
- `middleware(['auth', 'throttle:6,1'])`: da ospite risponde **302 a `/it/auth/login`**, quindi lo sweep da ospite misurava il login, mai la verifica.
- Non e' nella `sitemap.xml` (0 voci `/auth/`) e la lista fissa di `fo-sweep.mjs` aveva solo `/auth/login` e `/auth/register`: il tool puo' vedere solo cio' che gli si elenca, e la coerenza visiva con DC **non e' una sua misura** (DCP-455: strumenti di parita' cancellati).
- Lo sweep verificava assenza di difetti tecnici (chiavi grezze, overflow, asset 4xx, axe), non l'uso del layout giusto.

**Rimedio gia' applicato:** `fo-sweep.mjs` ora include `/auth/verify`, `/auth/password/reset`, `/auth/password/confirm`, `/profile/edit`, `/dashboard`.

**Altre pagine sospette (inventario per grep, NON ancora aperte nel browser):** pagine Folio dei moduli senza override in Sixteen con markup Tailwind e senza componenti DC: `User/auth/verify`, `User/auth/password/confirm`, `User/dashboard/index`, `User/profile/edit`, `User/learn/index`, `User/genesis/about`, `User/genesis/power-ups`, `Fixcity/prova`. Da verificare quali sono raggiungibili e con quale utente; **un 404 o un'incoerenza non si ripara con un file in `Themes/Sixteen/.../pages`** (regola del tema, solo `auth/` e' ammessa): si porta la vista del modulo ai componenti DC o si decide con DEC-70.
**Aperto:** correggere `verify` (e `password/confirm`) e rilanciare lo sweep da loggato; poi giro visivo delle altre sette.
