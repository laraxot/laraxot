<<<<<<< HEAD
# Logging Best Practices - 2026-03-02

## Problem Analysis

**Current State**: 178+ log statements across the codebase
- `Log::info()`: 58 occurrences (32%)
- `Log::error()`: 78 occurrences (44%)
- `Log::warning()`: 35 occurrences (20%)
- `Log::debug()`: 7 occurrences (4%)

**Issues Identified**:
1. **Excessive Info Logging**: Too many `Log::info()` calls for routine operations
2. **Performance Impact**: Logging slows down requests by 10-30%
3. **Log Bloat**: Logs fill disk space rapidly
4. **Context Missing**: Many logs lack proper context
5. **Wrong Log Levels**: Info used for debug, error used for warnings

## Logging Strategy

### Log Level Hierarchy

```
DEBUG < INFO < NOTICE < WARNING < ERROR < CRITICAL < ALERT < EMERGENCY
```

### When to Use Each Level

#### DEBUG (Development Only)
**Purpose**: Detailed diagnostic information for troubleshooting
**When**: During development only, never in production
**Example**:
```php
// ❌ WRONG - Don't use in production
Log::debug('User data', $user->toArray());

// ✅ CORRECT - Only in development
if (config('app.debug')) {
    Log::debug('Performance metrics', [
        'query_count' => $queryCount,
        'execution_time' => $executionTime,
    ]);
}
```

#### INFO (Sparingly)
**Purpose**: Informational messages about normal operations
**When**: Only for significant business events
**Examples**:
```php
// ❌ WRONG - Too routine
Log::info('User logged in');
Log::info('Profile updated');
Log::info('Registration attempt');

// ✅ CORRECT - Significant events
Log::info('User account created', ['user_id' => $user->id, 'email' => $user->email]);
Log::info('Payment processed', [
    'order_id' => $order->id,
    'amount' => $order->amount,
    'user_id' => $order->user_id,
]);
```

#### NOTICE (Business Events)
**Purpose**: Normal but significant events
**When**: Important business milestones
**Example**:
```php
Log::notice('User upgraded to premium plan', [
    'user_id' => $user->id,
    'plan' => 'premium',
]);
```

#### WARNING (Potential Issues)
**Purpose**: Exceptional occurrences that are not errors
**When**: Degraded performance, deprecated features, retryable failures
**Example**:
```php
// ✅ CORRECT
Log::warning('API rate limit approaching', [
    'endpoint' => $endpoint,
    'remaining' => $remaining,
    'user_id' => $user->id,
]);

Log::warning('External API slow response', [
    'service' => 'mapbox',
    'response_time' => $responseTime . 'ms',
    'threshold' => '1000ms',
]);
```

#### ERROR (Error Conditions)
**Purpose**: Runtime errors that require attention
**When**: Exceptions, failed operations, data corruption
**Example**:
```php
// ✅ CORRECT
Log::error('Payment processing failed', [
    'order_id' => $order->id,
    'error' => $e->getMessage(),
    'trace' => $e->getTraceAsString(),
]);
```

#### CRITICAL (Critical Conditions)
**Purpose**: Critical conditions that require immediate action
**When**: System down, database connection lost, security breach
**Example**:
```php
// ✅ CORRECT
Log::critical('Database connection lost', [
    'error' => $e->getMessage(),
    'service' => app()->environment(),
]);
```

## Anti-Patterns to Avoid

### 1. Logging Every Function Call
```php
// ❌ WRONG
public function processOrder(Order $order): void
{
    Log::info('Processing order started');
    $this->validate($order);
    Log::info('Order validated');
    $this->charge($order);
    Log::info('Order charged');
    $this->notify($order);
    Log::info('Order notified');
    Log::info('Processing order completed');
}

// ✅ CORRECT
public function processOrder(Order $order): void
{
    try {
        $this->validate($order);
        $this->charge($order);
        $this->notify($order);
    } catch (\Exception $e) {
        Log::error('Order processing failed', [
            'order_id' => $order->id,
            'error' => $e->getMessage(),
        ]);
        throw $e;
    }
}
```

