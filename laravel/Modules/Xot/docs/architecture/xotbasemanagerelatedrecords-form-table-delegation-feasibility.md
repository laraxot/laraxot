---
title: "Delega completa form/table: fattibilità e contratto"
type: architecture
status: discussion
implementation_status: not-started
created: 2026-09-11
updated: 2026-09-11
tags: [bmad, second-brain, filament, dry, kiss]
---

# Delega completa form/table: fattibilità e contratto

Questa è la fonte canonica della decisione proposta. Solo documentazione: nessuna implementazione o prova runtime eseguita in questa revisione. Sostituisce le precedenti conclusioni incompatibili: “bastano gli array”, “non si può togliere HasXotTable” e “rimozione senza perdita garantita”.

## Decisione proposta

Accogliere la delega **dell'intera configurazione** di form e tabella alla Resource concreta dei record correlati. Il padre della pagina coordina il contesto; la Resource continua a scegliere Form/Table tramite i resolver di XotBaseResource. Rimuovere HasXotForm/HasXotTable dalla pagina è una direzione coerente, da accompagnare alla verifica dei consumer. Non ricopiare i loro metodi nel padre.

Leggere solo getFormSchema/getTableColumns centralizza due array, ma lascia sulla pagina un secondo percorso di configurazione: non trasferisce automaticamente colonne del form, statePath, filtri, azioni, ordinamento o altre personalizzazioni dei configuratori. La proposta dell'utente risolve questo limite al livello corretto: form(Schema) e table(Table).

## Correzione dello snippet dell'utente

Nel codice corrente [Page::getResource()](../../../../vendor/filament/filament/src/Resources/Pages/Page.php) restituisce static::$resource. [ManageContacts](../../../Quaeris/app/Filament/Resources/SurveyPdfResource/Pages/ManageContacts.php) dichiara SurveyPdfResource. Quindi:

```text
$this->getResource()              → SurveyPdfResource (owner della pagina)
app($resource)->form($form)       → form di SurveyPdf, non di Contact
app($resource)->table($table)     → tabella SurveyPdf, non tabella Contact
```

Il problema non è la delega completa: è **il destinatario**. Non cambiare il significato di getResource per correggerlo: routing, record proprietario e navigazione devono continuare a riferirsi al SurveyPdf.

I metodi form/table della Resource sono statici. Istanziare la Resource con app non è necessario per chiamarli. Chiamare `$resourceClass::form($schema)` mantiene il late static binding della classe concreta e usa i metodi ereditati da XotBaseResource. Chiamare direttamente `XotBaseResource::form()` non fornisce invece il contesto concreto del modello.

## Due percorsi alternativi, non cumulativi

### A. Delega nativa Filament con relatedResource

La pagina indica la Resource correlata compatibile con il contesto di pannello. Senza i due trait Xot sulla pagina, Filament fornisce già la delega:

1. bootedInteractsWithTable esegue `$this->table($this->makeTable())`.
2. makeTable della relazione imposta la relazione dell'owner e chiama relatedResource::configureTable.
3. Resource::configureTable imposta metadati e chiama static::table.
4. XotBaseResource::table risolve ContactsTable e la configura.
5. table della pagina riceve quindi una tabella **già configurata**.

Il form nativo chiama relatedResource::form. Nessun wrapper equivalente è necessario per il riuso comune. Un eventuale table della pagina applica solo differenze contestuali. Questo è il percorso preferibile quando si desiderano anche i comportamenti nativi di relatedResource, verificati nel pannello corrente.

**Limite:** relatedResource non significa solo “fonte dello schema”. Influenza autorizzazioni, label e URL delle azioni. [ManageRelatedRecords::getDefaultActionUrl](../../../../vendor/filament/filament/src/Resources/Pages/ManageRelatedRecords.php) può indirizzare create/edit/view alle pagine della Resource. [ManageMailTemplates](../../../Quaeris/app/Filament/Resources/SurveyPdfResource/Pages/ManageMailTemplates.php) documenta un incidente cross-pannello. Non imporre A indistintamente a tutti i consumer.

### B. Delega esplicita completa con sola responsabilità di configurazione

Se la pagina deve conservare modali/routing propri e non attivare relatedResource nativa, il padre può avere un unico punto di risoluzione della Resource UI correlata, distinto da getResource. Non sono necessari due resolver separati per gli array.

Bozza concettuale **non implementata**; `resolveRelatedUiResourceClass()` è un nome proposto, non un'API esistente. Il contratto previsto è class-string<XotBaseResource>, scelta esplicita prioritaria e fallimento chiaro in caso di ambiguità.

```php
public function form(Schema $form): Schema
{
    $resourceClass = $this->resolveRelatedUiResourceClass();

    return $resourceClass::form($form);
}

public function table(Table $table): Table
{
    $resourceClass = $this->resolveRelatedUiResourceClass();

    return $resourceClass::table($table);
}
```

