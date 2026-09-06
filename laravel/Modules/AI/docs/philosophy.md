# AI Module: LLM Conversations & Automation

> **OpenAI Integration** — Conversational AI, ticket classification, action proposals.

---

## Zen

**"AI as a tool, not a replacement. Human-in-the-loop."**

AI suggests; humans decide. Decisions are auditable, reversible, never autonomous.

---

## Architecture (Quick)

### Models (4)
- **AiThread** — Conversation (user, topic, status)
- **AiMessage** — Message in thread (role: user/assistant, content, tokens)
- **AiActionProposal** — AI-suggested action (model, action, status: pending/confirmed/rejected)
- **AiToolLog** — Function call log (tool_name, args, result)

### Pattern
```
User prompt
  ↓
AiMessage (log it)
  ↓
OpenAI API call
  ↓
AiActionProposal (if action needed)
  ↓
User confirm/reject
  ↓
Execute or discard
```

### Actions (11)
- `CompletionAction` — OpenAI chat completion
- `ClassifyTicketAction` — Categorize support ticket
- `ConfirmAiActionProposalAction` — User approves AI action
- `CancelAiActionProposalAction` — User rejects
- `AiJsonResponseDecoderAction` — Parse JSON from LLM (unreliable, retry)

---

## Integration

**Who uses**:
- Notify (auto-compose emails)
- Employee (classify absence requests)
- Job (suggest bulk operations)

---

## Best Practices

✓ **Always log tokens** (track cost, quota)
✓ **Require human approval** (AiActionProposal)
✓ **Retry JSON parsing** (LLM JSON is unreliable)
✓ **Thread-based** (context window management)

❌ **Never autonomous** (no auto-execute)
❌ **No sensitive data in prompts** (logs are searchable)

---

## Roadmap

- Multi-model support (Claude, Gemini, Ollama)
- Fine-tuning on domain tasks
- Streaming responses (real-time UI)
- Cost analytics (tokens/cost per user)

---

## Summary

```
┌──────────────────────┐
│ AI (OpenAI)          │
├──────────────────────┤
│ Purpose: LLM chat    │
│ Models: 4            │
│ Migrations: 4        │
│ Status: Experimental │
│ Dependencies: Xot    │
└──────────────────────┘
```

---

- **Generated**: 2026-09-06

