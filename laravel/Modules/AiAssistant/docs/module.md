---	

title: "AiAssistant Module — Doctrine"
type: doctrine
tags: [aiassistant, chat, operator, module-doctrine]
created: 2026-09-05
updated: 2026-09-05
qmd: "AiAssistant module doctrine BMAD analysis purpose religion philosophy policy why zen gap enhancements split merge AI merge decision"
related:
  - "../../Xot/docs/module.md"
  - "../../AI/docs/module.md"
  - "./gestionale-commesse-port.md"
---

# AiAssistant Module — Doctrine

## Scope (Scopo)

AiAssistant è l'assistente AI operativo per compiti quotidiani. Gestisce sessioni chat, tool-calling con NeuronAI, e integrazione con Groq/OpenAI. È un'applicazione AI specifica.

## Religion (Religione)

**"Un operatore, non un oracolo."** L'assistente deve essere operativo: non risponde domande generiche, esegue compiti specifici collegati alle entità del sistema.

## Philosophy (Filosofia)

- **Tool-first agent**: agente pensa in termini di tool
- **Session management**: conversazioni tracciate
- **Domain integration**: tool per domini operativi
- **Streaming responses**: output in tempo reale
- **Action proposals**: suggerisce azioni da confermare

## Policy (Politica)

- Sessioni tracciate con contesto completo
- Tool NeuronAI configurabili
- Provider Groq/OpenAI come backend
- Proposte azioni tracciate e confermabili
- Nessuna azione senza conferma esplicita

## Why (Perché)

AiAssistant esiste perché un AI generico non basta: serve un agente concreto che conosca il dominio, abbia tool specifici, e proponga azioni operative.

## Zen

*"Un operatore che conosce il sistema. Non parla, esegue."*

## Gap

- OperatorAssistantAgent presente ma da testare con NeuronAI reale
- Test di integrazione limitati
- Documentazione tool-calling incompleta
- Azioni per domini non completamente coperte

## Add

- Test integrazione con NeuronAI reale
- Coverage completo dei tool per ogni dominio
- Streaming UI migliorata
- Feedback loop per miglioramento
- Multi-language support

## Split/Merge

**Mantenere come-is, NON fondere con AI.**

La distinzione è architetturale: AI è infra generica, AiAssistant è applicazione specifica.

## Future Enhancements

1. **Multi-agent orchestration**: coordinamento agenti specializzati
2. **Learning from feedback**: miglioramento proposte da feedback
3. **Proactive suggestions**: suggerimenti proattivi
4. **Agent personality**: configurazione tono
5. **Voice interface**: integrazione speech-to-text
6. **Mobile-first**: UI ottimizzata per mobile
