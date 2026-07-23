# LLM Wiki Second Brain Update - 2026-06-07

## ✅ PHPStan Fix Resolution: Unsafe usage of new static()

### Problematic Pattern Identified
In `Data` classes (Spatie Laravel Data) or other DTOs, using `new static()` in a factory method like `make()` or `from()` causes a PHPStan error if the class is not `final`.

```php
// ❌ Before (phpstan error: Unsafe usage of new static())
class SmsData extends Data
{
    public static function from(array $data): static
    {
        return new static($data);
    }
}
```

### Rule Learned
Make the class `final` if it's not intended to be extended. This makes `static` and `self` equivalent and safe for PHPStan. If the class MUST be extendable, use the `@phpstan-consistent-constructor` annotation on the class.

```php
// ✅ After (fixed)
final class SmsData extends Data
{
    public static function from(array $data): static
    {
        return new static($data); // or new self($data)
    }
}
```

### Files Modified
- `laravel/Modules/Notify/app/Datas/SmsData.php`
- `laravel/Modules/Xot/app/Datas/ArticleData.php`
- `laravel/Modules/Xot/app/Datas/AuthData.php`
- `laravel/Modules/Xot/app/Datas/CookieData.php`
- `laravel/Modules/Xot/app/Datas/FilemanagerData.php`
- `laravel/Modules/Xot/app/Datas/MailData.php`
- `laravel/Modules/Xot/app/Datas/NotificationData.php`
- `laravel/Modules/Xot/app/Datas/OptionData.php`
- `laravel/Modules/Xot/app/Datas/PwaData.php`
- `laravel/Modules/Xot/app/Datas/RouteData.php`
- `laravel/Modules/Xot/app/Datas/SearchEngineData.php`
- `laravel/Modules/Xot/app/Datas/SubscriptionData.php`

## PHPStan Verification
Run analysis from `laravel` directory:
```bash
./vendor/bin/phpstan analyse Modules --memory-limit=2G --no-progress
```
Currently achieving `[OK] No errors` at level `max`.

---
🕐 Ultimo aggiornamento: 2026-06-07 11:45 UTC
