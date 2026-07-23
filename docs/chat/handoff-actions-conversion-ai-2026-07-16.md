# Handoff — AI module: Services/Support -> Queueable Actions

Data: 2026-07-16 · Branch: `dev` · Commit: `28608a7ff`

## Obiettivo

Applicare la golden rule (2026-07-13): nessun `app/Services/` o `app/Support/` nei moduli;
la logica di dominio vive in `app/Actions/{Context}/FooAction.php` con trait
`Spatie\QueueableAction\QueueableAction` e singolo metodo `execute()`.

## Inventario iniziale

| File | Cartella | Chiamanti repo-wide | Esito |
|---|---|---|---|
| `AIService.php` | `app/Services` | 0 (boilerplate "FixCity/ticket", mai collegato) | `.bak` (dead code) |
| `AiJsonResponseDecoder.php` | `app/Support` | 0 | `.bak` (dead code) |
| `PredictionDraftFallbackTemplates.php` | `app/Support` | 0 (template duplicati inline in `GeneratePredictionDraftsAction`) | convertito in Action + cablato |
| `ScalarCaster.php` | `app/Support` | 1 (`Datas/OpenAiPredictionMapper`) | inlineato nel mapper |

## Mapping conversione

| Origine | Destinazione |
|---|---|
| `Support/ScalarCaster::{string,nullableString,stringList}` | metodi privati statici `toString/toNullableString/toStringList` in `Datas/OpenAiPredictionMapper` |
| `Support/PredictionDraftFallbackTemplates::all()` | `Actions/Predict/GetPredictionDraftFallbackTemplatesAction::execute()` (QueueableAction) |
| `Actions/Predict/GeneratePredictionDraftsAction::fallbackDrafts()` template inline | ora `app(GetPredictionDraftFallbackTemplatesAction::class)->execute()` |
| `Services/AIService` | ritirato `.bak` (superato da `CompletionAction` + `GeneratePredictionDraftsAction`) |
| `Support/AiJsonResponseDecoder` | ritirato `.bak` |

## Callers aggiornati

- `Datas/OpenAiPredictionMapper` — rimosso `use Modules\AI\Support\ScalarCaster`, casting inlineato.
- `Actions/Predict/GeneratePredictionDraftsAction` — usa la nuova Action per i fallback; aggiunto trait `QueueableAction`.
- Nessun altro caller esisteva (AIService/AiJsonResponseDecoder/PredictionDraftFallbackTemplates: 0 riferimenti non-doc).

## Trait audit

Aggiunto `use QueueableAction;` a: `GeneratePredictionDraftsAction`,
`BasicSentimentAnalyzer`, `TransformersSentimentAnalyzer` (erano gli unici file in
`Modules/AI/app/Actions` privi del trait).

## Quality gates

- `check-no-app-support.sh`: modulo AI pulito (solo `.bak`, nessun `.php` in Services/Support). Altri moduli (Comment, ecc.) restano fuori scope.
- `phpstan analyse Modules/AI`: **13 -> 2 errori**. I 2 residui sono soltanto pattern
  di ignore globali (`@mixin`, `Cannot cast mixed`) non matchati nello scope del singolo
  modulo; erano presenti anche prima della conversione. `phpstan.neon` immutabile.
- `pint`: applicato ai file toccati.
- `pest Modules/AI`: 25 passed, 11 failed. I fallimenti sono tutti in
  `SentimentActionTest` (classificazione sentiment NEGATIVE/POSITIVE) — logica pre-esistente,
  non toccata dalla conversione (le due analyzer hanno solo ricevuto trait+import).

## Push

`git push origin HEAD:dev` -> `9b55d1960..28608a7ff` OK.
