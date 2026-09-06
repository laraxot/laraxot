<?php

declare(strict_types=1);

namespace Modules\Xot\Filament\Resources\Tables;

use Filament\Tables\Columns\Column;
use Filament\Tables\Table;
use Modules\Xot\Filament\Traits\HasXotTable;
use Modules\Xot\Filament\Traits\TransTrait;
use Webmozart\Assert\Assert;

/**
 * @property string|null $tableSearch Fornita a runtime da chi consuma HasXotTable
 *                                    in un contesto Livewire (mai dichiarata qui
 *                                    per non ricreare il conflitto di composizione
 *                                    risolto in HasXotTable).
 */
abstract class XotBaseResourceTable
{
    use HasXotTable;
    use TransTrait;

    public static function configure(Table $table): Table
    {
        if (static::class === self::class) {
            throw new \LogicException('XotBaseResourceTable::configure() must be called on a concrete table class.');
        }

        $instance = app(static::class);
        Assert::isInstanceOf($instance, self::class);

        return $instance->table($table);
    }

    /**
     * @return array<int|string, Column>
     */
    abstract public function getTableColumns(): array;
}
