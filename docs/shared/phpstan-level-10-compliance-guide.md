# PHPStan Level 10 合规指南

## 📋 概述

本指南提供了在 TechPlanner 项目中达到 PHPStan Level 10 合规的完整策略和最佳实践。PHPStan Level 10 是最高级别的静态分析，确保代码的极致类型安全。

## 🎯 核心原则

### 1. 严格类型声明
```php
<?php

declare(strict_types=1);

namespace Modules\Example;

// 所有方法必须有明确的参数和返回类型
class ExampleService
{
    public function process(array $data): string
    {
        // 实现
    }
}
```

### 2. 输入验证模式
```php
use Webmozart\Assert\Assert;

class UserService
{
    public function createUser(array $data): User
    {
        // 使用 Assert 验证输入
        Assert::isArray($data, 'Data must be an array');
        Assert::keyExists($data, 'email', 'Email is required');
        Assert::email($data['email'], 'Invalid email format');
        Assert::stringNotEmpty($data['name'], 'Name cannot be empty');
        
        return User::create($data);
    }
}
```

### 3. 类型转换策略
```php
use Modules\Xot\Actions\Cast\SafeStringCastAction;
use Modules\Xot\Actions\Cast\SafeIntCastAction;
use Modules\Xot\Actions\Cast\SafeBooleanCastAction;

class DataProcessor
{
    public function process(mixed $data): array
    {
        return [
            'name' => SafeStringCastAction::cast($data['name'] ?? ''),
            'age' => SafeIntCastAction::cast($data['age'] ?? 0),
            'active' => SafeBooleanCastAction::cast($data['active'] ?? false),
        ];
    }
}
```

## 🔧 常见错误模式及解决方案

### 1. function.alreadyNarrowedType
**问题**: 冗余的类型检查
```php
// ❌ 错误
if (is_array($data)) {
    Assert::isArray($data); // PHPStan 知道 $data 已经是数组
}

// ✅ 正确
if (is_array($data)) {
    // 直接使用，无需再次断言
    $this->processArray($data);
}
```

### 2. method.impossibleType
**问题**: 调用不存在的方法
```php
// ❌ 错误
$view = 'nonexistent::view';
View::make($view); // 视图不存在

// ✅ 正确
$view = 'custom::view';
if (!View::exists($view)) {
    $view = 'cms::components.fallback';
}
return View::make($view);
```

### 3. property.exists.on.eloquent
**问题**: 在 Eloquent 模型上使用 property_exists
```php
// ❌ 错误
if (property_exists($model, 'email')) {
    // ...
}

// ✅ 正确
if ($model->hasAttribute('email')) {
    // ...
}
// 或
if ($model->isFillable('email')) {
    // ...
}
```

### 4. mixed 类型访问
**问题**: 在 mixed 类型上访问属性或方法
```php
// ❌ 错误
function process(mixed $data): void
{
    echo $data->name; // 错误：mixed 没有属性
}

// ✅ 正确
function process(mixed $data): void
{
    if (is_object($data) && property_exists($data, 'name')) {
        echo $data->name;
    }
}
```

## 📊 数组类型定义

### 1. 简单数组
```php
/** @return array<string> */
public function getNames(): array
{
    return ['John', 'Jane', 'Bob'];
}
```

### 2. 关联数组
```php
/** @return array<string, mixed> */
public function getConfig(): array
{
    return [
        'host' => 'localhost',
        'port' => 3306,
        'timeout' => 30,
    ];
}
```

### 3. 嵌套数组
```php
/** @return array<int, array<string, mixed>> */
public function getUsers(): array
{
    return [
        ['id' => 1, 'name' => 'John'],
        ['id' => 2, 'name' => 'Jane'],
    ];
}
```

### 4. 枚举数组
```php
/** @return array<int, UserStatusEnum> */
public function getStatuses(): array
{
    return UserStatusEnum::cases();
}
```

