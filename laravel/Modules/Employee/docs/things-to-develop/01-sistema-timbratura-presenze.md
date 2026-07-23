# 🕐 Sistema Timbratura Presenze - Guida Implementazione Step-by-Step

## 📋 Cosa Stiamo Costruendo

Un sistema completo che permette ai dipendenti di "timbrare" (registrare entrata/uscita) da qualsiasi dispositivo (PC, smartphone, tablet) con verifica della posizione geografica e foto opzionale.

## 🎯 Obiettivo Finale

Il dipendente potrà:
- Fare clic su "Entra" quando arriva al lavoro
- Fare clic su "Esci" quando finisce di lavorare  
- Fare pause con "Pausa" e "Riprendi"
- Tutto viene registrato automaticamente con orario e posizione

## 🏗️ Cosa Dobbiamo Creare (Lista Completa)

### 1. Database (Tabelle per Salvare i Dati)
- **time_entries**: Ogni singola timbratura (entrata, uscita, pausa)
- **work_locations**: Sedi di lavoro autorizzate
- **time_policies**: Regole orarie (es. orario di lavoro 9-18)

### 2. Modelli Laravel (Classi per Gestire i Dati)
- **TimeEntry**: Gestisce le timbrature
- **WorkLocation**: Gestisce le sedi di lavoro
- **TimePolicy**: Gestisce le politiche orarie

### 3. Interfacce Filament (Pannelli di Amministrazione)
- **TimeEntryResource**: Per vedere/modificare timbrature
- **WorkLocationResource**: Per gestire le sedi
- **TimePolicyResource**: Per gestire le regole

### 4. App Mobile/Web (Interfaccia Dipendenti)
- **Pagina Timbratura**: Bottoni grandi "Entra/Esci"
- **Storico Presenze**: Vedere le proprie timbrature
- **Calendario Personale**: Vista mensile delle presenze

### 5. API (Per Far Comunicare App e Server)
- **POST /api/clock-in**: Registra entrata
- **POST /api/clock-out**: Registra uscita
- **GET /api/my-timesheet**: Visualizza le proprie presenze

## 📊 STEP 1: Creare le Tabelle Database

### 1.1 Tabella time_entries (Timbrature)
**Cosa Contiene**: Ogni singola timbratura di ogni dipendente

```sql
Campi della tabella:
- id: Numero identificativo unico
- employee_id: Chi ha timbrato (collegamento a dipendente)
- type: Tipo timbratura (entrata/uscita/pausa_inizio/pausa_fine)
- timestamp: Quando ha timbrato (data e ora precisa)
- location_lat: Latitudine GPS dove ha timbrato
- location_lng: Longitudine GPS dove ha timbrato
- location_name: Nome del posto (es. "Ufficio Milano")
- device_info: Informazioni dispositivo (smartphone/PC)
- photo_path: Percorso foto (se scattata)
- notes: Note opzionali del dipendente
- status: Stato (in_attesa/approvata/rifiutata)
- approved_by: Chi ha approvato (manager)
- approved_at: Quando è stata approvata
```

**Perché Serve**: Dobbiamo registrare ogni singola azione del dipendente con tutti i dettagli per controlli e calcoli.

### 1.2 Tabella work_locations (Sedi di Lavoro)
**Cosa Contiene**: Tutti i posti dove i dipendenti possono lavorare

```sql
Campi della tabella:
- id: Numero identificativo unico
- name: Nome sede (es. "Ufficio Milano Centro")
- address: Indirizzo completo
- latitude: Latitudine GPS della sede
- longitude: Longitudine GPS della sede
- radius_meters: Raggio in metri per timbratura (es. 100m)
- is_active: Se la sede è attiva
- requires_photo: Se richiede foto per timbrare
- timezone: Fuso orario della sede
```

**Perché Serve**: Per verificare che il dipendente stia timbrando dal posto giusto.

### 1.3 Tabella time_policies (Politiche Orarie)
**Cosa Contiene**: Regole di lavoro per ogni dipendente/gruppo

```sql
Campi della tabella:
- id: Numero identificativo unico
- name: Nome politica (es. "Orario Ufficio Standard")
- description: Descrizione dettagliata
- work_hours_per_day: Ore di lavoro al giorno (es. 8)
- work_days_per_week: Giorni lavorativi (es. 5)
- start_time: Orario inizio (es. 09:00)
- end_time: Orario fine (es. 18:00)
- break_duration_minutes: Durata pausa pranzo (es. 60 minuti)
- overtime_threshold: Dopo quante ore scattano straordinari
- late_tolerance_minutes: Tolleranza ritardo (es. 15 minuti)
```

**Perché Serve**: Per calcolare automaticamente se il dipendente è in orario, in ritardo, o ha fatto straordinari.

