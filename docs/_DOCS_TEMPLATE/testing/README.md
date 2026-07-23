# Testing Documentation

## Testing Strategy
[Describe the overall testing approach]

## Test Organization
```
tests/
├── Feature/          # Integration tests
├── Unit/             # Unit tests
└── TestCase.php      # Base test case
```

## Running Tests

```bash
# All module tests
./vendor/bin/pest Modules/[ModuleName]

# Specific test file
./vendor/bin/pest Modules/[ModuleName]/tests/Feature/ExampleTest.php

# With coverage
./vendor/bin/pest Modules/[ModuleName] --coverage
```

## Writing Tests

### Example Unit Test
```php
test('example unit test', function () {
    // Arrange
    $value = 10;

    // Act
    $result = $value * 2;

    // Assert
    expect($result)->toBe(20);
});
```

### Example Feature Test
```php
test('example feature test', function () {
    // Test implementation
});
```

## Test Coverage Goals
- **Target**: 80%+
- **Critical Components**: 90%+

## See Also
- [Testing Strategy](./strategy.md)
- [Test Examples](./examples.md)
- [Mocking Guide](./mocking.md)
