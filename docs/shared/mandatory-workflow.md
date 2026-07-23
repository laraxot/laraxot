# MANDATORY WORKFLOW - Post File Modification

## 🚨 REGOLA CRITICA - SEGUI SEMPRE QUESTO WORKFLOW

Dopo **OGNI** modifica a un file, **DEVI** seguire questo workflow completo:

### 1. Verifica Qualità Codice

#### PHPStan Level 10
```bash
cd laravel
./vendor/bin/phpstan analyse path/to/modified/file.php --level=10 --memory-limit=-1
```

#### PHPMD (PHP Mess Detector)
```bash
./vendor/bin/phpmd path/to/modified/file.php text cleancode,codesize,design,naming,unusedcode
```

#### PHPInsights
```bash
./vendor/bin/phpinsights analyse path/to/modified/file.php --min-quality=90 --min-complexity=90 --min-architecture=90 --min-style=90
```

### 2. Loop di Correzione e Ricontrollo

Se vengono trovati errori:
- Correggi gli errori
- Ri-esegui tutti e tre i tool
- Ripeti fino a **PERFEZIONE** (0 errori, tutte le metriche passano)

### 3. Aggiornamento Documentazione

Dopo aver raggiunto la perfezione:
- Studia quello che hai imparato dalle correzioni
- Aggiorna la cartella `docs/` del modulo con:
  - Nuovi pattern scoperti
  - Correzioni applicate
  - Best practices apprese
  - Anti-pattern da evitare
- Aggiorna la cartella `docs/` del tema se pertinente

### 4. Workflow Git

Una volta aggiornata la documentazione:
```bash
git add .
git commit -m "type(scope): messaggio descrittivo

- Dettaglio 1
- Dettaglio 2
- PHPStan Level 10: ✅ 0 errori
- PHPMD: ✅ Pulito
- PHPInsights: ✅ Score 90+"

git push
```

## 📋 Checklist

Usa questa checklist per ogni modifica di file:

- [ ] File modificato
- [ ] PHPStan Level 10 eseguito
- [ ] PHPMD eseguito
- [ ] PHPInsights eseguito
- [ ] Tutti gli errori corretti
- [ ] Tutti i tool ri-eseguiti fino a perfezione
- [ ] Docs del modulo aggiornate
- [ ] Docs del tema aggiornate (se applicabile)
- [ ] Git commit creato
- [ ] Git push eseguito

## 🎯 Perché Questo Workflow?

1. **Garanzia di Qualità**: Assicura che ogni modifica rispetti gli standard più alti
2. **Preservazione della Conoscenza**: Documenta pattern e apprendimenti immediatamente
3. **Collaborazione Team**: Storico commit chiaro con metriche di qualità
4. **Miglioramento Continuo**: Le docs evolvono con il codebase
5. **Zero Debito Tecnico**: I problemi vengono catturati e risolti immediatamente

## 🚫 Non Saltare Mai

**MAI** saltare nessun passo di questo workflow. Ogni passo è critico:
- Salta controlli qualità = Il debito tecnico si accumula
- Salta aggiornamento docs = La conoscenza viene persa
- Salta git commit = Le modifiche non sono tracciate
- Salta git push = Il lavoro non è condiviso/backuppato

## 📖 Regole Correlate

- [PHPStan Critical Rules](./phpstan-critical-rules.md)
- [Documentation Standards](./documentation-standards.md)
- [Git Commit Conventions](./git-conventions.md)

## 🔄 Filosofia Laraxot

Questo workflow incarna i principi fondamentali Laraxot:

- **Logic**: Verifica matematica della correttezza (PHPStan)
- **Philosophy**: DRY + KISS + SOLID (PHPMD)
- **Politics**: Governance centralizzata della qualità (PHPInsights)
- **Religion**: Type safety come dogma (PHPStan Level 10)
- **Zen**: La perfezione è un processo, non una destinazione (Loop di correzione)

---

**Ultimo Aggiornamento**: 15 Dicembre 2025
**Status**: OBBLIGATORIO - NESSUNA ECCEZIONE
