---
title: Installazione
description: Come Installare la Base
extends: _layouts.documentation
section: content
<<<<<<< HEAD
type: note
status: draft
updated: 2026-10-18
tags: [docs, xot, base]
qmd: "installazione {#installation} xot"
=======
>>>>>>> 77b1e2748 (first)
---

# Installazione {#installation}

### virtual host & Laragon

<<<<<<< HEAD
Il progetto può utilizzare varie configurazioni, situati dentro la cartella laravel/config.  
=======
Il progetto può utilizzare varie configurazioni, situati dentro la cartella laravel/config.
>>>>>>> 77b1e2748 (first)

Crearsi il virtual host con il nome del dominio uguale a quello del file di configurazione. Esempio:

- copio la cartella laravel/config/localhost in laravel/config/local/dominio/* e imposto i parametri nei file

- questo significa che il virtual host deve chiamarsi dominio.local

Noi suggeriamo l'utilizzo di Laragon perchè crea automaticamente i virtual host.
Dopo aver installato laragon, Assicurarsi di abilitate tutte le estensioni php indicate nella foto

<<<<<<< HEAD
<img class="block m-auto" src="https://laraxot.github.io/module_xot_fila3/assets/img/php-extentions.png" alt="php extentions" />  

Configurare le preferenze di laragon come in foto

<img class="block m-auto" src="https://laraxot.github.io/module_xot_fila3/assets/img/laragon-config.png" alt="laragon config" />  

=======
<img class="block m-auto" src="https://laraxot.github.io/module_xot_fila5/assets/img/php-extentions.png" alt="php extentions" />

Configurare le preferenze di laragon come in foto

<img class="block m-auto" src="https://laraxot.github.io/module_xot_fila5/assets/img/laragon-config.png" alt="laragon config" />
>>>>>>> 77b1e2748 (first)

Creare una cartella _bases dentro la cartella www, in questa cartella si andrà a clonare il progetto.

### Clonare la base in locale nella cartella del server, scaricando i submodules, e senza la storia delle modifiche

```bash
git clone https://NOME_BASE.git --recurse-submodules --depth=1
git submodule foreach git pull
```

Verificare che tutti i moduli siano a master

<<<<<<< HEAD
### dalla cartella "laravel" creare il file delle variabili d'ambiente .env 
    
=======
### dalla cartella "laravel" creare il file delle variabili d'ambiente .env

>>>>>>> 77b1e2748 (first)
```bash
cp .env.latest .env
```

- modificare parametri a seconda delle necessità
- dopo aver messo i nomi dei database, in phpmyadmin creare i database vuoti

### dalla cartella laravel, lanciare in bash il comando

```bash
../bashscripts/composer_init.sh
```

<<<<<<< HEAD
alternativa: 
=======
alternativa:
>>>>>>> 77b1e2748 (first)

```bash
composer update
cd Modules/Xot/Services
composer install
php artisan key:generate
```

### vedere la lista dei moduli con il comando

alternativa da terminale:

```bash
<<<<<<< HEAD
php artisan module:list 
=======
php artisan module:list
>>>>>>> 77b1e2748 (first)
```

### abilitare tutti i moduli con il comando

<<<<<<< HEAD
Assicurarsi che tutti i moduli siano abilitati  
=======
Assicurarsi che tutti i moduli siano abilitati
>>>>>>> 77b1e2748 (first)
in caso eseguire:

```bash
php artisan module:enable NomeModulo
```

### fare la migration

Eseguire il comando delle migrazioni per ogni modulo:

```bash
php artisan module:migrate NomeModulo
```

### per compilare il tema (da dentro la cartella laravel/Themes/NOME_TEMA)

```bash
npm install
npm run dev
<<<<<<< HEAD
```  

### creare la Giunzione

in locale, se utilizzate Laragon, dopo aver clonato il progetto bisogna creare la giunzione nella cartella www.  
Per creare la giunzione, andare nella cartella www ed eseguire tramite il prompt dei comandi 

```bash
mklink /j nome_cartella_giuzione path_della_cartella_progetto
```  
=======
```

### creare la Giunzione

in locale, se utilizzate Laragon, dopo aver clonato il progetto bisogna creare la giunzione nella cartella www.
Per creare la giunzione, andare nella cartella www ed eseguire tramite il prompt dei comandi

```bash
mklink /j nome_cartella_giuzione path_della_cartella_progetto
```
>>>>>>> 77b1e2748 (first)

dopo riavviare il server di laragon.

Fatto ciò, andare nel browser e inserire nell'url http://nome_cartella_giuzione.local/

### lavorare nel branch dev

<<<<<<< HEAD
Sia il progetto che ogni singolo modulo hanno un branch dev, generalmente utilizzato per lo sviluppo del progetto.  

nel singolo modulo  
=======
Sia il progetto che ogni singolo modulo hanno un branch dev, generalmente utilizzato per lo sviluppo del progetto.

nel singolo modulo
>>>>>>> 77b1e2748 (first)

```bash
git branch dev
git checkout dev
git_init.sh dev
git pull origin dev
git push origin dev -u
git merge origin master
<<<<<<< HEAD
```  

dopo aver eseguito  
git branch nome_branch  
git checkout nome_branch  

se il branch di un modulo vuole essere pubblicato, eseguire nel modulo 
```bash
../../../bascripts/git_init.sh nome_branch
```  
### Versione HEAD


## Collegamenti tra versioni di installation.md
* [installation.md](../../../Xot/docs/filament/installation.md)
* [installation.md](../../../Xot/docs/installation.md)
* [installation.md](../../../Xot/docs/base/installation.md)
* [installation.md](../../../User/docs/installation.md)
* [installation.md](../../../Lang/docs/installation.md)
* [installation.md](../../../Cms/docs/installation.md)
* [installation.md](../../../../Themes/One/docs/installation.md)


### Versione Incoming


---

=======
```

dopo aver eseguito
git branch nome_branch
git checkout nome_branch

se il branch di un modulo vuole essere pubblicato, eseguire nel modulo
```bash
../../../bascripts/git_init.sh nome_branch
```
### Versione HEAD

## Collegamenti tra versioni di installation.md
* [installation.md](../../../xot/project_docs/filament/installation.md)
* [installation.md](../../../xot/project_docs/installation.md)
* [installation.md](../../../xot/project_docs/base/installation.md)
* [installation.md](../../../user/project_docs/installation.md)
* [installation.md](../../../lang/project_docs/installation.md)
* [installation.md](../../../cms/project_docs/installation.md)
* [installation.md](../../../../themes/one/project_docs/installation.md)

### Versione Incoming

---
>>>>>>> 77b1e2748 (first)
