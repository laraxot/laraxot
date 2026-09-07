---
name: 2026-09-07-repo-wide-filament-fatal-bugs-audit
description: "Audit repo-wide di tutte le Filament Resource (35 moduli, 142 Resource) per il pattern bug fatale namespace/static/table override gia' visto su Quotation/SiteSurveyResource — 0 nuove istanze trovate, round di conferma"
metadata:
  document_type: chat_handoff
  status: completato
  date: 2026-09-07
  agent: claude sonnet 5
---

# Audit repo-wide bug fatali Filament Schemas/Tables — 2026-09-07

## Contesto

Scansione di tutte le Filament Resource in 35 moduli, alla ricerca del pattern di
bug **fatale** (non solo gap PHPStan) gia' documentato in second brain
`pattern_xotbase_schemas_wrong_namespace_fatal` e nella story
`laravel/Modules/Quotation/docs/stories/sitesurveyresource-schemas-bootstrap-fatal-fix.story.md`:

1. `use` con namespace errato verso `XotBaseResourceForm`/`XotBaseResourceInfolist`/`XotBaseResourceTable`
   (segmento `Resources\`/`Schemas\` mancante o spostato) — causa `Class ... not found`
   a class-load time, che Filament scatena in fase di bootstrap panel per **tutto** l'albero `Modules`.
2. Override non-static di `getFormSchema()`/`getInfolistSchema()` (dichiarati `static`,
   il secondo anche `abstract`, sul genitore) — fatal error di dichiarazione di classe.
3. Override vietato di `table()` con Action Filament in namespace inesistente
   (`Filament\Tables\Actions\*` invece di `Filament\Actions\*`).

## Esito

| Metrica | Valore |
|---|---|
| Moduli scansionati | 35 |
| Resource totali | 142 |
| Gap strutturali (Schemas/Tables mancanti, non fatali) | 59 — gia' tracciati in `laravel/Modules/Billing/docs/stories/4.3.filament-audit-14-modules.story.md` (swarm attivo separato, non toccato in questo giro) |
| Bug fatali trovati | 0 |
| Bug fatali corretti | 0 |
| Moduli con commit di fix | 0 (nessun fix da committare) |
| Moduli con nuova story BMAD | 0 (nessun fix da documentare come story nuova) |

Nessun modulo ha prodotto un bug fatale in questo giro. L'unica istanza nota del
pattern resta quella gia' corretta in precedenza su
`Modules/Quotation/app/Filament/Resources/SiteSurveyResource` (vedi story linkata sopra).
Questo round di audit **conferma** che il pattern non si e' ripresentato altrove
nell'albero `Modules/` al momento della scansione — non esclude che possa
ripresentarsi su estrazioni Schemas/Tables future (in particolare lo swarm epic 4
sui 14 moduli con gap strutturali, story 4.3), che va ri-controllato ad ogni nuova
estrazione, non una tantum.

## Tabella modulo -> bug trovati -> bug corretti -> committato

| Modulo | Bug fatali trovati | Bug fatali corretti | Committato |
|---|---|---|---|
| (nessuno, su tutti i 35 moduli scansionati) | 0 | 0 | n/a |

Nessuna azione di quality gate (PHPStan/Pint), nessuna nuova story BMAD, nessun
commit sono stati necessari in questo giro: le condizioni del task ("per ogni
modulo con almeno un fix") non si sono verificate perche' l'audit non ha trovato
nulla da correggere.

## Bug non corretti e motivo

Nessuno. L'audit non ha trovato bug fatali da correggere in questo giro.

## Note

- I 59 gap strutturali (Schemas/Tables mancanti) restano di competenza esclusiva
  della story `4.3.filament-audit-14-modules.story.md` (swarm gia' attivo, non
  toccata da questo giro per esplicita indicazione del task).
- Second brain aggiornato in coda a
  `pattern_xotbase_schemas_wrong_namespace_fatal.md` con l'esito di questo giro
  (0 nuove istanze, ambito ampliato da singolo modulo a repo-wide 35 moduli/142 Resource).
