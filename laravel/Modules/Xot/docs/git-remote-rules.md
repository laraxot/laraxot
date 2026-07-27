# Git Remote Architecture & Root Cleanliness Rules

## 🚨 CRITICAL MANDATE: Root Remote Isolation

**Il repository monorepo radice (`/var/www/_bases/base_ptvx_fila5/`) deve contenere ESCLUSIVAMENTE il remote `origin`.**

```ini
# ✅ CORRETTO in /var/www/_bases/base_ptvx_fila5/.git/config
[remote "origin"]
    url = git@github.com:provtv/base_ptv_fila5.git
    fetch = +refs/heads/*:refs/remotes/origin/*

# ❌ ERRORE GRAVISSIMO - Nessun remote di singoli moduli deve esistere nel root:
[remote "geo"]
    url = git@github.com:laraxot/module_geo_fila5.git
```

---

## 🔍 Causa Radice e Prevenzione

1. **Origine dell'Errore**:
   Durante le operazioni di sincronizzazione dei moduli o subtree script (es. `bashscripts/git/subtrees/sync_remote_repo.sh`), venivano aggiunti remote temporanei nel `.git/config` del repository principale (es: `git remote add geo ...`) che non venivano successivamente rimossi o ripuliti.

2. **Azioni Correttive Obbligatorie**:
   - Gli script di sincronizzazione dei subtree devono sempre includere trappole di pulizia (`trap` / `cleanup`) ed eseguire `git remote remove "$origin"` su qualsiasi remote temporaneo prima di uscire.
   - Tutti gli script di maintenance git devono verificare e garantire che solo il remote `origin` persista a livello root.
   - I remotes dei singoli moduli devono risiedere esclusivamente nei rispettivi sub-repository o essere gestiti tramite `gitmodules.ini`.

---

## 🛡️ Checklist Pre-Commit / Pre-Push Git

- [ ] Ho eseguito `git remote -v` a livello root e confermato la presenza del **solo** remote `origin`?
- [ ] Ho verificato che nessun modulo abbia lasciato remote temporanei nel repo root?
