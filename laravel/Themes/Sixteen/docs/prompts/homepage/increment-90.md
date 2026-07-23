# Homepage Similarity - Incremento a 90%

## Obiettivo

Aumentare la similarità HTML della homepage dal 73% al 90% rispetto al reference Design Comuni.

## Lavoro Eseguito

### 1. Risoluzione Conflitti Git
- Rimossi markers git conflict dalla homepage blade
- Corretto il blocco calendario e la section Services

### 2. Aggiornamento Traduzioni
- Aggiunto array homepage in lang/it/navigation.php con tutte le chiavi per le sezioni della homepage

### 3. Struttura Riferimento Design Comuni

La homepage reference contiene queste sezioni:
1. Skip Links
2. Header Slim - Top bar con lingua, accesso
3. Header Main - Logo, ricerca, navigazione
4. Hero - News card + immagine
5. Calendario - Organi di governo + eventi
6. Argomenti - Topic cards
7. Altri Argomenti - Lista chip
8. Siti Tematici - Thematic sites gallery
9. Link Utili - Quick links
10. Rating - Form feedback stelle
11. Contatti - Contatti ufficio
12. Footer - Footer completo

### 4. Componenti Esistenti

Componenti gia disponibili nel tema:
- x-blocks.governance.cards - Per calendario/organi governo
- x-blocks.rating.default - Per form feedback
- x-blocks.topics.homepage - Per argomenti
- x-blocks.thematic.sites - Per siti tematici

## Prossimi Passi

### Blocco 1: Header
Aggiungere sezioni mancanti:
- Header slim con language switcher e login
- Header main con ricerca e navigazione

### Blocco 2: Argomenti e Tematici
- Aggiungere section Argomenti in evidenza
- Aggiungere Altri argomenti con lista
- Aggiungere Siti tematici

### Blocco 3: Rating e Contatti
- Aggiungere form rating con stelle
- Aggiungere contatti quick
- Aggiungere link utili
- Aggiungere Forse stavi cercando

### Blocco 4: Footer
- Verificare footer completo

## Note Multilingua

Tutte le stringhe devono usare il sistema di traduzioni:
blade: {{ __(navigation.homepage.services) }}

NON usare testo hardcoded nella blade.

## File Da Modificare

1. resources/views/design-comuni/pages/homepage.blade.php
2. resources/views/components/blocks/header/
3. lang/it/navigation.php
4. lang/en/navigation.php
