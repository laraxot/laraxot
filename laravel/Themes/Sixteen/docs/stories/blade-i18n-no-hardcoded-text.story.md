---
title: "Niente testi fissi nelle Blade del tema Sixteen (multilingua it/en/de/es)"
type: story
status: implemented
theme: Sixteen
updated: 2026-10-30
tags: [i18n, blade, multilingua]
---

# Niente testi fissi nelle Blade del tema Sixteen

## Scopo

Il sito risponde in italiano, inglese, tedesco e spagnolo: un testo scritto dentro una Blade esce uguale in ogni lingua. Il `fallback_locale` e' `it`, quindi una chiave mancante in `en`/`de`/`es` non da' errore e mostra l'italiano. Le frasi inglesi di Jetstream usate come chiave (`__('Frase')`) non erano tradotte da nessun file.

## Regola operativa

- Ogni testo visibile passa da `{{ __('pub_theme::contesto.collezione.chiave.tipo') }}`: cinque elementi, dove `contesto` e' il nome del file lingua e `tipo` il genere di testo (`label`, `title`, `description`, `placeholder`, `tooltip`, `helper_text`, `message`, `aria_label`, `alt`; [regola 008](../../../../../docs/wiki/rules/008-translation-5-level-protocol.md)). Mai la frase come chiave, e il traduttore ignora `['default' => ...]`.
- Namespace: solo `pub_theme::`, mai `sixteen::` ne' `two::`. Il CMS mappa `pub_theme` su `Themes/<tema attivo>/lang`: le chiavi si risolvono quando il tema attivo e' Sixteen.
- La stringa va in `Themes/Sixteen/lang/{it,en,de,es}/<contesto>.php`, con le stesse chiavi e gli stessi `:parametri` nelle quattro lingue. `it` e' l'originale al byte; `en`, `de`, `es` sono traduzioni vere. Se l'originale era inglese (scaffold), `en` lo conserva e `it` e' la traduzione.
- Vale anche per `aria-label`, `placeholder`, `title`, `alt`, per i default testuali di `@props` e per i fallback `?? 'Testo'`. Le frasi con variabili usano `:param`; i plurali `trans_choice`; i testi dentro `<script>` `@js(__('...'))`.
- Link a pagine CMS: niente `href="/it/..."`, niente `url('/...')`, niente nomi di rotta inventati per pagina. Si usa il builder `Themes\Sixteen\Infrastructure\Url\FrontofficeRoutes` oppure le rotte generiche della shell Folio, `route('container0.index', ['container0' => 'tickets'])` e `route('container0.view', ['container0' => 'area-personale', 'slug0' => 'notifiche'])`: il prefisso lingua lo aggiunge Folio. I nomi Folio veri (`home`, `login`, `logout`) restano validi. Dettaglio: [link multilingua del front office](../wiki/concepts/fo-folio-links-multilingua.md).
- Non sono errori: marchi e nomi propri, sigle, numeri, valori che arrivano dal modello o dal CMS, `alt=""` decorativo.

## Cosa e' stato fatto

- Rilevatore `bashscripts/tools/i18n/find-hardcoded-blade-text.py` sul tema: da 3.427 occorrenze in 427 file a 1.052 in 136 file (stesso strumento, commit di partenza contro stato attuale).
- 350 Blade convertite in quattro passate: flussi di appuntamento, assistenza, pagamento, area personale, segnalazione e ticket; componenti AGID e Bootstrap Italia; header, footer, ricerca, form, report e comune; layout, Livewire, pagine, navigazione, offline, e-mail; scaffold di profilo e team; blocchi di elenco.
- Creati 41 file lingua per ognuna delle quattro lingue, circa 1.440 chiavi per lingua. Flussi e AGID: `flow_appointment`, `flow_assistance`, `flow_payment`, `flow_personal_area`, `flow_report`, `flow_tickets`, `agid_components`, `bootstrap_italia`, `comune_pages`, `footer_blocks`, `blocks_common`, `blocks_content`, `auth_pages`, `api_tokens`. Componenti: `components_common`, `components_header`, `components_footer`, `components_reports`, `components_municipal`, `components_content`, `components_forms`, `components_search`. Layout e pagine: `common`, `municipal_components`, `navigation_components`, `pages_components`, `ui_components`, `utilities_components`, `home_components`, `layouts_components`, `offline_page`, `appointment_form`, `emails_christmas`, `account_menu`, `auth_components`, `legacy_homepage`. Scaffold e liste: `account_forms`, `listing_blocks`, `footer_full`, `legacy_prototypes`, `tickets_list`. In piu', `404` per `de` ed `es`.
- Scelte: terminologia fissa per «segnalazione» (en Report, de Meldung, es Incidencia); frasi di consenso con link in una chiave con `:terms`, `:privacy`, `:rules`; valutazione a stelle con `trans_choice`; circa 170 default di `@props`/`@php` portati a `__()`; correzioni di accessibilita' (ruoli ARIA, `sr-only`, bersagli minimi) mantenute.
- 77 link con lingua fissa portati a `route()` (vedi «Link e namespace»).

