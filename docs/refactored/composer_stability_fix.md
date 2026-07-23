# Fix Composer Stability

## Obiettivo
Impostare `"minimum-stability": "dev"` in tutti i file `composer.json` del progetto.

## File da Modificare

### 1. Composer.json Principale
- **Percorso**: `laravel/composer.json`
- **Stato**: Da verificare
- **Azione**: Aggiungere o modificare `"minimum-stability": "dev"`

### 2. Composer.json Moduli
- **Percorso**: `laravel/Modules/*/composer.json`
- **Moduli da controllare**:
  - `laravel/Modules/Xot/composer.json`
  - `laravel/Modules/User/composer.json`
  - `laravel/Modules/Geo/composer.json`
  - `laravel/Modules/Notify/composer.json`
  - `laravel/Modules/Tenant/composer.json`
  - Altri moduli...

### 3. Composer.json Bashscripts
- **Percorso**: `bashscripts/composer.json`
- **Stato**: Da verificare

## Comandi da Eseguire

```bash
# 1. Composer.json principale
sed -i 's/"minimum-stability": "stable"/"minimum-stability": "dev"/g' laravel/composer.json

# 2. Aggiungere se mancante nel composer.json principale
if ! grep -q '"minimum-stability"' laravel/composer.json; then
    sed -i '/"extra"/i\    "minimum-stability": "dev",' laravel/composer.json
fi

# 3. Moduli
for module in laravel/Modules/*/; do
    if [ -f "${module}composer.json" ]; then
        # Modificare se esiste
        sed -i 's/"minimum-stability": "stable"/"minimum-stability": "dev"/g' "${module}composer.json"
        # Aggiungere se mancante
        if ! grep -q '"minimum-stability"' "${module}composer.json"; then
            sed -i '/"extra"/i\    "minimum-stability": "dev",' "${module}composer.json"
        fi
    fi
done

# 4. Bashscripts
if [ -f "bashscripts/composer.json" ]; then
    sed -i 's/"minimum-stability": "stable"/"minimum-stability": "dev"/g' bashscripts/composer.json
    if ! grep -q '"minimum-stability"' bashscripts/composer.json; then
        sed -i '/"extra"/i\    "minimum-stability": "dev",' bashscripts/composer.json
    fi
fi
```

## Verifica
```bash
# Verificare che tutti i composer.json abbiano minimum-stability: dev
find . -name "composer.json" -exec grep -l "minimum-stability" {} \;
```

## Note
- Se `minimum-stability` è già impostato su `"dev"`, non fare nulla
- Se `minimum-stability` è impostato su `"stable"`, cambiarlo in `"dev"`
- Se `minimum-stability` non esiste, aggiungerlo prima della sezione `"extra"`

---
*Creato il: $(date)*
*Stato: In attesa di esecuzione* 