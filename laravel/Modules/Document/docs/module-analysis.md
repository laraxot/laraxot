---
title: Document Module Analysis
type: concept
tags: [document, documenti, file, storage]
created: 2026-09-05
updated: 2026-09-05
qmd: "document module-analysis scopo religione filosofia politica zen"
module: Document
---

# Document Module Analysis

## Scopo
Gestione documentale centralizzata. Upload, storage, versioning e retrieval di documenti con supporto per firme elettroniche e digitali.

## Religione
- **Polymorphic storage**: ogni documento appartiene a qualsiasi entità del sistema
- **Versioning automatico**: ogni upload sovrascrive crea una nuova versione
- **Storage drivers**: supporto per local, S3, Google Cloud, Azure Blob
- **MIME type validation**: solo file consentiti possono essere caricati
- **Virus scanning**: integrazione con ClamAV opzionale
- **Encryption at rest**: documenti sensibili cifrati nel storage

## Filosofia
- **Un posto per tutto**: tutti i documenti del sistema in un unico repository
- **Provenienza tracciata**: chi ha caricato cosa e quando
- **Accesso controllato**: permessi a livello di documento, non solo cartella
- **Longevità**: i documenti sopravvivono alle entità che li hanno generati

## Politica
- Ogni documento ha: file, nome, MIME type, size, hash (SHA256), owner, timestamps
- I documenti sono polymorphic: `documentable_type`, `documentable_id`
- Le versioni sono ordered per `version_number` discendente
- Il download genera audit log
- I documenti soft-deleted possono essere ripristinati entro 30 giorni
- Lo storage quota è per tenant

## Perché
Per centralizzare la gestione documentale in modo che ogni modulo non debba reinventare upload/storage/download.

## Zen
Il documento che vive per sempre - uploaded once, referenced everywhere, versioned automatically.

## Cosa manca
- Preview inline per PDF/docx/images
- Collaborative editing (Google Docs-like)
- Watermarking automatico
- OCR per PDF scansionati
- Full-text search nei documenti
- E-signature integration completa

## Cosa aggiungerei
- Document templates (pre-fill data)
- Automated archival policies (move to cold storage dopo X anni)
- Retention policies configurabili
- Chain of custody audit per documenti legali
- Digital signature verification automatica

## Divisione o Unione
- **Mantieni separato**: gestione documentale è cross-cutting concern
- **Potenziale unione**: potrebbe essere assorbito da Media se non servono firme
- **Conflitto**: overlap con Media (entrambi gestiscono file), ma Document ha versioning e firme mentre Media ha ottimizzazione immagini