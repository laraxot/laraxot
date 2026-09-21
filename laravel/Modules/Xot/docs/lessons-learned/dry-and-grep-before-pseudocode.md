---
name: dry-and-grep-before-pseudocode
description: "Prima di scrivere un metodo helper, grep nel codebase: se esiste già va estratto come riutilizzabile, non replicato"
metadata:
  type: lesson-learned
  created: 2026-09-15
  github_issues: []
---

# Grep prima di scrivere pseudocodice: DRY sui metodi helper

## L'errore che si è ripetuto

Proposto un nuovo metodo `hasColumn(string $column): bool` dentro `HasXotTable`, inline nel
metodo che lo usava, senza controllare se un metodo equivalente esisteva già nel codebase.

Un grep successivo ha trovato `hasColumnMotivo()` già presente su `IndennitaResponsabilita`
e `LettF` — implementazioni private, non condivise, che facevano la stessa introspection di
schema con un nome diverso.

## Perché è sbagliato

Scrivere pseudocodice "a memoria" senza verificare il codebase reale porta a duplicare
logica già esistente sotto un nome diverso. Ogni duplicato è un punto di manutenzione in
più e una fonte di comportamento divergente nel tempo.

## Come si fa correttamente

1. **Prima** di introdurre un metodo helper, grep per pattern equivalenti:
   ```bash
   rg "function hasColumn|hasColumn\(" laravel/Modules/ --include="*.php"
   ```
2. Se trovi 2+ implementazioni equivalenti (anche con nomi diversi), estrai un metodo/trait
   generico riutilizzabile invece di aggiungerne una terza.
3. Se il metodo è breve e riutilizzabile (es. una query di schema), **non inline-arlo** nel
   chiamante per "leggibilità": un metodo con un nome chiaro (`hasColumn()`) è più leggibile
   di 5 righe di logica di introspection ripetute in ogni punto che ne ha bisogno.

## Come riconoscerlo in futuro

Checklist prima di scrivere un nuovo metodo helper:

- [ ] Ho fatto grep per un nome simile o equivalente nel codebase?
- [ ] Se esistono 2+ varianti, ho valutato l'estrazione in un trait condiviso?
- [ ] Il metodo è abbastanza generico da meritare un nome proprio (non inline)?

## Riferimenti

- Story 5.104, 5.105 (`Modules/Xot/docs/stories/`)
- Memory: `dry-principle-schema-introspection.md`, `grep-before-pseudocode-rule.md`
