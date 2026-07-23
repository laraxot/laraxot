# Filament v4 迁移模式和最佳实践

## 📋 概述

本文档记录了 TechPlanner 项目从 Filament v3 升级到 v4 的成功经验、迁移模式和最佳实践。

## 🎯 升级策略

### 1. 渐进式升级

```php
// 阶段 1: 基础兼容性
class MyResource extends XotBaseResource  // 确保扩展正确基类
{
    // 保持现有功能不变
}

// 阶段 2: 类型安全升级
/** @return array<string, TextInput|Select> */
public static function getFormSchema(): array
{
    // 添加类型注解
}

// 阶段 3: 新功能采用
public static function getTableColumns(): array
{
    return [
        TextColumn::make('name')
            ->badge() // 使用新的 badge() 方法
            ->color(fn ($state) => 'primary'),
    ];
}
```

### 2. 向后兼容性保证

```php
// 保持旧 API 的兼容性
if (method_exists($component, 'badge')) {
    $component->badge();
} else {
    // 使用旧方式
    $component->formatStateUsing(fn ($state) => "[$state]");
}
```

## 🔧 关键变更和解决方案

### 1. BadgeColumn 替代

```php
// ❌ v3 - BadgeColumn 已弃用
BadgeColumn::make('status')
    ->colors([
        'active' => 'success',
        'inactive' => 'danger',
    ]);

// ✅ v4 - 使用 TextColumn + badge()
TextColumn::make('status')
    ->badge()
    ->color(fn (UserStatusEnum $status): string => match($status) {
        UserStatusEnum::ACTIVE => 'success',
        UserStatusEnum::INACTIVE => 'danger',
        default => 'gray',
    });
```

### 2. 类型安全增强

```php
// ❌ v3 - 缺少类型注解
public static function getFormSchema(): array
{
    return [
        TextInput::make('name'),
        Select::make('status'),
    ];
}

// ✅ v4 - 完整类型注解
/** @return array<string, TextInput|Select|Textarea> */
public static function getFormSchema(): array
{
    return [
        TextInput::make('name'),
        Select::make('status')
            ->options(UserStatusEnum::class),
        Textarea::make('description'),
    ];
}
```

### 3. 关系管理器自动发现

```php
// ❌ v3 - 手动注册关系
public static function getRelations(): array
{
    return [
        PostsRelationManager::class,
        CommentsRelationManager::class,
    ];
}

// ✅ v4 - 自动发现（XotBaseResource 中实现）
public static function getRelations(): array
{
    // 自动扫描 RelationManagers 目录
    return static::discoverRelationManagers();
}
```

### 4. 导航标签处理

```php
// ❌ v3 - 手动翻译
protected static ?string $navigationLabel = 'Users';
protected static ?string $modelLabel = 'User';

// ✅ v4 - 使用 Trait 自动处理
use NavigationLabelTrait;

// 自动从翻译文件加载
// resources/lang/en/resource.php: 'user.label' => 'User'
```

## 🏗️ 架构模式

### 1. 组件复用模式

```php
// 可复用的地址组件（Geo 模块）
class AddressSection extends XotBaseSection
{
    public function getFormSchema(): array
    {
        return AddressItemEnum::getFormSchema();
    }
}

// 在多个资源中复用
class ClientResource extends XotBaseResource
{
    public static function getFormSchema(): array
    {
        return [
            CompanySection::make('company'),
            AddressSection::make('address'),  // 复用 Geo 模块
            ContactSection::make('contacts'), // 复用 Notify 模块
        ];
    }
}
```

### 2. 枚举驱动模式

```php
// 枚举定义所有选项和方法
enum UserStatusEnum: string implements HasLabel, HasColor
{
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
    
    public function getLabel(): string
    {
        return __('user.status.'.$this->value);
    }
    
    public function getColor(): string
    {
        return match($this) {
            self::ACTIVE => 'success',
            self::INACTIVE => 'danger',
        };
    }
}

// 在 Filament 中直接使用
Select::make('status')
    ->options(UserStatusEnum::class)
    ->default(UserStatusEnum::ACTIVE);

TextColumn::make('status')
    ->badge()
    ->color(fn (UserStatusEnum $status): string => $status->getColor());
```

### 3. Actions 集成模式

```php
// Filament 表单操作调用 Actions
class CreateUser extends CreateRecord
{
    protected function handleRecordCreation(array $data): Model
    {
        return app(CreateUserAction::class)->execute($data);
    }
}

// 自定义操作使用 Actions
class SendWelcomeEmail extends Action
{
    public function action(): void
    {
        $user = $this->getRecord();
        app(SendWelcomeEmailAction::class)->execute($user);
    }
}
```

## 🎨 UI/UX 改进

### 1. 响应式设计

```php
// 使用新的响应式列配置
public static function getTableColumns(): array
{
    return [
        TextColumn::make('name')
            ->sortable()
            ->searchable(),
        
        TextColumn::make('email')
            ->sortable()
            ->searchable()
            ->toggleable(isToggledHiddenByDefault: true), // 默认隐藏
        
        TextColumn::make('created_at')
            ->dateTime()
            ->sortable()
            ->toggleable(),
    ];
}

// 响应式表单布局
public static function getFormSchema(): array
{
    return [
        Grid::make(2)->schema([
            TextInput::make('first_name')->columnSpan(1),
            TextInput::make('last_name')->columnSpan(1),
        ]),
        
        Section::make('Contact Information')->schema([
            TextInput::make('email')->columnSpanFull(),
            TextInput::make('phone')->columnSpanFull(),
        ])->columns(2),
    ];
}
```

