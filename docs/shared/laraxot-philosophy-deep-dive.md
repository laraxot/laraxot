# Laraxot 哲学深度解析

## 📖 概述

Laraxot 不仅仅是一个框架，更是一种开发哲学。它结合了数学精确性、工程原则和东方智慧，为大型企业级 Laravel 应用提供了坚实的架构基础。

## 🏛️ 五大核心原则

### 1. Logic（逻辑）- 数学精确性

**原则**: 每个操作都必须有数学上的精确性和确定性

**实现**:
```php
// 枚举定义的精确性
enum AddressItemEnum: string
{
    case ROUTE = 'route';
    case STREET_NUMBER = 'street_number';
    // ... 每个值都有明确的语义
}

// 条件列添加的数学精确性
public static function columns(Blueprint $table, ?XotBaseMigration $migration = null): void
{
    foreach (self::getColumnDefinitions() as $name => $definition) {
        // 精确的条件：只在需要时添加
        if ($migration === null || !$migration->hasColumn($name)) {
            $definition($table);
        }
    }
}
```

**实践要点**:
- 避免重复操作
- 确保操作的幂等性
- 使用枚举提供语义确定性

### 2. Philosophy（哲学）- DRY 原则

**原则**: 单一真实来源（Single Source of Truth）

**实现**:
```php
// 枚举作为字段定义的唯一来源
enum ContactTypeEnum: string
{
    case EMAIL = 'email';
    case PHONE = 'phone';
    
    // 所有字段定义集中在这里
    public static function columns(Blueprint $table, ?XotBaseMigration $migration = null): void
    {
        // 统一的列定义逻辑
    }
}

// 模型中的动态 fillable
public function getFillable(): array
{
    return [
        ...ContactTypeEnum::getValues(), // 从枚举获取，避免重复
        'id', 'uuid', 'created_at', 'updated_at'
    ];
}
```

**实践要点**:
- 避免硬编码重复
- 使用枚举作为配置中心
- 通过继承共享通用逻辑

### 3. Politics（政治）- 集中化治理

**原则**: 统一的架构治理和标准

**实现**:
```php
// XotBase 作为所有组件的统一基类
abstract class XotBaseResource extends Resource
{
    use NavigationLabelTrait;
    
    // 强制所有子类遵循相同模式
    abstract public static function getFormSchema(): array;
    
    // 统一的模型发现机制
    public static function getModel(): string
    {
        $moduleName = static::getModuleName();
        $modelName = Str::before(class_basename(static::class), 'Resource');
        return 'Modules\\'.$moduleName.'\Models\\'.$modelName;
    }
}
```

**实践要点**:
- 通过抽象基类强制标准
- 自动化约定优于配置
- 统一的命名和结构规范

### 4. Religion（宗教）- 强类型信仰

**原则**: 类型安全是不可动摇的信仰

**实现**:
```php
// 严格的类型声明
declare(strict_types=1);

class CreateUserAction extends XotBaseAction
{
    public function execute(array $data): User
    {
        // 类型验证的仪式
        Assert::isArray($data, 'Data must be an array');
        Assert::keyExists($data, 'email', 'Email is required');
        Assert::email($data['email'], 'Invalid email format');
        
        // 类型转换的仪式
        $name = SafeStringCastAction::cast($data['name'] ?? '');
        
        return User::create([
            'name' => $name,
            'email' => $data['email'],
        ]);
    }
}
```

**实践要点**:
- 每个输入都必须验证
- 使用类型安全的转换
- PHPStan Level 10 作为终极目标

### 5. Zen（禅）- 无形之形

**原则**: 适应上下文的灵活性

**实现**:
```php
// 一个方法，两种上下文（CREATE/UPDATE）
public static function columns(Blueprint $table, ?XotBaseMigration $migration = null): void
{
    // 在 CREATE 上下文中：直接添加所有列
    if ($migration === null) {
        foreach (self::getColumnDefinitions() as $definition) {
            $definition($table);
        }
        return;
    }
    
    // 在 UPDATE 上下文中：检查后添加
    foreach (self::getColumnDefinitions() as $name => $definition) {
        if (!$migration->hasColumn($name)) {
            $definition($table);
        }
    }
}
```

