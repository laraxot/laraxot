# Deployment e validazione produzione

## Produzione

- **Sito live**: https://sottana.net
- **Deploy**: push sul branch `master` attiva l’auto-deploy
- **Validazione**: dopo modifiche frontend o contatti/mappa verificare su https://sottana.net/it/contatti e altre pagine coinvolte

## Workflow

1. Sviluppo e test in locale (es. http://127.0.0.1:8002)
2. Commit e push su branch di sviluppo
3. Merge su `master` → auto-deploy
4. Verifica su https://sottana.net (stesse pagine modificate)

## Pagine da controllare dopo deploy

- Home: https://sottana.net/it
- Contatti (mappa, indirizzo, link Google Maps): https://sottana.net/it/contatti
- Servizi, Chi siamo, altre pagine toccate dalla release

## PageSpeed Insights

Validare le pagine frontoffice con [PageSpeed Insights](https://pagespeed.web.dev/). Elenco URL e procedura in:

- [Themes/Two/docs/pagespeed-frontoffice-validation.md](laravel/Themes/Two/docs/pagespeed-frontoffice-validation.md)
- Elenco URL: [docs/pagespeed-frontoffice-urls.txt](pagespeed-frontoffice-urls.txt)

## Collegamenti

- [Tema Two - Deploy e validazione](laravel/Themes/Two/docs/deployment-and-validation.md)
- [Contatti e mappa - Sottana Service](laravel/Themes/Two/docs/contatti-sottana-service.md)
- [PageSpeed frontoffice](laravel/Themes/Two/docs/pagespeed-frontoffice-validation.md)
- [Regole mappe solo gratuiti](.cursor/rules/free-maps-only.mdc)
