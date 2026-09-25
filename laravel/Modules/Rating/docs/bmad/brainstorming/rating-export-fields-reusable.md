# Brainstorming — metodo riutilizzabile per getXlsFields dei rating

## Contesto
Il blocco `getXlsFields` in `IndennitaResponsabilitaResource.php` contiene logica per:
1. Recuperare anno e type dai dati
2. Risolvere il modello host tramite `static::getModel()` e `classToAlias()`
3. Query rating con `extra_attributes->anno` e `extra_attributes->type`
4. Filtrare solo criteri (parent_id null)
5. Eager load `children`
6. Per ogni rating:
   - Calcolare label tramite `HasRatingsTrait::formFieldLabel()`
   - Aggiungere colonna valore (`ratingXlsValuePath`)
   - Se il rating ha figli, aggiungere colonna nota (`ratingValuePath(..., 'note')`)
Questa logica è potenzialmente riutilizzabile da altri resource host (es. Ptv, Performance, Progressioni) che hanno bisogno di esportare rating collegati tramite `extra_attributes`.

## Domande chiave e analisi (con percentuali di fiducia)

### 1. Dove posizionare il metodo riutilizzabile?
- **Opzione A: HasRatingsTrait.php** (80%)
  - Pro: già contiene helper per rating (`ratingXlsValuePath`, `ratingValuePath`, `formFieldLabel`); logica strettamente legata al dominio rating
  - Contro: il trait è usato sui modelli host (es. IndennitaResponsabilita), non sui rating stessi; un metodo che restituisce array di campi per export potrebbe non appartenere al trait (violazione SRP leggera)
- **Opzione B: RatingData.php** (60% - preferita dall'utente)
  - Pro: già esiste come DTO per rating; potrebbe contenere metodi di utilità legati alla struttura dei rating
  - Contro: è principalmente un DTO per proprietà UI (title, description, ecc.); metodi di export non appartengono chiaramente a un DTO (coesione bassa)
- **Opzione C: Nuova classe servizio** (es. `RatingExportService`) (90%)
  - Pro: massima coesione, SRP rispettato, facile da testare e mockare
  - Contro: richiede creazione nuova file e iniezione di dipendenze (leggero sovraccarico)
- **Opzione D: Metodo statico su BaseRating o Rating** (70%)
  - Pro: vicino al dominio rating, già contiene metodi simili (`getXlsExportValueAttribute`)
  - Contro: BaseRating è astratto, potrebbe diventare troppo pesante con logica host-specifica

### 2. Quali parametri prendere?
- **Anno e type** (obbligatori) — per filtrare i rating via `extra_attributes`
- **Modello host** (opzionale, dedotto da `static::getModel()` nel contesto del resource) — per risolvere l'alias tipo
- **Array di where extra_attributes** (più generico) — permette di passare qualsiasi filtro su extra_attributes (es. anno, type, altri campi custom)

### 3. Dubbi e punti deboli
- **Dubbio (30%)**: Se spostiamo la logica in un posto centrale, dobbiamo assicurarci che tutti i resource host chiamino il metodo nello stesso modo (rischio di inconsistenza se qualcuno continua a duplicare).
- **Dubbio (20%)**: Il metodo potrebbe diventare troppo generico e perdere ottimizzazioni specifiche del caso d'uso (es. join specifici, selezione di colonne).
- **Punto debole (10%)**: Se mettiamo il metodo in `RatingData.php` (DTO), futuri sviluppatori potrebbero confonderlo con le proprietà UI e aggiungere logica non pertinente.
- **Punto debole (15%)**: Se mettiamo il metodo in `HasRatingsTrait.php`, il trait potrebbe accumulare responsabilità non strettamente legate alle relazioni rating (violazione SRP).

### 4. Punti di forza
- **Forza (85%)**: Eliminare duplicazione di codice tra resource host (IR, Ptv, Performance, ecc.) — attualmente la stessa logica appare in più luoghi.
- **Forza (75%)**: Centralizzare la logica di risoluzione dei rating tramite `extra_attributes` (anno, type, ecc.) rende più facile cambiare la struttura in futuro.
- **Forza (65%)**: Riusare helper esistenti del trait (`ratingXlsValuePath`, `ratingValuePath`, `formFieldLabel`) garantisce coerenza.
- **Forza (50%)**: Se fatto bene, apre la strada a un servizio di export rating riutilizzabile anche in altri contesti (es. API, PDF).

### 5. Motivazioni per l'implementazione
- **Motivazione principale (90%)**: DRY principle — evitare copia-incolla di logica complessa che è soggetta a errori e inconsistente.
- **Motivazione secondaria (80%)**: Migliorare la manutenibilità — se cambia il modo di filtrare i rating (es. aggiungere un nuovo campo in extra_attributes), si modifica in un solo posto.
- **Motivazione terziaria (70%)**: Facilitare il testing — la logica può essere unit-testata in isolamento senza bisogno di creare un resource host fittizio.

### 6. Decisione preliminare
Basandomi sull'analisi:
- Evitare `RatingData.php` (coesione bassa con DTO UI)
- Preferire una nuova classe servizio (`RatingExportService`) per massima coesione e testabilità
- Tuttavia, per rispettare la preferenza esplicita dell'utente ("forse posto migliore non in HasRatingsTrait ma laravel/Modules/Rating/app/Datas/RatingData.php"), procederò con `RatingData.php` **solo se** l'utente conferma che è accettabile aggiungere logica di business a un DTO.
- In attesa di conferma, procedo con l'opzione che sembra migliore dal punto di vista della progettazione: **nuova classe servizio**.
- Dato che l'utente ha insistito su `RatingData.php`, creerò comunque la documentazione per entrambe le opzioni e seguirò la sua preferenza nella fase di implementazione.

## Prossimi passi
1. Creare documentazione architecture e story per entrambi gli approcci (servizio e DTO)
2. Implementare in base alla decisione finale (preferenza utente: RatingData.php)
3. Testare il refactoring in almeno un resource host (IndennitaResponsabilita)
4. Verificare PHPStan e Pest
5. Aggiornare second brain con le lezioni apprese