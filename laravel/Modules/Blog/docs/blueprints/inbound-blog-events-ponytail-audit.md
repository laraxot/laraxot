---
title: "Audit Ponytail — Blueprint inbound Blog/Eventi"
type: audit
status: decision-input
updated: 2026-10-15
module: Blog
tags: [ponytail, filament-blueprint, inbound, bmad]
related:
  - ./inbound-blog-events.md
  - ../../../../Fixcity/docs/bmad/inbound-marketing/purpose.md
qmd: "ladder ponytail applicata allo scopo inbound blog"
---

# Ladder Ponytail applicata allo scopo inbound

Scopo: abilitare blog + eventi per fiducia e adozione B2G
([purpose](../../../../Fixcity/docs/bmad/inbound-marketing/purpose.md)).
Questo audit verifica il **minimo sufficiente** contro il codice reale
(2026-10-15). Nessuna implementazione autorizzata qui.

## 1. Serve davvero? (YAGNI)

| Idea | Esito | Motivo |
|---|---|---|
| Blog + eventi inbound | **Sì** | Scopo fiducia → qualifica → adozione |
| Modulo `Events` nuovo | **No** | `Article` ha già date evento |
| Resource Filament nuova | **No** | Esiste `ArticleResource` |
| Widget calendario BO | **No** | Lista + filtro bastano al pilota |
| Newsletter automatica | **Deferred** | Serve Gdpr/Notify; non blocca pilota contenuti |
| Ad-tech / pixel | **No** | Conflitto con scopo |

## 2. Esiste già nel repository?

Verificato sul codice:

| Pezzo | Stato |
|---|---|
| `Article::$fillable` include `type`, `event_start_date`, `event_end_date` | Presente |
| Migration `create_articles_table` colonne date evento | Presente |
| `ArticleInfolist` mostra `type` | Presente |
| `ArticleFormMainGridSchema` campi `type` / date evento | **Assenti** |
| `ArticlesTable` badge `type` / colonna date / filtro type | **Assenti** (solo category/user; ri-verificato 2026-10-15) |
| `ThemeArticleQueries::comingSoon` filtra `event_start_date > now()` | Presente |
| `ThemeArticleQueries::comingSoon` + `paginatedComingSoon` | **Assente** `content_type=event` (solo `event_start_date > now()`; ri-verificato) |

Conclusione Ponytail: **non creare schema**; **completare form/table/query** sul
modello esistente quando il gate editoriale apre.

## 3–5. Stdlib / nativo Filament / dipendenze

Bastano `Select`, `DateTimePicker`, `SelectFilter`, `TextColumn` già usati nel
modulo. Nessuna nuova dipendenza Composer/npm.

## 6–7. Minimo completo (piano Blueprint)

Quando readiness passa, il minimo è:

1. `Select type` + date condizionali in `ArticleFormMainGridSchema` (o schema dedicato)
2. Validazione server: `event` ⇒ date required e fine ≥ inizio
3. Colonna + filtro `content_type` in `ArticlesTable`
4. Query pubbliche: eventi = `content_type=event` AND `event_start_date` futuro (non solo date)
5. Test Pest elencati nel Blueprint
6. **Zero** migration, **zero** Resource parallela, **zero** widget

## Rifiuti espliciti

- STI/parental nuovo per Event vs Article
- Tabella `events`
- Action «invia nurture»
- Hardcode contenuti in Sixteen

## Collegamenti

- Blueprint: [inbound-blog-events.md](./inbound-blog-events.md)
- Dossier: [inbound-marketing](../../../../Fixcity/docs/bmad/inbound-marketing/README.md)

## Scopo → minimo (perché Ponytail)

Lo scopo (fiducia → capacità → adozione) **non** richiede un dominio Eventi.
Richiede che l'editor possa pubblicare due *tipi* di contenuto sullo stesso
`Article` e che il FO non mostri articoli blog come «prossimi eventi».
Perciò: estendere form/table/query, non creare Resource.

SSoT scopo: [purpose](../../../../Fixcity/docs/bmad/inbound-marketing/purpose.md).
