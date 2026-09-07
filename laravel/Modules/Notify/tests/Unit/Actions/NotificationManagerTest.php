<?php

declare(strict_types=1);

namespace Modules\Notify\Tests\Unit\Actions;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Mockery;
<<<<<<< HEAD
<<<<<<< HEAD
use Modules\Notify\Actions\NotificationManager;
use Modules\Notify\Actions\SendNotificationAction;
use Modules\Notify\Models\NotificationTemplate;
=======
use Mockery\MockInterface;
use Modules\Notify\Actions\NotificationManager;
use Modules\Notify\Actions\SendNotificationAction;
use Modules\Notify\Tests\TestCase;

uses(TestCase::class)->group('notify-db');
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])

function actionsNotificationManagerRecipient(): Model
{
    return new class extends Model
=======
use Modules\Notify\Actions\NotificationManager;
use Modules\Notify\Actions\SendNotificationAction;
use Modules\Notify\Models\NotificationTemplate;

function actionsNotificationManagerRecipient(): Model
{
    return new class() extends Model
>>>>>>> a988596b (first)
    {
        protected $guarded = [];

        public $timestamps = false;
    };
}

<<<<<<< HEAD
<<<<<<< HEAD
=======
/**
 * @template T of object
 *
 * @param  class-string<T>  $class
 * @return MockInterface&T
 */
function actionsNotificationManagerMock(string $class): MockInterface
{
    /** @var MockInterface&T $mock */
    $mock = Mockery::mock($class);

    return $mock;
}

beforeEach(function (): void {
    $this->notificationManager = new NotificationManager;
});

>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
afterEach(function (): void {
    Mockery::close();
});

it('can send notification to single recipient', function (): void {
<<<<<<< HEAD
<<<<<<< HEAD
    $notificationManager = new NotificationManager;
=======
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
    $notificationManager = new NotificationManager();
>>>>>>> a988596b (first)
    $recipient = actionsNotificationManagerRecipient();
    $templateCode = 'test_template';
    $data = ['key' => 'value'];
    $channels = ['email'];
    $options = ['priority' => 'high'];

<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
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
<<<<<<< HEAD
    $notificationManager = new NotificationManager;
    $recipients = [
        actionsNotificationManagerRecipient(),
        actionsNotificationManagerRecipient()];
=======
    $action = actionsNotificationManagerMock(SendNotificationAction::class);
    $action->shouldReceive('handle')
        ->once()
        ->with($recipient, $templateCode, $data, $channels, $options);

    $this->instance(SendNotificationAction::class, $action);

    $this->notificationManager->send($recipient, $templateCode, $data, $channels, $options);
});

it('can send notification to multiple recipients', function (): void {
    $recipients = [
        actionsNotificationManagerRecipient(),
        actionsNotificationManagerRecipient(),
    ];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
    $notificationManager = new NotificationManager();
    $recipients = [
        actionsNotificationManagerRecipient(),
        actionsNotificationManagerRecipient()];
>>>>>>> a988596b (first)
    $templateCode = 'test_template';
    $data = ['key' => 'value'];
    $channels = ['email'];
    $options = ['priority' => 'high'];

<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
    $template = typedMock(NotificationTemplate::class);
    mockExpectation($template, 'getAttribute')->with('code')->andReturn($templateCode);

    $action = typedMock(SendNotificationAction::class);
    mockExpectation($action, 'handle')->times(2);

    app()->instance(SendNotificationAction::class, $action);

    $result = $notificationManager->sendMultiple($recipients, $templateCode, $data, $channels, $options);
<<<<<<< HEAD
=======
    $action = actionsNotificationManagerMock(SendNotificationAction::class);
    $action->shouldReceive('handle')->times(2);

    $this->instance(SendNotificationAction::class, $action);

    $result = $this->notificationManager->sendMultiple($recipients, $templateCode, $data, $channels, $options);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)

    expect($result)->toHaveCount(2);
});

it('can get template by code', function (): void {
<<<<<<< HEAD
<<<<<<< HEAD
    $notificationManager = new NotificationManager;
=======
    $notificationManager = new NotificationManager();
>>>>>>> a988596b (first)
    $code = 'test_template';

    $template = typedMock(NotificationTemplate::class);
    mockExpectation($template, 'getAttribute')->with('code')->andReturn($code);
    mockExpectation($template, 'getAttribute')->with('is_active')->andReturn(true);

    $result = $notificationManager->getTemplate($code);
<<<<<<< HEAD
=======
    $code = 'test_template';

    $result = $this->notificationManager->getTemplate($code);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)

    expect($result)->toBeNull();
});

it('can get templates by category', function (): void {
<<<<<<< HEAD
<<<<<<< HEAD
    $notificationManager = new NotificationManager;
    $category = 'test_category';

    $result = $notificationManager->getTemplatesByCategory($category);
=======
    $category = 'test_category';

    $result = $this->notificationManager->getTemplatesByCategory($category);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
    $notificationManager = new NotificationManager();
    $category = 'test_category';

    $result = $notificationManager->getTemplatesByCategory($category);
>>>>>>> a988596b (first)

    expect($result)->toHaveCount(0);
});

it('throws exception when template not found', function (): void {
<<<<<<< HEAD
<<<<<<< HEAD
    $notificationManager = new NotificationManager;
=======
    $notificationManager = new NotificationManager();
>>>>>>> a988596b (first)
    $recipient = actionsNotificationManagerRecipient();
    $templateCode = 'invalid_template';

    expect(fn () => $notificationManager->send($recipient, $templateCode))
<<<<<<< HEAD
=======
    $recipient = actionsNotificationManagerRecipient();
    $templateCode = 'invalid_template';

    expect(fn () => $this->notificationManager->send($recipient, $templateCode))
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
        ->toThrow(Exception::class, 'Template not found: invalid_template');
});

it('returns array from send method', function (): void {
<<<<<<< HEAD
<<<<<<< HEAD
    $notificationManager = new NotificationManager;
=======
    $notificationManager = new NotificationManager();
>>>>>>> a988596b (first)
    $recipient = actionsNotificationManagerRecipient();
    $templateCode = 'test_template';

    $action = typedMock(SendNotificationAction::class);
    mockExpectation($action, 'handle')->once();

    app()->instance(SendNotificationAction::class, $action);

    $notificationManager->send($recipient, $templateCode);
});

it('returns array from send multiple method', function (): void {
<<<<<<< HEAD
    $notificationManager = new NotificationManager;
=======
    $notificationManager = new NotificationManager();
>>>>>>> a988596b (first)
    $recipients = [actionsNotificationManagerRecipient()];
    $templateCode = 'test_template';

    $action = typedMock(SendNotificationAction::class);
    mockExpectation($action, 'handle')->once();

    app()->instance(SendNotificationAction::class, $action);

    $result = $notificationManager->sendMultiple($recipients, $templateCode);
<<<<<<< HEAD
=======
    $recipient = actionsNotificationManagerRecipient();
    $templateCode = 'test_template';

    $action = actionsNotificationManagerMock(SendNotificationAction::class);
    $action->shouldReceive('handle')->once();

    $this->instance(SendNotificationAction::class, $action);

    $this->notificationManager->send($recipient, $templateCode);
});

it('returns array from send multiple method', function (): void {
    $recipients = [actionsNotificationManagerRecipient()];
    $templateCode = 'test_template';

    $action = actionsNotificationManagerMock(SendNotificationAction::class);
    $action->shouldReceive('handle')->once();

    $this->instance(SendNotificationAction::class, $action);

    $result = $this->notificationManager->sendMultiple($recipients, $templateCode);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)

    expect($result)->toHaveCount(1);
});
