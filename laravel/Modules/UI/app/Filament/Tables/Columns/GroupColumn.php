<?php

declare(strict_types=1);

namespace Modules\UI\Filament\Tables\Columns;

use Filament\Tables\Columns\Column;

class GroupColumn extends Column
{
    /** @var array<int|string, mixed> */
    public array $form = [];

    /**
     * @var array<Column>
     */
    protected array $schema = [];

    protected string $view = 'ui::filament.tables.columns.group';

    /**
     * Initialize the component.
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Component initialization logic
    }

    /**
     * @return array<Column>
     */
    public function getFields(): array
    {
        return $this->schema;
    }

    /**
<<<<<<< HEAD
     * @param  array<int|string, mixed>  $form
=======
     * @param array<int|string, mixed> $form
>>>>>>> f5ffd8b4 (.)
     */
    public function schema(array $form): static
    {
        // Type-check to ensure all elements are Column instances
        $filtered = array_filter($form, function (mixed $item): bool {
            return $item instanceof Column;
        });

        /** @var array<int, Column> $filteredValues */
        $filteredValues = array_values($filtered);
        $this->schema = $filteredValues;

        return $this;
    }
<<<<<<< HEAD

    public function table(?Table $table): static
    {
        parent::table($table);
        $this->mountChildrenToTable($table);

        return $this;
    }

    private function mountChildrenToTable(?Table $table): void
    {
        if ($table === null) {
            return;
        }

        foreach ($this->schema as $child) {
            $child->table($table);
        }
    }
=======
>>>>>>> f5ffd8b4 (.)
}
