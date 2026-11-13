---
title: "UI: scopo del modulo"
type: concept
status: active
updated: 2026-10-27
---

# UI: scopo del modulo

## Scopo

UI e' il vocabolario visivo condiviso: colonne, componenti di form, widget, layout di
tabella e blocchi che gli altri moduli usano come mattoni, senza riscriverli. Il README
lo descrive come "componenti UI riutilizzabili e design system ... senza logica di
dominio" (`laravel/Modules/UI/README.md`, frontespizio).

Evidenza dei mattoni: `app/Filament/Tables/Columns/` (colonne come `PersonColumn`,
`OrganizationColumn`), `app/Filament/Forms/Components/` (`EnumSelect`, `TreeField`,
`InlineDatePicker`), `app/Enums/TableLayoutEnum.php` e
`app/Filament/Actions/Table/TableLayoutToggleTableAction.php`.

Consumatori reali fuori da UI: Incentivi, IndennitaCondizioniLavoro, IndennitaResponsabilita,
Media, Performance, Progressioni, Ptv, User e Xot (conteggio con
`grep -rl 'Modules\\UI\\' laravel/Modules`).

## Come raggiungerlo meglio

1. **Dichiarare chi possiede i dati.** UI ha tre migrazioni
   (`database/migrations/2026_07_15_100000_create_categories_table.php`, `..._100001_create_collections_table.php`,
   `..._100002_create_field_options_table.php`) e modelli `Category`, `Collection`,
   `FieldOption` (`app/Models/Category.php:60`). Un "vocabolario visivo" che persiste
   dati contraddice il README. **Azione:** o spostare queste tabelle nel modulo che le
   usa, o scrivere l'eccezione nel README.

2. **Togliere la dipendenza da User.** `app/Actions/GetUserDataAction.php:10` importa
   `Modules\User\Models\Profile`. Un componente di UI non dovrebbe conoscere il profilo
   utente. **Azione:** passare i dati gia' pronti come parametro o DTO.

3. **Rendere esplicita la dipendenza da Cms.** `app/Actions/Block/ResolveLocalizedBlockDataAction.php:23`
   cita `Modules\Cms\Actions\...` come stringa, protetta da `class_exists` (riga 25).
   Il modulo Cms non esiste in `laravel/Modules`. Il codice funziona senza, ma la
   dipendenza e' invisibile. **Azione:** un contratto vuoto in UI, implementato da Cms
   quando esiste.

## Confini

Il modulo NON fa:

- logica di dominio (indennita', valutazione, progressioni, anagrafica): le colonne
  mostrano il dato, non decidono nulla;
- definizione di ruoli, permessi o tenant: quelli stanno in User e Tenant;
- gestione della lingua: i testi vengono da Lang.

<<<<<<< HEAD
Dipendenze verso Xot: UI estende `Modules\Xot\Providers\XotBaseServiceProvider`
(`app/Providers/UIServiceProvider.php:8`). Un centinaio di riferimenti a Xot sono
attesi, perche' Xot e' la base. Il vincolo "Xot non dipende da nessuno" resta
violato da Xot verso UI (vedi `Xot/docs/purpose.md`).
=======
## Come raggiungerlo **meglio**

### 1. L'accessibilita' e' un requisito di legge, non una rifinitura

Un portale di pubblica amministrazione italiana e' soggetto agli obblighi di
accessibilita'. Se l'accessibilita' non e' nei componenti, va rifatta in ogni pagina —
cioe' non si fa.

**Azione:** ogni componente dichiara nel proprio doc il comportamento con tastiera, i
ruoli ARIA e il contrasto. E un test automatico di contrasto sui token di colore. E'
il contributo piu' alto che questo modulo puo' dare al progetto.

### 2. Serve una vetrina, non solo la documentazione

779 documenti e nessun posto dove **vedere** i componenti. Chi deve scegliere apre il
codice, non trova, e ne scrive uno nuovo: e' cosi' che nasce la duplicazione che il
modulo dovrebbe evitare.

**Azione:** una pagina catalogo (anche una Folio protetta) che renda ogni componente
nei suoi stati: normale, con errore, disabilitato, vuoto, in caricamento.

### 3. Gli stati "brutti" vanno progettati una volta per tutte

Vuoto, errore, caricamento, permesso negato: sono gli stati che ogni modulo reinventa
peggio.

**Azione:** un componente per ciascuno, e la regola che nessuna schermata li scriva a
mano. La qualita' percepita di un gestionale si misura li', non sul caso felice.

### 4. La parita' Forms ↔ Tables vale anche qui

La regola nata in Ptv — a ogni componente di form corrisponde una colonna che mostra lo
stesso concetto — e' una regola di **vocabolario**, e il vocabolario e' questo modulo.

**Azione:** dove UI offre un componente di input per un concetto ricorrente, offrire
anche la colonna corrispondente.

### 5. La documentazione va ridotta per essere usata

Come Xot, Notify e Activity: un `index.md` a una schermata, un canonico per argomento.

## Confini — cosa **non** appartiene a UI

- Il **tema** e le scelte cromatiche di questo progetto: `Themes/`.
- Qualunque **regola di dominio**. Un componente che sa cos'e' una scheda non e' un
  componente di UI: e' un componente di Ptv.
- Le **classi base Filament**: Xot.

## Collegamenti

- `laravel/Modules/Ptv/docs/form-column-parity.md` — la parita' come regola
- `docs/wiki/rules/filament-form-components-vocabulary.md` — quando estrarre
<<<<<<< HEAD
<<<<<<< .merge_file_vKNsXq
=======
<<<<<<< .merge_file_fVQ2Zr
=======
<<<<<<< HEAD
- `./architecture.md` — architettura tecnica del modulo (componenti, Filament, Blocks)
=======
>>>>>>> laraxot/dev
>>>>>>> .merge_file_tZrhTh
>>>>>>> .merge_file_crJzrt
=======
>>>>>>> 804451c (Lint)
>>>>>>> f5ffd8b4 (.)
