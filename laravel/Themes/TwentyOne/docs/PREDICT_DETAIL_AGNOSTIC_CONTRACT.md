# Predict Detail Agnostic Contract

Path: `laravel/Themes/TwentyOne/docs/PREDICT_DETAIL_AGNOSTIC_CONTRACT.md`

## Scopo

Definire il contratto corretto della pagina:

- `resources/views/pages/[container0]/[slug0]/index.blade.php`

Il file e shared infrastructure del theme e deve restare agnostico.

## Regole

### 1. Nessuna variabile module-specific nel container

Da evitare:

- `$predict`
- `$article`
- `$event`

Nel container il payload neutro e:

- `item`
- `record`

Alias legacy sono ammessi solo per compatibilita in uscita verso blocchi non ancora riallineati.

### 2. Slug CMS detail

Il contratto detail deve essere:

- `$pageSlug = $container0 . '.view'`

Esempi:

- `predicts.view`
- `articles.view`
- `events.view`

### 3. Titolo e meta

Title e meta-description possono derivare dall`item corrente, ma senza introdurre logica di dominio nel wrapper oltre la semplice estrazione di testo.

### 4. Wrapper semantico minimo

Il file deve restare naked:

```blade
<div>
    <x-page side="content" :slug="$pageSlug" :data="$data" />
</div>
```

## Compatibilita

Per il caso `predicts`, il payload puo includere temporaneamente anche:

- `predict`

Ma la direzione architetturale resta:

- blocchi CMS che consumano `item` o `record`

## Esito Atteso

Il theme puo servire predicts, articles, events, profiles e altri detail pages senza introdurre route files specifici di modulo o blade dedicate al dominio.
