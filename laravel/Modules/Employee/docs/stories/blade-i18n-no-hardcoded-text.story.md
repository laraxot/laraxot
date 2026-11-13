---
title: "Niente testi fissi nelle Blade del modulo Employee (multilingua it/en/de/es)"
type: story
status: implemented
module: Employee
updated: 2026-10-30
tags: [i18n, blade, multilingua]
---

# Niente testi fissi nelle Blade del modulo Employee

## Scopo

Il sito risponde in italiano, inglese, tedesco e spagnolo: un testo scritto dentro una Blade esce uguale in ogni lingua. Il `fallback_locale` e' `it`, quindi una chiave mancante in `en`/`de`/`es` non da' errore e mostra l'italiano.

## Regola operativa

- Ogni testo visibile passa da `{{ __('employee::contesto.collezione.chiave.tipo') }}`: cinque elementi, dove `contesto` e' il nome del file lingua e `tipo` il genere di testo (`label`, `title`, `description`, `placeholder`, `tooltip`, `helper_text`, `message`, `aria_label`, `alt`; [regola 008](../../../../../docs/wiki/rules/008-translation-5-level-protocol.md)). Mai la frase come chiave: nessun file la traduce, e il traduttore ignora `['default' => ...]`.
- La stringa va in `Modules/Employee/lang/{it,en,de,es}/<contesto>.php`, con le stesse chiavi e gli stessi `:parametri` nelle quattro lingue. `it` e' l'originale; `en`, `de`, `es` sono traduzioni vere.
- Vale anche per `aria-label`, `placeholder`, `title`, `alt` e, in PHP, per `->label()`, `->placeholder()`, `->tooltip()`, `->helperText()` e per le etichette di stato restituite dai componenti Livewire.
- Frasi con variabili: segnaposto `:param` e `trans_choice` per i plurali (conteggi di timbrature, ore).
- Link a pagine CMS: niente `href="/it/..."`, niente `url('/...')`, niente nomi di rotta inventati per pagina. Si usa la rotta generica della shell Folio, `route('container0.view', ['container0' => 'area-personale', 'slug0' => 'notifiche'])`: il prefisso lingua lo aggiunge Folio. L'indice ha un nome diverso per tema (`container0` in Two e Meetup, `container0.index` in Sixteen e TwentyOne): una vista di modulo condivisa tra temi usa `view` oppure riceve l'URL dal chiamante. I nomi Folio veri (`home`, `login`, `logout`) restano validi.
- Non sono errori: marchi e nomi propri, sigle, numeri, valori che arrivano dal modello.

## Cosa e' stato fatto

- Rilevatore `bashscripts/tools/i18n/find-hardcoded-blade-text.py` sul modulo: da 67 occorrenze in 9 file a 3 in 2 file (stesso strumento, commit di partenza contro stato attuale).
- 7 Blade convertite: viste di timbratura, ore lavorate e tabellone delle ore.
- Nuova collezione `work_hours_board` in `it`, `en`, `de`, `es` (8 chiavi per lingua).
- La cartella `lang/es` non esisteva: creati file parziali (`time_entries`, `time_tracking`, `workhour`, `work_hours`) con le sole chiavi usate dalle Blade. Completate le chiavi preesistenti usate dalle Blade: 8 in `de`, 58 in `es`.
- Controllo statico sulle 68 Blade di User, Employee e TechPlanner: 311 chiavi usate, nessuna mancante in `it`, `en`, `de`, `es`.

## Residui dichiarati (3 occorrenze)

| Categoria | N |
|---|---:|
| Scaffold nWidart non instradato (indice e layout master) | 3 |

Testi in PHP non toccati: circa 13 chiamate `->label()`, `->placeholder()`, `->tooltip()` con testo fisso (conteggio euristico) e le etichette di stato della timbratura restituite in inglese dal componente Livewire e mostrate dalla Blade.

## Limiti

- Nessuna verifica a video: chiavi e compilazione sono controllate in modo statico.
- `lang/es` e' parziale: le chiavi non ancora tradotte ricadono sull'italiano.
- Una parte delle chiavi usate dalle Blade (circa 47 usi) e' nata con quattro elementi invece di cinque: funziona in tutte le lingue ma non e' stata rinominata.
- Verifica del 2026-10-30: PHPStan sui moduli senza errori, compilazione Blade dei file convertiti senza errori, nessuna chiave usata dalla campagna manca in `it`, `en`, `de`, `es`.