**实践要点**:
- 设计适应不同场景的接口
- 避免硬编码的上下文判断
- 提供优雅的降级机制

## 🏗️ 架构实现模式

### 1. 模块化分层

```
应用层（TechPlanner 业务模块）
    ↓
服务层（User、Notify、Geo 等功能模块）
    ↓
基础设施层（Xot 核心模块）
    ↓
框架层（Laravel 框架）
```

### 2. 扩展而非修改

```php
// ✅ 扩展 XotBase
class ClientResource extends XotBaseResource
{
    // 继承所有基础功能
    // 只实现业务特定逻辑
}

// ❌ 修改框架核心
class Resource extends FilamentResource
{
    // 不应该修改框架基类
}
```

### 3. 约定优于配置

```php
// 自动发现关系管理器
public static function getRelations(): array
{
    // 基于约定自动发现，无需手动配置
    return $this->discoverRelationManagers();
}

// 自动路由解析
// /admin/clients → ClientResource
// /admin/users → UserResource
```

## 🎯 实践指南

### 1. 创建新模块

```bash
# 1. 使用 Artisan 命令
php artisan module:create MyModule

# 2. 继承 XotBase 类
class MyModel extends XotBaseModel { }
class MyResource extends XotBaseResource { }

# 3. 实现 Actions
class CreateMyModelAction extends XotBaseAction { }

# 4. 添加枚举
enum MyStatusEnum: string implements HasLabel
{
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
}
```

### 2. 迁移最佳实践

```php
// CREATE 迁移 - 每个表只有一个
final class CreateClientsTable extends XotBaseMigration
{
    public function up(): void
    {
        $this->tableCreate(function (Blueprint $table): void {
            $table->id();
            ClientTypeEnum::columns($table); // 一次性添加所有字段
            $table->timestamps();
        });
    }
}

// UPDATE 迁移 - 后续修改
final class AddAvatarToClientsTable extends XotBaseMigration
{
    public function up(): void
    {
        $this->tableUpdate(function (Blueprint $table): void {
            if (!$this->hasColumn('avatar_url')) {
                $table->string('avatar_url')->nullable();
            }
        });
    }
}
```

### 3. Filament 集成

```php
class ClientResource extends XotBaseResource
{
    // 自动模型发现，无需手动指定
    // protected static ?string $model = Client::class;
    
    public static function getFormSchema(): array
    {
        return [
            // 复用跨模块组件
            AddressSection::make('address'),
            ContactSection::make('contacts'),
            
            // 业务特定字段
            TextInput::make('company_name'),
            Select::make('status')->options(ClientStatusEnum::class),
        ];
    }
}
```

## 🔄 持续改进

### 1. 代码质量

```bash
# 每次提交前
./vendor/bin/phpstan analyse Modules --memory-limit=-1
./vendor/bin/phpinsights analyse Modules --min-quality=90
./vendor/bin/rector process Modules --dry-run
```

### 2. 文档维护

- 更新架构决策记录
- 记录最佳实践案例
- 维护 API 文档

### 3. 知识传承

- 定期代码审查
- 架构讨论会议
- 培训新团队成员

## 🌟 核心价值

### 1. 可维护性
- 清晰的模块边界
- 统一的代码风格
- 完善的类型系统

### 2. 可扩展性
- 插件化架构
- 约定优于配置
- 最小化耦合

### 3. 可靠性
- 类型安全保障
- 全面的测试覆盖
- 自动化质量检查

### 4. 开发效率
- 自动化工具链
- 代码生成器
- 智能提示支持

## 📚 相关资源

- [Laraxot 官方文档](https://laraxot.com)
- [XotBase API 参考](../xot/api-reference.md)
- [最佳实践案例](../best-practices/index.md)
- [架构决策记录](../architecture/adr/)

---

**维护者**: TechPlanner 架构团队  
**最后更新**: 2025-12-12  
**版本**: 2.0