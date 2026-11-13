---
title: "Asset binari"
type: guide
tags: [theme, zero, binary, assets]
created: 2026-07-29
updated: 2026-07-29
qmd: "zero theme asset binari"
id: zero-docs-wiki-rules-binary-assets
slug: binary-assets
description: "Gli asset binari sono file normali del repository."
document_type: guide
category: architecture
status: active
issues: []
discussions: []
---
# Asset binari

Gli asset binari sono file normali del repository.

Regole:
- non aggiungere filtri o backend di storage esterno in `.gitattributes`;
- non committare file pointer al posto del contenuto reale;
- se un asset manca, recuperare il binario originale e committarlo direttamente;
- prima del push verificare che immagini, font, archivi e PDF siano contenuti reali.
