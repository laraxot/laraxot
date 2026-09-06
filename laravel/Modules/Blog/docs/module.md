---
title: "Blog Module — Doctrine"
type: doctrine
tags: [blog, publishing, module-doctrine]
created: 2026-09-05
updated: 2026-09-05
qmd: "Blog module doctrine BMAD analysis purpose religion philosophy policy why zen gap enhancements split merge"
related:
  - "../../Xot/docs/module.md"
  - "../../Cms/docs/module.md"
  - "../../Comment/docs/module.md"
---

# Blog Module — Doctrine

## Scope (Scopo)

Blog gestisce la pubblicazione di articoli con categorie, tag, commenti, votazioni, profili utente, feed RSS/Atom, e social sharing. È la piattaforma di coinvolgimento che collega autori e lettori attraverso contenuti, interazioni, e distribuzione.

## Religion (Religione)

**"Un blog è una conversazione, non una vetrina."** La convinzione non negoziabile è che il blog deve facilitare l'interazione: commenti, reazioni, contenuti correlati. L'articolo è la radice di un aggregato che include categorie, tag, media, e metriche di engagement.

## Philosophy (Filosofia)

- **Aggregate pattern**: Articolo come radice dell'aggregato
- **Social features**: commenti, votazioni, condivisione come cittadini di prima classe
- **Feed generation**: RSS/Atom automatico da stato pubblicato
- **User profiles**: estensione del sistema utente centrale
- **Versioning**: ogni articolo versionato per revisione

## Policy (Politica)

- Slug e titolo obbligatori
- Contenuto supportato da blocchi strutturati (ereditati da Cms)
- Interazioni tracciabili e moderabili
- Feed generati automaticamente
- Profili utente estendono il contratto centrale

## Why (Perché)

Blog è sufficientemente complesso da giustificare un modulo dedicato. Gestisce domini distinti (contenuti, interazione sociale, distribuzione) che beneficiano di un contesto delimitato chiaro.

## Zen

*"La scrittura diventa conversazione. Autori e lettori, connessi."*

## Gap

- Test integrazione limitati per workflow completi
- Azioni con logica che potrebbe essere in servizi dominio
- API REST non documentata
- Eventi per transizioni di stato non implementati
- Policies granulari assenti

## Add

- Servizio di moderazione commenti con regole configurabili
- Test di comportamento per workflow social completi
- API resource documentate
- Suggerimenti contenuti correlati
- Webhook su eventi di pubblicazione

## Split/Merge

**Mantenere come-is.** Il modulo incapsula bene il dominio blogging con le sue preoccupazioni (contenuto, interazione, distribuzione). La divisione avrebbe senso solo per separare drasticamente contenuto da interazioni, ma romperebbe la coesione del dominio.

## Future Enhancements

1. **Newsletter automation**: invio automatico articoli via email
2. **Paid content**: articoli a pagamento con paywall
3. **Author dashboard**: analytics per autori
4. **Content calendar**: pianificazione pubblicazioni
5. **Social media auto-post**: pubblicazione automatica sui social
6. **Reading analytics**: tempo di lettura, completamento, engagement
