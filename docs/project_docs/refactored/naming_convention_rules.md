# Regole di Naming per Cartelle Docs

## Regola Critica: Nomi File e Cartelle

**NELLE CARTELLE DOCS:**
- ✅ **Solo caratteri minuscoli** per nomi di file e cartelle
- ✅ **Eccezione**: `README.md` può essere maiuscolo
- ❌ **Vietato**: Caratteri maiuscoli in qualsiasi altro nome

## Esempi Corretti
```
docs/
├── README.md ✅
├── installation.md ✅
├── configuration.md ✅
├── api-reference.md ✅
├── user-guide.md ✅
└── subfolder/
    ├── README.md ✅
    ├── getting-started.md ✅
    └── advanced-topics.md ✅
```

## Esempi Incorretti
```
docs/
├── Installation.md ❌
├── Configuration.md ❌
├── API-Reference.md ❌
├── UserGuide.md ❌
└── SubFolder/ ❌
    ├── GettingStarted.md ❌
    └── AdvancedTopics.md ❌
```

## Comandi per Correzione
```bash
# Rinominare file con maiuscole
find docs -name "*[A-Z]*" -type f | grep -v README.md

# Rinominare cartelle con maiuscole
find docs -name "*[A-Z]*" -type d

# Script di correzione automatica
for file in $(find docs -name "*[A-Z]*" -type f | grep -v README.md); do
    newname=$(echo "$file" | tr '[:upper:]' '[:lower:]')
    mv "$file" "$newname"
done
```

## Applicazione Globale
Questa regola si applica a:
- Tutte le cartelle `docs/` del progetto
- Tutti i moduli Laravel
- Tutti i temi
- Tutta la documentazione

---
*Regola critica da rispettare sempre* 