# BMAD Story — PHPStan Fix Campaign 2026-09-06

## Understand

- **Problem**: PHPStan analyse su tutti i moduli restituisce errori che devono essere corretti
- **Scope**: Tutti i moduli in `Modules/*/`
- **Constraint**: Non modificare mai phpstan.neon (solo l'utente può)
- **Constraint**: Dove trovi `mixed`, sostituirlo con tipo più specifico
- **Constraint**: User model → UserContract o XotData::make()->getUserClass()

## Plan

### Phase 1: Git Sync (tutti i moduli)
1. Per ogni modulo: `git fetch laraxot dev && git merge laraxot/dev --allow-unrelated-histories -s resolve`
2. Verificare che branch è `dev`

### Phase 2: PHPStan Analysis
1. Eseguire `phpstan analyse Modules` modulo per modulo
2. Categorizzare errori per tipo e priorità
3. Pattern ricorrenti da fixare:
   - `mixed` → tipo specifico
   - UserContract per riferimenti utente
   - XotData::make()->getUserClass() per classi utente

### Phase 3: Fix per modulo
1. Partire dal modulo Xot (base)
2. Fix errori uno per uno
3. Dopo ogni fix: phpstan + phpmd + phpinsights + pest
4. Incrementare coverage Pest

### Phase 4: Git Sync per modulo
1. `git add -A`
2. `git commit <msg>`
3. `git fetch laraxot dev && git merge laraxot/dev --allow-unrelated-histories -s resolve`
4. `git push -u`

## Implement

### Step 1: Fetch + Merge su tutti i moduli
```bash
for d in Modules/*/; do 
  cd "$d"
  git fetch laraxot dev 2>&1 | tail -1
  git merge laraxot/dev --allow-unrelated-histories -s resolve --no-edit 2>&1 | tail -1
  cd - > /dev/null
done
```

### Step 2: PHPStan per modulo Xot
```bash
./vendor/bin/phpstan analyse Modules/Xot --memory-limit=8G
```

### Step 3: Fix pattern errori
- File con `property.notFound` → verificare proprietà dichiarate
- File con `argument.type` → aggiungere type hints
- File con `method.nonObject` → verificare oggetto non è null prima di chiamare metodo

## Verify

- `phpstan analyse Modules/Xot --memory-limit=8G` → 0 errori
- `./tools/phpmd.sh Modules/Xot/app` → 0 violation
- `./vendor/bin/phpinsights analyse Modules/Xot/app --no-interaction` → quality pass
- `./vendor/bin/pest Modules/Xot/tests` → all green

## Document

- Aggiornare `Modules/Xot/docs/wiki/` con fix applicati
- Aggiornare second brain in `docs/wiki/`
- Creare backlink nelle pagine correlate

## Status

- Branch: dev
- Module: Xot (first)
- Next: Eseguire fetch/merge su tutti i moduli, poi PHPStan modulo per modulo
