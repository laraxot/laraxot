---
title: "Xot Module Quick Start"
type: guide
tags: [xot, framework]
created: 2026-07-28
updated: 2026-07-28
---

# Xot Module — Quick Start

## Use BaseModel

```php
use Modules\Xot\Models\XotBaseModel;

class MyModel extends XotBaseModel {
    // Automatically: UUID PK, timestamps, soft delete support
}
```

## Create Migration

```php
use Modules\Xot\Database\Migration\XotBaseMigration;

class CreateMyTable extends XotBaseMigration {
    public function getTableName(): string {
        return 'my_table';
    }

    public function up() {
        Schema::create($this->table, function (Blueprint $table) {
            $this->addCommonFields($table);
            // Add custom fields
        });
    }
}
```
