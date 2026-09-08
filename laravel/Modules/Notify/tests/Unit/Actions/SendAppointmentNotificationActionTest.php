<?php

declare(strict_types=1);

namespace Modules\Notify\Tests\Unit\Actions;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Modules\Notify\Actions\SendAppointmentNotificationAction;
use PHPUnit\Framework\Assert;

function sendAppointmentNotificationTestModel(int $patientId = 1): Model
{
    $appointment = new class extends Model
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
