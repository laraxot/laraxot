---
title: "State Transitions Guide"
type: concept
tags: [state, transitions]
created: 2026-07-14
updated: 2026-07-14
qmd: "state-transitions state transitions guide"
issues: ["https://github.com/provtv/base_ptv_fila5/issues/124"]
discussions: ["https://github.com/provtv/base_ptv_fila5/discussions/1"]
related:
  - "./00-index-1.md"
  - "./00-index.md"
  - "./04-datas.md"
  - "./advanced-form-components-1.md"
  - "./advanced-form-components.md"
  - "./agent-confidence-discipline.md"
  - "./agent-confidence-protocol.md"
  - "./agent-edit-discipline.md"
---

# State Transitions Guide

## Overview
This document outlines the standards and patterns for implementing state transitions in the application.

## Transition Class Structure

### Required Structure
```php
class ExampleTransition extends Transition
{
    public function __construct(
        public Model $model,
        public ?string $message = ''
    ) {}

    public function handle(): Model
    {
        // Transition logic here
        return $this->model;
    }
}
```

### Key Points
- Always extend `Spatie\ModelStates\Transition`
- Constructor must accept the model as first parameter
- Optional message parameter with empty string as default
- `handle()` must return the updated model

## Implementation Notes

### Required Parameters
1. `$model`: The model instance being transitioned
2. `$message`: Optional message for the transition (default: empty string)

### File Naming
- Use `PascalCase` for transition class names
- Suffix with `Transition` (e.g., `ActiveToSuspendedTransition`)
- Place in `app/States/{ModelName}/Transitions/`

### Best Practices
- Keep transition logic simple and focused
- Use type hints for all parameters
- Document complex transitions with PHPDoc blocks
- Always provide default values for optional parameters

## Related Documentation
- [State Management](./state-management.md)
- [SelectStateColumn Documentation](./select-state-column.md)
# State Transitions Guide

## Overview
This document outlines the standards and patterns for implementing state transitions in the application.

## Transition Class Structure

### Required Structure
```php
class ExampleTransition extends Transition
{
    public function __construct(
        public Model $model,
        public ?string $message = ''
    ) {}

    public function handle(): Model
    {
        // Transition logic here
        return $this->model;
    }
}
```

### Key Points
- Always extend `Spatie\ModelStates\Transition`
- Constructor must accept the model as first parameter
- Optional message parameter with empty string as default
- `handle()` must return the updated model

## Implementation Notes

### Required Parameters
1. `$model`: The model instance being transitioned
2. `$message`: Optional message for the transition (default: empty string)

### File Naming
- Use `PascalCase` for transition class names
- Suffix with `Transition` (e.g., `ActiveToSuspendedTransition`)
- Place in `app/States/{ModelName}/Transitions/`

### Best Practices
- Keep transition logic simple and focused
- Use type hints for all parameters
- Document complex transitions with PHPDoc blocks
- Always provide default values for optional parameters

## Related Documentation
- [State Management](./state-management.md)
- [SelectStateColumn Documentation](./select-state-column.md)