## 🎯 STEP 2: Creare i Modelli Laravel

### 2.1 Modello TimeEntry
**Cosa Fa**: Gestisce tutte le operazioni sulle timbrature

**Funzionalità Principali**:
- Salvare una nuova timbratura
- Verificare se la posizione GPS è valida
- Calcolare la durata tra entrata e uscita
- Verificare se è in orario o in ritardo

**Relazioni con Altri Modelli**:
- Appartiene a un Employee (dipendente)
- Può essere collegata a una WorkLocation (sede)
- Può avere un TimePolicy (politica oraria)

### 2.2 Modello WorkLocation  
**Cosa Fa**: Gestisce le sedi di lavoro autorizzate

**Funzionalità Principali**:
- Verificare se una posizione GPS è dentro il raggio consentito
- Calcolare la distanza tra dipendente e sede
- Gestire fusi orari diversi per sedi multiple

### 2.3 Modello TimePolicy
**Cosa Fa**: Gestisce le regole orarie aziendali

**Funzionalità Principali**:
- Calcolare se un dipendente è in orario
- Determinare straordinari automaticamente
- Applicare tolleranze per ritardi

## 🎨 STEP 3: Creare Interfacce Filament (Admin)

### 3.1 TimeEntryResource (Gestione Timbrature)
**Chi Lo Usa**: Manager, HR, Amministratori

**Cosa Permette di Fare**:
- Vedere tutte le timbrature di tutti i dipendenti
- Filtrare per dipendente, data, sede
- Approvare o rifiutare timbrature sospette
- Correggere timbrature errate
- Esportare dati per elaborazioni

**Campi Visibili nella Tabella**:
- Nome dipendente
- Tipo timbratura (entrata/uscita)
- Data e ora
- Sede/Posizione
- Stato (approvata/in attesa)
- Azioni (approva/rifiuta/modifica)

**Filtri Disponibili**:
- Per dipendente
- Per data (oggi, questa settimana, questo mese)
- Per sede di lavoro
- Per stato (approvate/in attesa/rifiutate)
- Per tipo (solo entrate, solo uscite)

### 3.2 WorkLocationResource (Gestione Sedi)
**Chi Lo Usa**: Amministratori, Manager

**Cosa Permette di Fare**:
- Aggiungere nuove sedi di lavoro
- Modificare indirizzi e coordinate GPS
- Impostare raggio di tolleranza per timbrature
- Attivare/disattivare sedi
- Vedere statistiche utilizzo per sede

**Form di Creazione/Modifica**:
- Nome sede (campo testo)
- Indirizzo completo (campo testo con autocompletamento)
- Coordinate GPS (automatiche da indirizzo o manuali)
- Raggio tolleranza (slider 10-500 metri)
- Richiedi foto (checkbox)
- Fuso orario (dropdown)

### 3.3 TimePolicyResource (Gestione Politiche)
**Chi Lo Usa**: HR, Amministratori

**Cosa Permette di Fare**:
- Creare regole orarie personalizzate
- Assegnare politiche a gruppi di dipendenti
- Impostare tolleranze e straordinari
- Vedere impatto delle politiche sui calcoli

**Form di Configurazione**:
- Nome politica (es. "Part-time Mattino")
- Ore giornaliere (slider 1-12 ore)
- Giorni settimanali (checkbox Lun-Dom)
- Orario inizio/fine (time picker)
- Durata pausa (slider 0-120 minuti)
- Tolleranza ritardo (slider 0-60 minuti)

## 📱 STEP 4: Creare App Mobile/Web (Dipendenti)

### 4.1 Pagina Timbratura Principale
**Cosa Vede il Dipendente**: Una pagina semplice con bottoni grandi

**Layout della Pagina**:
```
[Logo Azienda]

Ciao Mario Rossi!
Oggi è Lunedì 30 Luglio 2025

Stato Attuale: NON IN SERVIZIO
Ultima azione: Uscita ieri alle 18:30

[BOTTONE GRANDE VERDE: ENTRA]
[BOTTONE GRANDE ROSSO: ESCI]
[BOTTONE PICCOLO GIALLO: PAUSA]

Posizione: Ufficio Milano (GPS OK ✓)
Orario previsto: 09:00 - 18:00

[Link: Vedi le mie presenze]
[Link: Richiedi ferie/permessi]
```

**Cosa Succede Quando Clicca "ENTRA"**:
1. App rileva posizione GPS automaticamente
2. Verifica che sia nella sede autorizzata
3. Opzionalmente chiede di scattare una foto
4. Registra timbratura con timestamp preciso
5. Mostra messaggio "Entrata registrata alle 09:15"
6. Cambia stato in "IN SERVIZIO"

