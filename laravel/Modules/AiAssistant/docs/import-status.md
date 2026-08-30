---
title: "Import status — AiAssistant"
type: concept
tags: [import, aiassistant, gestionale-commesse, filament]
created: 2026-07-23
updated: 2026-07-24
qmd: "import status AiAssistant panel migrate uuid users phpstan"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
  - "https://github.com/laraxot/base_workorder_fila5/issues/14"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
  - "https://github.com/laraxot/base_workorder_fila5/discussions/15"
related:
  - "./README.md"
  - "./laraxot-reshape-fix.md"
  - "./visibility.md"
  - "../../../../docs/gestionale-commesse-comparison/functional-readiness.md"
  - "../../../../docs/gestionale-commesse-comparison/replica-singular-phase.md"
  - "../../../../laravel/Themes/docs/gc-modules-runtime-matrix.md"
---

# Import status — AiAssistant

**Perché esiste:** assistente AI field-service (thread/proposal/tool) — **replica**, non merge nel modulo `AI`.

- [x] Reshape + XotBaseModel + QueueableAction
- [x] Migrazioni `ai_*`; user cols = `string(36)` no FK
- [x] Enabled + panel `aiassistant/admin` (+ API routes)
- [x] **PHPStan L10** — 0 errori reali (Neuron stub `static` fluent + seeders Factory)
- [ ] Pest smoke chat/operator
- [ ] **Non cancellare** / non merge in AI

SSoT: [functional-readiness.md](../../../../docs/gestionale-commesse-comparison/functional-readiness.md) · religione [replica-singular-phase.md](../../../../docs/gestionale-commesse-comparison/replica-singular-phase.md).

## 2026-08-30 — anticipo dal ciclo Email

Portata `Exceptions/AiActionNotAllowedException` (namespace dest flat
`Modules\AiAssistant\Exceptions`): serve al catch dei processor Email/WhatsApp.
Il resto del delta guard AI (policy in `CreateAiActionProposalAction`, redazione
log/persistenza, stream chat) verrà portato nel ciclo AiAssistant.

## Delta seconda tranche (2026-08-30) — guardie di sicurezza AI

- **`AiActionPolicy`**: allow-list dei tipi di azione che un agente può proporre e
  tetto di proposte per utente nella finestra. La lista è chiusa di proposito: un
  tipo aggiunto domani all'esecutore resta rifiutato finché qualcuno non lo elenca,
  invece di passare perché nessuno ha pensato a vietarlo. Il conteggio è per utente
  e non per thread: un agente che va in loop apre thread nuovi mentre gira, quindi
  un tetto per thread non verrebbe mai raggiunto.
- **`SensitiveDataRedactor`**: due livelli diversi. I segreti (chiavi API, bearer
  token, chiavi private) si tolgono ovunque — in una risposta del modello sono una
  falla per chiunque la legga. I dati personali si tolgono solo verso i log, perché
  oscurare il telefono di un cliente nella risposta dell'assistente romperebbe
  proprio la cosa per cui l'assistente esiste. Minimizzare ciò che si *conserva*,
  non ciò che si *mostra* a chi ha già diritto di vederlo (OWASP LLM02 in uscita,
  minimizzazione GDPR sul percorso di logging).
- **`AiOutputGuard`** e `AiActionNotAllowedException`, con `config/ai_security.php`
  (solo default: niente `env()` nei config di modulo, come già in Catalog/metel).

### Correzione: il logger dei test non ascoltava più nulla

`LlmRawLoggingTest` usava la firma storica di `Log::listen()` a tre argomenti; da
Laravel 9 il listener riceve un solo `MessageLogged`, quindi la closure esplodeva al
primo log. Inoltre l'helper restituiva l'array **per valore**, e i log arrivano dopo
quella chiamata: anche sistemata la firma sarebbe rimasto sempre vuoto. Ora torna un
`ArrayObject`. Da 2 rossi a 20 verdi sul modulo.
