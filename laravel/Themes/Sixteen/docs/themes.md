# Gestione dei Temi

## Struttura
I temi sono organizzati nella cartella `Themes` e ciascuno ha la propria struttura indipendente. Ogni tema segue l'architettura Laravel standard con alcune personalizzazioni specifiche per il sistema.

## Temi Disponibili

### Sixteen
- [Documentazione Tema Sixteen](/Themes/Sixteen/docs/assets.md)
- [Gestione Asset Vite](/Modules/Theme/docs/assets.md)

## Problemi Comuni e Soluzioni

### Errori con gli Asset Vite
L'errore comune `Unable to locate file in Vite manifest: resources/css/app.css` indica che gli asset non sono stati pubblicati correttamente. Per risolvere:

```bash
cd /var/www/html/_bases/base_fixcity_fila5_mono/laravel/Themes/NomeTema
npm run copy
```

Maggiori dettagli nella [documentazione dedicata](/Modules/Theme/docs/assets.md).

## Best Practices
1. Utilizzare la struttura di cartelle standard
2. Mantenere gli asset organizzati
3. Pubblicare gli asset dopo ogni modifica significativa
4. Testare il tema in diversi contesti e risoluzioni

## Sviluppo Temi

### Creazione Nuovo Tema
Per creare un nuovo tema, utilizzare il comando:

```bash
php artisan theme:create NomeTema
```

### Pubblicazione Asset
Dopo modifiche agli asset (CSS, JS, immagini), eseguire:

```bash
cd /var/www/html/_bases/base_fixcity_fila5_mono/laravel/Themes/NomeTema
npm run copy
```

## Collegamenti
- [Documentazione Modulo Theme](/Modules/Theme/docs/assets.md)
- [Indice generale](/docs/index.md)
- [Documentazione moduli](/docs/modules/index.md) 
