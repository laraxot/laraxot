# TechPlanner Laravel Multi-Tenant Application

## 项目概述

TechPlanner 是一个基于 Laravel 12.x 构建的企业级多租端应用程序，采用模块化架构和现代开发实践。该应用集成了 Filament v4 管理面板、多语言支持和严格的代码质量标准。项目基于 `nwidart/laravel-modules` 构建，实现了模块化的架构设计，其中 Xot 模块作为基础引擎支撑其他所有模块。

## 核心原则

### 自主优先级规则 (Autonomous Priority Rule)
**"Ordine e priorita le scegli sempre te."** (Order and priority are always chosen by you.)

AI 助手**必须始终**确定操作的顺序和优先级。这是确保效率、遵循架构标准（Laraxot、DRY、KISS、SOLID）并防止"rabbit holes"的根本操作规则。

此规则与项目的核心哲学"逻辑、宗教、政治、禅"紧密结合：
- **逻辑 (Logica)**: 基于项目上下文的逻辑决策
- **宗教 (Religione)**: 遵循架构规则（XotBase，不直接扩展 Filament）
- **政治 (Politica)**: 开发流程的治理
- **禅 (Zen)**: 自主决策的流畅状态

### Super Mucca 方法论
采用最大置信度和深度代码分析的方法，在行动前彻底理解代码的目的、逻辑、宗教、政治和禅意。

## 核心架构

### 模块化设计
- 使用 `nwidart/laravel-modules` 包实现可扩展架构
- 15+ 个活跃模块：TechPlanner（业务逻辑）、User（认证）、Xot（基础）、Tenant（多租户）、Activity（活动日志）、Cms（内容管理）、Geo（地理服务）等
- 每个模块遵循一致的结构：app/（Actions、Models、Providers、Filament 等）、docs/、config/、database/ 等

### 多租户支持
- 支持多租户数据隔离架构
- 可通过 Tenant 模块实现租户特定配置和功能

### Filament v5 集成
- 现代化管理界面，使用 Filament v5
- 自定义 XotBase 和 LangBase 抽象类扩展模式
- 增强的资源模式和 UI 组件
- 内置翻译系统支持多语言功能

### 基础层（Xot 模块）
- 为所有其他模块提供核心功能和基础类
- 提供 XotBaseModel、XotBaseResource、XotBaseServiceProvider 等基础模式
- 包含内存优化工具（如 OptimizeFilamentMemoryCommand）以提升性能

## 关键特性

- **模块化架构**：每个模块包含完整的功能结构，可独立开发和维护
- **Filament v4 管理面板**：具有自定义扩展的现代管理界面
- **性能优化**：包含内存优化命令和性能分析工具
- **高级代码质量**：PHPStan Level Max、PHPMD、PHPInsights、Rector 等分析工具
- **前端构建**：使用 Vite + TailwindCSS 进行现代化前端构建
- **可扩展结构**：支持主题系统（Sixteen、Two、Zero 等）

## 开发环境设置

### 系统要求
- PHP 8.2+
- Composer
- Node.js & npm
- MySQL/PostgreSQL 数据库

### 安装步骤

1. 克隆仓库：
   ```bash
   git clone <repository-url>
   cd base_techplanner_fila4_mono
   ```

2. 进入 Laravel 目录并安装 PHP 依赖：
   ```bash
   cd laravel
   composer install
   ```

3. 安装 Node.js 依赖：
   ```bash
   npm install
   ```

4. 配置环境：
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. 在 `.env` 中配置数据库设置

6. 运行数据库迁移：
   ```bash
   php artisan migrate
   ```

7. 构建前端资源：
   ```bash
   npm run build
   ```

8. 启动开发服务器：
   ```bash
   php artisan serve
   ```

## 核心开发规则

### 🚨 XotBase/LangBase 扩展规则（强制）
**永远不要直接扩展 Filament 类。始终扩展 XotBase 或 LangBase 抽象类。**

⚠️ **关键**：首先检查模块是否多语言！

```php
// ❌ 错误
use Filament\Resources\Pages\ListRecords;
class MyPage extends ListRecords { }

// ✅ 用于非多语言模块
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;
class MyPage extends XotBaseListRecords { }

// ✅ 用于多语言模块（Cms、Blog、News）
use Modules\Lang\Filament\Resources\Pages\LangBaseListRecords;
class MyPage extends LangBaseListRecords { }
```

### BaseModel 模式
- 每个模块都有自己的 BaseModel，继承自 XotBaseModel
- 模型结构：Model → Module BaseModel → XotBaseModel → Laravel Model
- **永远不要直接扩展 XotBaseModel，而是扩展模块特定的 BaseModel**

### 方法签名规则（关键）
**始终精确匹配父级/特征方法签名 - 静态与非静态很重要！**

