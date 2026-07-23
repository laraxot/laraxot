# Gestione degli asset Vite per il tema Sixteen

## Problema
Se compare l'errore:

```
Unable to locate file in Vite manifest: resources/css/app.css
```

significa che gli asset Vite non sono stati pubblicati correttamente nella cartella del tema.

## Soluzione
Portarsi nella cartella del tema:

```bash
cd /var/www/html/_bases/base_fixcity_fila5_mono/laravel/Themes/Sixteen
npm run copy
```

Questo comando si occupa di copiare e generare tutti gli asset necessari, risolvendo l'errore di Vite.

---

## Collegamenti
- [Guida risoluzione errore Vite nella root](../../../../../docs/project/modules/cms.md)

Vedi anche la sezione "Gestione temi e asset" nella documentazione di root del progetto.
