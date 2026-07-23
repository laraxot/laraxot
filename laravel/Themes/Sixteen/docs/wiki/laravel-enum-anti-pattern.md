# Laravel Enum Anti-Pattern: Don't Wrap The Wrapper

> **If you cast to Enum, the Enum IS the accessor.**  
> **Never create accessors that wrap Enum methods.**

---

## The Crime (35 Lines → 0 Lines)

### Before (CACCA PUZZOLENTE)
```php
class Ticket extends Model
{
    protected function casts(): array
    {
        return ['type' => TicketTypeEnum::class];
    }
    
    // WHY?! The cast already provides this!
    protected function typeLabel(): Attribute
    {
        return Attribute::make(
            get: function (): string {
                $type = $this->getRawOriginal('type');
                return $this->resolveTypeLabel($type);
            },
        );
    }
    
    private function resolveTypeLabel(mixed $type): string  // DEAD CODE
    {
        if ($type instanceof TicketTypeEnum) {
            return $type->getLabel();
        }
        if (! \is_string($type) || $type === '') {
            return '';
        }
        return TicketTypeEnum::tryFrom($type)?->getLabel() ?? $type;
    }
}
```

### After (PURA EFFICIENZA)
```php
class Ticket extends Model
{
    protected function casts(): array
    {
        return ['type' => TicketTypeEnum::class];  // ← TUTTO QUI
    }
}

// Usage anywhere:
$ticket->type->getLabel();  // Native, 0 overhead, crystal clear
```

---

## Why It's "Cacca Puzzolente"

| Problem | Impact |
|---------|--------|
| **Double wrapping** | Cast → Accessor → Enum = 3 layers for 1 value |
| **Code bloat** | 35 lines vs 0 lines |
| **Maintenance burden** | Maintain 2 code paths instead of 0 |
| **Confusion** | Devs wonder "why not just use the enum?" |
| **Performance** | Accessor overhead for zero benefit |

---

## The Golden Rule

```
Cast to Enum → Use Enum directly  
        ↓
NO ACCESSORS NEEDED
```

### Blade Examples

```blade
{{-- WRONG (indirection) --}}
{{ $ticket->type_label }}

{{-- RIGHT (clear) --}}
{{ $ticket->type->getLabel() }}

{{-- Also available natively --}}
{{ $ticket->type->getColor() }}
{{ $ticket->type->value }}
{{ $ticket->type->is(TicketTypeEnum::ROAD_DAMAGE) }}
```

---

## Detection

```bash
# Find this anti-pattern
grep -rn "protected function.*Label.*Attribute" Modules/ --include="*.php" -B 5 | \
  grep -B 5 "Enum::class"

# Result should be: NOTHING
```

---

## References

- **Canonical Rule:** `docs/wiki/rules/laravel-enum-cast-vs-accessor.md`
- **Module Rule:** `laravel/Modules/Fixcity/docs/rules/no-enum-accessor-wrapper.md`
- **Applied To:** `laravel/Modules/Fixcity/app/Models/Ticket.php` (lines 178-206 DELETED)

---

## Lesson

> **Don't wrap the wrapper.**  
> **Don't abstract the abstraction.**  
> **Just use the fucking enum.**

---

*Documented: May 27, 2026*  
*Theme: Sixteen*  
*Principle: KISS (Keep It Simple, Stupid)*
