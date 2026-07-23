# Piano di Replicazione Design Comuni - HTML Parity

## Obiettivo

Rendere l'HTML dentro il tag `<body>` (esclusi gli script) **IDENTICO** a:
- **Target**: https://italia.github.io/design-comuni-pagine-statiche/sito/homepage.html
- **FixCity**: http://127.0.0.1:8000/it/tests/homepage

## Fase 1: HTML Esatto (CORRENTE)

### 1.1 Struttura Base - Confronto Direct

| Elemento | Design Comuni (Target) | FixCity (Attuale) | Status |
|----------|------------------------|-------------------|--------|
| Header wrapper | `<header class="it-header-wrapper">` | ✅ Presente | OK |
| Header slim | `<div class="it-header-slim-wrapper">` | ✅ Presente | OK |
| Skip links | `<div class="skiplink">` | ✅ Presente | OK |
| Main container | `<main id="main-container">` | ✅ Presente | OK |
| Footer | `<footer class="it-footer">` | ✅ Presente | OK |

### 1.2 Verifica HTML Esatto

```bash
# Scarica entrambe le pagine
curl -s https://italia.github.io/design-comuni-pagine-statiche/sito/homepage.html > /tmp/agid-homepage.html
curl -s http://127.0.0.1:8000/it/tests/homepage > /tmp/fixcity-homepage.html

# Estrai solo il body (senza script)
# Comparazione con diff
```

### 1.3 Strategia di Matching

1. **Struttura identica**: Stesso ordine dei tag
2. **Classi CSS**: Nomi esatti da Design Comuni
3. **Attributi data-**: Preservare tutti gli attributi
4. **ARIA**: Mantenere tutti gli attributi accessibility
5. **Testo contenuto**: Italiano esatto

### 1.4 Componenti Necessari

#### Header Components (da replicare)
- `it-header-wrapper` - wrapper principale
- `it-header-slim-wrapper` - slim header con links + lang dropdown
- `it-header-center-wrapper` - center con logo + social + search
- `it-header-navbar-wrapper` - navbar con menu hamburger

#### Content Sections
- `section#head-section` - Hero section con featured news
- `section#calendario` - Events calendar
- `section.evidence-section` - Argomenti in evidenza
- `section.useful-links-section` - Link utili + search
- Feedback rating section

#### Footer Sections
- Footer con 4 colonne (top)
- Footer main (6 colonne)
- Footer bottom (social + legal)

## Fase 2: CSS e JS (SUCCESSIVA)

Dopo aver raggiunto HTML parity, si procederà con:
- CSS styling esatto
- JavaScript interattivo
- Bootstrap Italia integration

## Pagine da Replicare (38 totali)

### Generali (9)
1. homepage.html
2. domande-frequenti.html
3. risultati-ricerca.html
4. argomenti.html
5. argomento.html
6. lista-risorse.html
7. lista-categorie.html
8. lista-risorse-categorie.html
9. mappa-sito.html

### Amministrazione (2)
10. amministrazione.html
11. documenti-dati.html

### Novità (2)
12. novita.html
13. novita-dettaglio.html

### Servizi (3)
14. servizi.html
15. servizi-categoria.html
16. servizio-dettaglio.html

### Vivere il Comune (2)
17. eventi.html
18. evento-dettaglio.html

### Prenotazione Appuntamento (8)
19-26. appuntamento-01 → appuntamento-06

### Richiesta Assistenza (2)
27-28. assistenza-01-dati, assistenza-02-conferma

### Segnalazione Disservizio (7)
29-35. segnalazione-dettaglio → ticket-list

## Workflow di Implementazione

### Step 1: Una pagina alla volta
Per ogni pagina:
1. Scarica HTML upstream
2. Confronta con FixCity
3. Identifica differenze
4. Aggiorna JSON content
5. Aggiorna block views se necessario
6. Verifica match

### Step 2: Automazione
- Script di confronto HTML
- Batch update per JSON simili

### Step 3: Verifica
- Test visual comparison
- Automated HTML diff

## Documentazione

### Indici Bidirezionali
- Theme docs: `Themes/Sixteen/docs/DOCUMENTATION_INDEX.md`
- Module docs: `Modules/Cms/docs/index.md`
- Link relativi tra documenti

### Riferimenti
- Design Comuni: https://italia.github.io/design-comuni-pagine-statiche/
- FixCity: http://127.0.0.1:8000/it/tests/homepage

## Status Attuale

| Task | Status |
|------|--------|
| Folio routing abilitato | ✅ |
| Route /it/tests/homepage funziona | ✅ |
| JSON content esistenti | ✅ (90+ file) |
| Header AGID conforme | 🔄 In verifica |
| HTML parity homepage | 🔄 Da verificare |

## Prossimi Passi

1. ✅ Folio routing verificato
2. ⏳ Verificare HTML body parità
3. ⏳ Aggiornare header component per match esatto
4. ⏳ Aggiornare JSON content
5. ⏳ Procedere con altre 37 pagine