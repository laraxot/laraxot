<?php

declare(strict_types=1);

namespace Modules\Xot\Tests\Unit;

use Illuminate\Database\Eloquent\Model;
use Modules\Xot\Actions\Mail\SendMailByRecordAction;
use Modules\Xot\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

<<<<<<< HEAD
/**
 * Relazione `myLogs()` del record: l'azione la usa solo dopo l'invio, il test deve fallire prima.
 */
final class SendMailRecordLogsFixture
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): void {}
}

=======
>>>>>>> 77b1e2748 (first)
it('throws if record has no email', function (): void {
    $record = new class extends Model
    {
        public function option(string $key): null
        {
            return null;
        }

        public function myLogs(): object
        {
<<<<<<< HEAD
            return new SendMailRecordLogsFixture;
=======
            return new class
            {
                /** @param array<string, mixed> $data */
                public function create(array $data): void {}
            };
>>>>>>> 77b1e2748 (first)
        }
    };

    try {
        app(SendMailByRecordAction::class)->execute($record, \stdClass::class);
        Assert::fail('Expected exception was not thrown.');
    } catch (\InvalidArgumentException $e) {
<<<<<<< HEAD
        Assert::assertSame('Model must have email property', $e->getMessage());
=======
        Assert::assertInstanceOf(\InvalidArgumentException::class, $e);
>>>>>>> 77b1e2748 (first)
    }
});
