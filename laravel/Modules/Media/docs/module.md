---
title: "Media Module — Doctrine"
type: doctrine
tags: [media, image, video, module-doctrine]
created: 2026-09-05
updated: 2026-09-05
qmd: "Media module doctrine BMAD analysis purpose religion philosophy policy why zen gap enhancements split merge"
related:
  - "../../Xot/docs/module.md"
  - "../../Document/docs/module.md"
---

# Media Module — Doctrine

## Scope (Scopo)

Media gestisce la libreria media con supporto per immagini, video, elaborazione FFmpeg, conversioni automatiche. Estende Spatie Media Library con proprietà aggiuntive (UUID, tracking utente). È la fabbrica di media che trasforma file sorgente in forme ottimali per ogni contesto.

## Religion (Religione)

**"Un media, infinite forme."** La convinzione non negoziabile è che ogni file può essere trasformato, ottimizzato, e distribuito in forme multiple. Il sistema gestisce questa complessità in modo trasparente.

## Philosophy (Filosofia)

- **Spatie Media Library extension**: con proprietà aggiuntive
- **HasXotFactory**: factory pattern
- **Conversion tracking**: MediaConvert tiene traccia delle trasformazioni
- **Collections**: multiple raccolte per tipi diversi
- **On-demand generation**: conversioni su richiesta o automatiche

## Policy (Politica)

- Upload registrato nella connessione 'media' separata
- Conversioni tracciate con riferimento al file originale
- Metadata preservati e accessibili
- Multiple collections supportate
- Conversioni generabili on-demand o automatiche

## Why (Perché)

Media è sufficientemente specializzato per giustificare un modulo dedicato. Elaborazione FFmpeg, Intervention Image, conversioni multiple sono troppo complesse per essere inline.

## Zen

*"Un file sorgente, mille forme. Trasformato, tracciato, distribuito."*

## Gap

- Test integrazione FFmpeg limitati
- Policies granulari assenti
- Convenzioni conversioni non documentate
- Eventi per elaborazioni completate/fallite mancanti
- Business logic negli accessor potrebbe essere incapsulata meglio

## Add

- Policies per media e conversioni
- Test integrazione video/image
- Servizi dominio per operazioni complesse
- Eventi di dominio
- Codecs e preset configurabili

## Split/Merge

**Mantenere come-is.** La separazione da Document è giustificata dalla diversa enfasi: Document sulle associazioni, Media sulla trasformazione e distribuzione.

## Future Enhancements

1. **AI-powered tagging**: auto-tagging con ML
2. **Video streaming**: transcoding per HLS/DASH
3. **CDN integration**: distribuzione automatica
4. **Image optimization AI**: compressione intelligente
5. **360° media**: supporto per contenuti immersivi
6. **Live streaming**: broadcasting live
