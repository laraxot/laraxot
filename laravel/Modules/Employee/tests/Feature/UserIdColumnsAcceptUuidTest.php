<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Modules\Employee\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

it('stores user ids as strings, not integers', function (): void {
    $columns = [
        ['absence_requests', 'decided_by_user_id'],
        ['absence_requests', 'user_id'],
        ['time_records', 'user_id'],
    ];

    $numeric = [];
    foreach ($columns as [$table, $column]) {
        Assert::assertTrue(
            Schema::hasColumn($table, $column),
            "La colonna {$table}.{$column} non esiste"
        );

        if (! in_array(Schema::getColumnType($table, $column), ['string', 'varchar', 'text'], true)) {
            $numeric[] = $table.'.'.$column;
        }
    }

    Assert::assertEquals([], $numeric, 'Colonne numeriche: un id UUID viene troncato');
});
