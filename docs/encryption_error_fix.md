# Risoluzione Errore Crittografia Laravel

## Problema Identificato

L'errore `Unsupported cipher or incorrect key length` indica che la chiave di crittografia di Laravel (`APP_KEY`) non è configurata correttamente o è mancante.

### Errore Specifico
```
RuntimeException
Unsupported cipher or incorrect key length. Supported ciphers are: aes-128-cbc, aes-256-cbc, aes-128-gcm, aes-256-gcm.
```

## Analisi del Problema

### Cause Possibili
1. **APP_KEY mancante**: La variabile d'ambiente `APP_KEY` non è definita
2. **APP_KEY non valida**: La chiave non ha la lunghezza corretta (32 caratteri base64)
3. **Cipher non supportato**: Il cipher configurato non è supportato
4. **File .env mancante**: Il file di configurazione non esiste

### Configurazione Attuale
```php
// laravel/config/app.php
'cipher' => 'AES-256-CBC',
'key' => env('APP_KEY'),
```

## Soluzioni

### 1. Verifica File .env

Prima di tutto, verifica se esiste il file `.env` nella root del progetto Laravel:

```bash
# Dalla root del progetto
ls -la laravel/.env
```

Se il file non esiste, crealo:

```bash
# Copia il file di esempio
cp laravel/.env.example laravel/.env
```

### 2. Genera APP_KEY

Genera una nuova chiave di crittografia:

```bash
# Dalla directory laravel
cd laravel

# Genera una nuova APP_KEY
php artisan key:generate
```

Questo comando:
- Genera una chiave base64 di 32 caratteri
- La aggiunge automaticamente al file `.env`
- Formato: `base64:xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx`

### 3. Verifica Configurazione

Controlla che la chiave sia stata generata correttamente:

```bash
# Verifica il contenuto del file .env
grep APP_KEY laravel/.env
```

Dovrebbe apparire qualcosa come:
```
APP_KEY=base64:xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
```

### 4. Clear Cache

Pulisci la cache di configurazione:

```bash
# Dalla directory laravel
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

### 5. Verifica Permessi

Assicurati che i file abbiano i permessi corretti:

```bash
# Imposta i permessi corretti
chmod 644 laravel/.env
chmod -R 755 laravel/storage
chmod -R 755 laravel/bootstrap/cache
```

## Soluzioni Alternative

### Se il Problema Persiste

#### 1. Verifica Cipher Supportato
```php
// laravel/config/app.php
'cipher' => 'AES-256-CBC', // Assicurati che sia questo
```

#### 2. Forza Regenerazione Chiave
```bash
# Rimuovi la chiave esistente
sed -i '/APP_KEY=/d' laravel/.env

# Rigenera la chiave
php artisan key:generate
```

#### 3. Verifica Estensioni PHP
```bash
# Verifica che le estensioni necessarie siano installate
php -m | grep -E "(openssl|mbstring)"
```

#### 4. Controlla Versione PHP
```bash
# Verifica la versione PHP
php --version
```

Laravel 12 richiede PHP 8.2+.

## Debug Avanzato

### 1. Verifica Configurazione Manuale
```php
// Test manuale della configurazione
php artisan tinker
>>> config('app.key')
>>> config('app.cipher')
```

### 2. Log di Debug
```bash
# Abilita il debug temporaneamente
echo "APP_DEBUG=true" >> laravel/.env

# Controlla i log
tail -f laravel/storage/logs/laravel.log
```

### 3. Test Crittografia
```php
// Test manuale della crittografia
php artisan tinker
>>> encrypt('test')
>>> decrypt(encrypt('test'))
```

## Prevenzione

### 1. Script di Setup Automatico
```bash
#!/bin/bash
# setup-encryption.sh

cd laravel

# Verifica se .env esiste
if [ ! -f .env ]; then
    echo "File .env non trovato. Creazione da .env.example..."
    cp .env.example .env
fi

# Genera APP_KEY se non esiste
if ! grep -q "APP_KEY=base64:" .env; then
    echo "Generazione APP_KEY..."
    php artisan key:generate
fi

# Clear cache
php artisan config:clear
php artisan cache:clear

echo "Setup crittografia completato!"
```

### 2. Verifica Pre-deploy
```bash
#!/bin/bash
# check-encryption.sh

cd laravel

# Verifica APP_KEY
if ! grep -q "APP_KEY=base64:" .env; then
    echo "ERRORE: APP_KEY non configurata!"
    exit 1
fi

# Test crittografia
if ! php artisan tinker --execute="echo encrypt('test');" > /dev/null 2>&1; then
    echo "ERRORE: Crittografia non funzionante!"
    exit 1
fi

echo "Crittografia OK!"
```

## Troubleshooting

### Problema: Chiave Generata ma Errore Persiste

1. **Verifica formato chiave**:
   ```bash
   grep APP_KEY laravel/.env
   ```
   Deve essere: `APP_KEY=base64:xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx`

2. **Verifica lunghezza**:
   ```bash
   # Estrai la chiave e verifica la lunghezza
   KEY=$(grep APP_KEY laravel/.env | cut -d'=' -f2)
   echo $KEY | base64 -d | wc -c
   ```
   Deve essere 32 byte.

### Problema: Cipher Non Supportato

1. **Verifica cipher supportati**:
   ```php
   php artisan tinker
   >>> openssl_get_cipher_methods()
   ```

2. **Cambia cipher se necessario**:
   ```php
   // laravel/config/app.php
   'cipher' => 'AES-128-CBC', // Prova con AES-128-CBC
   ```

### Problema: Permessi File

```bash
# Imposta permessi corretti
sudo chown -R www-data:www-data laravel/storage
sudo chown -R www-data:www-data laravel/bootstrap/cache
sudo chmod -R 755 laravel/storage
sudo chmod -R 755 laravel/bootstrap/cache
```

## Comandi Utili

### Verifica Stato
```bash
# Verifica configurazione
php artisan config:show app.key
php artisan config:show app.cipher

# Verifica ambiente
php artisan env
```

### Reset Completo
```bash
# Reset completo della configurazione
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
composer dump-autoload
```

### Backup e Ripristino
```bash
# Backup configurazione
cp laravel/.env laravel/.env.backup

# Ripristino
cp laravel/.env.backup laravel/.env
```

## Note Importanti

1. **Sicurezza**: Non committare mai il file `.env` nel repository
2. **Ambiente**: Usa `.env.example` come template
3. **Deploy**: Genera sempre una nuova APP_KEY per ogni ambiente
4. **Backup**: Mantieni backup delle chiavi per ambienti di produzione

---

*Documento creato il: $(date)*
*Problema: Errore crittografia Laravel*
*Soluzione: Configurazione APP_KEY* 