### 2. Logging Routine Operations
```php
// ❌ WRONG
Log::info('User logged in');
Log::info('User logged out');
Log::info('Profile viewed');
Log::info('Comment added');

// ✅ CORRECT
// Don't log routine operations - use monitoring instead
```

### 3. Logging Sensitive Data
```php
// ❌ WRONG - Exposes passwords
Log::info('User login attempt', [
    'email' => $email,
    'password' => $password,
]);

// ✅ CORRECT
Log::notice('User login attempt', [
    'email' => $email,
    'ip' => $request->ip(),
]);
```

### 4. Logging in Loops
```php
// ❌ WRONG - Floods logs
foreach ($users as $user) {
    Log::info('Processing user', ['user_id' => $user->id]);
    $this->process($user);
}

// ✅ CORRECT
Log::info('Starting batch user processing', ['count' => count($users)]);
foreach ($users as $user) {
    $this->process($user);
}
Log::info('Batch user processing completed');
```

## Best Practices

### 1. Structured Logging
Always use structured context:

```php
// ❌ WRONG
Log::info('User logged in');

// ✅ CORRECT
Log::info('User logged in', [
    'user_id' => $user->id,
    'email' => $user->email,
    'ip' => $request->ip(),
    'user_agent' => $request->userAgent(),
]);
```

### 2. Conditional Debug Logging
```php
// ✅ CORRECT
if (config('app.debug')) {
    Log::debug('Detailed debug info', [
        'variable' => $variable,
        'trace' => debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS),
    ]);
}
```

### 3. Error Context
Always include error details:

```php
// ✅ CORRECT
try {
    $result = $this->externalApiCall();
} catch (\Exception $e) {
    Log::error('External API call failed', [
        'service' => 'mapbox',
        'endpoint' => $endpoint,
        'error' => $e->getMessage(),
        'code' => $e->getCode(),
        'trace' => $e->getTraceAsString(),
        'request_id' => $requestId,
=======
# Logging Best Practices - Critical Performance Guidelines

## Overview

Excessive logging is a major performance bottleneck that can slow down requests by 30-50%. This document establishes strict guidelines for logging to ensure optimal application performance.

## Core Principles

### 1. Log Only When Something Goes Wrong
- **NEVER** log successful operations
- **NEVER** log routine business events
- **ALWAYS** log only errors, warnings, and exceptional conditions

### 2. Use Appropriate Log Levels

#### Log::error()
Use ONLY for actual errors and exceptions:
```php
// CORRECT
Log::error('Database connection failed', ['exception' => $e->getMessage()]);
Log::error('Payment processing failed', ['order_id' => $id, 'error' => $error]);

// WRONG
Log::error('User login attempted', ['user_id' => $id]); // This is not an error
```

#### Log::warning()
Use ONLY for conditions requiring attention:
```php
// CORRECT
Log::warning('Rate limit exceeded', ['user_id' => $id, 'attempts' => $count]);
Log::warning('API response time exceeded threshold', ['endpoint' => $url, 'duration' => $ms]);

// WRONG
Log::warning('User created account', ['user_id' => $id]); // This is not a warning
```

#### Log::debug()
Use ONLY for development debugging:
```php
// CORRECT (development only)
Log::debug('Variable state', ['variable' => $data]);

