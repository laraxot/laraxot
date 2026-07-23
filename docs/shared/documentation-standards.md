# TechPlanner 文档标准

## 📋 概述

本文档定义了 TechPlanner 项目的文档标准，确保所有模块和主题的文档保持一致性、可维护性和高质量。

## 🎯 文档原则

### 1. 一致性原则
- 所有文档遵循相同的结构和格式
- 统一的命名规范
- 一致的链接策略

### 2. 可维护性原则
- 避免重复内容
- 使用共享文档库
- 自动化质量检查

### 3. 可访问性原则
- 清晰的导航结构
- 相对链接优先
- 多语言支持

## 📁 标准目录结构

```
docs/
├── README.md                    # 模块/主题概览（必需）
├── CHANGELOG.md                 # 变更日志（可选）
├── architecture/                # 架构文档
│   ├── overview.md              # 架构概览
│   ├── patterns.md              # 设计模式
│   ├── decisions.md             # 架构决策
│   └── components.md            # 组件架构
├── best-practices/              # 最佳实践
│   ├── coding.md                # 编码规范
│   ├── testing.md               # 测试指南
│   ├── deployment.md            # 部署指南
│   └── performance.md           # 性能优化
├── filament/                    # Filament 集成
│   ├── resources.md             # 资源指南
│   ├── widgets.md               # 组件开发
│   ├── forms.md                 # 表单构建
│   └── pages.md                 # 页面开发
├── phpstan/                     # 代码质量
│   ├── compliance.md            # 合规指南
│   ├── fixes.md                 # 常见修复
│   └── level-10.md              # Level 10 指南
├── testing/                     # 测试文档
│   ├── unit.md                  # 单元测试
│   ├── feature.md               # 功能测试
│   └── integration.md           # 集成测试
├── troubleshooting/             # 故障排除
│   ├── common-issues.md         # 常见问题
│   ├── debugging.md             # 调试指南
│   └── faq.md                   # 常见问答
└── examples/                    # 示例代码
    ├── basic-usage.md           # 基础用法
    ├── advanced-features.md     # 高级功能
    └── integrations.md          # 集成示例
```

## 📝 文件命名规范

### 基本规则
- 使用小写字母
- 单词用连字符分隔
- 使用有意义的描述性名称
- 避免特殊字符和空格

### 正确示例
```
✅ user-management.md
✅ phpstan-compliance.md
✅ filament-integration.md
✅ best-practices.md
```

### 错误示例
```
❌ UserManagement.md
❌ phpStan_compliance.md
❌ Filament Integration.md
❌ best practices.md
```

### 例外情况
- `README.md` - 目录概览文件（大写）
- `CHANGELOG.md` - 变更日志文件（大写）

## 🔗 链接规范

### 相对链接优先
```markdown
✅ 正确
[架构概览](architecture/overview.md)
[PHPStan 合规](phpstan/compliance.md)
[测试指南](testing/unit.md)

❌ 错误
[架构概览](/var/www/project/docs/architecture/overview.md)
[架构概览](https://example.com/docs/architecture/overview.md)
```

### 锚点链接
```markdown
<!-- 定义锚点 -->
## 安装指南 {#installation}

<!-- 引用锚点 -->
[查看安装指南](#installation)
[其他文件的安装指南](../user-guide/#installation)
```

### 外部链接
```markdown
<!-- 使用有意义的描述 -->
[Laravel 官方文档](https://laravel.com/docs)
[Filament 文档](https://filamentphp.com/docs)

<!-- 避免裸 URL -->
❌ https://laravel.com/docs
```

## 🌐 多语言支持

### 语言策略
- **主语言**: 意大利语（it）
- **支持语言**: 英语（en）、德语（de）
- **翻译文件**: 必须包含目标语言内容

### 翻译文件结构
```
lang/
├── it/
│   ├── messages.php
│   └── validation.php
├── en/
│   ├── messages.php
│   └── validation.php
└── de/
    ├── messages.php
    └── validation.php
```

