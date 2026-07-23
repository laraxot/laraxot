---
title: "Lezioni apprese — sessione BMAD create-story gestionale_commesse"
type: retro
tags: [bmad, lessons-learned, multi-agent, gestionale-commesse, self-correction]
created: 2026-07-23
updated: 2026-07-23
qmd: "lessons learned agent errors gestionale_commesse multi-agent porting singular naming"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
related:
  - "./gestionale-commesse-comparison/laraxot-compliance-audit.md"
  - "./gestionale-commesse-comparison/copy-then-normalize.md"
  - "./chat/gestionale-commesse-parity-handoff.md"
  - "./wiki/rules/multi-agent-lock-coordination.md"
  - "./wiki/rules/module-name-singular-mandatory.md"
---

# Lezioni apprese — porting gestionale_commesse (2026-07-23)

> Retro autocritica di un agente Claude Code su questa sessione. Scopo: non ripetere gli stessi errori nelle sessioni successive sullo stesso task o su task multi-agente analoghi.

## Errore 1 — Non ho controllato la coordinazione multi-agente prima di iniziare

Ho creato documenti di confronto (Auth vs User, HR vs Employee) ed un'epic BMAD senza prima cercare `docs/chat/`, issue/discussion GitHub, o file `.lock` esistenti. Un altro processo aveva **già** prodotto analisi più approfondite (`docs/gestionale-commesse-comparison/*`) collegate a Issue #7 / Discussion #8.

**Causa:** ho eseguito il comando `/bmad:create-story` seguendo solo le istruzioni del comando stesso, senza applicare la regola già scritta in questo repo — [multi-agent-lock-coordination.md](./wiki/rules/multi-agent-lock-coordination.md) — che impone di controllare `git remote`, lock file e `docs/chat/` **prima** di iniziare qualunque task.

**Come evitarlo:** prima di produrre qualunque artefatto di planning/documentazione in un repo con più agenti attivi, eseguire sempre: ricerca `docs/chat/*handoff*`, `grep` per issue/discussion recenti nel dominio del task, `find laravel/Modules -name "*.lock"`. Farlo diventa parte del pre-flight, non un controllo a posteriori.

## Errore 2 — Ho scambiato lavoro coordinato per una collisione pericolosa

Quando ho visto sparire `Modules/Vehicles` e `Modules/AiAssistant` tra un controllo e l'altro, ho reagito come se fosse una perdita di dati o un conflitto grave, fermando il lavoro e chiedendo conferma con toni allarmati. In realtà era l'esecuzione normale, pianificata e documentata (`laraxot-compliance-audit.md`, `module-name-singular-mandatory.md`) del rename plurale→singolare e del merge `AiAssistant`→`AI` da parte di un altro agente sullo stesso piano condiviso.

**Causa:** ho applicato la regola generale "investigare prima di assumere perdita di lavoro" in modo troppo letterale, senza prima verificare se il repo aveva già un piano scritto che spiegasse il cambiamento (che esisteva: `module-mapping.md`, `copy-then-normalize.md`).

**Come evitarlo:** quando un file/cartella sparisce in un repo con multi-agente noto attivo, il primo passo è cercare un documento di piano che lo preveda (`docs/gestionale-commesse-comparison/`, `docs/chat/`), **non** presumere immediatamente un errore. Solo se non esiste alcun piano che lo spieghi, trattarlo come anomalia da segnalare.

## Errore 3 — Ho collassato "import di massa" e "reshape XotBase completo" in un solo passo concettuale

Nella pianificazione iniziale (epic + story) ho trattato il porting di un modulo come un'unica operazione che include copia + rename singolare + reshape alla struttura canonica XotBase (`app/Models`, `XotBaseResource`, ecc.). L'utente ha corretto: la fase di import di massa iniziale deve limitarsi a **copia as-is + rename singolare**; il reshape XotBase è una fase 2 separata, da innescare modulo per modulo solo quando serve davvero (il modulo va abilitato, c'è una collisione di naming da risolvere, o inizia lavoro reale sulle feature).

**Causa:** ho generalizzato dal caso maturo (`Employee`, `User`, già completamente in struttura XotBase) assumendo che ogni nuovo modulo dovesse arrivare subito allo stesso livello, senza chiedere se la fase 1 dovesse fermarsi prima.

**Come evitarlo:** per un porting massivo di moduli esterni, proporre esplicitamente le due fasi separate nel piano/epic iniziale e chiedere conferma su quale fase eseguire, invece di assumere che "fatto bene" significhi "fatto fino in fondo alla struttura canonica" fin dal primo giro.

## Errore 4 — Non ho verificato lo stato attuale del filesystem prima di riportare conclusioni nella risposta finale

In una fase avevo relazionato la struttura di alcuni moduli (es. mapping "corrispondenza assente") basandomi sul report di un agente Explore che aveva già raccolto i dati minuti prima; nel frattempo lo stato reale del repo era cambiato (moduli nuovi apparsi/scomparsi). Questo ha causato incoerenze tra quanto riportato all'utente e lo stato reale al momento della lettura.

**Come evitarlo:** in un repo con agenti concorrenti attivi, trattare i risultati di un sub-agente Explore come una fotografia con timestamp, non come stato corrente garantito — se il gap temporale è significativo (minuti, non secondi) e la risposta finale è già rischiosa/di impatto, fare un controllo diretto e rapido (`ls`/`find`) sui path chiave prima di scrivere la conclusione all'utente.

## Cosa ha funzionato bene (da ripetere)

- Chiedere conferma con `AskUserQuestion` prima di eseguire operazioni ad ampio impatto (rename, delete, batch composer.json) invece di procedere in autonomia.
- Delegare l'esplorazione approfondita di un codebase esterno di grandi dimensioni (`gestionale_commesse`, 23 moduli) a un agente `Explore` dedicato invece di leggerlo file-per-file nel contesto principale.
- Salvare in memoria (`~/.claude/projects/.../memory/`) le correzioni ricevute dall'utente subito dopo la correzione, con il "perché", così le sessioni future partono già allineate.

## Riferimento memorie collegate

- `feedback_gestionale_commesse_module_naming.md` — replicate+singular-rename non è una collisione
- `feedback_porting_phases.md` — fase import di massa ≠ fase reshape XotBase
