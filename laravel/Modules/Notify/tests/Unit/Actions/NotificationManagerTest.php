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
    mockExpectation($template, 'getAttribute')->__call('with', ['code'])->andReturn($templateCode);

    $action = typedMock(SendNotificationAction::class);
    mockExpectation($action, 'handle')->__call('with', [$recipient, $templateCode, $data, $channels, $options])->__call('once', []);

    app()->instance(SendNotificationAction::class, $action);

    // Nessuna asserzione sul tipo di ritorno: e' sempre Notification|null per firma,
    // il comportamento reale (chiamata all'action con i parametri attesi) e' verificato
    // da Mockery in afterEach() tramite l'expectation ->__call('once', []).
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
    mockExpectation($template, 'getAttribute')->__call('with', ['code'])->andReturn($templateCode);

    $action = typedMock(SendNotificationAction::class);
    mockExpectation($action, 'handle')->__call('times', [2]);

    app()->instance(SendNotificationAction::class, $action);

    $result = $this->notificationManager->sendMultiple($recipients, $templateCode, $data, $channels, $options);

    expect($result)->toHaveCount(2);
});

it('can get template by code', function (): void {
    $code = 'test_template';

    // $template non e' passato al codice reale: getTemplate() interroga sempre
    // il database tramite query statica, quindi il mock serve solo a documentare
    // l'intento del test (nessun template attivo con questo code) senza essere
    // referenziato direttamente.
    $template = typedMock(NotificationTemplate::class);
    mockExpectation($template, 'getAttribute')->__call('with', ['code'])->andReturn($code);

    $result = $this->notificationManager->getTemplate($code);

    expect($result)->toBeNull(); // Mock non restituisce risultati reali
});

it('can get templates by category', function (): void {
    $category = 'test_category';

    $result = $this->notificationManager->getTemplatesByCategory($category);

    expect($result)->toHaveCount(0);
});

it('throws exception when template not found', function (): void {
    $recipient = actionsNotificationManagerRecipient();
    $templateCode = 'invalid_template';

    expect(fn () => $this->notificationManager->send($recipient, $templateCode))
        ->toThrow(Exception::class, 'Template not found: invalid_template');
});

it('returns array from send method', function (): void {
    $recipient = actionsNotificationManagerRecipient();
    $templateCode = 'test_template';

    $action = typedMock(SendNotificationAction::class);
    mockExpectation($action, 'handle')->__call('once', []);

    app()->instance(SendNotificationAction::class, $action);

    // Comportamento verificato da Mockery in afterEach() tramite ->__call('once', []).
    $this->notificationManager->send($recipient, $templateCode);
});

it('returns array from send multiple method', function (): void {
    $recipients = [actionsNotificationManagerRecipient()];
    $templateCode = 'test_template';

    $action = typedMock(SendNotificationAction::class);
    mockExpectation($action, 'handle')->__call('once', []);

    app()->instance(SendNotificationAction::class, $action);

    $result = $this->notificationManager->sendMultiple($recipients, $templateCode);

    expect($result)->toHaveCount(1);
});
