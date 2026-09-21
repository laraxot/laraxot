---
title: "BMAD — consolidare la delega completa form/table"
type: story
status: done
implementation_status: "implementato e verificato nel filone #115/#117 (story xotbasemanagerelatedrecords-convention-over-configuration.story.md) — stessa decisione, thread paralleli non riconciliati per scelta"
created: 2026-09-11
updated: 2026-09-11
tags: [bmad, second-brain, filament, dry, kiss]
github:
  repository: https://github.com/laraxot/module_xot_fila5
  task_issues:
    - https://github.com/laraxot/module_xot_fila5/issues/112
  task_discussions:
    - https://github.com/laraxot/module_xot_fila5/discussions/114
  related_issues:
    - https://github.com/laraxot/module_xot_fila5/issues/75
    - https://github.com/laraxot/module_xot_fila5/issues/115
  related_discussions:
    - https://github.com/laraxot/module_xot_fila5/discussions/117
  tracking_status: linked
  note: "Tracking dedicato creato con gh; MCP GitHub tentato ma scrittura negata con 403. #75 resta un antecedente. #115/#117 e' un secondo filone parallelo sulla stessa decisione, nato indipendentemente (vedi story xotbasemanagerelatedrecords-convention-over-configuration.story.md, piu' aggiornata)."
---

# BMAD — consolidare la delega completa form/table

## Stato e obiettivo

Analisi documentale completata; sviluppo non iniziato. Come manutentore voglio riusare la configurazione completa della Resource correlata, eliminando inoltri di array e duplicazione dell'orchestrazione, senza confondere owner e righe.

## Contratto concordato nella documentazione

[Fattibilità canonica](../architecture/xotbasemanagerelatedrecords-form-table-delegation-feasibility.md): delega completa, selezione corretta, percorsi A/B esclusivi. Rimozione dei trait dalla pagina come direzione; nessuna modifica ai trait globali o alle classi applicative in questo task.

## Criteri documentali

- [x] Riletti documenti indicati e sorgenti locali del ciclo Filament/Xot.
- [x] Corretto getResource owner nello snippet e chiarita chiamata statica alla Resource concreta.
- [x] Ritirata raccomandazione finale dei soli array e divieto assoluto di rimuovere HasXotTable.
- [x] Distinti delega nativa e wrapper completo; documentata doppia configurazione se sommati.
- [x] Documentati azioni contestuali, pannelli, policy, statePath e layout.
- [x] Consolidati indici e second brain; percentuali senza pretese di verifica runtime.

## Futuro sviluppo — non eseguito

- [ ] Scegliere A/B per i consumer effettivamente registrati.
- [ ] Verificare query/scoping Contact con owner SurveyPdf e ordinamento corretto.
- [ ] Provare singola configurazione, modali create/edit, import e associazione con owner corretto.
- [ ] Verificare override contestuali, policy, routing cross-pannello, data e layout.
- [ ] Eseguire Pest e quality gate PHP previsti dopo eventuali modifiche applicative.

## Review documentale

Lettura statica e controllo link/diff; nessuna prova runtime nuova. QMD indisponibile per mismatch ABI Node/SQLite. Le precedenti affermazioni di test live non sono riutilizzate come prova corrente.

[Memoria second brain](../wiki/concepts/manage-related-records-resource-delegation.md). Questa è la story canonica; le altre due story raccolgono indirizzo e ricognizione senza duplicare gli acceptance criteria.



## Coordinamento agenti — proposta file documentata

- Writer responsabile: agente `/root`; perimetro esclusivo della nuova bozza: `docs/app/Filament/Resources/Pages/XotBaseManageRelatedRecords.php.md`.
- Reviewer assegnato: `/root/proposal_review`, sola lettura di sorgenti/vendor e segnalazione difetti; nessuna scrittura concorrente sulla bozza.
- Agenti esterni non presenti in questa sessione: usare questa story come handoff e aggiungere osservazioni senza sovrascrivere la bozza durante la redazione. Non è un lock tecnico né prova che abbiano ricevuto il messaggio.
- Decisione di redazione: form/table completi; Resource owner invariata; delega tabella esplicita solo se il percorso nativo non l'ha già eseguita; un hook contestuale dopo i default.
- Stato: redazione documentale in corso; nessuna modifica al PHP applicativo.

### Consegna e review del file proposto

[Versione completa con PHPDoc](../app/Filament/Resources/Pages/XotBaseManageRelatedRecords.php.md). Il contributo concorrente basato sugli array è stato letto e preservato come storico esplicitamente superato, senza dichiarare accordo con sessioni esterne. Reviewer `/root/proposal_review`: confermati lifecycle e biforcazione Relation/Builder; rilevati toggle layout inerte senza capability e registro che non rileva ambiguità. Finding recepiti nelle note. Sintassi del primo blocco PHP verificata via stdin e link locali verificati; nessuna modifica applicativa, nessuna validazione runtime. Stato documentale: consegnato per discussione.


### Audit richiesto: frontmatter e API Resource

La story esisteva con YAML ma senza link GitHub: omissione confermata. Remote del modulo verificato: laraxot/module_xot_fila5. Consultate issue e discussion in sola lettura; #75 tratta un bug correlato e rimanda alla ristrutturazione in Quaeris #30. Non spacciato per issue creata per questa proposta. Stato dell’audit precedente: tracking allora incompleto; completato dall’aggiornamento GitHub sotto.

