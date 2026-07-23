---
title: Design Comuni Italia — Struttura pagine di riferimento
type: concept
tags: [design-comuni, bootstrap-italia, page-templates, UX, sixteen]
---

# Design Comuni Italia — Struttura pagine di riferimento

Fonte: https://comuni.designers.italia.it/

## Sezioni principali del sito comunale

| Sezione | URL pattern | Descrizione |
|---------|-------------|-------------|
| Homepage | `/` | Contenuti evidenza, novità, eventi, argomenti |
| Servizi | `/servizi/` | Tutti i servizi per categoria |
| Servizio detail | `/servizio/<slug>/` | Dettaglio singolo servizio |
| Argomenti | `/argomenti/` | Tassonomia cross-contenuto |
| Argomento detail | `/argomento/<slug>/` | Hub argomento con novità/servizi |
| Novità | `/novita/` | Notizie, avvisi, comunicati |
| Notizia detail | `/novita/<slug>/` | Articolo/notizia singola |
| Amministrazione | `/amministrazione/` | Organi, uffici, personale, documenti |
| Vivere il Comune | `/vivere-il-comune/` | Eventi e luoghi |

## Struttura pagina Servizio (template obbligatorio)

```
# Titolo servizio
[badge: Servizio attivo / non attivo]
[abstract breve]
[CTA: Richiedi online]  [Condividi] [Vedi azioni: Stampa|Ascolta|Invia]

---
## A chi è rivolto     ← target audience
## Descrizione         ← cosa è il servizio
## Come fare           ← procedura passo-passo
## Cosa serve          ← documenti e prerequisiti (SPID, CIE, ISEE...)
## Cosa si ottiene     ← output/risultato
## Tempi e scadenze    ← calendario/scadenze
## Accedi al servizio  ← CTA finale
## Contatti            ← ufficio/sportello referente
## Altro               ← correlati
[Rating widget]
[Contatta il comune sidebar]
```

## Struttura pagina Argomento

```
# Titolo argomento
[Descrizione breve]

## Novità              ← notizie correlate
## Amministrazione     ← uffici correlati
## Servizi             ← servizi correlati
## Documenti           ← documenti pubblici correlati
[Rating widget]
[Contatta il comune sidebar]
```

## Struttura pagina Notizia/Avviso

```
# Titolo notizia
[Data] [Tempo di lettura] [Condividi] [Vedi azioni]
[Argomenti tag]
[Immagine hero]
[Contenuto]
[Rating widget]
[Correlati]
```

## Categorie Servizi (Design Comuni standard)

- Agricoltura e pesca
- Ambiente
- Anagrafe e stato civile
- Appalti pubblici
- Autorizzazioni
- Catasto e urbanistica
- Cultura e tempo libero
- Educazione e formazione
- Lavoro
- Mobilità e trasporti
- Salute e benessere
- Sicurezza e ordine pubblico
- Tributi, finanze e contravvenzioni
- Turismo
- Vita lavorativa

## Categorie Novità

- Notizie
- Avvisi (bandi, concorsi, opportunità)
- Comunicati (ufficiali da organi politici)

## Elementi UI ricorrenti

### Rating widget (obbligatorio su ogni pagina)
```html
"Quanto sono chiare le informazioni su questa pagina?"
[★ 5] [★ 4] [★ 3] [★ 2] [★ 1]
→ step 2: Cosa hai preferito? (checkbox multiple)
→ step 3: Difficoltà? (checkbox multiple)
→ step 4: Dettagli liberi (max 200 caratteri)
```
Mappa al modulo **Rating**.

### Social share (presente su servizi e notizie)
```
Facebook | Twitter | LinkedIn | WhatsApp
+ Stampa | Ascolta | Invia via email
```

### Contatta il comune (sidebar/footer sezione)
```
- Leggi le domande frequenti → /domande-frequenti/
- Richiedi assistenza → /servizi/assistenza/
- Numero verde 800xxxxxx
- Prenota appuntamento → /servizi/prenotazioni/
- [Segnala disservizio] → Fixcity module
```

### Skip links
```html
<a href="#main-container">Vai ai contenuti</a>
<a href="#footer">Vai al footer</a>
```

## Mapping su moduli del progetto

| Elemento Design Comuni | Modulo Laraxot |
|------------------------|----------------|
| Servizi / pagine servizio | Cms (Page + Section) |
| Argomenti | Cms (Menu/Tag taxonomy) |
| Novità / notizie | Blog module |
| Amministrazione / documenti | Cms (documenti pubblici) |
| Vivere il Comune / eventi | Cms (eventi) |
| Segnala disservizio | **Fixcity module** |
| Rating widget | **Rating module** |
| Prenota appuntamento | Cms (servizi) |

## Gap identificati (da colmare)

1. **Mancano template dedicati per**: Servizio-dettaglio, Argomento-detail, Notizia-detail, Amministrazione
2. **Rating widget** non integrato nelle pagine CMS pubbliche
3. **Social share** non implementato come componente riusabile
4. **"Segnala disservizio"** non è esposto in sidebar/footer delle pagine contenuto
5. **Argomenti** come tassonomia cross-contenuto non implementata nel CMS
6. **Skip links** da verificare su tutte le pagine

## Riferimenti

- Reference: https://comuni.designers.italia.it/
- Story: `8-41`
- Design System: Bootstrap Italia (già adottato nel tema Sixteen)
