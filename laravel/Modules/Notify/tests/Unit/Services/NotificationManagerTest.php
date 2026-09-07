<?php

declare(strict_types=1);

namespace Modules\Notify\Tests\Unit\Services;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Mockery;
<<<<<<< HEAD
<<<<<<< HEAD
=======
use Mockery\MockInterface;
use Modules\Notify\Actions\SendNotificationAction;
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
use Modules\Notify\Services\NotificationManager;
use Modules\Notify\Tests\TestCase;
use PHPUnit\Framework\Assert;

<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
/**
 * Unit test del NotificationManager.
 *
 * Perché: il manager è una facciata tipizzata su template + SendNotificationAction.
 * Qui si verifica il contratto senza seed DB (template assente → null/exception/collection vuota).
 */
class NotificationManagerTest extends TestCase
{
    private NotificationManager $serviceManager;
<<<<<<< HEAD
=======
class NotificationManagerTest extends TestCase
{
    private NotificationManager $serviceNotificationManager;
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)

    protected function setUp(): void
    {
        parent::setUp();
<<<<<<< HEAD
<<<<<<< HEAD
        $this->serviceManager = new NotificationManager();
=======
        $this->serviceNotificationManager = new NotificationManager;
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
        $this->serviceManager = new NotificationManager();
>>>>>>> a988596b (first)
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /** @test */
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
    public function it_throws_exception_when_template_not_found(): void
    {
        $recipient = $this->recipient();

        try {
            $this->serviceManager->send($recipient, 'invalid_template');
<<<<<<< HEAD
=======
    public function it_can_send_notification_to_single_recipient(): void
    {
        $recipient = $this->recipient();
        $templateCode = 'test_template';
        $data = ['key' => 'value'];
        $channels = ['email'];
        $options = ['priority' => 'high'];

        $action = $this->mockSendNotificationAction();
        $action->shouldReceive('handle')
            ->once()
            ->with($recipient, $templateCode, $data, $channels, $options);

        $this->instance(SendNotificationAction::class, $action);

        $this->serviceNotificationManager->send($recipient, $templateCode, $data, $channels, $options);
    }

    /** @test */
    public function it_can_send_notification_to_multiple_recipients(): void
    {
        $recipients = [
            $this->recipient(),
            $this->recipient(),
        ];
        $templateCode = 'test_template';
        $data = ['key' => 'value'];
        $channels = ['email'];
        $options = ['priority' => 'high'];

        $action = $this->mockSendNotificationAction();
        $action->shouldReceive('handle')->times(2);

        $this->instance(SendNotificationAction::class, $action);

        $result = $this->serviceNotificationManager->sendMultiple($recipients, $templateCode, $data, $channels, $options);

        $this->assertCount(2, $result);
    }

    /** @test */
    public function it_can_get_template_by_code(): void
    {
        $code = 'test_template';

        $result = $this->serviceNotificationManager->getTemplate($code);

        $this->assertNull($result);
    }

    /** @test */
    public function it_can_get_templates_by_category(): void
    {
        $category = 'test_category';

        $result = $this->serviceNotificationManager->getTemplatesByCategory($category);

        $this->assertCount(0, $result);
    }

    /** @test */
    public function it_throws_exception_when_template_not_found(): void
    {
        $recipient = $this->recipient();
        $templateCode = 'invalid_template';

        try {
            $this->serviceNotificationManager->send($recipient, $templateCode);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
            Assert::fail('Expected Exception was not thrown');
        } catch (Exception $exception) {
            Assert::assertSame('Template not found: invalid_template', $exception->getMessage());
        }
    }

    /** @test */
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
    public function it_can_get_template_by_code_returns_null_when_missing(): void
    {
        Assert::assertNull($this->serviceManager->getTemplate('test_template'));
    }

    /** @test */
    public function it_can_get_templates_by_category_returns_empty_collection(): void
    {
        $result = $this->serviceManager->getTemplatesByCategory('test_category');

        Assert::assertCount(0, $result);
<<<<<<< HEAD
=======
    public function it_returns_array_from_send_method(): void
    {
        $recipient = $this->recipient();
        $templateCode = 'test_template';

        $action = $this->mockSendNotificationAction();
        $action->shouldReceive('handle')->once();

        $this->instance(SendNotificationAction::class, $action);

        $this->serviceNotificationManager->send($recipient, $templateCode);
    }

    /** @test */
    public function it_returns_array_from_send_multiple_method(): void
    {
        $recipients = [$this->recipient()];
        $templateCode = 'test_template';

        $action = $this->mockSendNotificationAction();
        $action->shouldReceive('handle')->once();

        $this->instance(SendNotificationAction::class, $action);

        $result = $this->serviceNotificationManager->sendMultiple($recipients, $templateCode);

        $this->assertCount(1, $result);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
    }

    private function recipient(): Model
    {
<<<<<<< HEAD
<<<<<<< HEAD
        return new class() extends Model
        {
            /** @var list<string> */
=======
        return new class extends Model
        {
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
        return new class() extends Model
        {
            /** @var list<string> */
>>>>>>> a988596b (first)
            protected $guarded = [];

            public $timestamps = false;
        };
    }
<<<<<<< HEAD
<<<<<<< HEAD
=======

    /**
     * @return MockInterface&SendNotificationAction
     */
    private function mockSendNotificationAction(): MockInterface
    {
        /** @var MockInterface&SendNotificationAction $mock */
        $mock = Mockery::mock(SendNotificationAction::class);

        return $mock;
    }
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
}
