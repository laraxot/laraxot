---
title: "XotBaseResourceTable: aggiungere \$model esplicito ad ogni sottoclasse + audit getTableColumns() (campi esistenti, UI/UX, schema.org)"
type: story
module: Xot
epic: null
story_id: null
slug: xotbaseresourcetable-model-property-and-gettablecolumns-audit
status: in-progress
created: '2026-09-11'
updated: '2026-09-11'
repository: "https://github.com/laraxot/module_xot_fila5.git"
github_issue: null
github_discussion: null
estimated_effort: null
blocked_by: []
blocks: []
owned_scope: []
related:
  - "personcolumn-schema-org-aggregate (second brain)"
  - "xot-baseresourcetable-no-table-override (second brain)"
---

# XotBaseResourceTable: $model esplicito + audit getTableColumns()

## Richiesta utente (2026-09-11 sera, non ancora iniziata)

> "studia tutte le classi che estendono XotBaseResourceTable aggiungi ad
> ognuna protected static string $model = con il model a cui fa
> riferimento, poi studia e migliora getTableColumns controllando che i
> campi esistano ancora e migliorando la ui/ux aiutandoti anche con
> schema.org"

## Scope (da confermare/misurare domani, non ancora fatto)

1. Censire TUTTE le classi `extends XotBaseResourceTable` in tutti i moduli
   (`grep -rl "extends XotBaseResourceTable"` repo-wide) — numero non ancora
   contato in questa sessione.
2. Per ognuna: aggiungere `protected static string $model = ModelClass::class;`
   esplicito — verificare PRIMA se `XotBaseResourceTable`/`HasXotTable` ha
   gia' un modo per derivare il model per convenzione (nome della classe Table
   meno suffisso `Table`, o `getModelClass()`) per capire se questa e'
   un'aggiunta ridondante-ma-esplicita o se risolve un buco reale (es. Table
   class il cui nome non matcha il model per convenzione).
3. Per ognuna: rileggere `getTableColumns()` e verificare con
   `Schema::hasColumn($table, $field)` (o equivalente) che ogni colonna
   referenziata esista ancora sul model/tabella reale — cercare colonne
   orfane (rinominate/rimosse in migrazioni successive, vedi precedente reale
   in questa saga: `xotbaseresourcetable-orphaned-columns-git-archaeology`).
4. Migliorare UI/UX delle colonne dove sensato, riusando pattern gia'
   esistenti nel repo (es. `PersonColumn` — aggregazione anagrafica+contatto
   ispirata a schema.org/Person, vedi memoria collegata) invece di
   duplicare celle gia' ricche.
5. Applicare vocabolario schema.org dove pertinente (es. Person, Organization,
   PostalAddress) per dare struttura semantica alle colonne che rappresentano
   quei concetti — coerente con l'uso gia' esistente in `PersonColumn`.

## Aggiornamento: gia' iniziata da una sessione concorrente

Commit `9ead9d9f` (nello stesso pomeriggio, altra sessione sulla stessa
working tree): `$model` aggiunto e verificato contro il model del sibling
Resource (mai indovinato dal nome file) su 6 classi core del modulo Xot
(`CacheLocksTable`, `CachesTable`, `ExtrasTable`, `LogsTable`, `ModulesTable`,
`SessionsTable`). `getTableColumns()` verificato contro
`Schema::getColumnListing()` reale — nessuna colonna orfana trovata, solo
un'anomalia d'ambiente segnalata (tabella `cache` assente dal DB nonostante
`migrate:status` la dia "Ran" — documentata, non toccata). Domani: continuare
da qui sulle classi rimanenti (Xot ha altre classi oltre queste 6, e gli
altri 19 moduli non sono ancora stati toccati), non ripartire da zero.

## Perche' non ancora iniziata (dal punto di vista di QUESTA sessione)

Arrivata mentre ero nel mezzo di: (a) un incidente serio di history-rewrite +
regressione live su `XotBaseManageRelatedRecords` (vedi story
`module-git-sync-unrelated-histories-2026-09-11.story.md` e second brain
`history-rewrite-force-push-destroys-cited-commits`), (b) il censimento sync
git di 20 moduli. Task di questa portata (dozine di classi, potenzialmente in
piu' moduli) merita di partire a mente libera, non incastrato a fine
sessione — rischio di fare un audit superficiale o, peggio, rompere colonne
reali senza verificare a fondo (esattamente il tipo di errore che questa
sessione ha passato ore a correggere in file altrui).

## Piano per domani

1. `grep -rln "extends XotBaseResourceTable" laravel/Modules/*/app` → lista
   completa, contare.
2. Per ogni classe: leggere il model correlato (via naming convention o
   `GetRelatedResourceClassAction`-style resolution gia' esistente altrove in
   Xot), verificare che il nome coincida, poi aggiungere `$model`.
3. Per `getTableColumns()`: script/grep di supporto per estrarre ogni
   `->make('campo')`/`Column::make('campo')` e verificarlo contro
   `Schema::getColumnListing($table)` del model reale — produrre un report
   PRIMA di modificare nulla.
4. Story BMAD per-modulo (non una story gigante unica) se il lavoro tocca piu'
   moduli — coerente con owned_scope disgiunti per lavoro parallelo.
5. Chiusura modulo standard dopo ogni modifica (phpstan+phpmd+phpinsights+pest,
   coverage.md, commit+push).

## Riconciliazione con story parallela (2026-09-11, stessa sera)

Un'altra sessione concorrente (stessa working tree) ha eseguito la fase 1
di questo identico task nel frattempo, in
`xotbaseresourcetable-model-property-and-column-audit.story.md`: 96/99
classi censite come vive, 95/96 con `$model` gia' inserito e verificato
via `Resource::getModel()` (mai indovinato), commit+push su tutti i 15
moduli coinvolti + root, phpstan repo-wide 0 errori. Il piano "per domani"
sotto e' quindi in parte gia' fatto — questa story resta come riferimento
per la RICHIESTA UTENTE originale e il piano fase 2/3 (audit colonne
orfane, redesign UX/schema.org), non ancora iniziati sistematicamente in
nessuna delle due sessioni. Vedi quella story per i risultati fase 1
reali, non ripeterli qui.

## Acceptance criteria (da questa sessione, per domani)

- [ ] Censimento completo delle classi (numero + elenco)
- [ ] Per ognuna: confermato se `$model` e' un'aggiunta utile o ridondante
- [ ] Report colonne orfane (campo non piu' esistente) prima di qualunque fix
- [ ] Story per-modulo create per il lavoro effettivo, questa story chiusa come "solo scoping"
