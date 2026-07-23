# Policy rendering boundary

## Scopo

Allineare il tema Sixteen alla governance policy dei moduli senza duplicare logica autorizzativa nel layer view.

## Regola tema

Il tema non decide quale base policy usare (`UserBasePolicy` vs `XotBasePolicy`).
Il tema consuma solo capability gia' risolte dal backend (gate/policy) e renderizza UI coerente.

## Implicazioni pratiche

- niente logica autorizzativa di dominio hardcoded nelle Blade del tema
- niente assunzioni implicite su ruoli senza gate/check esplicito lato backend
- la differenza tra `UserBasePolicy` e `XotBasePolicy` resta nel dominio modulo, non nel tema

## Quando tocca al tema

- mostrare/nascondere elementi in base a esito autorizzativo gia' disponibile
- mantenere coerenza visuale per stati autorizzati/non autorizzati

## Quando NON tocca al tema

- introdurre nuove regole ACL
- accoppiare componenti UI a classi policy specifiche
- replicare condizioni backend nel frontend

## Riferimenti

- [xot policy base strategy](../../../Modules/Xot/docs/wiki/concepts/policy-base-strategy.md)
- [xot policy module matrix](../../../Modules/Xot/docs/wiki/concepts/policy-module-matrix.md)
- [user policy inheritance boundary](../../../Modules/User/docs/wiki/concepts/policy-inheritance-boundary.md)
