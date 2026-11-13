---
issues: []
discussions: []
title: "Comment Module — Doctrine"
type: doctrine
tags: [comment, social, interaction, module-doctrine]
created: 2026-09-05
updated: 2026-09-05
qmd: "Comment module doctrine BMAD analysis purpose religion philosophy policy why zen gap enhancements split merge"
related:
  - "../../Xot/docs/module.md"
  - "../../Notify/docs/module.md"
status: draft
---

# Comment Module — Doctrine

## Scope (Scopo)

Comment gestisce commenti annidati, menzioni, reazioni, notifiche, moderazione, e integrazione polimorfica con qualsiasi entità. Trasforma il consumo passivo in dialogo attivo, costruendo comunità.

## Religion (Religione)

**"Ogni commento è una conversazione."** La convinzione non negoziabile è che i commenti costruiscono comunità: ogni interazione è tracciata, moderabile, collegata. Non sono feedback isolati.

## Philosophy (Filosofia)

- **Polymorphic model**: commentabile qualsiasi entità
- **Tree structure**: self-referencing per risposte
- **Pipeline design**: ogni commento passa attraverso processing
- **Notification-ready**: menzioni → notifiche
- **Moderation pipeline**: pre/post moderazione

## Policy (Politica)

- Relazione morphTo obbligatoria
- Struttura ad albero supportata (padre/figli)
- Menzioni tracciate con notifiche
- Moderazione pre o post configurabile
- Ogni azione tracciata (approva/rifiuta/reagisci)

## Why (Perché)

Commenti implicano gerarchia, menzioni, notifiche, moderazione. Un modulo dedicato evita di replicare questa logica in ogni entità.

## Zen

*"Da lettura a dialogo. Da contenuto a comunità."*

## Gap

- Test integrazione workflow limitati
- Policies assenti
- Azioni con logica dispersa
- API integrazione non documentata
- Eventi per flussi completi mancanti

## Add

- Policies per commenti, menzioni, reazioni
- Test per workflow completi
- Servizi dominio per operazioni complesse
- Eventi per ciclo di vita completo
- Discovery per entità altamente commentabili

## Split/Merge

**Mantenere come-is.** La complessità è ben incapsulata. Separare romperebbe coesione.

## Future Enhancements

1. **Sentiment analysis**: analisi tono
2. **Auto-moderation AI**: moderazione ML
3. **Threading optimization**: visualizzazione gerarchica
4. **Gamification**: punti/badge per engagement
5. **Rich media**: immagini/video nei commenti
6. **Comment analytics**: trend, sentiment, engagement
