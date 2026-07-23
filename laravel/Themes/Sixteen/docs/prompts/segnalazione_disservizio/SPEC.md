# SPEC.md - Segnalazioni Elenco HTML Parity

## Obiettivo
Raggiungere 90% di struttura HTML identica per la pagina "ticket-list" confrontando reference Italy Design Comuni con local.

## Parity Attuale: 55.3%

## Differenze Critiche - Tag Mismatch (9)

Questi sono i problemi più gravi - la struttura HTML non corrisponde:

### 1. Header - Link dropdown lingua
- **Path**: `document/body[1]/header[1]/div[1]/div[1]/div[1]/div[1]/div[1]/div[1]/a[1]/span[1]`
- **Ref**: `<span>` - **Local**: `<svg>`
- **Correzione**: Il locale usa SVG per l'icona invece di span

### 2. Header - Dropdown toggle
- **Path**: `document/body[1]/header[1]/div[2]/div[1]/div[1]/div[1]/div[1]/div[1]/div[1]`
- **Ref**: `<div>` - **Local**: `<button>`
- **Correzione**: Cambiare da button a div

### 3. Header - Dropdown container
- **Path**: `document/body[1]/header[1]/div[2]/div[1]/div[1]/div[1]/div[1]/div[1]/div[2]`
- **Attributi e classi diverse**
- **Correzione**: Allineare attributi

### 4. Header - Link item nel dropdown
- **Path**: `document/body[1]/header[1]/div[2]/div[1]/div[1]/div[1]/div[1]/div[1]/div[2]/div[1]`
- **Ref**: `<div>` - **Local**: `<a>`
- **Correzione**: Cambiare da a a div

### 5. Header - Button area
- **Path**: `document/body[1]/header[1]/div[2]/div[2]/div[1]/div[1]/div[1]/div[1]/button[1]`
- **Ref**: `<button>` - **Local**: `<div>`
- **Correzione**: Cambiare da div a button

### 6. Main - Breadcrumb path structure
- **Path**: `document/body[1]/main[1]/div[2]/div[1]/div[1]/div[1]/div[1]/div[1]/div[1]/div[1]`
- **Ref**: `<div>` - **Local**: `<h2>`
- **Correzione**: Verificare struttura breadcrumb

### 7. Main - Fieldset area
- **Path**: `document/body[1]/main[1]/div[2]/div[1]/div[1]/div[1]/div[1]/div[1]/div[2]/div[1]`
- **Ref**: `<div>` - **Local**: `<fieldset>`
- **Correzione**: Verificare struttura fieldset

### 8. Main - Title section
- **Path**: `document/body[1]/main[1]/div[3]/div[1]/div[1]/div[1]/div[1]`
- **Ref**: `<div>` - **Local**: `<h2>`
- **Correzione**: Verificare struttura titoli

### 9. Main - Filter/Button area
- **Path**: `document/body[1]/main[1]/div[4]/div[1]/div[1]/div[1]/div[1]`
- **Ref**: `<div>` - **Local**: `<h2>`
- **Correzione**: Verificare struttura

### 10. Main - Button area
- **Path**: `document/body[1]/main[1]/div[4]/div[1]/div[1]/div[1]/div[2]`
- **Ref**: `<div>` - **Local**: `<button>`
- **Correzione**: Verificare struttura

## Elementi Mancanti (10)

Questi elementi esistono nel reference ma non nel locale:

1. Header link extra span
2. Header dropdown area div (2)
3. Main cards div struttura
4. Main extra section div
5. Main rating section div
6. Main footer extra div

## Differenze Attributi (65)

La maggior parte sono:
- href diversi (local usa路径 relative, ref usa ../)
- classi diverse
- src diversi per immagini
- text diverso (traduzioni)

## Azioni Correzione

### Alta Priorità
1. Analizzare la struttura del main content nel reference
2. Confrontare con la struttura attuale del layout.blade.php
3. Identificare dove la struttura diverge

### Media Priorità
4. Allineare attributi delle immagini
5. Correggere riferimenti SVG (href vs xlink:href)

## Note
La struttura header/footer è gestita dal layout del tema Sixteen e non deve essere replicata esattamente.
Il focus deve essere sul **contenuto principale** (main) e sulla **struttura delle card/liste**.