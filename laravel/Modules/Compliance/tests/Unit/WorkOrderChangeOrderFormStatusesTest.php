<?php

declare(strict_types=1);

namespace Modules\Compliance\Tests\Unit;

use Modules\Compliance\Filament\Resources\WorkOrderChangeOrderResource\Schemas\WorkOrderChangeOrderForm;
use Modules\Compliance\Tests\TestCase;

uses(TestCase::class);

/*
|--------------------------------------------------------------------------
| WorkOrderChangeOrderForm::statuses()
|--------------------------------------------------------------------------
|
| statuses() feeds both the status Select on the form and the status
| SelectFilter on the table, so it is the single source of truth for the
| WorkOrderChangeOrder lifecycle labels. It used to live as a private
| method on WorkOrderChangeOrderResource, which made it reachable only
| from within the Resource itself even though the Form/Table classes
| already called it across a namespace boundary (a PHPStan
| staticMethod.private violation). Moved to the Form as public static,
| the same pattern already used by ComplianceDocumentForm.
*/

test('statuses returns a label for every WorkOrderChangeOrder lifecycle status', function (): void {
    $statuses = WorkOrderChangeOrderForm::statuses();

    expect($statuses)->toBe([
        'proposed' => 'Proposta',
        'pending_approval' => 'Da approvare',
        'approved' => 'Approvata',
        'executed' => 'Eseguita',
        'billed' => 'Fatturata',
        'rejected' => 'Respinta',
    ]);
});

test('statuses keys match the values accepted by the status column', function (): void {
    $statuses = WorkOrderChangeOrderForm::statuses();

    expect(array_keys($statuses))
        ->toBe(['proposed', 'pending_approval', 'approved', 'executed', 'billed', 'rejected'])
        ->and($statuses)->each->toBeString();
});
