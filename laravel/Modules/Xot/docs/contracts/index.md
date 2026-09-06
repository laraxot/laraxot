# Contracts — Catalogo Xot

Interfacce canoniche del modulo `Xot`. SSoT per type-hint cross-module.

## Identity

| Contract | Path | Implementato da |
|---|---|---|
| `UserContract` | `Modules\Xot\Contracts\UserContract` | `Modules\User\Models\BaseUser` |
| `ProfileContract` | `Modules\Xot\Contracts\ProfileContract` | `Modules\User\Models\BaseProfile` |

> `Modules\User\Contracts\UserContract` esiste come alias legacy di `Xot\Contracts\UserContract`.
> Usare sempre `Xot\Contracts\*` nei nuovi file.

## Pattern

| Contract | Doc |
|---|---|
| `ModelContract` | [model-contract.md](model-contract.md) |
| `ModelWithUserContract` | [model-with-user-contract.md](model-with-user-contract.md) |
| `ModelWithPosContract` | [model-with-pos-contract.md](model-with-pos-contract.md) |
| `ModelWithStatusContract` | [model-with-status-contract.md](model-with-status-contract.md) |
| `ModelWithAuthorContract` | [model-with-author-contract.md](model-with-author-contract.md) |
| `ModelContactContract` | [model-contact-contract.md](model-contact-contract.md) |
| `ModelInputContract` | [model-input-contract.md](model-input-contract.md) |
| `HasRecursiveRelationshipsContract` | [has-recursive-relationships-contract.md](has-recursive-relationships-contract.md) |
| `ErrorFormatterContract` | [error-formatter-contract.md](error-formatter-contract.md) |

## Regola type-hint (Laraxot 2026-09-04)

```php
// ✅ Corretto
use Modules\Xot\Contracts\UserContract;
use Modules\Xot\Contracts\ProfileContract;
use Modules\Xot\Datas\XotData;

/** @var class-string<UserContract> $userClass */
$userClass = XotData::make()->getUserClass();

// ❌ Vietato in app/
use Modules\User\Models\User;
$userClass = User::class;
```

Vedi: [User docs: contract adoption](../../../User/docs/wiki/concepts/user-profile-contract-adoption.md)
