# Validazione frontoffice (batch)

Questa guida permette di validare in modo ripetibile il frontoffice (performance, accessibilità, markup HTML) senza usare procedure manuali.

## Prerequisiti

- Node.js (per `npx`)
- Connettività verso `https://sottana.net`

## Lista URL

La lista URL è in:

- `laravel/docs/pagespeed-frontoffice-urls.txt`

Un URL per riga.

## 1. Lighthouse (equivalente PageSpeed)

Esegue audit Desktop e Mobile e salva report HTML:

```bash
./bashscripts/validation/run-lighthouse-frontoffice.sh
```

Output:

- `laravel/storage/validation/lighthouse/`

## 2. Axe (accessibilità)

Esegue audit WCAG e salva report JSON:

```bash
./bashscripts/validation/run-axe-frontoffice.sh
```

Output:

- `laravel/storage/validation/axe/`

## 3. W3C Nu Validator (markup HTML)

Scarica l’HTML e lo invia al validator via POST (senza copiare/incollare):

```bash
./bashscripts/validation/validate-frontoffice-w3c.sh
```

Output:

- `laravel/storage/validation/w3c/`

## Interpretazione risultati

- Lighthouse: apri i report `.html` e confronta i punteggi.
- Axe: apri i `.json` e guarda `violations`.
- W3C: apri i `.json` e filtra `messages` per `type=error`.