## Riferimenti

Metodo e regola completa: [story del tema Two](../../../../Themes/Two/docs/stories/blade-i18n-no-hardcoded-text.story.md); protocollo a cinque elementi: [regola 008](../../../../../docs/wiki/rules/008-translation-5-level-protocol.md). Il dettaglio per file e' nel repo privato di audit.

## Seguito: forma delle chiavi

Le chiavi a 2-3 segmenti create da questa campagna sono state in parte portate a `ns::file.collezione.chiave.tipo`: **26 chiavi spostate** in questo proprietario (stesso testo, tutte le lingue, usi riscritti, nessun uso della vecchia chiave rimasto). Una chiave non e' stata spostata (`widgets.time_clock.session_inactive`): `de/widgets.php` contiene commenti; la chiave manca anche in de/es. La frase sui «tre o quattro elementi» qui sopra descrive lo stato precedente: le chiavi rimaste a 3-4 elementi non sono state verificate una per una; la misura assoluta, lo strumento e i residui sono in [la story di Lang](../../../Lang/docs/stories/translation-key-shape-rekey.story.md).

### Passata 2026-10-30 (sessione 97f16a39, i18n-small-modules: Employee)

Scanner `Modules/Employee`: prima 3 occorrenze in 2 file (3 text), dopo 3 in 2 file (invariato: sono i due stub). Lo scanner non vedeva il difetto vero: 143 usi di chiavi `employee::...` di cui 72 senza voce in almeno una lingua (it 6, en 9, de 14, es 72).

- Blade toccate (4): `filament/widgets/time-clock-widget`, `filament/widgets/time-tracking-widget`, `widgets/attendance-overview/attendance-list`, `widgets/time-clock/time-display`. Usavano 14 chiavi senza voce in nessuna o in poche lingue e ora puntano a file nuovi (`widgets.php` di `it`/`en`/`de` ha commenti e lo strumento lo rifiuta).
- Chiavi nuove in `it`, `en`, `de`, `es` (14 per lingua, cinque elementi): `time_clock_widget` (5: timbra entrata/uscita, sessione attiva/inattiva, nessuna timbratura), `time_tracking_widget` (5), `attendance_list` (2, con `:type`, `:count`, `:total`), `time_display` (2). I testi `it` di `time_clock_widget` e `time_tracking_widget.status_*` sono quelli gia' presenti in `it/widgets.php`; gli altri (`attendance_list`, `time_display`, clock in/out di `time_tracking_widget`) non avevano originale e sono scritti ex novo.
- Spagnolo: nuovo `es/widgets.php` con 47 chiavi (`pending_requests`, `time_off_balance`, `today_presence`, `todo`, `upcoming_schedule`, tradotte da it/en/de) e `es/work_hours.php` +11 chiavi (riepilogo settimanale). Totale: it +14, en +14, de +14, es +72.
- Controlli: `check-blade-keys` su `Modules/Employee` da 72 chiavi mancanti a 0 in tutte e quattro le lingue; compilazione Blade 22 file OK; `php -l` pulito sui file lingua toccati; `check-key-shape`: nessuna delle chiavi nuove e' tra le storte.
- Residui (3): stub `index` e `layouts/master` («Hello World», «Module:», «Employee Module -»): `EmployeeController` esiste ma le sue rotte sono commentate.
- Da sapere: le vecchie voci `widgets.time_clock.*` e `widgets.time_tracking.*` restano nei file `it`/`en`/`de` senza piu' usi nelle Blade. `attendance-list` riceve in `:type` lo slug (`absences`, `smart_working`, `transfers`) da `AttendanceOverviewWidget.php`, quindi la frase mostra lo slug: va passata un'etichetta tradotta dal PHP (non toccato). Le chiavi `employee::widgets.*` e `employee::work_hours.*` hanno meno di cinque elementi (preesistenti).
- Non verificato: nessuna pagina guardata; `es` resta parziale (le chiavi non tradotte ricadono sull'italiano).
