<?php

declare(strict_types=1);

namespace Modules\Notify\Tests\Unit\Actions;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Mockery;
use Modules\Notify\Actions\NotificationManager;
use Modules\Notify\Actions\SendNotificationAction;
use Modules\Notify\Models\NotificationTemplate;
use Modules\Notify\Tests\TestCase;

uses(TestCase::class);

function actionsNotificationManagerRecipient(): Model
{
    return new class() extends Model
    {
        protected $guarded = [];

        public $timestamps = false;
    };
}

beforeEach(function (): void {
    $this->notificationManager = new NotificationManager();
});

afterEach(function (): void {
    Mockery::close();
});

it('can send notification to single recipient', function (): void {
    $recipient = actionsNotificationManagerRecipient();
    $templateCode = 'test_template';
    $data = ['key' => 'value'];
    $channels = ['email'];
    $options = ['priority' => 'high'];

    $template = typedMock(NotificationTemplate::class);
    mockExpectation($template, 'getAttribute')->with('code')->andReturn($templateCode);

    $action = typedMock(SendNotificationAction::class);
    mockExpectation($action, 'handle')
        ->with($recipient, $templateCode, $data, $channels, $options)
        ->once();

    app()->instance(SendNotificationAction::class, $action);

    $this->notificationManager->send($recipient, $templateCode, $data, $channels, $options);
});

it('can send notification to multiple recipients', function (): void {
    $recipients = [
        actionsNotificationManagerRecipient(),
        actionsNotificationManagerRecipient(),
    ];
    $templateCode = 'test_template';
    $data = ['key' => 'value'];
    $channels = ['email'];
    $options = ['priority' => 'high'];

    $template = typedMock(NotificationTemplate::class);
    mockExpectation($template, 'getAttribute')->with('code')->andReturn($templateCode);

    $action = typedMock(SendNotificationAction::class);
    mockExpectation($action, 'handle')->times(2);

    app()->instance(SendNotificationAction::class, $action);

    $result = $this->notificationManager->sendMultiple($recipients, $templateCode, $data, $channels, $options);

    expect($result)->toHaveCount(2);
});

it('can get template by code', function (): void {
    $code = 'test_template';

    $template = typedMock(NotificationTemplate::class);
    mockExpectation($template, 'getAttribute')->with('code')->andReturn($code);
    mockExpectation($template, 'getAttribute')->with('is_active')->andReturn(true);

    $result = $this->notificationManager->getTemplate($code);

    expect($result)->toBeNull();
});

it('can get templates by category', function (): void {
    $result = $this->notificationManager->getTemplatesByCategory('test_category');

    expect($result)->toHaveCount(0);
});

it('throws exception when template not found', function (): void {
    $recipient = actionsNotificationManagerRecipient();

    expect(fn () => $this->notificationManager->send($recipient, 'invalid_template'))
        ->toThrow(Exception::class, 'Template not found: invalid_template');
});

it('returns array from send method', function (): void {
    $recipient = actionsNotificationManagerRecipient();
    $templateCode = 'test_template';

    $action = typedMock(SendNotificationAction::class);
    mockExpectation($action, 'handle')->once();

    app()->instance(SendNotificationAction::class, $action);

    $this->notificationManager->send($recipient, $templateCode);
});

it('returns array from send multiple method', function (): void {
    $recipients = [actionsNotificationManagerRecipient()];
    $templateCode = 'test_template';

    $action = typedMock(SendNotificationAction::class);
    mockExpectation($action, 'handle')->once();

    app()->instance(SendNotificationAction::class, $action);

    $result = $this->notificationManager->sendMultiple($recipients, $templateCode);

    expect($result)->toHaveCount(1);
});