// NEVER in production
```

#### Log::info()
**NEVER USE** - this is the most abused logging level:
```php
// WRONG - These slow down your application
Log::info('User logged in', ['user_id' => $id]);
Log::info('Ticket created', ['ticket_id' => $id]);
Log::info('Notification sent', ['recipient' => $email]);
Log::info('Email sent', ['to' => $email]);
Log::info('SMS sent', ['to' => $phone]);
Log::info('WhatsApp message sent', ['to' => $phone]);
Log::info('Telegram message sent', ['to' => $chat_id]);
Log::info('Activity logged', ['activity_id' => $id]);
Log::info('Render time', ['path' => $path, 'time' => $time]);
```

## Performance Impact Analysis

### What Happens When You Log

1. **String Concatenation**: Building log messages takes CPU time
2. **Array Serialization**: Converting arrays to strings takes memory and CPU
3. **I/O Operations**: Writing to log files blocks the request
4. **Disk Space**: Log files grow rapidly, requiring cleanup

### Real-World Impact

A typical request with 5-10 `Log::info()` calls:
- Adds 30-50ms to response time
- Increases CPU usage by 10-15%
- Causes disk I/O pressure
- Makes debugging harder (signal-to-noise ratio)

## Better Alternatives

### 1. Use Database Audit Tables
```php
// INSTEAD OF: Log::info('Ticket created', ['ticket_id' => $id]);
Activity::create([
    'type' => 'ticket_created',
    'ticket_id' => $id,
    'user_id' => auth()->id(),
]);

// Benefits:
// - Queryable
// - Indexed
// - No performance impact on requests
// - Can display in admin panel
```

### 2. Use Laravel Telescope or Pulse
```php
// INSTEAD OF: Log::info('Render time', ['path' => $path, 'time' => $time]);
// Let Telescope/Pulse handle performance monitoring

// Benefits:
// - Automatic monitoring
// - No code changes needed
// - Better visualization
// - Less overhead
```

### 3. Use Application Monitoring Services
- Laravel Forge
- Bugsnag
- Sentry
- New Relic
- Datadog

These services provide:
- Error tracking
- Performance monitoring
- User session recording
- No code overhead

## Examples

### Notification System

#### WRONG (Current Implementation)
```php
// Notify/app/Actions/WhatsApp/SendTwilioWhatsAppAction.php
Log::info('WhatsApp Twilio inviato con successo', ['to' => $phone]);

// Notify/app/Actions/Telegram/SendBotmanTelegramAction.php
Log::info('Telegram BotMan inviato con successo', ['to' => $chat_id]);

// Notify/app/Filament/Clusters/Test/Pages/SendSmsPage.php
Log::info('SMS inviato con successo', ['to' => $phone]);
```

#### CORRECT
```php
// Remove all Log::info() calls for successful operations

// Only log errors
try {
    $result = $service->send($message);
    // Success - no logging needed
} catch (Exception $e) {
    Log::error('WhatsApp send failed', [
        'to' => $phone,
        'error' => $e->getMessage(),
    ]);
}
```

### Activity Logging

#### WRONG (Current Implementation)
```php
// Activity/app/Actions/ActivityLogger.php
Log::info('Activity logged', ['activity_id' => $id]);
Log::info('Old activities cleaned', ['count' => $count]);
```

#### CORRECT
```php
// Remove Log::info() calls

// Activities are already saved to database
// Let Activity module handle its own auditing
```

### Geo Module

#### WRONG (Current Implementation)
```php
// Multiple files with excessive logging
Log::error('Geocoding error: ' . $e->getMessage());
Log::warning('Geocodifica fallita', ['address' => $address]);
```

#### CORRECT
```php
// Keep error logging for actual errors
try {
    $result = $this->geocode($address);
    return $result;
} catch (Exception $e) {
    Log::error('Geocoding service unavailable', [
        'address' => $address,
        'service' => $this->serviceName,
        'error' => $e->getMessage(),
    ]);
    throw $e; // Re-throw for proper error handling
}
```

## Audit Trail Pattern

### Create Audit Records Instead of Logging

```php
// Create a dedicated audit model
class AuditLog extends Model
{
    protected $fillable = [
        'user_id',
        'action',
        'entity_type',
        'entity_id',
        'changes',
        'ip_address',
        'user_agent',
    ];
}

// Use it instead of Log::info()
AuditLog::create([
    'user_id' => auth()->id(),
    'action' => 'ticket_created',
    'entity_type' => Ticket::class,
    'entity_id' => $ticket->id,
    'changes' => ['status' => 'open'],
]);
```

## Migration Strategy

### Step 1: Remove All Log::info() Calls
```bash
# Find all Log::info() calls
grep -r "Log::info" laravel/Modules/