### 抽象方法规则
**必须实现父类和特征中的所有抽象方法。**

## 模块结构

### 核心模块
- **Xot**：提供基础类和共享功能的核心模块，包含服务提供者、基础模型等
- **User**：用户管理和身份验证
- **Tenant**：多租户支持和数据隔离
- **Lang**：国际化和语言管理

### 业务逻辑模块
- **TechPlanner**：主要业务逻辑模块
- **Employee**：员工管理功能
- **Activity**：活动日志和跟踪
- **Job**：后台作业管理
- **AI**：AI 相关功能集成

### 支持模块
- **UI**：用户界面组件和 Filament 自定义
- **Geo**：地理功能和位置服务
- **Media**：文件和媒体管理
- **Notify**：通知和电子邮件系统
- **Cms**：内容管理系统
- **Gdpr**：GDPR 合规功能

### 主题系统
- **Sixteen**：现代响应式主题
- **Two**：简洁商务主题
- **Zero**：极简主义主题

## 开发指南

### 代码质量标准
- **PHPStan Level Max**：完全类型安全合规（配置在 `phpstan.neon` 中）
- **PSR-12**：编码标准合规
- **严格类型**：所有文件使用 `declare(strict_types=1)`
- **类型提示**：全面的参数和返回类型声明
- **Webmozart Assert**：用于输入验证和类型检查

### 架构模式
- **XotBase 扩展**：所有 Filament 类扩展 XotBase* 或 LangBase* 抽象类
- **ServiceProvider 模式**：使用 XotBaseServiceProvider 作为基础
- **多租户**：在数据操作中始终考虑租户上下文

### 翻译标准
- **始终在所有三种语言中实现缺失的键**（意大利语、英语、德语）
- **永远不要混合不同语言**在单个翻译中
- **在系统中使用一致的术语**
- **对于文件上传字段**：占位符应指示操作（例如"上传发票"）而不是内容

### 文件和文件夹结构
- **在 docs 文件夹中**：仅使用小写字符，README.md 除外
- **在 Blade 模板中**：使用 `@lang` 而不是 `@trans`
- 每个模块遵循一致的结构，包含 app/、docs/、tests/ 等

## 性能优化

### 内存优化
- 使用 `php artisan filament:optimize-memory` 命令优化 Filament 内存使用
- 包含分析功能，可识别模型中的过度 eager loading、重资源组件等
- 提供缓存清理和自动优化功能

### 前端构建
- 使用 Vite 进行现代化前端构建
- 集成 TailwindCSS 作为 CSS 框架
- 包含 Laravel Vite 插件以简化资源管理

## 质量保证

### 静态分析
- **PHPStan**：Level Max 类型安全分析（配置在 `phpstan.neon`）
- **PHPMD**：代码质量和设计规则检查
- **PHPInsights**：全面代码质量分析
- **Rector**：自动化重构和现代化

### 测试
- **Pest**：主要测试框架
- **单元/功能/集成测试**：全面测试覆盖
- **模块化测试**：每个模块包含独立的测试套件

### 开发命令
```bash
# 开发环境启动
cd laravel && composer run dev

# 测试
cd laravel && composer run test
# 或
cd laravel && php artisan test

# PHPStan 分析
cd laravel && ./vendor/bin/phpstan analyse --memory-limit=-1

# Rector 重构
cd laravel && ./vendor/bin/rector process --dry-run
cd laravel && ./vendor/bin/rector process

# PHP Insights 质量分析
cd laravel && ./vendor/bin/phpinsights -n --min-quality=90

# 内存优化
cd laravel && php artisan filament:optimize-memory --analyze
```

## 完整开发工作流

### 1. 分析和纠正
```bash
# 执行 PHPStan 分析
cd laravel && ./vendor/bin/phpstan analyse --memory-limit=-1
```

### 2. 应用纠正
- 使用 Webmozart Assert 验证 array/string/int/iterable/object
- 使用集中化转换：`Modules\Xot\Actions\Cast\Safe*CastAction`
- 为 Eloquent relations/collections 使用 PHPDoc generics
- 避免在 mixed 上访问属性/方法：先检查类型
- 对于 Filament：将预期类型的数组传递给 `schema()` 和组件

### 3. 文档更新
- 每次纠正后更新 `Modules/{Modulo}/docs/`
- 记录修改、模式和决策

### 4. 验证和重复
- 重新运行 PHPStan 直到达到 0 错误
- 对于大批量：使用 Rector（dry-run 然后 apply）

## 文档结构

### 🚨 必要文档（首先阅读）
- `docs/README.md` - 项目完整文档
- `docs/critical_rules/` - 关键开发规则

### 🏗️ 架构文档
- `docs/architecture/` - 系统架构、设计模式和扩展规则
- Filament 扩展模式、模块图标系统、命名空间约定

