# Visual Parity Verification Rule

## Data: 2026-04-23

---

## REGOLA

Dopo **OGNI** modifica di file PHP, Blade, CSS o JS:

1. Aprire la URL interessata nel browser (Playwright MCP o manuale)
2. Verificare visivamente che il fix funzioni
3. Controllare la regressione delle pagine vicine
4. **Solo allora** considerare il task "completato"

---

## Perché

Il fix a livello di codice può risolver l'errore PHP ma non garantire che la pagina funzioni correttamente nel browser.

L'esperienza diretta: fixato un `EnumSelect::make()` con signature mismatch → il fatal error PHP è sparito → MA senza verifica browser non si può sapere se altri errori (JS, CSS, componenti) esistono.

---

## Anti-pattern

| Anti-pattern | Conseguenza |
|---|---|
| Modificare senza verificare | Bug nascosti, regressioni silenti |
| Solo `vendor/bin/phpstan` | Catcha type errors ma non regressione visuale |
| Solo `curl URL` | HTTP 200 != pagina funzionante |
| Dire "fixed" senza browser | Fix non validato — lavoro incompleto |

---

## URL di riferimento per segnalazione-crea

| Test | URL |
|---|---|
| Step wizard | `http://127.0.0.1:8000/it/tests/segnalazione-crea?step=form.dati-della-segnalazione::data::wizard-step` |
| Pagina completa | `http://127.0.0.1:8000/it/tests/segnalazione-crea` |
| Guest state | URL senza autenticazione |
| Auth state | URL dopo login |

---

## Workflow post-modifica

```
1. Modifica codice (PHP/Blade/CSS/JS)
2. Se CSS/JS: npm run build && npm run copy (da laravel/Themes/Sixteen)
3. grep "<<<<<<" (merge conflict markers) → risolvere se presenti
4. PHP lint del file modificato → php -l <file>
5. Aprire URL nel browser → verificare visualmente
6. Se OK → commit
```

### Ordine di priorità

| Step | Strumento | Cosa cattura |
|---|---|---|
| 1 | grep `<<<<<<<` | Merge conflict = bootstrap failure |
| 2 | `php -l <file>` | Syntax error immediato |
| 3 | Browser/Playwright | Errore 500, JS console error, regressione visuale |
| 4 | PHPStan level max | Type errors, signature mismatch, null dereference |

---

## False Friend: "HTTP 200"

```bash
# SBAGLIATO — curl ritorna 200 ma la pagina ha errori JS/CSS
curl -s -o /dev/null -w "%{http_code}" http://127.0.0.1:8000/it/tests/segnalazione-crea

# CORRETTO — aprire nel browser o usare Playwright snapshot
browser_snapshot → controlla contenuto visuale, console errors, stato guest/auth
```

Un HTTP 200 non garantisce che JavaScript funzioni, che CSS sia compilato, o che i dropdown aprano.

---

## Correlata

- `bashscripts/ai/.claude/rules/visual-parity-verification.md` — regola a livello progetto
- `bashscripts/ai/.claude/rules/post-modifica-verifica-obbligatoria.md` — verifica tools di qualità