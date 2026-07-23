# PHPUnit 到 Pest PHP 转换报告

**日期**: 2025-12-15  
**状态**: 部分完成 (2/11 文件已转换)

## 执行摘要

在 TechPlanner 项目中，我们已经识别出 11 个仍使用 PHPUnit 类语法的测试文件需要转换为 Pest PHP 格式。大多数测试文件（333 个中的 322 个）已经使用 Pest 格式。

## 转换状态

### ✅ 已转换 (2/11)

1. **Themes/Two/Main_files/filament-peek-demo/tests/Unit/ExampleTest.php**
   - 简单示例测试
   - 转换成功

2. **Themes/Two/Main_files/filament-peek-demo/tests/Feature/ExampleTest.php**
   - 基本 HTTP 响应测试
   - 转换成功

### ⚠️ 需要删除而非转换 (1/11)

3. **tests/Unit/RefreshDatabaseTest.php**
   - **原因**: 违反 Laraxot 哲学 - 使用 `RefreshDatabase` trait
   - **操作**: 应删除此文件，不应转换
   - **规则**: Tests 不应修改数据库状态

### 🔄 待转换 - 复杂文件 (8/11)

#### Tenant 模块 (5 个文件)

4. **Modules/Tenant/Tests/Performance/SushiToJsonPerformanceTest.php** (527 行)
   - 性能测试套件
   - 包含 setUp/tearDown 方法
   - 包含私有辅助方法 (`mockTenantService`, `createTestData`)
   - 使用 PHPUnit 属性 (#[Test], #[Group])
   - 复杂度: 高

5. **Modules/Tenant/Tests/Integration/SushiToJsonIntegrationTest.php** (536 行)
   - 集成测试套件
   - 包含 setUp/tearDown 方法
   - 包含私有辅助方法
   - 复杂度: 高

6. **Modules/Tenant/Tests/Feature/TenantBusinessLogicTest.php** (458 行)
   - 业务逻辑测试
   - 复杂度: 高

7. **Modules/Tenant/Tests/Integration/Traits/SushiToJsonIntegrationTest.php** (401 行)
   - Trait 集成测试
   - 复杂度: 中高

8. **Modules/Tenant/Tests/Unit/Traits/SushiToJsonTest.php** (401 行)
   - Trait 单元测试
   - 复杂度: 中高

#### Themes/Sixteen (3 个文件)

9. **Themes/Sixteen/tests/Feature/ComunePagesTest.php**
   - 页面功能测试
   - 复杂度: 中

10. **Themes/Sixteen/tests/Feature/Components/BootstrapItaliaComponentsTest.php**
    - 组件测试
    - 复杂度: 中

11. **Themes/Sixteen/tests/Unit/ComuneControllerTest.php**
    - 控制器单元测试
    - 复杂度: 中

## 转换挑战

### 1. 辅助方法处理

PHPUnit 类中的私有方法需要转换为：
- Pest 中的独立函数
- 或者使用 `beforeEach()` 中的闭包变量

**示例**:
```php
// PHPUnit
private function createTestData(int $count): array
{
    // ...
}

// Pest - 选项 1: 独立函数
function createTestData(int $count): array
{
    // ...
}

// Pest - 选项 2: beforeEach 中的闭包
beforeEach(function () {
    $this->createTestData = function (int $count): array {
        // ...
    };
});
```

### 2. setUp/tearDown 转换

```php
// PHPUnit
protected function setUp(): void
{
    parent::setUp();
    $this->model = new TestModel();
}

// Pest
beforeEach(function () {
    $this->model = new TestModel();
});
```

### 3. PHPUnit 属性转换

```php
// PHPUnit
#[Test]
#[Group('performance')]
public function it_handles_large_datasets(): void

// Pest
uses()->group('performance');

test('handles large datasets', function () {
    // ...
});
```

### 4. 断言转换

| PHPUnit | Pest |
|---------|------|
| `$this->assertTrue($value)` | `expect($value)->toBeTrue()` |
| `$this->assertFalse($value)` | `expect($value)->toBeFalse()` |
| `$this->assertEquals($expected, $actual)` | `expect($actual)->toBe($expected)` |
| `$this->assertCount($count, $array)` | `expect($array)->toHaveCount($count)` |
| `$this->assertLessThan($max, $value)` | `expect($value)->toBeLessThan($max)` |
| `$this->assertInstanceOf($class, $obj)` | `expect($obj)->toBeInstanceOf($class)` |

## 转换策略

### 简单文件 (< 100 行)
- 手动转换
- 直接替换类结构为函数式语法

### 复杂文件 (> 400 行)
1. 提取辅助方法为独立函数
2. 转换 setUp/tearDown 为 beforeEach/afterEach
3. 逐个转换测试方法
4. 验证每个转换后的测试

### 推荐工具

**选项 1: 手动转换** (推荐用于复杂文件)
- 更可控
- 可以优化测试结构
- 可以改进测试描述

**选项 2: 半自动化**
- 使用正则表达式辅助
- 手动验证每个转换
- 适合重复性模式

## 下一步行动

### 立即行动

1. **删除违规文件**
   ```bash
   rm tests/Unit/RefreshDatabaseTest.php
   ```

2. **转换 Themes/Sixteen 文件** (中等复杂度)
   - 先转换这 3 个文件作为练习
   - 建立转换模式

3. **转换 Tenant 模块文件** (高复杂度)
   - 最后处理这些大型文件
   - 可能需要重构测试结构

### 验证步骤

每次转换后：
```bash
# 运行单个测试文件
php artisan test --filter=TestFileName

# 检查语法
php -l path/to/test/file.php

# 运行 PHPStan
./vendor/bin/phpstan analyse path/to/test/file.php --level=10
```

## 转换清单

- [x] Themes/Two/Main_files/filament-peek-demo/tests/Unit/ExampleTest.php
- [x] Themes/Two/Main_files/filament-peek-demo/tests/Feature/ExampleTest.php
- [ ] tests/Unit/RefreshDatabaseTest.php (删除)
- [ ] Themes/Sixteen/tests/Unit/ComuneControllerTest.php
- [ ] Themes/Sixteen/tests/Feature/ComunePagesTest.php
- [ ] Themes/Sixteen/tests/Feature/Components/BootstrapItaliaComponentsTest.php
- [ ] Modules/Tenant/Tests/Unit/Traits/SushiToJsonTest.php
- [ ] Modules/Tenant/Tests/Integration/Traits/SushiToJsonIntegrationTest.php
- [ ] Modules/Tenant/Tests/Feature/TenantBusinessLogicTest.php
- [ ] Modules/Tenant/Tests/Integration/SushiToJsonIntegrationTest.php
- [ ] Modules/Tenant/Tests/Performance/SushiToJsonPerformanceTest.php

## 统计数据

- **总测试文件**: 333
- **已使用 Pest**: 322 (96.7%)
- **仍使用 PHPUnit**: 11 (3.3%)
- **已转换**: 2 (18.2%)
- **需删除**: 1 (9.1%)
- **待转换**: 8 (72.7%)

## 预计完成时间

- **简单文件** (Themes/Sixteen): 1-2 小时
- **复杂文件** (Tenant 模块): 3-4 小时
- **测试和验证**: 1 小时
- **总计**: 5-7 小时

## 参考资料

- [Pest PHP 官方文档](https://pestphp.com/docs)
- [Pest 迁移指南](https://pestphp.com/docs/migrating-from-phpunit)
- [项目 Pest 规则](./critical_rules/pest-testing-rules.md)
- [Laraxot 测试哲学](../IFLOW.md#filosofia-laraxot-tests)

## 结论

转换进度良好。大部分测试文件已经使用 Pest 格式。剩余的 11 个文件中，2 个已转换，1 个需删除，8 个待转换。建议先处理中等复杂度的文件以建立转换模式，然后再处理大型复杂文件。