## Link e namespace

- Link: dei 77 link, 62 chiamate `route('tests.view'|'tests.index', ...)` in 18 Blade sono state portate a `route('container0.view', ['container0' => 'tests', 'slug0' => ...])` e `route('container0.index', ['container0' => 'tests'])`. Un nome per pagina presuppone una pagina Folio per dominio, vietata dalla regola sulle directory semantiche. Residuo `tests.*`: 0.
- Namespace: 23 chiamate `__('sixteen::...')` in 14 Blade sono diventate `__('pub_theme::...')`. `sixteen::` e' registrato dal provider ma lega la vista a un solo tema; in Sixteen `pub_theme` punta alla stessa cartella `lang`, quindi la risoluzione non cambia.
- Restano, per classe, contati dal rilevatore e non corretti: nomi di rotta di pagina richiesti da un test di contratto dell'header (31), URL fissi in `url('/...')` (74), nomi di rotta che nessun file definisce (66).
- Forma da preferire in Sixteen: `Themes\Sixteen\Infrastructure\Url\FrontofficeRoutes`, che incapsula `container0.view` e `container0.index`.

## Residui dichiarati (1.052 occorrenze, stima per categoria)

| Categoria | N |
|---|---:|
| Viste di prototipo, legacy o demo non instradate (pagine vecchie, template, prove, dump) | circa 630 |
| Direttive Blade, frammenti di codice e binding Alpine: falsi positivi | circa 190 |
| Dati di esempio, segnaposto e contenuti mock (indirizzi, e-mail, codici pratica, elenchi di prova) | circa 120 |
| Marchi e nomi di servizio (social, VISA, PagoPA, OpenStreetMap) | circa 100 |
| Autonimi di lingua (Italiano, English) | circa 13 |

Testi in PHP: non cercati in questa campagna sul tema, quindi non toccati.

## Limiti

- Nessuna verifica a video: la resa, `@js()` nei template JavaScript e la risoluzione di `route()` con il prefisso lingua non sono state provate; le traduzioni de/es non sono riviste da un madrelingua.
- Alcuni link restano nella forma precedente, per classe in «Link e namespace»; percorsi con lingua fissa scritti dentro `{{ }}` possono sfuggire al rilevatore.
- Dove il contenuto arriva dal CMS (JSON, database), il testo resta nella lingua in cui e' stato scritto: de/es dei contenuti non fanno parte di questa campagna.
- Verifica del 2026-10-30: compilazione Blade dei file convertiti senza errori, nessuna chiave usata dalla campagna manca in `it`, `en`, `de`, `es`.

## Riferimenti

Metodo e regola completa: [story del tema Two](../../../Two/docs/stories/blade-i18n-no-hardcoded-text.story.md); protocollo a cinque elementi: [regola 008](../../../../../docs/wiki/rules/008-translation-5-level-protocol.md). Regola completa dei link e motivi: [§2 del promemoria sul protocollo di traduzione](../../../../../docs/wiki/memories/translation-protocol-memory-aid.md). Il dettaglio per file e' nel repo privato di audit.

## Seguito: forma delle chiavi

