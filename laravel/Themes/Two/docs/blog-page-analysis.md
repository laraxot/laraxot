# Blog Page Analysis: Target vs Local

## Data: 2026-02-07

## Sito Target
URL: https://lightseagreen-dogfish-560272.hostingersite.com/blog

### Struttura Pagina

#### Hero Section
- **Titolo**: "Blog e Risorse"
- **Sottotitolo**: "Guide pratiche, aggiornamenti normativi e consigli per mantenere il tuo studio sempre conforme e sicuro"
- **Background**: Gradiente o immagine hero

#### Filtri Categorie (Tabs/Pills)
- Tutti
- Dentistica
- Veterinaria
- Normative
- Formazione

#### Grid Articoli (8 articoli visibili)

**Articolo 1:**
- Categoria: Dentistica
- Titolo: "Sicurezza negli Studi Dentistici: Guida Completa 2026"
- Excerpt: "Tutto quello che devi sapere per garantire la massima sicurezza nel tuo studio dentistico secondo le normative più recenti."
- Data: 1 feb 2026
- Tempo lettura: 8 min
- CTA: "Leggi Articolo"

**Articolo 2:**
- Categoria: Veterinaria
- Titolo: "Biosicurezza Veterinaria: Proteggi il Tuo Team e gli Animali"
- Excerpt: "Protocolli essenziali di biosicurezza per cliniche veterinarie che garantiscono la salute di staff e pazienti animali."
- Data: 28 gen 2026
- Tempo lettura: 10 min

**Articolo 3:**
- Categoria: Normative
- Titolo: "Normative ATECO 74.99.21: Cosa Devi Sapere"
- Excerpt: "Analisi dettagliata del..."

## Locale Attuale

### Problemi Identificati

#### 1. Brand Errato
- JSON configurato per "TechPlanner" invece di "Marco Sottana"
- Riferimenti a "Servizi Municipali" invece di studi dentistici/veterinari

#### 2. Categorie Non Corrette
Attuali (errate):
- Radioprotezione
- Normativa
- Elettromedicali
- Guide Pratiche
- Veterinaria
- Novità

Target (corrette):
- Dentistica
- Veterinaria
- Normative
- Formazione

#### 3. Articoli Non Corrispondenti
Gli articoli nel JSON locale sono generici e non corrispondono al target.

## Piano di Correzione

### Fase 1: Aggiornare blog.json
- [ ] Cambiare titolo in "Blog e Risorse"
- [ ] Aggiornare sottotitolo con testo target
- [ ] Modificare categorie: Dentistica, Veterinaria, Normative, Formazione
- [ ] Creare articoli che corrispondano al target

### Fase 2: Verificare Componenti
- [ ] Controllare esistenza `hero.enhanced` blade component
- [ ] Verificare `blog.search-bar` component
- [ ] Verificare `blog.category-filter` component
- [ ] Verificare `blog.featured-grid` component

### Fase 3: Testare Rendering
- [ ] Screenshot pagina locale dopo modifiche
- [ ] Confronto visivo con target
- [ ] Verificare responsive

## Note Tecniche

Il blog.json usa:
- `view: "pub_theme::components.blocks.hero.enhanced"` per hero
- `view: "laravel.components.blog.search-bar"` per search
- `view: "laravel.components.blog.category-filter"` per categorie
- `view: "laravel.components.blog.featured-grid"` per articoli

Se i componenti non esistono, vanno creati o modificati i riferimenti.

## Collegamenti
- [blog.json](../../../config/local/techplanner/database/content/pages/blog.json)
- [Target Blog](https://lightseagreen-dogfish-560272.hostingersite.com/blog)
- [Local Blog](http://127.0.0.1:8000/it/pages/blog)
