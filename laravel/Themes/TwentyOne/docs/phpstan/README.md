# Analisi Errore Vite: Unable to locate file in Vite manifest

> **Nota:** Questo errore è documentato in modo centrale nella sezione "Errori di pubblicazione asset Vite/NPM e gestione temi" della documentazione PHPStan del modulo CMS. Consulta [Modules/Cms/docs/phpstan/README.md](../../../Modules/Cms/docs/phpstan/README.md) per dettagli, motivazioni architetturali e strategie di prevenzione condivise.

## Cosa succede
Quando Laravel, tramite la direttiva `@vite(['resources/css/app.css'],'themes/TwentyOne')`, non trova il file `resources/css/app.css` nel manifest generato da Vite, viene sollevata una `Illuminate\Foundation\ViteException` o una `Illuminate\Foundation\ViteManifestNotFoundException`.

```
Unable to locate file in Vite manifest: resources/css/app.css.
```

Questo errore si manifesta tipicamente quando:
- Il tema non è stato buildato correttamente.
- `public_html/themes/TwentyOne` non contiene il manifest aggiornato.
- I file statici non sono stati copiati dalla sorgente locale del tema alla destinazione runtime prevista.

## Perché succede
**Motivazione architetturale:**  
Nel nostro sistema, ogni tema (es. `TwentyOne`) gestisce le proprie risorse statiche (CSS, JS, immagini) tramite Vite e NPM. La pipeline corretta del tema e':
- `npm install` per allineare le dipendenze
- `npm run build` per generare `public/manifest.json`
- `npm run copy` per pubblicare `public/*` in `public_html/themes/TwentyOne`

Se questa pipeline non viene eseguita dopo modifiche o dopo il primo deploy, Laravel non trova i file richiesti nel manifest runtime e l'applicazione va in errore.

## Cosa significa "pubblicare il tema"
**Pubblicare il tema** significa assicurarsi che tutte le risorse siano compilate e disponibili nel path runtime `public_html/themes/TwentyOne`, e che il manifest di Vite sia aggiornato.  
Questo è fondamentale per:
- Visualizzare correttamente lo stile e le funzionalità del tema
- Evitare errori di caricamento delle risorse
- Garantire la coerenza tra codice sorgente e asset pubblicati

## Come riconoscere e diagnosticare il problema
- L’errore appare tipicamente in ambiente di sviluppo o dopo un deploy.
- Il messaggio di errore cita sempre un file mancante nel manifest Vite.
- Nel log degli errori Laravel si trova la traccia della chiamata a `@vite()`.

## Soluzione operativa (da documentare, NON da eseguire automaticamente)
1. **Entrare nella cartella del tema**  
   Esempio:  
   ```
   cd /var/www/html/_bases/base_predict_fila5_mono/laravel/Themes/TwentyOne
   ```
2. **Eseguire la pipeline del tema**  
   ```
   npm install
   npm run build
   npm run copy
   ```
   Questa pipeline:
   - Installa le dipendenze del tema
   - Compila le risorse con Vite
   - Aggiorna il manifest locale
   - Copia i file nella cartella runtime letta da Laravel
3. **Verificare che la cartella runtime contenga i file e il manifest aggiornato**  
   - Deve essere presente `public_html/themes/TwentyOne/manifest.json`
   - Devono essere presenti gli asset compilati

## Prevenzione
- Dopo ogni modifica alle risorse del tema, ricordarsi di eseguire almeno `npm run build && npm run copy`.
- Automatizzare il processo di build nel deploy pipeline.
- Documentare nei README dei temi questa dipendenza fondamentale.

## Collegamenti e Approfondimenti
- [Vite Manifest Documentation](https://vitejs.dev/guide/backend-integration.html)
- [Laravel Vite Integration](https://laravel.com/docs/11.x/vite)
- [Sezione pubblicazione temi in docs root](../../../../project_docs/themes/management.md)

---

## Ipotesi di Risoluzione
- **Automatizzare**: Integrare un controllo che segnali la mancanza del manifest o dei file nella pipeline di CI/CD.
- **Documentare**: Aggiornare la documentazione di tutti i temi e la root per ricordare la necessità di pubblicare le risorse.
- **Script di verifica**: Creare uno script che controlli la presenza dei file necessari prima di avviare Laravel.

---

## Sintesi
**Questo errore è sintomo di una pipeline asset del tema incompleta. La soluzione consiste nell’eseguire `npm install`, `npm run build` e `npm run copy` nella cartella del tema coinvolto, verificando poi il manifest in `public_html/themes/TwentyOne/manifest.json`.**
