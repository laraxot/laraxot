# Aggiornamento Brand e Contenuti - Febbraio 2026

## Obiettivo
Aggiornare l'identità visiva e i contenuti del sito per riflettere il nuovo brand "Sottana Service" e focalizzare i servizi su studi dentistici e cliniche veterinarie.

## Modifiche Apportate

### 1. Brand Identity
- Sostituito il nome "Marco Sottana Consulenza Sicurezza" con **"Sottana Service"** in tutto il sito.
- Implementato logo elefante stilizzato di profilo con proboscide a destra nella testata (Header v1).
- Aggiornato il copyright nel footer.

### 2. Terminologia Normativa
- Sostituito "Esperto Qualificato" con **"Esperto di Radioprotezione"** (adeguamento D.Lgs 101/2020).
- Semplificata "Segnaletica luminosa e acustica di sicurezza" in **"Segnaletica"**.

### 3. Servizi di Consulenza
Focalizzazione sui tre pilastri principali:
1. **Autorizzazioni Sanitarie**
2. **Controllo Apparecchi RX ed Elettromedicali**
3. **Documenti di Valutazione del Rischio (DVR)**

### 4. Pulizia Contenuti
Rimosse le seguenti sezioni/voci non più prioritarie:
- Verifiche straordinarie
- Biosicurezza Radiologica
- Fluoroscopia e Arco a C
- Interblocchi
- Schede dosimetriche
- Registro macchine
- Sezione "Risorse Utili"
- Sezione "Rimani Aggiornato" (Newsletter)

### 5. Contatti
- Telefono Fisso: **+39 041 455552**
- Telefono Mobile: **+39 347 58 96 127**
- Email: **studio@sottana.com**
- Indirizzo: **Via Vanzo 86, 31021 Mogliano Veneto TV**

### 6. Mappa
- Implementata mappa statica (PNG) in `Modules/TechPlanner/resources/images/map-via-vanzo.png`.
- La mappa è cliccabile e reindirizza a Google Maps con destinazione impostata sull'indirizzo dello studio.
- Utilizzo del modulo GEO per la logica di visualizzazione (Componente `map.static-clickable`).

## Note per i Futuri Agenti
- Tutte le definizioni di contenuto sono nei file JSON sotto `config/local/techplanner/database/content/`.
- Il tema attivo è `Two`.
- Non utilizzare mai Google Maps API a pagamento; usare solo link gratuiti o OpenStreetMap.


## Stato Finale
Tutte le richieste dell'utente sono state implementate e verificate nel codice sorgente e nelle configurazioni JSON. La cache è stata pulita. L'immagine della mappa è stata generata e posizionata correttamente.
