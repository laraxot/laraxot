<<<<<<< HEAD
<<<<<<< HEAD
---
title: "Logging Best Practices - Performance & Quality"
module: notify
type: integration
tags: [integrations, modules, notify]
created: 2026-08-24
updated: 2026-08-24
---

# Logging Best Practices - Performance & Quality

## 🚫 CRITICAL RULE: NEVER USE Log::info()

### Why Log::info() is Harmful

**Performance Impact**:
- Each `Log::info()` call performs disk I/O
- Slows down requests by 30-50% when overused
- Blocks execution while writing to disk
- Increases memory usage for log buffering

**Storage Impact**:
- Log files grow uncontrollably
- Wastes disk space with useless information
- Requires constant log rotation and cleanup
- Increases backup costs and times

**Maintenance Impact**:
- Log files become unreadable with noise
- Makes debugging harder, not easier
- Requires filtering through thousands of useless entries
- Degrades log search performance

---

## ✅ When to Log

### Log::error() - Actual Errors
```php
// CORRECT
Log::error('Database connection failed', [
    'connection' => $connectionName,
    'error' => $e->getMessage(),
]);

// CORRECT
Log::error('Payment processing failed', [
    'order_id' => $order->id,
    'amount' => $order->amount,
    'error' => $e->getMessage(),
]);
```

### Log::warning() - Conditions Requiring Attention
```php
// CORRECT
Log::warning('Rate limit exceeded', [
    'user_id' => $user->id,
    'ip' => request()->ip(),
    'attempts' => $attempts,
]);

// CORRECT
Log::warning('API response slow', [
    'endpoint' => $endpoint,
    'duration_ms' => $duration,
]);
```

### Log::debug() - Development Only
```php
// CORRECT - Development only
Log::debug('User data', ['user' => $user->toArray()]);

// NEVER in production
if (config('app.debug')) {
    Log::debug('Debug info', [...]);
}
```

---

## ❌ When NOT to Log

### Routine Operations
```php
// WRONG - User logged in successfully
Log::info('User logged in', ['user_id' => $id]);

// WRONG - Ticket created
Log::info('Ticket created', ['ticket_id' => $id]);

// WRONG - Notification sent
Log::info('Notification sent', ['recipient' => $email]);

// WRONG - Request received
Log::info('Request received', ['url' => $url]);

// WRONG - User registered
Log::info('Registration attempt', ['email_hash' => $hash]);
```

### Successful Completions
```php
// WRONG - Action completed successfully
Log::info('Email sent successfully', [...]);

// WRONG - File uploaded
Log::info('File uploaded', ['filename' => $name]);

// WRONG - Cache refreshed
Log::info('Cache refreshed', ['key' => $key]);
```

---

## 📊 Better Alternatives

### For Audit Trails
```php
// Use database tables, not logs
activity()
    ->causedBy(auth()->user())
    ->performedOn($model)
    ->withProperties(['ip' => request()->ip()])
    ->log('Model updated');

// Or custom audit log
AuditLog::create([
    'user_id' => auth()->id(),
    'action' => 'create',
    'model_type' => get_class($model),
    'model_id' => $model->id,
    'ip_address' => request()->ip(),
]);
```

### For Monitoring
```php
// Use Laravel Telescope
use Laravel\Telescope\Telescope;
Telescope::record($event);

// Use Laravel Pulse
use Laravel\Pulse\Facades\Pulse;
Pulse::record(...);

// Use APM tools (New Relic, Datadog, etc.)
```

### For Metrics
```php
// Use metrics collectors
Metrics::increment('tickets.created');
Metrics::measure('api.response_time', $duration);

// Use Laravel Pulse metrics
Pulse::record('user registrations', $count);
```

---

## 🎯 Performance Optimization

### Remove Existing Log::info() Calls
```php
// BEFORE
public function register(array $data): User
{
    $user = User::create($data);
    Log::info('User registered', ['user_id' => $user->id]); // ❌ SLOW
    return $user;
}

// AFTER
public function register(array $data): User
{
    $user = User::create($data);
    activity()->causedBy($user)->log('User registered'); // ✅ FAST
    return $user;
}
```

### Batch Operations
```php
// BEFORE
foreach ($items as $item) {
    $item->process();
    Log::info('Item processed', ['item_id' => $item->id]); // ❌ SLOW
}

// AFTER
$count = 0;
foreach ($items as $item) {
    $item->process();
    $count++;
}
Log::info('Batch completed', ['count' => $count]); // ✅ ONE LOG
=======
=======
>>>>>>> a988596b (first)
# Logging Best Practices - Performance Critical

## Executive Summary

**CRITICAL ISSUE:** Excessive `Log::info()` calls cause **30-50% performance degradation**.

**Action Required:** Remove all routine logging. Use only for errors/warnings.

---

## The Problem

### Performance Impact
```
Without excessive logging:    100ms per request
With Log::info() in loops:    3000-5000ms per request  (30-50x slower!)
With proper logging:          100-150ms per request
```

### Why It's Slow
1. **Disk I/O:** Every log write hits disk
2. **File Locking:** Multiple processes contend for log file
3. **Memory Buffering:** Log buffers accumulate in memory
4. **Serialization:** Context arrays must be serialized
5. **Noise:** Makes actual errors hard to find

---

## Rules: What NOT To Do

### ❌ NEVER Log Routine Operations
```php
// ❌ WRONG - Routine success
Log::info('User logged in', ['user_id' => $id]);
Log::info('Ticket created', ['ticket_id' => $id]);
Log::info('Email sent', ['recipient' => $email]);
Log::info('Payment processed', ['amount' => $amount]);