**Cosa Succede Quando Clicca "ESCI"**:
1. Verifica che abbia fatto almeno l'entrata
2. Calcola ore lavorate automaticamente
3. Registra uscita con timestamp
4. Mostra riepilogo "Hai lavorato 8 ore e 30 minuti"
5. Cambia stato in "NON IN SERVIZIO"

### 4.2 Pagina Storico Presenze
**Cosa Vede**: Lista delle sue timbrature recenti

**Layout**:
```
Le Mie Presenze - Luglio 2025

[Filtri: Questa settimana | Questo mese | Personalizzato]

30 Lug 2025 (Oggi)
├─ 09:15 ENTRATA (Ufficio Milano) ✓
├─ 12:30 PAUSA PRANZO ✓  
├─ 13:30 RIENTRO PAUSA ✓
└─ 18:30 USCITA (Ufficio Milano) ✓
   Totale: 8h 30min

29 Lug 2025 (Ieri)  
├─ 09:00 ENTRATA (Ufficio Milano) ✓
├─ 12:00 PAUSA PRANZO ✓
├─ 13:00 RIENTRO PAUSA ✓
└─ 18:00 USCITA (Ufficio Milano) ✓
   Totale: 8h 00min

[Bottone: Scarica Report Mensile]
```

### 4.3 Calendario Presenze
**Cosa Vede**: Vista calendario con colori per ogni giorno

**Layout Calendario**:
```
Luglio 2025

L  M  M  G  V  S  D
1  2  3  4  5  6  7
🟢 🟢 🟢 🟢 🟢 ⚪ ⚪  (Verde=presente, Bianco=weekend)

8  9  10 11 12 13 14
🟢 🟢 🟢 🟢 🟢 ⚪ ⚪

15 16 17 18 19 20 21  
🟢 🟢 🟢 🟢 🟡 ⚪ ⚪  (Giallo=ferie/permesso)

22 23 24 25 26 27 28
🟢 🟢 🟢 🟢 🟢 ⚪ ⚪

29 30 31
🟢 🟢 🔵  (Blu=oggi)

Legenda:
🟢 Presente  🔴 Assente  🟡 Ferie/Permesso  
🟠 Ritardo  ⚪ Weekend/Festivo  🔵 Oggi
```

## 🔌 STEP 5: Creare API (Comunicazione App-Server)

### 5.1 API Timbratura Entrata
**Endpoint**: `POST /api/employee/clock-in`

**Cosa Riceve dall'App**:
```json
{
  "latitude": 45.4642,
  "longitude": 9.1900,
  "device_info": "iPhone 14 Pro - iOS 17.1",
  "photo": "base64_encoded_image_data",
  "notes": "Entrata regolare"
}
```

**Cosa Controlla il Server**:
1. Dipendente autenticato correttamente
2. Non ha già fatto entrata oggi
3. Posizione GPS è dentro raggio sede autorizzata
4. Orario è ragionevole (non alle 3 di notte)

**Cosa Risponde**:
```json
{
  "success": true,
  "message": "Entrata registrata alle 09:15",
  "time_entry": {
    "id": 12345,
    "type": "clock_in", 
    "timestamp": "2025-07-30 09:15:00",
    "location": "Ufficio Milano Centro"
  },
  "next_action": "clock_out"
}
```

### 5.2 API Timbratura Uscita  
**Endpoint**: `POST /api/employee/clock-out`

**Cosa Controlla il Server**:
1. Ha fatto entrata oggi
2. Non ha già fatto uscita
3. È passato tempo minimo (es. almeno 1 ora)

**Cosa Calcola Automaticamente**:
- Ore totali lavorate
- Eventuali straordinari
- Durata pause
- Conformità alla politica oraria

### 5.3 API Storico Presenze
**Endpoint**: `GET /api/employee/timesheet?month=2025-07`

**Cosa Risponde**:
```json
{
  "employee": "Mario Rossi",
  "period": "Luglio 2025",
  "summary": {
    "total_days": 22,
    "worked_days": 20,
    "total_hours": 160,
    "overtime_hours": 5,
    "average_daily_hours": 8.0
  },
  "entries": [
    {
      "date": "2025-07-30",
      "clock_in": "09:15:00",
      "clock_out": "18:30:00", 
      "total_hours": 8.5,
      "status": "approved"
    }
  ]
}
```

## 🔧 STEP 6: Funzionalità Avanzate

### 6.1 Geolocalizzazione Intelligente
**Cosa Fa**: Verifica automaticamente se il dipendente è nel posto giusto

**Come Funziona**:
1. App rileva coordinate GPS del telefono
2. Server calcola distanza da sede autorizzata
3. Se dentro raggio (es. 100 metri) → OK
4. Se fuori raggio → Chiede conferma o rifiuta

