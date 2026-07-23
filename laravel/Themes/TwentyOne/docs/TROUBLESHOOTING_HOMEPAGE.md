# Troubleshooting Homepage - Error 500

## Problema
La homepage http://predict.local/it restituisce errore 500.

## Errore Identificato
```
Illuminate\Foundation\ViteException: Unable to locate file in Vite manifest: resources/css/app.css.
```

## Altro Errore Critico Osservato
```text
SQLSTATE[42S02]: Base table or view not found: 1146 Table 'predict_data.banners' doesn't exist
```

## Analisi

### Stack Trace
```
View: /var/www/_bases/base_predict_fila5/laravel/Themes/TwentyOne/resources/views/components/layouts/app.blade.php
```

### Causa
Il tema TwentyOne cerca di caricare `resources/css/app.css` tramite la direttiva:
```blade
@vite(['resources/css/app.css'],'themes/TwentyOne')
```

Ma il manifest di Vite non viene trovato nel percorso atteso.

## Verifiche Effettuate

### 1. Manifest Esistente
```bash
✅ File esiste: public_html/themes/TwentyOne/manifest.json
✅ Contenuto corretto:
{
  "resources/css/app.css": {
    "file": "assets/app-D5P4IeE3.css",
    "src": "resources/css/app.css",
    "isEntry": true
  }
}
```

### 2. Asset Compilati
```bash
✅ File CSS esiste: public_html/themes/TwentyOne/assets/app-D5P4IeE3.css
✅ File JS esiste: public_html/themes/TwentyOne/assets/app-CdDIvux5.js
```

### 3. Configurazione Vite Tema
```javascript
// laravel/Themes/TwentyOne/vite.config.js
laravel({
    publicDirectory: "../../../public_html/",
    input: [
        path.resolve(__dirname, "./resources/css/app.css"),
        path.resolve(__dirname, "./resources/js/app.js"),
    ],
})
```

## Possibili Cause

### Ipotesi 1: Configurazione Vite non allineata
Il `publicDirectory` potrebbe non essere configurato correttamente per come Laravel si aspetta di trovare i temi.

### Ipotesi 2: Cache non aggiornata
Laravel potrebbe aver cachato una vecchia configurazione.

### Ipotesi 3: Hot Module Replacement attivo
Se Vite è in modalità dev, il manifest potrebbe essere diverso.

### Ipotesi 4: infrastruttura `Predict` incompleta
Il tema renderizza lo slider hero tramite `$_theme->getMethodData('getBanner')`.
Se il modulo `Predict` espone il model `Banner` ma non ha la tabella `banners` sulla connection `predict`, la homepage puo` andare in errore anche con asset corretti.

## Soluzioni da Provare

### 1. Verificare configurazione Laravel
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

### 2. Ricompilare il tema
```bash
cd laravel/Themes/TwentyOne
npm run build
npm run copy
```

### 3. Verificare schema banner del modulo Predict
```bash
php artisan migrate
php artisan db:seed --class="Modules\\Predict\\Database\\Seeders\\BannerSeeder"
```

### 4. Verificare permessi
```bash
chmod -R 775 public_html/themes/TwentyOne
chown -R www-data:www-data public_html/themes/TwentyOne
```

### 5. Debug Vite
Aggiungere nel file .env:
```
VITE_DEBUG=true
```

## Comandi Utili per Debug

```bash
# Test con curl
curl -v http://predict.local/it 2>&1 | grep -i "vite\|manifest"

# Verifica manifest
cat public_html/themes/TwentyOne/manifest.json | jq .

# Verifica build
ls -lah public_html/themes/TwentyOne/assets/

# Test diretto asset
curl -I http://predict.local/themes/TwentyOne/assets/app-D5P4IeE3.css
```

## Prossimi Passi

1. ✅ Verificare manifest esistente
2. ✅ Verificare asset compilati
3. ✅ Verificare contratto slider/banner del modulo Predict
4. ⏳ Ricompilare tema con permessi corretti
5. ⏳ Testare con curl dopo ricompilazione e migrazione
6. ⏳ Creare o aggiornare test Pest automatizzato
7. ⏳ Documentare soluzione finale

## Risorse
- [Laravel Vite Documentation](https://laravel.com/docs/vite)
- [Vite Manifest Format](https://vitejs.dev/guide/backend-integration.html)
- [Laravel Themes Integration](https://github.com/laraxot/theme-twentyone-fila3)
