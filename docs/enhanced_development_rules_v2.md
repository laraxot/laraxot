# TechPlanner 增强开发规则 v2.0

## 🚨 核心架构规则（强制）

### 1. Laraxot 哲学 - XotBase 扩展模式
```php
// ✅ 正确 - 扩展 XotBase
class MyResource extends XotBaseResource { }
class MyPage extends XotBaseListRecords { }

// ❌ 错误 - 直接扩展 Filament
class MyResource extends Resource { }
class MyPage extends ListRecords { }
```

**关键原则**:
- 所有 Filament 类必须扩展相应的 XotBase 类
- 禁止直接扩展 Filament 基类
- 多语言模块扩展 LangBase 类（Cms、Blog、News）

### 2. Actions 模式 - 业务逻辑封装
```php
// ✅ 使用 Actions 封装业务逻辑
class CreateUserAction extends XotBaseAction
{
    public function execute(array $data): User
    {
        Assert::isArray($data);
        Assert::keyExists($data, 'email');
        
        return User::create($data);
    }
}

// ❌ 避免在 Controller 中直接写业务逻辑
public function store(Request $request)
{
    // 复杂的业务逻辑不应该在这里
    return User::create($request->all());
}
```

### 3. PHPStan Level 10 合规（强制）

#### 类型声明
```php
declare(strict_types=1);

// 方法签名必须精确
public function process(array $data): string
{
    Assert::isArray($data);
    return SafeStringCastAction::cast($data['value'] ?? '');
}

// 数组必须定义结构
/** @return array<string, mixed> */
public function getConfig(): array
{
    return $this->config;
}
```

#### 类型安全
```php
// ✅ 使用 Safe Cast Actions
$name = SafeStringCastAction::cast($data['name']);
$active = SafeBooleanCastAction::cast($data['active']);
$amount = SafeFloatCastAction::cast($data['amount']);

// ✅ 使用 Assert 验证
Assert::stringNotEmpty($email, 'Email cannot be empty');
Assert::email($email, 'Invalid email format');

// ❌ 避免直接类型转换
$name = (string) $data['name']; // 可能出错
```

#### Eloquent 最佳实践
```php
// ✅ 使用 PHPDoc generics
/** @return BelongsToMany<User> */
public function users(): BelongsToMany
{
    return $this->belongsToMany(User::class);
}

// ✅ 避免 property_exists() 在 Eloquent 模型
if ($this->hasAttribute('email')) { /* ... */ }

// ❌ 错误
if (property_exists($this, 'email')) { /* ... */ }
```

### 4. 迁移原则 - Laraxot 哲学

```php
// ✅ 每个表只有一个 CREATE 迁移
final class CreateUsersTable extends XotBaseMigration
{
    public function up(): void
    {
        $this->tableCreate(function (Blueprint $table): void {
            $table->id();
            UserStatusEnum::columns($table); // 添加所有状态字段
            // ... 其他字段
        });
    }
}

// ✅ 后续迁移使用 UPDATE
final class AddAvatarToUsersTable extends XotBaseMigration
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

// ❌ 错误 - 多个迁移创建同一个表
```

### 5. 枚举驱动的 Fillable 模式

```php
// ✅ 使用枚举驱动
class Client extends XotBaseModel
{
    public function getFillable(): array
    {
        return [
            ...ClientStatusEnum::getValues(),
            ...ClientTypeEnum::getValues(),
            'id', 'uuid', 'created_at', 'updated_at',
        ];
    }
}

// ❌ 避免静态数组
protected $fillable = ['name', 'email', 'status']; // 难以维护
```

## 📝 文档规则

### 1. 文件命名规范
```
✅ 正确命名：
- readme.md
- user-management.md
- phpstan-analysis.md
- README.md (例外)
- CHANGELOG.md (例外)

❌ 错误命名：
- UserManagement.md
- PHPStan_Analysis.md
- readme.MD
```

### 2. 文档结构标准
```
docs/
├── README.md              # 模块概览
├── architecture/          # 架构文档
│   ├── overview.md
│   ├── patterns.md
│   └── decisions.md
├── best-practices/        # 最佳实践
│   ├── coding.md
│   ├── testing.md
│   └── deployment.md
├── filament/              # Filament 集成
│   ├── resources.md
│   ├── widgets.md
│   └── forms.md
├── phpstan/               # 代码质量
│   ├── compliance.md
│   ├── fixes.md
│   └── level-10.md
└── testing/               # 测试指南
    ├── unit.md
    ├── feature.md
    └── integration.md
```

