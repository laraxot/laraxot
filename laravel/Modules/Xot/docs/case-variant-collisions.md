# Collisioni di nome per sola differenza di maiuscole

**Misurato**: 2026-08-31
**Regola violata**: `no-case-variations`, `case_sensitive_naming_critical`
**Quadro generale**: [Modules/Xot/docs/stato-qualita-progetto-2026-08-31.md](../../Xot/docs/stato-qualita-progetto-2026-08-31.md)

## Il problema

In questo modulo esistono percorsi che differiscono solo per maiuscole. Su Linux
convivono. Su filesystem case-insensitive, cioe' macOS di default e Windows, i due
percorsi sono lo stesso percorso: al clone uno dei due file sovrascrive l'altro, in
modo non deterministico. Il repository risulta corrotto senza che nulla segnali
l'errore.

Riproduzione:

```bash
cd laravel && find Modules/Xot -name '*.php' -not -path '*/vendor/*' \
  | awk '{print tolower($0)}' | sort | uniq -d
```

## Coppie a contenuto identico (18)

Pura duplicazione. Si tiene la variante conforme a PSR-4 e si cancella l'altra.

```
Modules/Xot/app/Http/Http/Controllers/xotbasecontroller.php
Modules/Xot/app/Http/Http/Controllers/XotBaseController.php

Modules/Xot/lang/pt_br/alerts.php
Modules/Xot/lang/pt_BR/alerts.php

Modules/Xot/lang/pt_br/auth.php
Modules/Xot/lang/pt_BR/auth.php

Modules/Xot/lang/pt_br/buttons.php
Modules/Xot/lang/pt_BR/buttons.php

Modules/Xot/lang/pt_br/exceptions.php
Modules/Xot/lang/pt_BR/exceptions.php

Modules/Xot/lang/pt_br/health.php
Modules/Xot/lang/pt_BR/health.php

Modules/Xot/lang/pt_br/history.php
Modules/Xot/lang/pt_BR/history.php

Modules/Xot/lang/pt_br/http.php
Modules/Xot/lang/pt_BR/http.php

Modules/Xot/lang/pt_br/labels.php
Modules/Xot/lang/pt_BR/labels.php

Modules/Xot/lang/pt_br/menus.php
Modules/Xot/lang/pt_BR/menus.php

Modules/Xot/lang/pt_br/navs.php
Modules/Xot/lang/pt_BR/navs.php

Modules/Xot/lang/pt_br/pagination.php
Modules/Xot/lang/pt_BR/pagination.php

Modules/Xot/lang/pt_br/passwords.php
Modules/Xot/lang/pt_BR/passwords.php

Modules/Xot/lang/pt_br/roles.php
Modules/Xot/lang/pt_BR/roles.php

Modules/Xot/lang/pt_br/strings.php
Modules/Xot/lang/pt_BR/strings.php

Modules/Xot/lang/pt_br/validation.php
Modules/Xot/lang/pt_BR/validation.php

Modules/Xot/resources/views/components/dashboard/item.blade.php
Modules/Xot/resources/views/Components/dashboard/item.blade.php

Modules/Xot/resources/views/components/terminal.blade.php
Modules/Xot/resources/views/Components/terminal.blade.php
```

## Coppie a contenuto divergente (2)

Attenzione: qui i due file **non** sono uguali. Sotto lo stesso nome logico
convivono due verita' diverse, e non si sa quale sia quella viva. Vanno letti e
riconciliati a mano, mai cancellati a colpo d'occhio.

```
Modules/Xot/lang/en/labels/backend/takeaway/ingredient.php
Modules/Xot/lang/en/labels/backend/takeaway/Ingredient.php

Modules/Xot/tests/pest.php
Modules/Xot/tests/Pest.php
```

## Come si chiude

1. Per ogni coppia divergente, confrontare il contenuto e decidere quale versione
   sopravvive; portare in quella le parti utili dell'altra.
2. Rinominare in avanti verso la forma PSR-4 (`Fixtures/`, `Unit/`, `Feature/`,
   `DataObjects/`, `Config/`, `Providers/`).
3. Rimuovere la variante superflua.
4. Verificare che il conteggio del comando qui sopra sia sceso a zero.

Serve un test che impedisca la ricomparsa del difetto: senza, il conteggio torna a
crescere. Vedi il punto 4 del quadro generale.
