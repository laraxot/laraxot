# PHPStan Fixes 2026-07-16

## Summary
Fixed 39 PHPStan errors in `Modules/Notify/tests/` — all `function.notFound` and
`class.notFound` issues caused by missing imports and one `method.protected`.

## Errors Fixed

| Pattern | Count | Fix |
|---------|-------|-----|
| `Pest\Laravel\get` unused import | 7 | Removed unused `use function Pest\Laravel\get` |
| `Pest\Laravel\actingAs` not found | 1 | Replaced with `$this->actingAs()` |
| `Mockery` class not found | 1 | Added `use Mockery;` |
| `Exception` class not found | 1 | Added `use Exception;` |
| `ReflectionClass` not found | 3 | Added `use ReflectionClass;` |
| `ReflectionNamedType` not found | 3 | Added `use ReflectionNamedType;` |
| `TestCase::once()` protected | 1 | Replaced PHPUnit mock with `Mockery::mock()` + `shouldReceive()->once()` |

## Key Pattern: PHPUnit Protected Method Visibility in Pest

`$this->once()` (and `$this->expectsOnce()`) are `protected` methods on
`PHPUnit\Framework\TestCase`. In Pest closures, PHPStan may flag these as
`method.protected` even though the runtime context is the TestCase subclass.

**Fix**: Use `Mockery::mock()` and `shouldReceive()->once()` instead:
```php
// Before
$mock = $this->createUnitMock(MyClass::class);
$mock->expects($this->once())->method('handle')->willReturn($result);

// After
$mock = Mockery::mock(MyClass::class);
$mock->shouldReceive('handle')->once()->andReturn($result);
```

## Files Modified
- `tests/Feature/EmailTemplatesTest.php`
- `tests/Feature/JsonComponentTest.php`
- `tests/Feature/SpatieTranslatablePluginTest.php`
- `tests/Unit/Actions/NotificationManagerTest.php`
- `tests/Unit/Actions/SMS/SendAgiletelecomSMSActionTest.php`
- `tests/Unit/Actions/SMS/SendAgiletelecomSMSv1ActionTest.php`
- `tests/Unit/Actions/SMS/SendAgiletelecomSMSv2ActionTest.php`
- `tests/Unit/Actions/SendNotificationFlowTest.php`
- `tests/Unit/Models/MailTemplateLogTest.php`
- `tests/Unit/Models/MailTemplateTest.php`
- `tests/Unit/Models/NotificationTest.php`
- `tests/Unit/Models/NotifyThemeTest.php`
- `tests/Unit/Models/NotifyThemeableTest.php`