Le chiavi a 2-3 segmenti create da questa campagna sono state in parte portate a `ns::file.collezione.chiave.tipo`: **5 chiavi spostate** in questo proprietario (stesso testo, tutte le lingue, usi riscritti, nessun uso della vecchia chiave rimasto). Le altre sono bloccate da file lingua con commenti o gia' in HEAD: vedi [la story dei residui di Sixteen](pub-theme-keys-and-route-names-residuals.story.md). La frase sui «tre o quattro elementi» qui sopra descrive lo stato precedente: le chiavi rimaste a 3-4 elementi non sono state verificate una per una; la misura assoluta, lo strumento e i residui sono in [la story di Lang](../../../../Modules/Lang/docs/stories/translation-key-shape-rekey.story.md).

### Passata 2026-10-30 (sessione 97f16a39, viste fuori da `components/`, `pages_old/`, `pages_old1/`)

Ambito: `resources/views/` senza `components/`, `pages_old/`, `pages_old1/` (queste ultime due solo accertate, non tradotte; `components/` e' di un'altra passata). Misura: `find-hardcoded-blade-text.py Themes/Sixteen/resources/views --kind text,attr,phrase-key`.

| Cartella | Prima | Dopo |
|---|---:|---:|
| `templates/` | 41 | 41 |
| `layouts/` | 35 | 35 |
| `pub_theme/` | 28 | 28 |
| `tickets/` | 22 | 22 |
| `style.blade.php` | 21 | 21 |
| `pages/` | 13 | 13 |
| `livewire/` | 11 | 10 |
| `partials/` | 6 | 6 |
| `design-comuni/` | 4 | 2 |
| `profile/`, `mobile/`, `auth/` | 3 + 2 + 1 | 3 + 2 + 1 |
| Totale (38 file -> 37) | 187 | 184 |