### 2. 增强的交互

```php
// 实时搜索
Select::make('user')
    ->searchable()
    ->getSearchResultsUsing(function (string $search) {
        return User::where('name', 'like', "%{$search}%")
            ->limit(50)
            ->pluck('name', 'id');
    })
    ->getOptionLabelUsing(function ($value) {
        return User::find($value)?->name;
    });

// 动态表单字段
Repeater::make('contacts')
    ->schema([
        Select::make('type')
            ->options(ContactTypeEnum::class)
            ->reactive(),
        
        TextInput::make('value')
            ->visible(fn (callable $get) => $get('type') === ContactTypeEnum::EMAIL->value),
        
        PhoneNumber::make('value')
            ->visible(fn (callable $get) => $get('type') === ContactTypeEnum::PHONE->value),
    ]);
```

## 🚀 性能优化

### 1. 懒加载关系

```php
// 使用 Eager Loading 减少查询
public static function getTableQuery(): Builder
{
    return parent::getTableQuery()
        ->with(['roles', 'profile']) // 预加载关系
        ->select(['id', 'name', 'email', 'created_at']); // 只选择需要的列
}

// 在列中使用预加载的关系
TextColumn::make('roles.name')
    ->formatStateUsing(fn ($state) => collect($state)->join(', ')),
```

### 2. 缓存策略

```php
// 缓存选项数据
Select::make('category')
    ->options(function () {
        return Cache::remember('categories', 3600, function () {
            return Category::pluck('name', 'id');
        });
    });

// 缓存统计数据
protected function getTableRecordsTotal(): int
{
    return Cache::remember(
        'users_count_' . static::$model,
        300,
        fn () => static::$model::count()
    );
}
```

## 📊 测试策略

### 1. 组件测试

```php
class UserResourceTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_list_users(): void
    {
        $users = User::factory()->count(5)->create();
        
        Livewire::test(UserResource\Pages\ListUsers::class)
            ->assertSee($users->first()->name);
    }
    
    public function test_create_user(): void
    {
        $newUser = User::factory()->make();
        
        Livewire::test(UserResource\Pages\CreateUser::class)
            ->fillForm([
                'name' => $newUser->name,
                'email' => $newUser->email,
            ])
            ->call('create')
            ->assertHasNoErrors();
            
        $this->assertDatabaseHas('users', [
            'name' => $newUser->name,
            'email' => $newUser->email,
        ]);
    }
}
```

### 2. 集成测试

```php
class FilamentIntegrationTest extends TestCase
{
    public function test_user_can_access_resource(): void
    {
        $user = User::factory()->create();
        
        $this->actingAs($user)
            ->get('/admin/users')
            ->assertSuccessful();
    }
    
    public function test_form_validation(): void
    {
        Livewire::test(UserResource\Pages\CreateUser::class)
            ->fillForm(['email' => 'invalid-email'])
            ->call('create')
            ->assertHasFormErrors(['email' => 'email']);
    }
}
```

## 🔍 故障排除

### 1. 常见错误

```php
// 错误: Call to undefined method badge()
// 解决: 确保使用 TextColumn 而不是 BadgeColumn
TextColumn::make('status')->badge(); // ✅

// 错误: Type mismatch in form schema
// 解决: 添加正确的 PHPDoc 注解
/** @return array<string, TextInput|Select> */ // ✅

// 错误: Navigation label not translated
// 解决: 使用 NavigationLabelTrait
use NavigationLabelTrait; // ✅
```

### 2. 调试技巧

```php
// 使用 dump() 调试表单数据
protected function handleRecordCreation(array $data): Model
{
    dump($data); // 调试数据
    return parent::handleRecordCreation($data);
}

// 使用 logger() 记录错误
protected function mutateFormDataBeforeCreate(array $data): array
{
    try {
        return $this->processData($data);
    } catch (\Exception $e) {
        logger()->error('Form data processing failed', [
            'error' => $e->getMessage(),
            'data' => $data,
        ]);
        throw $e;
    }
}
```

## 📈 升级检查清单

### 升级前
- [ ] 备份数据库
- [ ] 创建新分支
- [ ] 更新依赖
- [ ] 运行测试套件

### 升级中
- [ ] 更新基类扩展
- [ ] 替换弃用组件
- [ ] 添加类型注解
- [ ] 修复编译错误

### 升级后
- [ ] 运行完整测试
- [ ] 手动测试关键功能
- [ ] 性能基准测试
- [ ] 更新文档

## 📚 相关资源

- [Filament v4 升级指南](https://filamentphp.com/docs/4.x/upgrade-guide)
- [新功能概览](https://filamentphp.com/docs/4.x/releases)
- [ breaking changes](https://filamentphp.com/docs/4.x/upgrade-guide#breaking-changes)
- [最佳实践指南](../best-practices/filament.md)

---

**维护者**: TechPlanner 前端团队  
**最后更新**: 2025-12-12  
**版本**: 1.0