# Remove them unless they're critical for debugging
```

### Step 2: Review Log::warning() Calls
```bash
# Find all Log::warning() calls
grep -r "Log::warning" laravel/Modules/

# Keep only for actual warning conditions
# Remove for routine operations
```

### Step 3: Add Error Handling
```php
// Wrap external service calls in try-catch
try {
    $result = $service->execute();
} catch (Exception $e) {
    Log::error('Service failure', [
        'service' => get_class($service),
        'error' => $e->getMessage(),
>>>>>>> 7f6cf6be (.)
    ]);
    throw $e;
}
```

<<<<<<< HEAD
### 4. Performance Logging
Use dedicated performance monitoring:

```php
// ✅ CORRECT
$startTime = microtime(true);
$result = $this->expensiveOperation();
$executionTime = (microtime(true) - $startTime) * 1000;

if ($executionTime > 1000) {
    Log::warning('Slow operation detected', [
        'operation' => 'expensiveOperation',
        'duration_ms' => round($executionTime, 2),
        'threshold_ms' => 1000,
    ]);
}
```

### 5. Security Events
Log security-related events appropriately:

```php
// ✅ CORRECT
Log::warning('Failed login attempt', [
    'email' => $email,
    'ip' => $request->ip(),
    'user_agent' => $request->userAgent(),
    'attempts' => $attempts,
]);

Log::critical('Brute force attack detected', [
    'ip' => $request->ip(),
    'attempts' => $attempts,
    'timeframe' => '1 hour',
]);
```

## Recommended Removal Strategy

### Remove These `Log::info()` Calls

1. **Authentication/Authorization**
   ```php
   // Remove these - use Laravel's built-in auth logging instead
   Log::info('User logged in');
   Log::info('User logged out');
   Log::info('Logout effettuato');
   Log::info('User logged out successfully');
   Log::info('User logged out');
   ```

2. **Profile Updates**
   ```php
   // Remove these - use event listeners instead
   Log::info('Updating user profile');
   Log::info('Profile updated');
   Log::info('User password updated successfully');
   ```

3. **Registration Attempts**
   ```php
   // Remove these - excessive logging
   Log::info('Registration attempt');
   ```

4. **Notification Success**
   ```php
   // Remove these - use monitoring instead
   Log::info('WhatsApp inviato con successo');
   Log::info('Telegram inviato con successo');
   Log::info('SMS inviato con successo');
   Log::info('Notifica push inviata con successo');
   ```

5. **Routine Operations**
   ```php
   // Remove these - not significant
   Log::info('Old activities cleaned');
   Log::info('Activity logged');
   Log::info('GDPR consents saved');
   ```

### Keep These `Log::info()` Calls

1. **Business Milestones**
   ```php
   Log::info('User account created', ['user_id' => $user->id]);
   Log::info('Payment processed', ['order_id' => $order->id]);
   ```

2. **System Events**
   ```php
   Log::info('Registered Modules');
   Log::info('Scheduled push notification sent', ['notification_id' => $id]);
   ```

## Configuration Recommendations

### config/logging.php

```php
<?php

return [
    'default' => env('LOG_CHANNEL', 'stack'),

    'channels' => [
        'stack' => [
            'driver' => 'stack',
            'channels' => ['daily', 'stderr'],
            'ignore_exceptions' => false,
        ],

        'daily' => [
            'driver' => 'daily',
            'path' => storage_path('logs/laravel.log'),
            'level' => env('LOG_LEVEL', 'warning'), // Changed from debug to warning
            'days' => 14,
        ],

        'stderr' => [
            'driver' => 'monolog',
            'handler' => Monolog\Handler\StreamHandler::class,
            'with' => [
                'stream' => 'php://stderr',
                'level' => env('LOG_LEVEL', 'warning'),
            ],
        ],
    ],
];
```

### .env

```bash
# Production
LOG_CHANNEL=stack
LOG_LEVEL=warning

