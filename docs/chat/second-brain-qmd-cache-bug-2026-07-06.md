---
title: "Second brain (qmd) — bug cache wrapper + stato embedding — 2026-07-06"
type: chat
created: 2026-07-06
updated: 2026-07-06
tags: [second-brain, qmd, llm-wiki, multi-agent]
---

# Second brain (qmd) — bug e stato — 2026-07-06 sera

## Contesto

Il "second brain" di questo repo NON è `docs/wiki/second-brain/` (solo una sottocartella tematica) — è l'intero sistema **LLM Wiki** in `docs/wiki/` indicizzato da **qmd** (Quick Markdown Search, binario reale in `/usr/bin/qmd`, ricerca ibrida BM25 + vettoriale + reranking), esposto tramite `bashscripts/docs/llm-wiki-qmd.sh`. Architettura coerente con il pattern "LLM Wiki / second brain" di Andrej Karpathy (raw/ immutabile + wiki/ sintetizzato, indice navigabile, niente RAG a chunk finché la wiki resta sotto ~500 pagine).

## Bug trovato: il wrapper ufficiale punta a una cache vuota

`bashscripts/docs/llm-wiki-qmd.sh` esporta:

```bash
export XDG_CACHE_HOME="${REAL_HOME}/.cache/qmd-cache"
```

Ma l'indice reale, popolato (1051 file .md indicizzati, 56 MB), vive nella posizione **di default** di qmd: `~/.cache/qmd/index.sqlite`. La variabile `XDG_CACHE_HOME=~/.cache/qmd-cache` (nome diverso, con suffisso `-cache`) fa sì che qmd cerchi/crei l'indice in `~/.cache/qmd-cache/qmd/` — **vuoto**. Risultato: ogni chiamata al wrapper documentato in *tutti* i CLAUDE.md di bootstrap (`bashscripts/docs/llm-wiki-qmd.sh search "<topic>" -n 5`) restituisce sempre **"No results found."**, mentre il binario `qmd` chiamato direttamente (`qmd search "phpstan"`) funziona perfettamente e trova risultati pertinenti.

**Impatto**: ogni agente che ha seguito fedelmente il bootstrap ufficiale finora ha creduto che la ricerca wiki non desse risultati (o peggio, ha concluso erroneamente che l'argomento non fosse documentato), quando in realtà l'indice reale è pieno e funzionante — semplicemente irraggiungibile dal canale ufficiale.

## Stato al momento della scoperta

- `bashscripts/docs/llm-wiki-qmd.sh` e `.lock` presente — **non toccato**, in mano a un altro agente (probabilmente sta indagando lo stesso problema in parallelo). Chi ha il lock: il fix è di una riga, rimuovere l'override di `XDG_CACHE_HOME` (o allinearlo al default `~/.cache/qmd`), poi verificare con `bash bashscripts/docs/llm-wiki-qmd.sh search "phpstan" -n 3` che torni risultati reali (non "No results found").
- `qmd status`: 1051 documenti indicizzati (collection `wiki`, pattern `**/*.md`), **0 vettori embedded, 1047 in attesa** — solo la ricerca lessicale BM25 (`qmd search`) funziona pienamente; la ricerca ibrida/semantica (`qmd query`, `qmd vsearch`) è degradata a solo-lessicale finché non si esegue `qmd embed`.

## Azione presa (non richiede il lock, non è una modifica file)

Eseguito `qmd embed` per generare gli embedding vettoriali mancanti (vedi esito sotto). Questa è un'operazione sui dati dell'indice (`~/.cache/qmd/index.sqlite`), non tocca file sotto version control, quindi non necessitava di `.lock`.

## Prossima azione per chi tiene il lock su `llm-wiki-qmd.sh`

1. Rimuovere/correggere la riga `export XDG_CACHE_HOME="${REAL_HOME}/.cache/qmd-cache"` (usare il default di qmd, o puntare esplicitamente a `~/.cache/qmd`).
2. Verificare: `bash bashscripts/docs/llm-wiki-qmd.sh search "phpstan" -n 3` deve restituire risultati reali.
3. Aggiungere un self-check/smoke-test (es. in `bashscripts/docs/llm-wiki-qmd.sh` stesso o in uno script separato) che fallisca rumorosamente se la ricerca su un termine noto-presente (es. "phpstan") restituisce zero risultati — per evitare che questa regressione silenziosa si ripeta inosservata.

Il `.lock` su `llm-wiki-qmd.sh` è rimasto fermo (nessun diff) per oltre 20 minuti in questa sessione — se lo trovi ancora così a distanza di ore, probabile lock orfano di un agente terminato senza pulizia: valuta di verificarne l'età prima di assumerlo ancora attivo.

## Aggiornamento (stessa sera): embedding vettoriali generati

Eseguito `qmd embed` (in background, il modello `embeddinggemma-300M-Q8_0.gguf` va scaricato la prima volta, ~333 MB). Durante l'attesa il numero di documenti indicizzati nella collection `wiki` è salito da 1051 a **5058** (altri agenti hanno esteso la collezione o rilanciato `qmd update` nel frattempo). Imbedding lasciato in esecuzione in background — con migliaia di documenti richiede tempo, non bloccante per l'uso di `qmd search` (BM25) che resta pienamente funzionante durante il processo. Aggiunto anche un contesto alla collezione root (`qmd context add qmd://wiki/ "..."`) per migliorare la qualità del retrieval ibrido una volta pronti i vettori, come da tip integrato di `qmd status`.

Verifica dello stato attuale: `qmd status` (mostra documenti totali, vettori embedded, pending).

## Nota sul lock — risolto

Il `.lock` su `llm-wiki-qmd.sh` è rimasto **senza alcun diff** per oltre 20 minuti (creato 21:33:40, ancora presente e invariato alle 21:47+), a differenza di altri lock osservati nello stesso arco di tempo (es. `Modules/Cms/tests/Feature/HomepageFilamentBlocksArchitectureTest.php.lock`, che nello stesso intervallo ha accumulato un diff reale). Verificato come lock orfano (nessun processo lo stava effettivamente editando), quindi con l'ok esplicito dell'utente ho proceduto al fix.

## Fix applicato

Rimosse le righe che sovrascrivevano `XDG_CACHE_HOME` e `XDG_CONFIG_HOME` con path paralleli (`~/.cache/qmd-cache`, `~/.cache/qmd-config`) mai usati da nessun'altra invocazione diretta di `qmd`. Il wrapper ora eredita l'ambiente reale, usando lo stesso indice (`~/.cache/qmd/index.sqlite`, 5058 documenti) e la stessa config (`~/.config/qmd/index.yml`) di ogni chiamata diretta a `qmd`.

Verificato dopo il fix:
```
bash bashscripts/docs/llm-wiki-qmd.sh search "phpstan" -n 3       # risultati reali, non più "No results found"
bash bashscripts/docs/llm-wiki-qmd.sh search "multi-agent coordination" -n 2   # idem, score 93%
```

Le vecchie directory orfane `~/.cache/qmd-cache/` e `~/.cache/qmd-config/` restano sul disco (non pulite, per non toccare stato di altri processi in corso) ma non sono più referenziate da nessun path di questo repo.

— Claude (`claude-sonnet-5`)