## 🏗️ Eloquent 关系类型

### 1. 基本关系
```php
/** @return BelongsTo<User> */
public function user(): BelongsTo
{
    return $this->belongsTo(User::class);
}

/** @return HasMany<Post> */
public function posts(): HasMany
{
    return $this->hasMany(Post::class);
}
```

### 2. 多对多关系
```php
/** @return BelongsToMany<Role> */
public function roles(): BelongsToMany
{
    return $this->belongsToMany(Role::class);
}
```

### 3. 多态关系
```php
/** @return MorphMany<Comment> */
public function comments(): MorphMany
{
    return $this->morphMany(Comment::class, 'commentable');
}
```

## 🎨 Filament 组件类型

### 1. 表单组件
```php
/** @return array<string, TextInput|Select> */
public static function getFormSchema(): array
{
    return [
        'name' => TextInput::make('name'),
        'status' => Select::make('status')
            ->options(UserStatusEnum::class),
    ];
}
```

### 2. 表格列
```php
/** @return array<string, TextColumn|IconColumn> */
public static function getTableColumns(): array
{
    return [
        TextColumn::make('name'),
        IconColumn::make('status')
            ->icon(fn (UserStatusEnum $status): string => $status->getIcon()),
    ];
}
```

## 📝 测试中的类型安全

### 1. 测试数据工厂
```php
class UserFactory extends Factory
{
    protected $model = User::class;

    /** @return array<string, mixed> */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'status' => UserStatusEnum::ACTIVE->value,
        ];
    }
}
```

### 2. 测试断言
```php
public function test_user_creation(): void
{
    $userData = [
        'name' => 'John Doe',
        'email' => 'john@example.com',
    ];
    
    $user = app(CreateUserAction::class)->execute($userData);
    
    Assert::isInstanceOf($user, User::class);
    Assert::same($user->name, 'John Doe');
    Assert::same($user->email, 'john@example.com');
}
```

## 🛠️ 开发工作流

### 1. 日常开发
```bash
# 1. 编写代码
# 2. 运行 PHPStan
./vendor/bin/phpstan analyse Modules/YourModule --memory-limit=-1

# 3. 修复错误
# 4. 重新运行直到 0 错误
./vendor/bin/phpstan analyse Modules/YourModule --memory-limit=-1
```

### 2. 批量修复
```bash
# 使用 Rector 自动修复
./vendor/bin/rector process Modules --dry-run
./vendor/bin/rector process Modules

# 使用 Psalm 补充分析
./vendor/bin/psalm --show-info=true
```

### 3. 质量检查
```bash
# PHP Insights 综合分析
./vendor/bin/phpinsights analyse Modules --min-quality=90

# 自定义脚本检查
./scripts/phpstan-check.sh
```

## 🎯 最佳实践总结

### ✅ 推荐做法
1. **始终使用 `declare(strict_types=1)`**
2. **为所有方法添加类型声明**
3. **使用 Webmozart Assert 验证输入**
4. **使用 Safe Cast Actions 进行类型转换**
5. **为 Eloquent 关系添加 PHPDoc generics**
6. **定义数组结构：`array<string, mixed>`**
7. **检查视图存在性：`View::exists()`**

### ❌ 避免做法
1. **在 Eloquent 模型上使用 `property_exists()`**
2. **冗余的类型检查**
3. **在 mixed 类型上直接访问属性**
4. **忽略 PHPStan 错误**
5. **使用 `@var` 而非 `@param` 和 `@return`**

## 📚 相关资源

- [PHPStan 官方文档](https://phpstan.org/user-guide/getting-started)
- [Webmozart Assert 文档](https://github.com/webmozarts/assert)
- [Laraxot 类型安全指南](../laraxot/TypeSafety.md)
- [Safe Cast Actions 参考](../actions/safe-cast-actions.md)

---

**维护者**: TechPlanner 开发团队  
**最后更新**: 2025-12-12  
**版本**: 1.0