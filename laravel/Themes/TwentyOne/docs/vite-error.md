# Errore Vite: Unable to locate file in Vite manifest

## Descrizione dell'Errore

L'errore si verifica quando Laravel non riesce a trovare i file di asset (CSS, JS) nel manifest di Vite. Questo accade tipicamente quando:

1. I file non sono stati compilati correttamente
2. Il manifest non è stato generato
3. I percorsi nel manifest non corrispondono a quelli richiesti

## Sintomo Specifico

```
Internal Server Error
Illuminate\Foundation\ViteException
Unable to locate file in Vite manifest: resources/css/app.css
```

## Cause Possibili

1. **Tema non pubblicato**: Il tema non è stato pubblicato correttamente
2. **Manifest mancante**: Il file `manifest.json` non è stato generato
3. **Percorsi errati**: I percorsi nel manifest non corrispondono a quelli richiesti
4. **Cache non aggiornata**: La cache di Laravel non è stata aggiornata

## Soluzione

### 1. Pubblicare il Tema

```bash
cd /var/www/html/_bases/base_predict_fila5_mono/laravel/Themes/TwentyOne
npm run copy
```

Questo comando:
- Compila gli asset
- Genera il manifest
- Copia i file nella cartella di destinazione

### 2. Verificare la Struttura

Dopo la pubblicazione, verificare che esistano:
- `public/themes/TwentyOne/dist/manifest.json`
- `public/themes/TwentyOne/dist/resources/css/app.css`

### 3. Pulire la Cache

```bash
php artisan cache:clear
php artisan view:clear
php artisan config:clear
```

### 4. Verificare la Configurazione

Controllare in `config/vite.php` che i percorsi siano corretti:

```php
'build_path' => 'themes/TwentyOne/dist',
```

## Debug

### 1. Verificare il Manifest

```bash
cat public/themes/TwentyOne/dist/manifest.json
```

Verificare che contenga l'entry per `resources/css/app.css`

### 2. Verificare i File

```bash
ls -la public/themes/TwentyOne/dist/resources/css/
```

### 3. Log di Vite

```bash
tail -f storage/logs/laravel.log
```

## Prevenzione

1. **Automazione**: Aggiungere lo script di pubblicazione nel workflow di deployment
2. **Verifica**: Implementare controlli pre-deployment
3. **Documentazione**: Mantenere aggiornata questa documentazione

## Note Importanti

- L'errore può verificarsi anche dopo aggiornamenti di Laravel o Vite
- È importante mantenere sincronizzati i percorsi tra il codice e il manifest
- In ambiente di sviluppo, assicurarsi che il server di sviluppo Vite sia in esecuzione 