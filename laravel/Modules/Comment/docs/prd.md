# Comment - Product Requirements Document (PRD)

> Documento vivente. Modulo commenti e interazione utente.
> Stato stimato: 62% implementato, 38% da consolidare.

## 1. Purpose & Vision

Il modulo **Comment** gestisce commenti, discussione e potenziale moderazione sui contenuti pubblicati. Nel contesto prediction market abilita contesto qualitativo e confronto fra utenti attorno ai mercati.

**Visione**: sistema commenti modulare, integrabile sui contenuti pubblicabili senza sporcare il dominio editoriale o il dominio market.

## 2. Problem Statement

Senza Comment:
- manca una conversazione persistente attorno ai contenuti
- i mercati perdono segnali qualitativi e discussione pubblica
- ogni modulo dovrebbe reinventare thread, moderazione e notifica

## 3. Target Users

| User | Ruolo | Bisogni |
|------|-------|---------|
| **Utente finale** | Commenta contenuti | Thread semplici e chiari |
| **Moderatore/Admin** | Tiene pulita la discussione | Visibilita', approvazione, audit |
| **Sviluppatore** | Integra commenti su modelli | Contratti semplici e riusabili |

## 4. Scope

### In Scope
- Commenti collegabili ai modelli pubblicabili
- Thread, reply e moderazione di base
- Integrazione con notifiche
- Estensione dei modelli commentabili

### Out of Scope
- Chat realtime general purpose
- Ranking reputazionale avanzato
- Settlement o scoring di prediction market

## 5. Functional Requirements

### P0
- **FR-001**: Consentire commenti sui contenuti pubblicabili.
- **FR-002**: Fornire un contratto commentabile riusabile.
- **FR-003**: Supportare visibilita' e moderazione base.

### P1
- **FR-004**: Notificare nuovi commenti ai soggetti interessati.
- **FR-005**: Supportare thread e reply.
- **FR-006**: Integrare sentiment o AI solo come supporto, non come fonte di verita'.

## 6. Non-Functional Requirements

- **NFR-001**: Nessun conflitto di trait o contratti Laravel/Filament.
- **NFR-002**: Compatibilita' con modelli di Blog e Predict.
- **NFR-003**: Tipizzazione rigorosa per relazioni e payload.

## 7. Technical Architecture

- **Dipendenze**: Xot, UI, Notify, Tenant.
- **Integrazione**: trait/concern sui modelli commentabili.
- **Surface**: frontoffice e possibili pannelli admin.

## 8. Current State & Gaps

### Stato reale al 2026-03-12
- Integrazione base commentabile: **70%**
- Moderazione strutturata: **45%**
- Notifiche coerenti: **55%**
- Copertura test business: **40%**

### Gap prioritari
- Va chiarito meglio il contratto di moderazione e visibilita'.
- Serve piu' documentazione sull'integrazione con `Blog\Article` e `Predict`.
- Le dipendenze configurative devono essere esplicitate senza passaggi manuali ambigui.

## 9. Success Metrics

| Metrica | Target |
|--------|--------|
| Modello commentabile integrabile senza hack | 100% |
| PHPStan su `Modules/Comment` | 0 errori |
| Thread pubblici senza regressioni runtime | 95% |

## 10. Risks & Assumptions

- Assunzione: il modulo restera' trasversale e non specifico al prediction market.
- Rischio: dipendere troppo da package esterni rende fragile il contratto locale.
- Rischio: moderazione incompleta degrada fiducia e qualita' del contenuto.

## 11. References

- [README.md](../README.md)
- [PRD Indice Centrale](../../../../docs/project/PRD_INDEX_2026_03_12.md)

## Testing & Coverage

- test su modelli commentabili
- test su pubblicazione e visibilita'
- test su notifiche dei commenti critici