// ❌ WRONG - Loop iterations
foreach ($items as $item) {
    Log::info('Processing item', ['item_id' => $item->id]);
}

// ❌ WRONG - Successful completions
Log::info('Task completed successfully');
Log::info('Migration finished');
Log::info('Cache cleared');
<<<<<<< HEAD
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
```

---

<<<<<<< HEAD
<<<<<<< HEAD
## 📈 Performance Metrics

### Log::info() Impact
- **Single call**: ~1-2ms latency
- **100 calls per request**: 100-200ms latency
- **1000 calls per request**: 1-2 second latency
- **Disk I/O**: Blocks execution while writing

### Recommended Limits
- **Errors**: As needed (unlimited)
- **Warnings**: < 10 per request
- **Debug**: 0 in production
- **Info**: 0 (never use)

---

## 🔍 Code Review Checklist

- [ ] No `Log::info()` calls in production code
- [ ] All `Log::error()` calls are for actual errors
- [ ] All `Log::warning()` calls are for conditions needing attention
- [ ] No logging in loops (batch at end instead)
- [ ] Audit trails use database tables, not logs
- [ ] Monitoring uses Telescope/Pulse, not logs
- [ ] Metrics use dedicated collectors, not logs

---

## 📚 Related Documentation

- `.windsurfrules` - Complete architectural rules
- `AGENTS.md` - Project guidelines
- Module-specific docs for implementation examples
=======
=======
>>>>>>> a988596b (first)
## Rules: What TO Do

### ✅ CORRECT - Log Only Errors
```php
// ✅ CORRECT - Actual errors
Log::error('Login failed', ['user_id' => $id, 'reason' => $error]);
Log::error('Payment failed', ['amount' => $amount, 'error' => $e->getMessage()]);

// ✅ CORRECT - Warnings
Log::warning('Rate limit exceeded', ['user_id' => $id]);
Log::warning('Slow query detected', ['duration' => $ms]);

// ✅ CORRECT - Critical issues
Log::critical('Database connection lost');
Log::critical('Authentication service unavailable');

// ✅ CORRECT - Debug only in development
if (config('app.debug')) {
    Log::debug('Processing item', ['item_id' => $item->id]);
}
```

---

## Monitoring Alternatives

Instead of logging routine operations, use proper monitoring tools:

### 1. Laravel Pulse (Built-in)
```php
// Real-time application monitoring
// Tracks: requests, jobs, exceptions, cache, database
// No performance impact
```

### 2. Laravel Telescope (Development)
```php
// Request/query inspection
// Excellent for debugging
// Disable in production
```

### 3. Sentry (Production)
```php
// Error tracking and performance monitoring
// Captures exceptions automatically
// Performance monitoring included
```

### 4. New Relic
```php
// Application performance monitoring
// Infrastructure monitoring
// Real-time dashboards
```

### 5. DataDog
```php
// Infrastructure and application monitoring
// Log aggregation without performance hit
// Real-time alerting
```

---

## Implementation Checklist

### Phase 1: Audit (This Week)
- [ ] Search for all `Log::info()` calls
- [ ] Identify which are routine operations
- [ ] Mark for removal

### Phase 2: Remove (This Week)
- [ ] Remove all routine `Log::info()` calls
- [ ] Keep only `Log::error()` and `Log::warning()`
- [ ] Add `Log::debug()` where needed (with config check)

### Phase 3: Monitor (Next Week)
- [ ] Set up Laravel Pulse
- [ ] Configure Sentry for production
- [ ] Verify performance improvement

### Phase 4: Verify (Next Week)
- [ ] Run load tests
- [ ] Measure request times
- [ ] Compare before/after metrics

---

## Files to Audit

### Service Classes (High Priority)
- `Modules/Fixcity/app/Services/NotificationService.php`
- `Modules/Fixcity/app/Services/TicketService.php`
- `Modules/Fixcity/app/Services/WorkflowService.php`
- All other Service classes in all modules

### Actions (Medium Priority)
- `Modules/Fixcity/app/Actions/*.php`
- All Spatie QueueableActions

### Controllers (Medium Priority)
- All controller files
- Check for routine logging

### Seeders (Low Priority)
- Database seeders
- Migration files

---

## Search Commands

Find all Log::info calls:
```bash
grep -r "Log::info" laravel/Modules/ --include="*.php"
```

Find all Log calls:
```bash
grep -r "Log::" laravel/Modules/ --include="*.php"
```

---

## Performance Verification

### Before Cleanup
```bash
# Measure request time
time curl http://localhost:8000/api/endpoint
```

### After Cleanup
```bash
# Should be significantly faster
time curl http://localhost:8000/api/endpoint
```

### Expected Results
- **Request time reduction:** 20-50%
- **Log file size reduction:** 80-90%
- **Memory usage reduction:** 10-20%
- **Disk I/O reduction:** 70-80%

---

## Related Documentation

- [LARAXOT FRAMEWORK RULES](./laraxot-framework.md) - Logging best practices section
- [Performance Optimization Guide](./performance-optimization.md)
- [Monitoring Setup Guide](./monitoring-setup.md)

---

## Status

**Last Updated:** 2026-03-02  
**Priority:** CRITICAL  
**Status:** Active & Enforced  
**Performance Impact:** 30-50% degradation with excessive logging
<<<<<<< HEAD
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
