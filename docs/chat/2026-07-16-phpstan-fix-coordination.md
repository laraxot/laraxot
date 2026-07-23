# PHPStan Fix Coordination - 2026-07-16

## Fixed by this agent
- `laravel/Modules/Notify/tests/Unit/Actions/NotificationManagerTest.php` — Added `public NotificationManager $notificationManager;` property to `Modules\Notify\Tests\TestCase`. PHPStan now passes.

## Locked by other agents — needs fix
- `laravel/Modules/Notify/tests/Unit/Actions/SendNotificationFlowTest.php` — PHPStan error at line 68: `$action->shouldReceive('handle')->once()` returns `ExpectationInterface|HigherOrderMessage`, `once()` not found on union. **Fix:** replace with `mockExpectation($action, 'handle')->once()->andReturn($notification)`.
- `laravel/Modules/UI/tests/Unit/Models/ComponentModelTest.php` — PHPStan error at lines 64-72: `$casts['is_active']` offset access on mixed. **Fix:** add `/** @var array<string, mixed> $casts */` before `Assert::assertSame('boolean', $casts['is_active']);`.
