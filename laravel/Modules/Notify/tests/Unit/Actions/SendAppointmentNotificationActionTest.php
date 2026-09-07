<?php

declare(strict_types=1);

namespace Modules\Notify\Tests\Unit\Actions;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Modules\Notify\Actions\SendAppointmentNotificationAction;
<<<<<<< HEAD
<<<<<<< HEAD
use PHPUnit\Framework\Assert;

=======
use Modules\Notify\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class)->group('notify-db');

>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
function sendAppointmentNotificationTestModel(int $patientId = 1): Model
{
    $appointment = new class extends Model
=======
use PHPUnit\Framework\Assert;

function sendAppointmentNotificationTestModel(int $patientId = 1): Model
{
    $appointment = new class() extends Model
>>>>>>> a988596b (first)
    {
        protected $guarded = [];

        public $timestamps = false;
    };
    $appointment->setAttribute('patient_id', $patientId);

    return $appointment;
}

test('send appointment notification returns false and logs info when models are missing', function () {
    Log::shouldReceive('info')->once();

    $result = app(SendAppointmentNotificationAction::class)->execute(
        appointment: sendAppointmentNotificationTestModel(),
        type: 'reminder',
    );

    Assert::assertFalse($result);
});