**Gestione Errori GPS**:
- GPS non disponibile → Permette timbratura con nota
- GPS impreciso → Usa raggio più ampio
- Sede multipla → Sceglie automaticamente la più vicina

### 6.2 Modalità Offline
**Problema**: Dipendente senza internet non può timbrare

**Soluzione**:
1. App salva timbratura in locale
2. Quando torna internet, sincronizza automaticamente
3. Server valida e approva timbrature offline
4. Dipendente vede stato "In sincronizzazione..."

### 6.3 Foto Timbratura (Opzionale)
**Quando Serve**: Per sedi sensibili o controlli extra

**Come Funziona**:
1. App apre fotocamera automaticamente
2. Dipendente scatta selfie
3. Foto viene compressa e crittografata
4. Server salva foto collegata alla timbratura
5. Manager può vedere foto in caso di controlli

### 6.4 Notifiche Push
**Promemoria Automatici**:
- "Ricordati di timbrare l'entrata" (se in ritardo)
- "Hai dimenticato di uscire?" (se dopo orario)
- "Pausa pranzo terminata" (dopo 1 ora di pausa)

## 🚨 STEP 7: Controlli e Sicurezza

### 7.1 Prevenzione Frodi
**Problemi da Evitare**:
- Dipendente fa timbrare un collega
- Timbratura da casa quando dovrebbe essere in ufficio
- Manipolazione orari

**Soluzioni**:
- Foto obbligatoria per timbrature sospette
- Verifica GPS rigorosa
- Algoritmi per rilevare pattern anomali
- Audit trail di tutte le modifiche

### 7.2 Privacy e GDPR
**Dati Sensibili da Proteggere**:
- Posizione GPS dei dipendenti
- Foto delle timbrature
- Orari di lavoro dettagliati

**Misure di Protezione**:
- Crittografia di tutti i dati GPS
- Cancellazione automatica foto dopo 30 giorni
- Accesso ai dati solo per personale autorizzato
- Log di chi accede ai dati quando

## 📊 STEP 8: Report e Analytics

### 8.1 Dashboard Manager
**Cosa Vede il Manager**:
- Quanti dipendenti sono attualmente al lavoro
- Chi è in ritardo oggi
- Statistiche presenze del team
- Alert per anomalie

### 8.2 Report HR
**Report Automatici**:
- Presenze mensili per dipendente
- Statistiche ritardi e assenze
- Calcolo ore straordinario
- Export per sistema paghe

### 8.3 Analytics Predittive
**Funzionalità Avanzate**:
- Previsione assenteismo
- Identificazione pattern di ritardo
- Ottimizzazione orari di lavoro
- Suggerimenti per migliorare produttività

## ✅ STEP 9: Testing e Validazione

### 9.1 Test Funzionali
**Scenari da Testare**:
- Dipendente timbra normalmente
- Dipendente dimentica di timbrare
- GPS non funziona
- Internet non disponibile
- Timbratura da posizione sbagliata

### 9.2 Test di Carico
**Cosa Verificare**:
- 100 dipendenti timbrano contemporaneamente
- App funziona con connessione lenta
- Server gestisce picchi di traffico

### 9.3 Test Sicurezza
**Controlli Necessari**:
- Impossibile falsificare timbrature
- Dati GPS protetti da intercettazioni
- API resistenti ad attacchi

## 🚀 STEP 10: Deployment e Manutenzione

### 10.1 Rilascio Graduale
**Fasi di Rollout**:
1. Test con 5 dipendenti volontari
2. Estensione a un dipartimento
3. Rollout completo aziendale
4. Monitoraggio e ottimizzazioni

### 10.2 Formazione Utenti
**Materiali Necessari**:
- Video tutorial per dipendenti
- Manuale per manager
- FAQ per problemi comuni
- Supporto tecnico dedicato

### 10.3 Monitoraggio Continuo
**Metriche da Seguire**:
- Tasso di adozione dell'app
- Numero errori di timbratura
- Tempo medio per timbrare
- Soddisfazione utenti

---

## 🎯 Risultato Finale

Alla fine di questa implementazione avremo:

✅ **Per i Dipendenti**:
- App semplice per timbrare in 2 secondi
- Storico completo delle proprie presenze
- Notifiche utili per non dimenticare

✅ **Per i Manager**:
- Controllo real-time del team
- Report automatici e dettagliati
- Strumenti per approvare/correggere

✅ **Per l'Azienda**:
- Dati precisi per calcolo stipendi
- Compliance con normative
- Riduzione errori e frodi
- Integrazione con sistemi esistenti

**Tempo Stimato Implementazione**: 6-8 settimane
**Complessità**: Media-Alta
**Priorità**: ALTA (funzionalità core)

---

*Guida creata: 2025-07-30*  
*Livello: Dettagliato step-by-step*  
*Target: Sviluppatori di tutti i livelli*