### 🛡️ 错误预防文档
- `docs/error-prevention/` - 关键错误模式分析和预防策略
- XotBase 实现模式、方法签名错误预防、抽象方法要求

### 🎯 开发模式
- `docs/patterns/` - 可重用代码模式和最佳实践
- Actions、队列操作、语言文件模式、模块配置模式

### 🔬 代码质量文档
- `docs/code-quality/` - PHPStan、测试和代码质量指南
- PHPStan level Max 合规指南、错误分析和解决方案

## 部署

### 环境配置
- 生产环境：针对性能和安全优化
- 开发环境：启用调试模式和详细日志

### 部署步骤
1. 运行 `composer install --optimize-autoloader --no-dev`
2. 运行 `npm run build`
3. 清除和缓存配置
4. 运行迁移
5. 设置正确的文件权限

## 贡献指南

1. 遵循既定的代码标准和架构规则
2. 为新功能编写全面的测试
3. 对重要更改更新文档
4. 使用约定式提交格式
5. 确保 PHPStan level Max 合规
6. 维护模块化架构完整性

## 质量门禁工作流 (Quality Gates Workflow)

在每次代码更改后（包括文档更新），必须从 `/laravel` 目录执行以下步骤以保持项目质量标准：

### 1. PHPStan 分析
```bash
./vendor/bin/phpstan analyse --memory-limit=-1
```
- 使用根目录的 `phpstan.neon` 配置文件
- 不要使用 `--level` 参数覆盖
- 确保达到 Max Level 合规

### 2. PHPMD 分析
```bash
./vendor/bin/phpmd Modules text cleancode,codesize,controversial,design,naming,unusedcode
```

### 3. PHP Insights 分析
```bash
./vendor/bin/phpinsights -n Modules
```

**注意**: 在执行质量门禁检查之前，始终确保文档已更新，这是项目"先文档后代码"原则的一部分。

## 许可证

本项目是专有软件。保留所有权利。

## 🚨 NEW MEMORIES - 2026-02-08

### Frontend Architecture - Folio + Volt Only

**CRITICAL**: MAI usare Controllers per il frontend. Usare SEMPRE Folio + Volt.

```blade
<!-- ✅ CORRETTO - Folio Page -->
<!-- resources/views/pages/chi-si-siamo.blade.php -->
<x-page side="content" slug="about">
    <h1>Chi Siamo</h1>
</x-page>

<!-- ❌ SBAGLIATO - Controller -->
<!-- app/Http/Controllers/PagesController.php -->
class PagesController extends Controller {
    public function about() { ... }
}
```

**DOCUMENTAZIONE**: [docs/critical-frontend-rules.md](docs/critical-frontend-rules.md)

### Blade Type Safety - htmlspecialchars() Error

**CRITICAL**: Mai passare array a `{{ }}` in Blade.

```blade
<!-- ❌ SBAGLIATO -->
{{ $item['label'] }}  // Errore se array

<!-- ✅ CORRETTO -->
{{ $item['label'] ?? '' }}  // Type-safe
```

**LESSON**: Il pattern `is_array()` check è error-prone. Usare sempre struttura dati consistente con null coalescing.

### WCAG Contrast Requirements

**CRITICAL**: Calcolare sempre il rapporto di contrasto prima di scegliere colori.

```css
/* ❌ SBAGLIATO - 4.2:1 (sotto AA) */
text-gray-400 su #0F3460

/* ✅ CORRETTO - 6:1 (AA) */
text-gray-200 su #0F3460

/* ✅ CORRETTO - 7:1 (AAA) */
text-gray-100 su #0F3460
```

### Component Validation Before JSON Definition

**CRITICAL**: SEMPRE verificare esistenza componenti prima di definire blocchi JSON.

```bash
# Script di verifica
for view in "pub_theme::components.blocks.hero.about"; do
    view_path=$(echo $view | sed 's/pub_theme::/laravel\/Themes\/Two\/resources\/views\//g')
    if [ ! -f "$view_path" ]; then
        echo "❌ Missing: $view_path"
    fi
done
```

### Git Workflow - Commit Frequently

**CRITICAL**: MAI aspettare perfezione assoluta. Commit frequentemente quando stabile.

```bash
# Workflow standard
1. Implementazione
2. Test verifica
3. Se OK → git add .
4. git commit -m "feat: descrizione"
5. git push
```

**DOCUMENTAZIONE**: [docs/continuous-improvement-lessons.md](docs/continuous-improvement-lessons.md)

### Cache Clearing Strategy

**CRITICAL**: Dopo ogni modifica al codice frontend, pulire tutte le cache.

```bash
cd /var/www/_bases/base_techplanner_fila5/laravel
rm -rf bootstrap/cache/*
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

本项目是专有软件。保留所有权利。
**LESSON**: SerializableClosure errors sono spesso causati da cache corrotte.