# Theme philosophy zen

**Path**: `laravel/Themes/TwentyOne/docs/THEME_PHILOSOPHY_ZEN.md`
**Last updated**: 2026-03-26

## Il ruolo del tema

Il tema e il vestito, non il motore dati.

Fa:

- layout;
- shell di pagina;
- composizione visuale;
- motion, typography, styling.

Non fa:

- query di dominio;
- widget tabellari custom fuori modulo;
- eccezioni architetturali locali.

## Regola operativa

- la shell detail `[container0]/[slug0]` resta agnostica;
- Blade resta bridge-only;
- se una sezione del detail mostra una collezione strutturata di outcome con comportamenti tabellari, il tema invoca il widget modulo e non lo rimpiazza;
- nel repository il contratto corretto del widget e `XotBaseTableWidget`.

## Perche

- il tema deve restare riusabile;
- il riuso vero richiede la stessa gerarchia tecnica per pattern uguali;
- DRY + KISS peggiorano quando ogni detail inventa il proprio listato.

## Riferimenti bidirezionali

- [TwentyOne docs index](./00-INDEX.md)
- [Predict detail agnostic contract](./PREDICT_DETAIL_AGNOSTIC_CONTRACT.md)
- [Predict docs index](../../../Modules/Predict/docs/00-INDEX.md)
- [AI agents architecture rule](../../../../bashscripts/ai/.agents/docs/architecture/filament-table-vs-blade-component.md)
