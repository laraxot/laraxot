---
title: "Xot Module Testing"
type: guide
tags: [xot, testing, pest]
created: 2026-07-28
---

# Xot Module — Testing

## BaseModel Tests

```php
test('base model uses uuid primary key', function () {
    $model = MyModel::factory()->create();
    expect($model->id)->not()->toBeNull();
    expect(Str::isUuid($model->id))->toBeTrue();
});

test('base model supports soft delete', function () {
    $model = MyModel::factory()->create();
    $model->delete();
    expect($model->deleted_at)->not()->toBeNull();
});
```
