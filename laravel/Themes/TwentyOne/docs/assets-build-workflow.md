# Workflow Asset Build - Theme TwentyOne

## Sintomo

```text
Illuminate\Foundation\ViteManifestNotFoundException
Vite manifest not found at: /var/www/_bases/base_predict_fila5/public_html/themes/TwentyOne/manifest.json
```

## Causa reale

La view del tema usa `@vite(..., 'themes/TwentyOne')`, quindi Laravel cerca il manifest dentro `public_html/themes/TwentyOne/`.

Questo implica due passaggi distinti:

1. `npm run build`
   genera `laravel/Themes/TwentyOne/public/manifest.json`
2. `npm run copy`
   copia quel manifest nella directory pubblica letta da Laravel

Senza `copy`, la build esiste ma il frontend continua a fallire.

## Procedura standard

```bash
cd /var/www/_bases/base_predict_fila5/laravel/Themes/TwentyOne
npm install
npm run build
npm run copy
```

## Verifiche

```bash
ls -la /var/www/_bases/base_predict_fila5/public_html/themes/TwentyOne
cat /var/www/_bases/base_predict_fila5/public_html/themes/TwentyOne/manifest.json
```

Devono esistere almeno:

- `manifest.json`
- `assets/app-*.css`
- `assets/app-*.js`

## Regole

- I path CSS verso `vendor/filament/*` devono essere relativi alla posizione reale del file nel tema.
- Lo script `copy` deve creare la directory di destinazione.
- Un errore manifest del tema va risolto prima nella pipeline asset, non nel codice PHP.