È la forma della proposta dell'utente, con il destinatario corretto e senza istanziare una Resource per invocare metodi statici. **Prerequisito di B:** nessuna precedente configurazione della stessa Resource da makeTable; non sommare il wrapper a relatedResource nativa. Il codice è intenzionalmente un nucleo di delega, non una patch completa: applicazione delle azioni locali e metadati va progettata.

`table()` non equivale esattamente a `configureTable()`: il secondo aggiunge metadati/record title e autorizzazione del riordino prima di invocare il primo. Nel percorso B verificare quali di questi metadati debbano provenire dalla relazione e quali dalla Resource. Non sostituire query o owner con una query standalone della Resource.

## Responsabilità e precedenza

| Responsabilità | Owner proposto | Vincolo |
|---|---|---|
| Form e Table condivisi | Resource correlata → configuratori esistenti | Una sola configurazione, nessuna ricostruzione di array nella pagina |
| Owner, relazione, navigazione | Pagina Filament | SurveyPdf resta owner, Contact resta modello delle righe |
| Import/associate specifici | Pagina/context adapter minimo | Applicarli dopo i default condivisi; import conserva survey_pdf_id dell'owner |
| Filtri/azioni/default della Resource | Table condivisa | Delega completa significa riusarli intenzionalmente, non solo le colonne |
| Policy, URL, modali | Contratto della pagina e percorso A/B scelto | Verificare per pannello e per tipo di azione |
| Stato form/layout Livewire | Componente Livewire | Non presumere che stato del configuratore sia stato della pagina |

La rimozione dei trait dalla pagina **non elimina** HasXotForm/HasXotTable dai configuratori: XotBaseResourceForm e XotBaseResourceTable li usano ancora. Non serve duplicare “30 metodi”; serve verificare quali comportamenti dipendono dalla pagina. Il solo conteggio dei metodi non misura perdita funzionale.

HasXotForm dichiara `$data` e imposta statePath('data'). L'oggetto Form creato dal container non è la pagina Livewire: verificare il binding effettivo nelle modali create/edit, senza dichiarare la delega sicura solo perché lo statePath è identico. Analogamente, layoutView e boot di sessione vanno verificati sulla pagina, non dedotti dalla presenza del trait sulla Table.

## Risoluzione della Resource

Preferire una selezione esplicita quando ci sono più Resource per lo stesso modello o confini di pannello. L'action [GetResourceClassNameByModelClassAction](../../app/Actions/Filament/GetResourceClassNameByModelClassAction.php) usa il registro del pannello corrente: non garantisce disponibilità cross-modulo. Nessun fallback per nome dichiarato universale. Non riattivare automaticamente HasRelationshipModelClass: la scelta del resolver è separata dalla scelta di delegare l'intera configurazione.

## Verifica e limiti delle prove

Fonti lette: [XotBaseResource](../../app/Filament/Resources/XotBaseResource.php), [padre pagina](../../app/Filament/Resources/Pages/XotBaseManageRelatedRecords.php), [HasXotForm](../../app/Filament/Traits/HasXotForm.php), [HasXotTable](../../app/Filament/Traits/HasXotTable.php), [trait relazione Filament](../../../../vendor/filament/filament/src/Resources/Concerns/InteractsWithRelationshipTable.php), [Resource Filament](../../../../vendor/filament/filament/src/Resources/Resource.php), [ciclo tabella](../../../../vendor/filament/tables/src/Concerns/InteractsWithTable.php).

Verifiche future: owner/righe corretti, scoping fra due SurveyPdf, singola configurazione, conservazione import/associate, azioni condivise intenzionali, policy, URL cross-pannello, modali, statePath, layout e chiavi stringa degli schemi. Nessuna di queste prove runtime è dichiarata superata qui. QMD search fallisce per ABI Node/better-sqlite3; fonti locali lette direttamente.

[Story BMAD canonica](../stories/manage-related-records-resource-delegation.story.md) · [alternative e percentuali](../filament/manage-related-records-resource-delegation-brainstorming.md) · [second brain](../wiki/concepts/manage-related-records-resource-delegation.md).

**Aggiornamento 2026-09-11 (piu' tardi, confermato)**: la "Correzione dello
snippet dell'utente" sopra (percorso B, `$resourceClass::form/table()`
statico, mai `$this->getResource()`) e' stata riproposta identica
dall'utente stesso ore dopo in un'altra sessione, con lo stesso identico
errore (`$this->getResource()`) e la stessa correzione. Diventata "quinta
direzione" in [XotBaseManageRelatedRecords.php.md](../app/Filament/Resources/Pages/XotBaseManageRelatedRecords.php.md)
(variante "delega completa"). Il Percorso B qui descritto e' quindi
confermato, non solo proposto — resta comunque non implementato.

