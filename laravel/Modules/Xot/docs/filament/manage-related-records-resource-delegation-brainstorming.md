---
title: "Brainstorming: configurazione completa dei record correlati"
type: architecture
status: discussion
implementation_status: not-started
created: 2026-09-11
updated: 2026-09-11
tags: [bmad, second-brain, filament, dry, kiss]
---

# Brainstorming: configurazione completa dei record correlati

La proposta corrente accoglie il livello di delega indicato dall'utente: form/table completi della Resource concreta, non due array recuperati indirettamente. [Contratto, fonti e pseudocodice canonici](../architecture/xotbasemanagerelatedrecords-form-table-delegation-feasibility.md).

La selezione resta distinta: getResource della pagina è la Resource dell'owner. In ManageContacts lo snippet letterale configura SurveyPdf. Usare la Resource correlata e mantenere un solo percorso di configurazione.

## Alternative e percentuali

Punteggi soggettivi di aderenza progettuale, **non probabilità di successo né benchmark**. Pesi: DRY 35%, KISS 30%, prevedibilità 25%, compatibilità da valutare 10%. I valori descrivono le alternative, non certificano una migrazione.

| Variante | DRY | KISS | Prevedibilità | Compatibilità | Totale |
|---|---:|---:|---:|---:|---:|
| Recupero dei soli array | 60% | 70% | 75% | 75% | 68,25% |
| A: delega nativa, contesto relatedResource compatibile | 95% | 90% | 85% | 55% | 87% |
| B: delega completa esplicita, risoluzione UI distinta e unico percorso | 90% | 80% | 85% | 55% | 82,25% |

A riduce al minimo i wrapper quando si vogliono i comportamenti nativi. B è l'alternativa da progettare quando la sola configurazione va riusata mantenendo routing/modali locali. Non sommarle. Il recupero di array rimane solo un'alternativa storica parziale, non la raccomandazione finale.

Nel ManageContacts letto esistono 2 metodi di inoltro su 3 metodi dichiarati. Obiettivo futuro: eliminare 2/2 inoltri (100%); riduzione teorica dei metodi 3→1 (66,7%) **solo se** la configurazione delle azioni contestuali non richiede altri metodi. Nessuna percentuale globale di pagine convertibili o guadagno runtime è sostenuta da questa analisi.

## Decisioni ancora aperte

Risolvere Resource ambigue o non registrate senza fallback inventati; scegliere A/B per consumer; preservare import/associate; definire ordine di applicazione di metadati e azioni; verificare stato Livewire e modalità delle azioni. Non aggiungere nuovi campi o correggere gli schemi di Contact durante questo lavoro documentale.

[Story BMAD](../stories/manage-related-records-resource-delegation.story.md) · [second brain](../wiki/concepts/manage-related-records-resource-delegation.md) · [censimento](../architecture/xotbasemanagerelatedrecords-convention-over-configuration-brainstorm.md).

**Aggiornamento 2026-09-11 (piu' tardi)**: percorso B confermato
dall'utente in sessione successiva ("quinta direzione" in
[XotBaseManageRelatedRecords.php.md](../app/Filament/Resources/Pages/XotBaseManageRelatedRecords.php.md)).
`HasXotTable::getTableActions()` autorizza contro l'owner (bug preesistente,
verificato riga per riga), risolto strutturalmente dalla delega completa.
Non implementato.

