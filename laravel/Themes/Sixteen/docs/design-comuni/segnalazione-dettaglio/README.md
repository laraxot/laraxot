# Segnalazione dettaglio

Data: 2026-04-03

## Obiettivo

Rimpiazzare la test page placeholder `tests.segnalazione-dettaglio` con una pagina strutturata come la reference Design Comuni per la scheda servizio.

## File toccati

- `laravel/Themes/Sixteen/resources/views/components/blocks/tests/ticket-detail.blade.php`
- `laravel/config/local/fixcity/database/content/pages/tests.segnalazione-dettaglio.json`
- `laravel/Themes/Sixteen/resources/css/app.css`

## Esito

- route locale: `200 OK`
- breadcrumb reale aggiunto
- hero/header servizio aggiunto
- indice laterale pagina aggiunto
- sezioni principali del servizio aggiunte
- sezione contatti nel `main` aggiunta
- rating finale mantenuto come blocco tema esistente
- `npm run build` eseguito
- `npm run copy` eseguito
- `artisan optimize:clear` eseguito

## Artifacts

- [local.png](./local.png)
- [reference.png](./reference.png)

## Note

La pagina non usa Bootstrap Italia runtime: la replica e ottenuta con markup compatibile e CSS scoped del tema Sixteen.