Convertite 3 occorrenze, 2 file toccati. `livewire/appointment/create-appointment.blade.php`: «Indietro» diventa `pub_theme::common.actions.back.label` (chiave gia' esistente in it, en, de, es). `design-comuni/pages/homepage.blade.php`: `title` e `alt` dell'immagine hero diventano `pub_theme::design_comuni_pages.homepage.hero_image.title` e `.alt`. Nuovo file lingua `design_comuni_pages.php` in it, en, de, es (2 chiavi per lingua; `it` conserva «titolo immagine» e «descrizione immagine», l'HTML `it` e' identico). Nessun file lingua preesistente riscritto.

Le restanti 184 non si traducono (o non adesso):

| Motivo | N |
|---|---:|
| Falsi positivi: direttive `@session`/`@error`/`@csrf`/`@volt`/`@hasSection`, prop `:title="__()"`, binding `:aria-label`/`:alt` | 33 |
| Marchio e dati dimostrativi: `<title>FixCity</title>` e `demo@fixcity.it` in `mobile/`, payload demo «New Friend Request» in `livewire/toast` (`window.customToastHTML`, mai richiamato), data «18 mag 2026» ed e-mail `urp@comune.it` copiate dal riferimento in `design-comuni/` | 8 |
| Prototipi non instradati e rotti (nessun `view()`, `@include`, `<x-...>`, route o JSON li richiama): `layouts/layout-test` (35, tag non bilanciati, usa `x-layouts.test`), `templates/home` e `templates/landing` (41, `<x-button` duplicati, `@extends('layouts.marketing')` inesistente, route `services.*` inesistenti; `landing` non compila gia' prima), `style.blade.php` (21, copia statica di una tabella Filament con URL `fixcity.local`), `pub_theme/components/blocks/*` (27, copia orfana: i blocchi veri sono in `components/blocks/`, qui segnaposto «Contenuto BMAD», «Mario Rossi», pagina statica `template-index`) | 124 |
| `tickets/index.blade.php`: bloccato da `tickets/index.blade.php.lock` (task `map-popup-contrast`, agent-codex, 2026-10-07, oltre 23 giorni: probabilmente orfano, non l'ho toccato ne' rimosso). La vista non e' richiamata: `/it/tickets` e' la pagina CMS `pages/tickets/index.json` con blocco `ticket-list/design-comuni` | 22 |

Decisione al proprietario per i 124 + 22: cancellare i prototipi oppure tradurli. Tradurli aggiungerebbe circa 110 chiavi per lingua per codice che non viene mai reso.

#### `pages_old/` e `pages_old1/`: accertamento (sola lettura)

- Occorrenze: 345 in `pages_old/` (32 Blade piu' 13 file `.stub`/`.lock`/`.gitkeep`), 200 in `pages_old1/` (8 Blade, 8 `.stub`): totale 545. Non tradotte.
- Folio non le monta: `FolioVoltServiceProvider` registra `resources/views/pages`, `resources/views/livewire` e `resources/views/components/blocks`, mai `pages_old*`. Nessuna route, config (`config/theme.php`, `config/sixteen.php`), `@include`, `view()`, `<x-...>` o JSON di PHP/Blade/JS nel repo le richiama; le citazioni sono solo in docs, in un commento CSS (`16-homepage-civic-reframe.css`) e in due test di guardia (`VoltFragmentNamesAreUniqueTest` le scansiona per evitare doppioni di nomi `@volt`, `ApiUrlsAreNotLocalizedTest` le esclude).
- Conclusione: sono codice morto. Il proprietario decide se cancellarle (la guardia `VoltFragmentNamesAreUniqueTest` e l'eccezione in `ApiUrlsAreNotLocalizedTest` andrebbero allora semplificate).
- Effetto collaterale rilevato: 26 nomi di route sono dichiarati solo li' (tra gli altri `services.categories`, `news`, `services.report-issue`, `segnalazioni.create`). Codice vivo chiama `route('services.categories')` (9 file: sotto `resources/views/components/` gli header `sections/header/partials/user-dropdown`, `sections/header/partials/personal-area-guest-parity`, `header/user-dropdown`, `header/authenticated`, `ui/app/header`, `layout/design-comuni-header`; fuori da `resources/views` `Themes/Sixteen/components/header-comune`, `Themes/Sixteen/components/footer-comune`, `Themes/Sixteen/pages/comune/homepage`) e `route('news')` (5 file, 6 chiamate: `resources/views/templates/home` due volte, `resources/views/components/blocks/news/homepage`, e le stesse tre viste fuori da `resources/views`): se la vista gira con il tema Sixteen attivo, la route non esiste e `route()` lancia `RouteNotFoundException`. Non provato a video; non corretto qui (sono `route-unknown`, residui con decisione dell'utente).
- Blocchi `.lock` orfani trovati (non toccati): `pages_old/auth/login.blade.php.lock`, `pages_old/auth/register.blade.php.lock` (vuoti), `pages_old/area-personale/pratiche.blade.php.lock`, `pages_old/personal-area/notifications.blade.php.lock`.

#### Controlli eseguiti

- `php -l` sui 4 file lingua nuovi: nessun errore.
- `blade-compile-check.php` su tutti i 38 file dell'ambito, prima e dopo: stesso esito. Non compilavano gia' prima: `livewire/appointment/create-appointment` (`elseif` a riga 563 del compilato, per i `<x-button` duplicati) e `templates/landing` (`endif` a riga 195). Ho modificato solo la riga di testo «Indietro» in `create-appointment`; la rottura strutturale resta all'altro agente.
- `check-blade-keys.php --only=Themes/Sixteen`: le 3 chiavi usate da questa passata esistono in it, en, de, es. Il tema ha comunque 252 chiavi distinte mancanti (non introdotte qui); 40 sono usate da viste di questo ambito (per namespace e file: `gdpr::cookie` 13 in `partials/cookie-bar`, `pub_theme::accessibility` 8 in `partials/accessibility-bar` e `accessibility/contrast-toggle`, `pub_theme::ui` 8 tra cui `mobile/tickets/create`, `fixcity::auth` 7 in `pages/auth/login`, `pages/auth/register` e `pages/[container0]/[slug0]/index`, piu' `fixcity::ticket`, `fixcity::segnalazione`, `user::auth.Password` in `livewire/auth/passwords/reset`, `xot::xot_base_resource_form`). Il modulo Fixcity ha `lang` solo in `en` e `it` e nessun file `auth`: sulle pagine vive di login e registrazione titolo e descrizione meta escono come chiave grezza. Non corrette qui: sono chiavi di altri proprietari (Fixcity, Gdpr, User, Xot) o di partial non richiamati, e `lang/de/accessibility.php` ha commenti.
- `check-key-shape.py`: le 3 chiavi usate non compaiono tra le storte.
- Annotazione: `partials/accessibility-bar` e `partials/cookie-bar` usano `:aria-label="__('...')"` su elementi HTML nativi, cioe' un binding Alpine che chiama `__` in JavaScript (non definito): sono dormienti perche' i partial non sono richiamati, ma nella forma attuale non produrrebbero l'etichetta.

Non verificato: nessuna resa a video e nessuna richiesta HTTP (Sixteen non e' il tema attivo); l'identita' dell'HTML `it` e' garantita per costruzione (testo `it` copiato al byte), non misurata con un diff del rendering; le traduzioni de/es di `design_comuni_pages` non sono riviste da un madrelingua.

### Passata 2026-10-30 (sessione 97f16a39, components)

Ambito: `Themes/Sixteen/resources/views/components/**`. Task `i18n-sixteen-components`.

- Occorrenze (scanner, classi text/attr/phrase-key): da 311 in 70 file a 239 in 67 file (text 267 -> 200, attr 42 -> 39, phrase-key 2 -> 0). Le altre classi (url-literal, route-page-name, route-unknown) non toccate.
- Blade toccate (13): `welcome`, `layout/sections/welcome` (scaffold Jetstream, `en` conserva l'originale e `it` e' la traduzione), `layout/sections/page`, `blocks/tickets-list`, `ui/marketing/footer` (scaffold Flowbite, stessa regola), `utilities/pagopa-button`, `blocks/tabs/map-list`, `blocks/footer/exact-1to1`, `agid/header`; in piu', non rilevate dallo scanner perche' il testo segue un `{{ }}`: `blocks/footer/full`, `layouts/auth`, `blocks/navigation/footer-institutional`, `footer/institutional` («Tutti i diritti riservati»).
- Chiavi aggiunte, uguali in `it`, `en`, `de`, `es` (49 per lingua, 6 file nuovi): `components_jetstream_welcome` 10, `components_project_home` 8, `components_tickets_list` 13, `components_marketing_footer` 12, `components_pagopa_button` 5, `components_copyright` 1. Riuso di chiavi esistenti: `blocks_common.shared.{log_in,reports,details}.label`, `components_footer.contacts.tax_code_vat.label`, `components_footer.legal.rights_reserved.label`, `legacy_prototypes.listing.category_water_sewage.label`, `tickets_list.categories.{environment,urban_hygiene}.label`, `header.social.{facebook,twitter}.label`.
- `utilities/pagopa-button`: le coppie `@if($language === 'en') ... @else ... @endif` sono diventate `__('...', [], $language)` e il default della prop `language` e' ora `app()->getLocale()` (prima `'it'`): in `it` il testo e' identico, in `de`/`es` ora si traduce.
- Verifica `it`: per le sostituzioni con originale italiano (34) il testo risolto da `lang/it` coincide con quello sostituito.
- Residui (239) lasciati con motivo: marchi e nomi di servizio (Twitter, Facebook, YouTube, Telegram, Whatsapp, RSS, LinkedIn, Instagram, Flowbite, Tailwind, Laracasts, VISA, PostePay, SEPA, PagoPA, OpenStreetMap), autonimi di lingua (ITA, Italiano, English), dati di esempio e indirizzi segnaposto (Comune di Example/Nome Comune, Via Example/Roma, e-mail e PEC fittizie, IBAN/BIC/codici di prova, `CF:`/`P.IVA` con numeri finti), formati di esempio (`MM/YY`, codice fiscale, `TCK-XXXX`), acronimi (CVV, URP, IBAN, BIC), frammenti di direttive Blade/PHP/Alpine (`@break @case`, `@svg(...)`, `all(), ])`) e `x-adsense.blade.php` (pseudo-template con `{% %}`, non Blade).
- Non verificato: nessuna resa a video (Sixteen non e' il tema attivo, nessuna richiesta HTTP); traduzioni `de`/`es` non riviste da un madrelingua; `check-blade-keys` su tutto il tema segnala ancora chiavi mancanti preesistenti (non introdotte da questa passata, tra cui 44 `pub_theme::` in `components/`, per lo piu' 2-3 segmenti in `footer`, `segnalazione`, `alerts`).
