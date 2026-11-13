---
title: "Mobile demo accessibility contract"
type: contract
theme: Sixteen
created: 2026-09-27
updated: 2026-09-27
qmd: "sixteen mobile demo accessibility nativephp touch target focus contrast"
status: draft
tags: [docs, sixteen]
---

# Contratto demo mobile e responsive

Sixteen governa web/PWA; NativePHP usa componenti nativi e non copia markup web. La
demo deve però mantenere lo stesso linguaggio: stato chiaro, CTA unica, testo leggibile,
contrasto verificabile e fallback per errore/offline.

## Gate minimi

- viewport web: 390, 768 e 1440 px, senza overflow orizzontale;
- focus visibile e ordine logico su ogni form e CTA;
- target interattivi almeno 44 px web e 48 dp native;
- label, nome accessibile e feedback di errore associato al campo;
- colori da token del tema, mai colore arbitrario per stato semanticamente importante;
- riduzione movimento rispettata e nessun messaggio espresso solo con colore;
- demo nativa verificata con VoiceOver/TalkBack prima di dichiararla pronta.

La prova di accessibilità non è sostituita da un punteggio statico: conservare screenshot,
console report e percorso riproducibile nell'evidence della sessione.
