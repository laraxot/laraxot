---
title: "Memoria: delega completa, owner distinto"
type: decision
status: discussion
implementation_status: not-started
created: 2026-09-11
updated: 2026-09-11
tags: [bmad, second-brain, filament, dry, kiss]
---

# Memoria: delega completa, owner distinto

Decisione proposta: la pagina riusa form/table completi della Resource correlata, non soltanto array di componenti. Fonte canonica: [fattibilità](../../architecture/xotbasemanagerelatedrecords-form-table-delegation-feasibility.md).

Fatti da ricordare:

- getResource della pagina restituisce la Resource dell'owner: in ManageContacts è SurveyPdfResource.
- Chiamare la Resource concreta staticamente riusa XotBaseResource con il corretto late static binding; app non è necessario.
- relatedResource nativa configura già la tabella in makeTable. Un wrapper che la richiama nuovamente duplica la configurazione.
- Delega completa esplicita con sola Resource UI è alternativa al percorso nativo, non un'aggiunta automatica.
- Rimuovere i trait dalla pagina non li rimuove dai configuratori; verificare separatamente lo stato Livewire e le azioni contestuali.
- Registro per pannello e generazione degli URL richiedono verifica: niente risoluzione universale per nome.

Superate le raccomandazioni precedenti di soli hook array o mantenimento obbligatorio di HasXotTable. Nessuna implementazione e nessuna validazione runtime nuova.

[Story BMAD](../../stories/manage-related-records-resource-delegation.story.md) · [alternative](../../filament/manage-related-records-resource-delegation-brainstorming.md) · [indice](../index.md).


## Proposta completa del file

[PHP con PHPDoc e note di migrazione](../../app/Filament/Resources/Pages/XotBaseManageRelatedRecords.php.md). Review separata: toggle layout può diventare inerte senza HasTableLayoutPage; il registro sceglie la prima Resource corrispondente e non rileva ambiguità. Non confondere il blocco consigliato con il contributo storico conservato in fondo.

## Getter nativo e registro

getRelatedResource legge la property nullable, non deduce la Resource dalla relazione. Il getter di registro esistente è Filament::getModelResource; Xot lo incapsula già in GetResourceClassNameByModelClassAction. Non aggiungere Action duplicative per aggirare il solo default null. Frontmatter della story ora distingue antecedente GitHub verificato da tracking specifico ora collegato nella story.

## Tracking condiviso

[Issue Xot #112](https://github.com/laraxot/module_xot_fila5/issues/112) e [Discussion Xot #114](https://github.com/laraxot/module_xot_fila5/discussions/114). Issue per perimetro/ownership e criteri; discussion per decisioni. Solo proposta documentale, sviluppo non iniziato.

**Riconciliazione 2026-09-11**: esiste un secondo filone di tracking sullo
stesso argomento, issue/discussion #115/#117 (story
`xotbasemanagerelatedrecords-convention-over-configuration.story.md`),
nato indipendentemente. `GetRelatedResourceClassAction` (qui sopra
considerata "da giustificare rispetto a `GetResourceClassNameByModelClassAction`")
esiste gia', e' implementata e testata in quel filone — non e' piu' una
proposta aperta. I due filoni descrivono la stessa decisione; non
riconciliati in un'unica issue per non chiudere tracking altrui senza
conferma esplicita.
