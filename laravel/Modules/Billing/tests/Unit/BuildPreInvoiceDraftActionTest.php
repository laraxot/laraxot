<?php

declare(strict_types=1);

use Modules\Billing\Actions\BuildPreInvoiceDraftAction;
use Modules\Billing\Datas\PreInvoiceLine;
use Modules\Billing\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(\Modules\Billing\Tests\TestCase::class);

it('sums line amounts into the draft total', function (): void {
    $action = new BuildPreInvoiceDraftAction();

    $draft = $action->execute([
        new PreInvoiceLine(description: 'Manodopera', amount: 100.0, sourceCode: PreInvoiceLine::SOURCE_LABOR),
        new PreInvoiceLine(description: 'Materiali', amount: 50.5, sourceCode: PreInvoiceLine::SOURCE_MATERIAL),
    ]);

    Assert::assertSame(150.5, $draft->total);
    Assert::assertCount(2, $draft->lines);
    Assert::assertFalse($draft->hasUndecidedExtras());
});

it('flags undecided quote extras via the pending total', function (): void {
    $action = new BuildPreInvoiceDraftAction();

    $draft = $action->execute([], pendingExtraTotal: 25.0);

    Assert::assertTrue($draft->hasUndecidedExtras());
});
