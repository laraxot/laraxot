# Contributing

## Panoramica

Questa documentazione descrive le linee guida per contribuire al tema One e come partecipare allo sviluppo.

## Come Contribuire

### 1. Setup

1. Clona il repository:

```bash
git clone https://github.com/laraxot/theme_one_fila3.git
```

2. Installa le dipendenze:

```bash
composer install
npm install
```

3. Configura l'ambiente:

```bash
cp .env.example .env
php artisan key:generate
```

4. Esegui le migrazioni:

```bash
php artisan migrate
```

5. Compila gli asset:

```bash
npm run build
```

### 2. Sviluppo

1. Crea un branch:

```bash
git checkout -b feature/nome-feature
```

2. Sviluppa la feature:

```bash
# Modifica i file
# Esegui i test
# Compila gli asset
```

3. Committa le modifiche:

```bash
git add .
git commit -m "feat: aggiungi feature"
```

4. Pusha le modifiche:

```bash
git push origin feature/nome-feature
```

5. Crea una Pull Request:

```bash
# Vai su GitHub
# Crea una Pull Request
# Attendi la review
```

### 3. Test

1. Esegui i test unitari:

```bash
php artisan test
```

2. Esegui i test browser:

```bash
php artisan dusk
```

3. Esegui l'analisi statica:

```bash
./vendor/bin/phpstan analyse
```

4. Esegui il linting:

```bash
./vendor/bin/pint
```

5. Esegui i test di integrazione:

```bash
php artisan test --testsuite=Integration
```

## Linee Guida

### 1. Codice

- Seguire le [PSR-12](https://www.php-fig.org/psr/psr-12/) per il codice PHP
- Utilizzare [Laravel Pint](https://laravel.com/docs/10.x/pint) per il linting
- Utilizzare [PHPStan](https://phpstan.org/) per l'analisi statica
- Utilizzare [PHPUnit](https://phpunit.de/) per i test
- Utilizzare [Laravel Dusk](https://laravel.com/docs/10.x/dusk) per i test browser

### 2. Viste

- Utilizzare [Blade](https://laravel.com/docs/10.x/blade) per le viste
- Utilizzare [Volt](https://livewire.laravel.com/docs/volt) per i componenti
- Utilizzare [Folio](https://laravel.com/docs/10.x/folio) per il routing
- Utilizzare [Tailwind CSS](https://tailwindcss.com/) per lo styling
- Utilizzare [Alpine.js](https://alpinejs.dev/) per l'interattività

### 3. Asset

- Utilizzare [Vite](https://vitejs.dev/) per il bundling
- Utilizzare [TypeScript](https://www.typescriptlang.org/) per il JavaScript
- Utilizzare [PostCSS](https://postcss.org/) per il CSS
- Utilizzare [ESLint](https://eslint.org/) per il linting JavaScript
- Utilizzare [Prettier](https://prettier.io/) per la formattazione

### 4. Documentazione

- Utilizzare [Markdown](https://www.markdownguide.org/) per la documentazione
- Utilizzare [Laravel Scribe](https://scribe.knuckles.wtf/) per la documentazione API
- Utilizzare [Laravel Telescope](https://laravel.com/docs/10.x/telescope) per il debugging
- Utilizzare [Laravel Horizon](https://laravel.com/docs/10.x/horizon) per le code
- Utilizzare [Laravel Nova](https://nova.laravel.com/) per l'admin panel

## Processo di Review

### 1. Pull Request

1. Crea una Pull Request:

```bash
# Vai su GitHub
# Crea una Pull Request
# Attendi la review
```

2. Attendi la review:

```bash
# Attendi i commenti
# Rispondi ai commenti
# Apporta le modifiche necessarie
```

3. Merge della Pull Request:

```bash
# Attendi l'approvazione
# Merge della Pull Request
# Elimina il branch
```

### 2. Code Review

1. Verifica il codice:

```bash
# Verifica la qualità del codice
# Verifica i test
# Verifica la documentazione
```

2. Verifica le performance:

```bash
# Verifica le performance
# Verifica la sicurezza
# Verifica l'accessibilità
```

3. Verifica la compatibilità:

```bash
# Verifica la compatibilità con Laravel
# Verifica la compatibilità con Filament
# Verifica la compatibilità con Vite
```

## Riferimenti

- [Laravel Documentation](https://laravel.com/docs)
- [Filament Documentation](https://filamentphp.com/docs)
- [Vite Documentation](https://vitejs.dev/guide)
- [Tailwind CSS Documentation](https://tailwindcss.com/docs) 