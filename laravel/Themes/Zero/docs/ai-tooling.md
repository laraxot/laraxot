---
title: "Strumenti AI nel tema Zero"
type: guide
tags: [ai-tooling, graphify, headroom, caveman, tema]
created: 2026-08-03
updated: 2026-08-03
qmd: "graphify headroom caveman tema Zero blade indicizzazione scaffold"
related:
  - ./no-ai-tool-scaffold-dirs.md
  - ../../../Modules/Xot/docs/ai-tooling-stack.md
---

# Strumenti AI nel tema Zero

Canonico dello stack (versioni, installazione, configurazione):
[Xot — ai-tooling-stack](../../../Modules/Xot/docs/ai-tooling-stack.md). Qui solo ciò che cambia
per un tema.

## graphify su un tema

Zero contiene 22 blade e 28 file PHP più il build Vite (`package.json`, `postcss.config.js`,
`public/`). L'estrazione ha senso su `resources/` e `app/`, mai sulla root del tema: `node_modules/`
e `public/build/` fanno esplodere il grafo con codice che non è del tema.

```bash
graphify update laravel/Themes/Zero/resources
graphify query "quali componenti usano il layout app" \
  --graph laravel/Themes/Zero/resources/graphify-out/graph.json
```

## La trappola: scaffold nel tema

`graphify update <path>` scrive `<path>/graphify-out/`. Su un tema questo crea esattamente la
categoria di cartella che [no-ai-tool-scaffold-dirs](./no-ai-tool-scaffold-dirs.md) vieta: il tema
vive anche come repo Git indipendente, e uno strumento lanciato nella sua root ci deposita la
propria cache ignorando le convenzioni del monorepo.

Mitigazione applicata: `graphify-out/` e `.headroom/` sono nel `.gitignore` del tema, oltre che in
quello della root. La regola resta: l'output degli strumenti non si committa mai, né qui né altrove.

## headroom e caveman

Nessuna configurazione specifica per tema. Headroom comprime output di tool (PHPStan, Pest, build
Vite) a livello di monorepo; caveman agisce sulle risposte dell'agente, non sul codice.

Una sola avvertenza per chi lavora sul tema: caveman **non** va tenuto attivo quando si scrivono
contenuti destinati all'interfaccia — label, traduzioni, testi di pagina. Comprime la sintassi, e
quel testo finisce davanti agli utenti finali.
