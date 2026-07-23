# Claude Code Theme Rules Path Scoping

## Decisione

Le regole Claude Code che riguardano il tema Sixteen devono essere path-scoped su `laravel/Themes/Sixteen/**` e rimandare ai docs owner del tema.

`./.claude` e' il runtime Claude Code, ma Sixteen resta owner di:

- Blade del tema;
- CSS Design Comuni;
- integrazione bundle frontend;
- verifica visuale responsive desktop/tablet/mobile.

## Perche'

Claude Code carica sempre le rules senza `paths`. Le regole di tema sono molto utili quando si lavora su Blade/CSS/JS Sixteen, ma sono rumore per task backend o di altri moduli.

La soluzione corretta e':

1. docs owner nel tema;
2. rule Claude breve;
3. frontmatter `paths`;
4. verifica visiva quando cambia UI.

## Pattern Consigliato

```md
---
paths:
  - "laravel/Themes/Sixteen/resources/**/*.blade.php"
  - "laravel/Themes/Sixteen/resources/**/*.css"
  - "laravel/Themes/Sixteen/resources/**/*.js"
---

# Nome Regola

Sintesi breve e link al doc Sixteen owner.
```

## Rule Tema Gia' Path-Scoped

- `header-auth-state.md`
- `component-extraction-header.md`
- `no-page-specific-css.md`
- `theme-css-build-workflow.md`
- `visual-parity-verification.md`

## Verifica

Quando una modifica impatta UI pubblica o admin:

- verificare desktop, tablet e mobile;
- usare Playwright o browser reale;
- documentare screenshot/audit nel wiki del tema se il comportamento e' riusabile.