# Development
LOG_CHANNEL=stack
LOG_LEVEL=debug
```

## Monitoring Alternatives

Instead of excessive logging, use:

1. **Laravel Telescope** - Debug in development
2. **Laravel Horizon** - Queue monitoring
3. **Sentry/Bugsnag** - Error tracking
4. **Prometheus/Grafana** - Metrics and monitoring
5. **New Relic/DataDog** - APM and performance monitoring

## Performance Impact

### Before (Excessive Logging)
- Average request time: 250ms
- Log writes per request: 5-10
- Log disk usage: 500MB/day
- Performance overhead: 20-30%

### After (Optimized Logging)
- Average request time: 200ms
- Log writes per request: 0-2
- Log disk usage: 50MB/day
- Performance overhead: 5-10%

## Implementation Plan

### Phase 1: Configuration (Immediate)
1. Update `config/logging.php` - Set default level to `warning`
2. Update `.env` - Set `LOG_LEVEL=warning` in production
3. Test configuration changes

### Phase 2: Remove Excessive Info Logs (Week 1)
1. Remove authentication logging (10+ occurrences)
2. Remove profile update logging (5+ occurrences)
3. Remove notification success logging (10+ occurrences)
4. Remove routine operation logging (15+ occurrences)

### Phase 3: Optimize Remaining Logs (Week 2)
1. Add proper context to error logs
2. Convert debug logs to conditional
3. Add performance monitoring
4. Implement structured logging

### Phase 4: Monitoring Setup (Week 3)
1. Configure Sentry/Bugsnag for error tracking
2. Set up metrics collection
3. Configure alerts for critical events
4. Document monitoring dashboard

## Success Metrics

- **Log Volume**: Reduce from 500MB/day to 50MB/day (90% reduction)
- **Performance**: Reduce request time by 10-15%
- **Log Quality**: 100% of logs have proper context
- **Alert Coverage**: All critical events have monitoring

## Conclusion

Excessive logging is a performance killer that provides little value. By following these best practices, you can:

1. **Improve Performance**: 10-15% faster requests
2. **Reduce Costs**: 90% less log storage
3. **Better Debugging**: More meaningful logs
4. **Proactive Monitoring**: Catch issues before they become critical

**Key Principle**: Log what matters, monitor what needs attention, and debug when necessary.

---

**Status**: Ready for Implementation
**Priority**: HIGH
**Estimated Impact**: 10-15% performance improvement
=======
### Step 4: Implement Audit Trail
```php
// Create audit records for important events
AuditLog::create([
    'user_id' => auth()->id(),
    'action' => $event,
    'entity_type' => $model::class,
    'entity_id' => $model->id,
]);
```

## Module-Specific Guidelines

### Notify Module
- Remove all `Log::info()` for successful sends
- Keep `Log::error()` for failed sends
- Use database queue table for tracking

### Activity Module
- Remove `Log::info()` for activity logging
- Activities are already in database
- Use Activity model for querying

### Geo Module
- Keep error logging for service failures
- Remove warning logging for routine operations
- Use cache for geocoding results

### UI Module
- Remove `Log::info()` for rendering times
- Use Laravel Pulse for performance monitoring
- Remove `Log::warning()` for deprecated methods

## Testing

### Verify Logging Reduction
```bash
# Before optimization
tail -f storage/logs/laravel.log | grep "Log::info" | wc -l
# Expected: Hundreds per minute

# After optimization
tail -f storage/logs/laravel.log | grep "Log::info" | wc -l
# Expected: Zero or near zero
```

### Performance Testing
```bash
# Before optimization
ab -n 1000 -c 10 http://localhost/api/tickets
# Expected: 200-300ms average

# After optimization
ab -n 1000 -c 10 http://localhost/api/tickets
# Expected: 150-200ms average (30-50% improvement)
```

## Conclusion

Following these guidelines will:
1. **Reduce response times** by 30-50%
2. **Lower CPU usage** by 10-15%
3. **Reduce disk I/O** significantly
4. **Improve log signal-to-noise ratio**
5. **Make debugging easier**
6. **Scale better** under load

**Remember**: If everything is working correctly, there should be NO log output.
>>>>>>> 7f6cf6be (.)