### 翻译规范
```php
// ✅ 正确 - 包含目标语言内容
'it' => [
    'user.name' => 'Nome Utente',
    'user.email' => 'Email Utente',
],

'en' => [
    'user.name' => 'User Name',
    'user.email' => 'User Email',
],

'de' => [
    'user.name' => 'Benutzername',
    'user.email' => 'Benutzer-E-Mail',
],
```

## 📄 文档模板

### README.md 模板
```markdown
# [模块名称]

## 概述
[模块的简要描述和用途]

## 功能特性
- [特性 1]
- [特性 2]
- [特性 3]

## 安装
[安装说明]

## 使用指南
[基本使用方法]

## API 文档
[API 链接或说明]

## 测试
[测试相关说明]

## 贡献
[贡献指南]

## 许可证
[许可证信息]
```

### 架构文档模板
```markdown
# [主题] 架构

## 概述
[架构设计的目标和原则]

## 组件结构
[组件之间的关系和依赖]

## 数据流
[数据如何在组件间流动]

## 设计决策
[重要的架构决策及其原因]

## 扩展点
[如何扩展架构]

## 性能考虑
[性能相关的设计决策]
```

## 📊 质量标准

### 内容要求
- [ ] 内容准确且最新
- [ ] 包含代码示例
- [ ] 有清晰的导航
- [ ] 没有断链
- [ ] 格式一致

### 格式要求
- [ ] 使用标准 Markdown
- [ ] 正确的标题层级
- [ ] 代码块语法高亮
- [ ] 表格格式正确
- [ ] 列表格式一致

### 技术要求
- [ ] 所有链接有效
- [ ] 图片正确加载
- [ ] 代码示例可运行
- [ ] 命令行示例正确
- [ ] 版本信息准确

## 🛠️ 自动化工具

### 链接检查器
```bash
#!/bin/bash
# docs-link-checker.sh

find docs -name "*.md" -exec markdown-link-check {} \;
```

### 格式验证器
```bash
#!/bin/bash
# docs-format-validator.sh

# 检查文件命名
find docs -name "*.md" | grep -E "[A-Z]" && echo "发现大写文件名" || echo "命名规范正确"

# 检查链接格式
grep -r "](/" docs/ && echo "发现绝对链接" || echo "链接格式正确"
```

### 重复检测器
```bash
#!/bin/bash
# docs-duplicate-detector.sh

# 检测重复内容
find docs -name "*.md" -exec md5sum {} \; | sort | uniq -d -w32
```

## 🔄 维护流程

### 1. 创建文档
1. 使用标准模板
2. 遵循命名规范
3. 添加到导航结构
4. 运行质量检查

### 2. 更新文档
1. 检查内容准确性
2. 更新版本信息
3. 验证链接有效性
4. 运行自动化测试

### 3. 审查文档
1. 内容审查
2. 格式检查
3. 链接验证
4. 同行评审

## 📈 质量指标

### 完整性指标
- 文档覆盖率：目标 90%
- API 文档完整性：目标 100%
- 示例代码覆盖率：目标 80%

### 可用性指标
- 链接有效率：目标 100%
- 搜索命中率：目标 85%
- 用户满意度：目标 4.5/5

### 维护性指标
- 更新频率：每周至少一次
- 过期内容比例：目标 < 5%
- 重复内容比例：目标 < 10%

## 🚀 最佳实践

### 1. 内容组织
- 使用逻辑清晰的目录结构
- 提供多种导航方式
- 包含目录和索引
- 使用标签和分类

### 2. 写作风格
- 使用简洁明了的语言
- 避免技术术语的过度使用
- 提供上下文和背景
- 使用主动语态

### 3. 代码示例
- 提供完整可运行的示例
- 包含必要的注释
- 展示常见用例
- 包含错误处理

### 4. 版本管理
- 标记文档版本
- 记录变更历史
- 提供迁移指南
- 维护多版本支持

## 📚 相关资源

- [Markdown 语法指南](https://www.markdownguide.org/basic-syntax/)
- [技术写作最佳实践](https://developers.google.com/tech-writing)
- [文档驱动开发](https://documentation.divio.com/)
- [API 文档规范](https://github.com/microsoft/api-guidelines/blob/master/Guidelines.md)

---

**维护者**: TechPlanner 文档团队  
**最后更新**: 2025-12-12  
**版本**: 1.0