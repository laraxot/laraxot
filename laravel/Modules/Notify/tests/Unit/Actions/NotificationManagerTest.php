<?php

declare(strict_types=1);

namespace Modules\Notify\Tests\Unit\Actions;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Mockery;
use Modules\Notify\Actions\NotificationManager;
use Modules\Notify\Actions\SendNotificationAction;
use Modules\Notify\Models\NotificationTemplate;

function actionsNotificationManagerRecipient(): Model
{
    return new class extends Model
    {
        protected $guarded = [];

        public $timestamps = false;
    };
}

afterEach(function (): void {
    Mockery::close();
});

it('can send notification to single recipient', function (): void {
    $notificationManager = new NotificationManager;
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

    $notificationManager->send($recipient, $templateCode, $data, $channels, $options);
});

it('can send notification to multiple recipients', function (): void {
    $notificationManager = new NotificationManager;
    $recipients = [
        actionsNotificationManagerRecipient(),
        actionsNotificationManagerRecipient()];
    $templateCode = 'test_template';
    $data = ['key' => 'value'];
    $channels = ['email'];
    $options = ['priority' => 'high'];

    $template = typedMock(NotificationTemplate::class);
    mockExpectation($template, 'getAttribute')->with('code')->andReturn($templateCode);

    $action = typedMock(SendNotificationAction::class);
    mockExpectation($action, 'handle')->times(2);

    app()->instance(SendNotificationAction::class, $action);

    $result = $notificationManager->sendMultiple($recipients, $templateCode, $data, $channels, $options);

    expect($result)->toHaveCount(2);
});

it('can get template by code', function (): void {
    $notificationManager = new NotificationManager;
    $code = 'test_template';

    $template = typedMock(NotificationTemplate::class);
    mockExpectation($template, 'getAttribute')->with('code')->andReturn($code);
    mockExpectation($template, 'getAttribute')->with('is_active')->andReturn(true);

    $result = $notificationManager->getTemplate($code);

    expect($result)->toBeNull();
});

it('can get templates by category', function (): void {
    $notificationManager = new NotificationManager;
    $category = 'test_category';

    $result = $notificationManager->getTemplatesByCategory($category);

    expect($result)->toHaveCount(0);
});

it('throws exception when template not found', function (): void {
    $notificationManager = new NotificationManager;
    $recipient = actionsNotificationManagerRecipient();
    $templateCode = 'invalid_template';

    expect(fn () => $notificationManager->send($recipient, $templateCode))
        ->toThrow(Exception::class, 'Template not found: invalid_template');
});

it('returns array from send method', function (): void {
    $notificationManager = new NotificationManager;
    $recipient = actionsNotificationManagerRecipient();
    $templateCode = 'test_template';

    $action = typedMock(SendNotificationAction::class);
    mockExpectation($action, 'handle')->once();

    app()->instance(SendNotificationAction::class, $action);

    $notificationManager->send($recipient, $templateCode);
});

it('returns array from send multiple method', function (): void {
    $notificationManager = new NotificationManager;
    $recipients = [actionsNotificationManagerRecipient()];
    $templateCode = 'test_template';

    $action = typedMock(SendNotificationAction::class);
    mockExpectation($action, 'handle')->once();

    app()->instance(SendNotificationAction::class, $action);

    $result = $notificationManager->sendMultiple($recipients, $templateCode);

    expect($result)->toHaveCount(1);
});
