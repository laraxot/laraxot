---
title: "Rimozione dei trait dalla pagina: responsabilità da preservare"
type: architecture
status: discussion
implementation_status: not-started
created: 2026-09-11
updated: 2026-09-11
tags: [bmad, second-brain, filament, dry, kiss]
---

# Rimozione dei trait dalla pagina: responsabilità da preservare

Direzione proposta: rimuovere HasXotForm e HasXotTable da XotBaseManageRelatedRecords e delegare la configurazione completa alla Resource correlata. Non applicato.

È ritirata la precedente conclusione “HasXotTable non va tolto perché bisognerebbe duplicare tutti i suoi metodi”. Il trait resta nei configuratori XotBaseResourceTable; il comportamento va seguito lungo il ciclo reale, non contato per numero di metodi.

È ritirata anche la conclusione “HasXotForm è quasi vuoto, quindi rimozione a rischio zero”: contiene stato data, schema e colonne. Occorre verificare dove risiede lo stato delle modali. Non promettiamo perdita zero senza prove.

Non proponiamo di conservare i trait solo per aggirarne la precedenza dei metodi con dichiarazioni sulla stessa classe. La precedenza PHP fra classe e trait non risolve il problema architetturale dei due percorsi di configurazione. Non è corretto confonderla con il divieto di override di un metodo final ereditato.

La pagina conserva owner e relazione; i configuratori gestiscono UI condivisa; le differenze contestuali vengono applicate esplicitamente dopo i default. Stato Livewire, layout, URL e autorizzazioni sono criteri di verifica, non motivi per copiare un intero trait.

Il metodo table della pagina non deve riconfigurare la Resource se relatedResource l'ha già configurata dentro makeTable. Per il percorso esplicito alternativo, la risoluzione UI deve essere distinta da getResource dell'owner.

[Analisi canonica con i due percorsi](xotbasemanagerelatedrecords-form-table-delegation-feasibility.md) · [story BMAD](../stories/manage-related-records-resource-delegation.story.md).

**Aggiornamento 2026-09-11 (piu' tardi)**: percorso B confermato dall'utente
stesso in una sessione successiva ("quinta direzione", variante "delega
completa") in [XotBaseManageRelatedRecords.php.md](../app/Filament/Resources/Pages/XotBaseManageRelatedRecords.php.md).
Non implementato.

