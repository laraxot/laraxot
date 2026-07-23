# Piano di Risoluzione Accessibilità (WCAG 2.1) - Tema Two

## Stato Attuale
Il sito è stato validato con MAUVE++ e PageSpeed Insights, evidenziando diverse criticità di livello A e AA.

## Obiettivi
Raggiungere la piena conformità WCAG 2.1 AA migliorando l'esperienza per gli utenti che utilizzano tecnologie assistive.

## Strategia di Risoluzione

### 1. Percezione (Principio 1)
- **Contrasto Colore (1.4.3)**: 
    - Aumentare il contrasto del colore `brand-orange` (#E67E22) quando usato su sfondi chiari.
    - Verificare il contrasto dei testi grigi (#6B7280) su sfondo bianco.
    - Target: Rapporto minimo 4.5:1 per testo normale, 3:1 per testo grande.
- **Alternative Testuali (1.1.1)**:
    - Assicurarsi che ogni `<img>` abbia un attributo `alt`. Se decorativa, usare `alt=""`.
    - Gli SVG devono avere `aria-hidden="true"` se puramente illustrativi o un `<title>`/`aria-label` se funzionali.

### 2. Utilizzabilità (Principio 2)
- **Indicatore di Focus (2.4.7)**:
    - Rimuovere `focus:outline-none` a meno che non sia sostituito da un indicatore di focus altamente visibile (es. anello ad alto contrasto).
    - Implementare `focus-visible` per uno stile coerente.
- **Scopo del Link (2.4.4)**:
    - Sostituire link generici (es. "Scopri di più", icone social senza testo) con testi descrittivi o `aria-label` espliciti (es. "Scopri di più sui nostri servizi di radioprotezione").
- **Nomi e Ruoli (2.5.3)**:
    - Assicurarsi che l'`aria-label` contenga il testo visibile del componente per evitare confusione agli utenti di screen reader.

### 3. Comprensibilità (Principio 3)
- **Etichette dei Moduli (3.3.2)**:
    - Verificare che ogni campo di input abbia una `<label>` associata via `for`/`id`.
    - Posizionare le etichette in modo prevedibile.

### 4. Robustezza (Principio 4)
- **Landmark ARIA (1.3.1)**:
    - Utilizzare correttamente i tag HTML5 semantici (`<header>`, `<main>`, `<footer>`, `<nav>`) per identificare le regioni della pagina.

## Piano d'Azione Immediato
1. **Header**: Correggere il logo (aggiungere alt/title), migliorare i link di navigazione e lo switcher lingua.
2. **Footer**: Risolvere i problemi di contrasto e i nomi accessibili dei link social.
3. **Mappa**: Migliorare l'accessibilità della mappa statica cliccabile.
4. **Blocchi Contenuto**: Revisionare l'ordine delle intestazioni (`H1` -> `H2` -> `H3`) per garantire una gerarchia logica.

## Risorse Gratuite
- Utilizzare OpenStreetMap per le mappe interattive.
- Utilizzare immagini statiche salvate localmente per evitare dipendenze esterne pesanti.
- No Google Maps API (solo link di navigazione gratuiti).
