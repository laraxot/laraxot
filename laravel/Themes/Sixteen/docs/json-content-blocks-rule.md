# Regola content_blocks per JSON delle pagine

## Regola

Tutti i file JSON nella cartella `config/local/fixcity/database/content/pages/` DEVONO usare la struttura `content_blocks` con la chiave lingua `it`:

```json
{
    "id": "tests.nome-pagina",
    "title": {
        "it": "Titolo in italiano",
        "en": "Title in English"
    },
    "slug": "tests.nome-pagina",
    "category": "Categoria",
    "content": null,
    "content_blocks": {
        "it": [
            {
                "type": "hero",
                "data": {
                    "view": "pub_theme::components.blocks.hero.default",
                    "title": "Titolo",
                    "subtitle": "Sottotitolo"
                }
            }
        ],
        "en": []
    },
    "sidebar_blocks": {
        "it": [],
        "en": []
    },
    "footer_blocks": {
        "it": "",
        "en": ""
    }
}
```

## Struttura obbligatoria

| Campo | Tipo | Obbligatorio | Descrizione |
|-------|------|--------------|-------------|
| `id` | string | ✅ | Identificatore univoco |
| `title` | object | ✅ | Titolo multilingua (it, en) |
| `slug` | string | ✅ | Slug per routing |
| `category` | string | ✅ | Categoria della pagina |
| `content` | null | ✅ | Campo legacy (sempre null) |
| `content_blocks` | object | ✅ | Blocchi di contenuto |
| `content_blocks.it` | array | ✅ | Blocchi in italiano |
| `content_blocks.en` | array | ✅ | Blocchi in inglese (vuoto o array) |
| `sidebar_blocks` | object | ✅ | Blocchi sidebar (it, en) |
| `footer_blocks` | object | ✅ | Blocchi footer (it, en) |

## Perché questa struttura

1. **Multilingua**: La struttura `content_blocks.{locale}` supporta traduzione futura
2. **Consistenza**: Tutte le 90+ pagine usano lo stesso pattern
3. **Componenti separati**: `sidebar_blocks` e `footer_blocks` permettono gestione indipendente
4. **Traducibilità**: Il nodo `title` con chiavi `it`/`en` è il pattern standard per i18n

## Errori comuni da evitare

### ❌ ERRORE: non usare `blocks` alla root
```json
// SBAGLIATO
{
    "slug": "tests.pagina",
    "blocks": [...]
}
```

### ❌ ERRORE: non usare `content_blocks` come array
```json
// SBAGLIATO
{
    "content_blocks": [...]
}
```

### ✅ CORRETTO: `content_blocks` come object con chiavi lingua
```json
// CORRETTO
{
    "content_blocks": {
        "it": [...],
        "en": []
    }
}
```

## Verifica automatica

Per verificare che tutti i JSON seguano la regola:

```bash
# Cerca file che usano la vecchia struttura
grep -r '"blocks"' config/local/fixcity/database/content/pages/
grep -r '"content_blocks": \[' config/local/fixcity/database/content/pages/
```

## File sistemati

- `tests.homepage.json` - convertito da `content_blocks: []` a `content_blocks: { it: [], en: [] }`
- `tests.argomenti.json` - convertito da `blocks: []` a `content_blocks: { it: [], en: [] }`