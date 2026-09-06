# BMAD Story — PHPStan Fix AiAssistant Module

## Understand
- 115 errori PHPStan nel modulo AiAssistant
- Principali categorie:
  1. Cannot cast mixed to string/int (~90+ errori)
  2. Offset access array|null (~10 errori)
  3. Pest internal class errors in tests (~5 errori)
  4. Parameter type errors (~5 errori)

## Plan
1. Analizzare errori per priorità
2. Fix cast mixed -> tipi specifici
3. Fix offset access array|null
4. Fix Pest test assertions
5. Verificare con phpstan + phpmd + phpinsights + pest

## Implement

### Step 1: Cast Mixed
Dove trovo `(string)$var` o `(int)$var` con mixed:
- Aggiungere type check esplicito
- Usare pattern: `is_string($var) ? $var : ''` per string
- Usare pattern: `is_int($var) ? $var : 0` per int

### Step 2: Offset Access
Usare null coalescing: `$array[$key] ?? default`

### Step 3: Pest Tests
Cambiare `expect()->toBe()` con `Assert::assertSame()`

## Verify
- phpstan Modules/AiAssistant
- phpmd ./tools
- phpinsights ./tools
- pest

## Status
- Branch: dev
- Module: AiAssistant
- Next: Fix main action files