getRelatedResource() nel vendor restituisce soltanto static::$relatedResource (default null). ManageContacts non la imposta. Non è un resolver automatico. Per il registro modello→Resource esiste Filament::getModelResource(), già incapsulato in GetResourceClassNameByModelClassAction. Il registro è scoped al pannello e seleziona la prima corrispondenza; non risolve automaticamente ambiguità o Resource non registrate. Una nuova Action non è giustificata dal solo null di getRelatedResource.

Contributi concorrenti hanno proposto GetRelatedResourceClassAction nella bozza: proposta ancora da giustificare rispetto all'action esistente, non dichiarata necessaria. PersonColumn esiste in Modules/UI/app/Filament/Tables/Columns; la composizione di colonne UI e la selezione delle Resource sono responsabilità distinte.


## Tracking GitHub completato — 2026-09-11

- Issue dedicata: https://github.com/laraxot/module_xot_fila5/issues/112
- Discussion architetturale: https://github.com/laraxot/module_xot_fila5/discussions/114
- Repository verificato dal remote del modulo: laraxot/module_xot_fila5.
- Verifica anti-duplicati: esaminate issue e discussion; #75 è bug precedente, non questa proposta.
- MCP GitHub create_issue tentato: 403 Resource not accessible by integration. Creazione tramite gh autenticato riuscita; nessuna installazione o modifica dei permessi necessaria.
- Scope: documentazione e coordinamento; file locali non dichiarati pubblicati su GitHub, implementazione non iniziata.

### Acceptance criteria del tracking

- [x] Issue e discussion dedicate nel repository del modulo corretto.
- [x] Collegamenti incrociati e frontmatter YAML con URL reali.
- [x] Protocollo multi-agente pubblicato: ownership dei file sulla issue, decisioni sulla discussion, sincronizzazione della story.
- [x] Second brain aggiornata con riferimenti verificabili.
- [ ] Review architetturale finale e rimozione delle ridondanze: resta lavoro di discussione, non marcato concluso dal solo tracking.

## Aggiornamento 2026-09-11 (v5) — contratto v4 esteso a getTableActions/getTableFilters/getTableBulkActions

Correzione richiesta dall'utente: il "Contratto proposto" (v4) nel `.md`
collegato toccava solo `getFormSchema()`/`getTableColumns()`, lasciando
`getTableActions()`/`getTableFilters()`/`getTableBulkActions()` sul default
generico di `HasXotTable` — che per `ContactsTable` significa perdere filtro
`search_contacts`, bulk `make-token`/`send-invite`/`send-spatie-email`. Il
documento gia' lo ammetteva in prosa (sottosezione "CORREZIONE") ma non lo
applicava nel codice. Risolto SOLO nella documentazione (nessuna modifica
`.php`): sezione "REVISIONE 2026-09-11 (quinta direzione)" in fondo a
[XotBaseManageRelatedRecords.php.md](../app/Filament/Resources/Pages/XotBaseManageRelatedRecords.php.md).

Trovato anche, verificato con lettura diretta di `XotBaseResourceTable.php` e
`HasXotTable.php` (non ipotizzato): `HasXotTable::getTableActions()`
(riga 324-325) autorizza le row action contro `$this->getResource()`, che su
una pagina `ManageRelatedRecords` e' sempre la Resource OWNER, mai quella
della relazione — bug di autorizzazione preesistente, indipendente da questa
proposta, aggirato (non risolto) dalla delega v5 per le sole pagine che la
adottano. Merita una story propria se si vuole una correzione alla fonte nel
trait.

Domanda ancora aperta, non decisa: `getRelatedResourceClass()` nullable
(versione piu' vecchia in questo stesso `.md`) o throwing
(`Assert::notNull`, versione oggi nel file reale) — il contratto v5 assume
throwing per coerenza col file reale, ma serve una decisione esplicita prima
di implementare, non presa qui.

Story `xotbasemanagerelatedrecords-convention-over-configuration.story.md`
risultava lockata (altra sessione attiva) al momento di questo aggiornamento:
non toccata.

## Riconciliazione 2026-09-11 (sessione successiva) — GetRelatedResourceClassAction non e' piu' "da giustificare"

La sezione "Audit richiesto: frontmatter e API Resource" sopra dichiara
`GetRelatedResourceClassAction` "proposta ancora da giustificare rispetto
all'action esistente [GetResourceClassNameByModelClassAction], non
dichiarata necessaria". Non e' piu' vero: l'Action esiste, e' in
`Modules/Xot/app/Actions/Filament/GetRelatedResourceClassAction.php`, usata
da `XotBaseManageRelatedRecords::getRelatedResourceClass()` nel file reale
oggi, con test di reflection passati (`XotBaseManageRelatedRecordsRegressionTest.php`).
La giustificazione per cui non riusa `GetResourceClassNameByModelClassAction`
(quella usa `Filament::getModelResource()`, scoped al pannello corrente,
verificato dal vivo cieco su model di altri moduli) e' documentata nel suo
stesso PHPDoc. Questo filone di tracking (issue/discussion #112/#114) e
quello piu' recente (#115/#117, story
`xotbasemanagerelatedrecords-convention-over-configuration.story.md`)
descrivono la stessa decisione, arrivata a conclusioni convergenti in modo
indipendente — non riconciliati in un'unica issue per non chiudere
tracking altrui senza conferma esplicita dell'utente.
