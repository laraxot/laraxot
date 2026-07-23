# GDPR e Privacy - Documentazione

## Data: 2026-02-06

## Linee Guida GDPR per il Sito

### 1. Raccolta Dati
- **Moduli Contatto**: Nome, email, telefono (consenso esplicito richiesto)
- **Newsletter**: Email con doppia opt-in
- **Cookie**: Banner cookie necessario
- **Analytics**: Google Analytics con IP anonimizzato

### 2. Pagine Richieste
- **Privacy Policy** (`/privacy`): Dettagli raccolta dati, finalità, conservazione
- **Cookie Policy** (`/cookie`): Tipi di cookie, scopo, come disabilitare
- **Termini e Condizioni** (`/termini`): Condizioni d'uso del sito

### 3. Implementazione Modulo GDPR
- Utilizzare modulo `Gdpr` esistente nel progetto
- Aggiungere checkbox consenso in tutti i form
- Log delle attività di trattamento dati
- Possibilità di esercitare diritti (accesso, rettifica, cancellazione)

### 4. Configurazione Form
```json
{
  "fields": [
    {"name": "privacy_consent", "type": "checkbox", "required": true, "label": "Ho letto e accetto la Privacy Policy"},
    {"name": "marketing_consent", "type": "checkbox", "required": false, "label": "Acconsento all'invio di comunicazioni marketing"}
  ]
}
```

### 5. Prossimi Passi
1. Creare pagine `/privacy`, `/cookie`, `/termini`
2. Aggiungere banner cookie nel layout
3. Integrare modulo GDPR con i form esistenti
4. Configurare Google Analytics con IP anonimizzato