### 3. 链接规范
```markdown
✅ 正确 - 相对路径
[架构文档](architecture/overview.md)
[PHPStan 合规](phpstan/compliance.md)

❌ 错误 - 绝对路径
[架构文档](/var/www/project/docs/architecture/overview.md)
```

### 4. 语言策略
- **主语言**: 意大利语（it）
- **支持语言**: 英语（en）、德语（de）
- **翻译文件**: 必须包含目标语言内容，不能是英语占位符

## 🛠️ 开发工具和命令

### PHPStan 分析
```bash
# 完整分析
cd laravel && ./vendor/bin/phpstan analyse Modules --memory-limit=-1

# 单模块分析
./vendor/bin/phpstan analyse Modules/Xot --memory-limit=-1

# 检查特定错误类型
./vendor/bin/phpstan analyse Modules --error-type function.alreadyNarrowedType
```

### 代码质量工具
```bash
# Rector 重构
./vendor/bin/rector process Modules --dry-run
./vendor/bin/rector process Modules

# Psalm 分析
./vendor/bin/psalm --show-info=true --show-suggestions=true

# PHP Insights
./vendor/bin/phpinsights analyse Modules --min-quality=90
```

### 文档工具
```bash
# 建议实现的脚本
docs-link-checker.sh      # 检查断链
docs-format-validator.sh  # 验证格式
docs-duplicate-detector.sh # 检测重复
```

## 🎯 常见错误和解决方案

### 1. 视图不存在错误
```php
// ❌ 问题
$view = 'custom::view';
return view($view); // 可能失败

// ✅ 解决方案
$view = 'custom::view';
if (!View::exists($view)) {
    return view('cms::components.empty');
}
return view($view);
```

### 2. 类型转换错误
```php
// ❌ 问题
$value = $mixed['key']; // 可能为 null
echo strtoupper($value); // 错误

// ✅ 解决方案
$value = SafeStringCastAction::cast($mixed['key'] ?? '');
echo strtoupper($value);
```

### 3. 模型属性访问
```php
// ❌ 问题
if (property_exists($model, 'email')) { /* ... */ }

// ✅ 解决方案
if ($model->hasAttribute('email')) { /* ... */ }
// 或
if ($model->isFillable('email')) { /* ... */ }
```

### 4. 数组访问安全
```php
// ❌ 问题
$value = $array['deep']['key']; // 可能出错

// ✅ 解决方案
$value = $array['deep']['key'] ?? null;
// 或使用 Assert
Assert::keyExists($array, 'deep');
Assert::keyExists($array['deep'], 'key');
$value = $array['deep']['key'];
```

## 📊 质量检查清单

### 代码提交前检查
- [ ] PHPStan Level 10 通过（0 错误）
- [ ] 所有类型声明完整
- [ ] Webmozart Assert 验证输入
- [ ] Safe Cast Actions 转换类型
- [ ] 文档更新（如有 API 变更）
- [ ] 测试通过

### 文档发布前检查
- [ ] 文件命名规范正确
- [ ] 所有链接有效
- [ ] 相对路径使用正确
- [ ] 语言一致性
- [ ] 无重复内容

## 🔄 工作流程

### 1. 开发新功能
1. 创建/扩展相应的 XotBase 类
2. 实现 Actions 封装业务逻辑
3. 添加类型声明和 Assert 验证
4. 编写测试
5. 运行 PHPStan 确保合规
6. 更新文档

### 2. 修复错误
1. 运行 PHPStan 识别错误
2. 应用相应的修复模式
3. 验证修复效果
4. 更新相关文档

### 3. 文档维护
1. 定期检查链接有效性
2. 更新过时的内容
3. 标准化格式
4. 移除重复内容

---

## 📚 相关资源

- [PHPStan 文档](https://phpstan.org/user-guide/getting-started)
- [Webmozart Assert](https://github.com/webmozarts/assert)
- [Laraxot 哲学](docs/critical_rules/laraxot-philosophy.md)
- [Filament v4 升级指南](docs/filament/filament-v4-upgrade.md)
- [Actions 模式](docs/patterns/actions-pattern.md)

---

**版本**: 2.0  
**更新日期**: 2025-12-12  
**适用范围**: TechPlanner 项目所有模块和主题