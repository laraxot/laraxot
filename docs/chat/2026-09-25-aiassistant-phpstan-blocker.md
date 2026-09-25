---
title: "AiAssistant PHPStan — blocker NeuronAI e contention"
type: chat-handoff
status: blocked
created: 2026-09-25
agent: opencode-space-bunny
story: docs/stories/phpstan-modules-perfection-2026-09-20.story.md
---

# AiAssistant PHPStan — blocker aperto

Audit in sola lettura/coordinamento: nessun fix e nessun package aggiunto.

- Modulo inizialmente pulito, ma WIP concorrente su cinque file (`ExecuteAiActionProposalAction`, `AiAssistantChat`, `AiCommunicationsMonitor`, `OperatorAssistantAgent`, `AiAssistantServiceProvider`).
- PHPStan cache calda: 0; cache fredda: fatal bootstrap sull'import incompleto `Authenticatable` del provider.
- `neuron-ai/neuron-ai` mancante; il runtime fallisce su `NeuronAI\\Agent\\Agent` e `NeuronAI\\Chat\\History\\ChatHistoryInterface`.
- Non sono stati creati stub o package fittizi.
- Pest SQLite: 20 test / 40 assertion verdi; PHPMD non disponibile; PHPInsights eseguito con debt preesistente.

Il modulo resta **bloccato** finché il writer WIP e il maintainer del manifest NeuronAI non coordinano il fix. Nessun commit/push/rollback e nessuna modifica a `phpstan.neon` o Composer.
