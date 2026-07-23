# Memento - Base TechPlanner Fila3

Ultimo aggiornamento: 2025-01-13

## 🏗 Struttura del Progetto

### Directory Principali
- `laravel/`: Core dell'applicazione Laravel
- `public_html/`: Directory pubblica per il web server
- `bashscripts/`: Script di utilità per gestione progetto
- `docs/`: Documentazione e configurazioni
  - `memento.md`: Documentazione generale
  - `troubleshooting.md`: Problemi noti e soluzioni
  - `scripts_reference.md`: Riferimento script di automazione
- `cache/`: File di cache temporanei

### File Chiave
- `docs/techplanner.local.conf`: Configurazione Apache del virtual host
- `docs/certificate.md`: Istruzioni per la configurazione SSL
- `.gitmodules`: Configurazione dei submoduli Git

### Configurazioni
- Server Name: techplanner.local
- SSL abilitato con certificati self-signed
- Log files:
  - Error log: `/var/www/html/_bases/base_techplanner_fila3/error.log`
  - Access log: `/var/www/html/_bases/base_techplanner_fila3/access.log`

## 💡 Funzionalità Principali
- Sistema basato su Laravel
- Integrazione con TailwindCSS (configurazione presente in bashscripts)
- Testing con PHPUnit configurato
- Geolocalizzazione e calcolo distanze per i clienti:
  - Widget per impostare coordinate di riferimento
  - Calcolo distanza in km da punto di riferimento
  - Calcolo tempo di percorrenza in auto (via OSRM API)
  - Ordinamento clienti per distanza
  - Persistenza coordinate in sessione e cookie

## 🔧 Implementazioni Tecniche
### Stack Tecnologico
- Laravel (Framework PHP)
- Apache con SSL
- TailwindCSS per lo styling
- PHPUnit per testing
- OSRM per calcolo percorsi
- Filament 3 per admin panel

### Convenzioni SVG nei Moduli
- Gli SVG dei moduli vanno posizionati nella cartella `resources/svg` del modulo
- Quando un SVG è all'interno di un modulo (es. modulo Geo), il nome dell'icona nella traduzione deve essere prefissato con il nome del modulo
  - Esempio: se il file SVG si chiama `geo-menu.svg` nel modulo Geo, nella traduzione va referenziato come `'icon' => 'geo-geo-menu'`
  - Per evitare doppi prefissi, è consigliabile nominare il file SVG senza il prefisso del modulo (es. `menu.svg`) così da avere nella traduzione `'icon' => 'geo-menu'`

### Strumenti di Sviluppo
- Composer per gestione dipendenze PHP
- Git con gestione submoduli
- Scripts bash per automazione:
  - Gestione Git e organizzazione repository
  - Setup iniziale con Composer
  - Backup e sincronizzazione
  - Testing e CI/CD

### Geolocalizzazione
- Coordinate salvate in sessione e cookie (30 giorni)
- Calcolo distanza con formula di Haversine
- Integrazione con OSRM per tempi di percorrenza
- Widget Filament per gestione coordinate
- Colonna distanza/tempo nella lista clienti
- Ottimizzazione SQL per ordinamento per distanza

### Filament Customization
- Widget personalizzato per coordinate
- Override corretto dei metodi XotBaseListRecords
- Sistema notifiche Filament 3
- Gestione viste modulo correttamente registrate

## ⚠️ Punti di Attenzione
- Presenza di conflitti Git nel file di configurazione Apache (merge non risolto tra HEAD e origin/dev)
- Gestione certificati SSL self-signed richiede configurazione specifica
- Multiple configurazioni DocumentRoot nel virtual host
- Coordinate dei clienti devono essere popolate per il funzionamento del calcolo distanze
- OSRM API richiede connessione internet per il calcolo dei tempi di percorrenza
- Rate limiting possibile su OSRM API pubblica

## 📝 Note di Sviluppo
### Setup Iniziale
1. Configurare virtual host con `techplanner.local.conf`
2. Generare e installare certificati SSL seguendo `certificate.md`
3. Eseguire setup Composer con gli script in `bashscripts/`
4. Configurare TailwindCSS se necessario
5. Popolare le coordinate dei clienti usando l'azione bulk

### Utilizzo Geolocalizzazione
1. Impostare le coordinate di riferimento nel widget
2. Le coordinate vengono salvate in sessione e cookie (30 giorni)
3. La colonna distanza mostra km e tempo di percorrenza
4. Possibile ordinare i clienti per distanza

### Problemi Noti
- Vedere `troubleshooting.md` per una lista completa di problemi e soluzioni
- Particolare attenzione ai metodi di override in XotBaseListRecords
- Gestione corretta dei percorsi delle viste Filament

---
## Log delle Sessioni

### 2025-01-13
- Creazione del file memento.md
- Analisi iniziale della struttura del progetto
- Documentazione configurazione server e strumenti di sviluppo
- Implementazione sistema di geolocalizzazione e calcolo distanze
  - Creazione widget coordinate
  - Aggiunta calcolo distanze e tempi
  - Implementazione ordinamento per distanza
- Fix problemi di implementazione:
  - Corretto percorso viste widget
  - Aggiornato sistema notifiche per Filament 3
  - Sistemato access level metodi XotBaseListRecords
- Creazione documentazione aggiuntiva:
  - troubleshooting.md per problemi e soluzioni
  - scripts_reference.md per riferimento script