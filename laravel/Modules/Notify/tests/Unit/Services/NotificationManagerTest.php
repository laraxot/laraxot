<?php

declare(strict_types=1);

namespace Modules\Notify\Tests\Unit\Services;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Mockery;
use Modules\Notify\Services\NotificationManager;
use Modules\Notify\Tests\TestCase;
use PHPUnit\Framework\Assert;

/**
 * Unit test del NotificationManager.
 *
 * Perché: il manager è una facciata tipizzata su template + SendNotificationAction.
 * Qui si verifica il contratto senza seed DB (template assente → null/exception/collection vuota).
 */
class NotificationManagerTest extends TestCase
{
    private NotificationManager $serviceManager;

    protected function setUp(): void
    {
        parent::setUp();
        $this->serviceManager = new NotificationManager();
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /** @test */
    public function it_throws_exception_when_template_not_found(): void
    {
        $recipient = $this->recipient();

        try {
            $this->serviceManager->send($recipient, 'invalid_template');
            Assert::fail('Expected Exception was not thrown');
        } catch (Exception $exception) {
            Assert::assertSame('Template not found: invalid_template', $exception->getMessage());
        }
    }

    /** @test */
    public function it_can_get_template_by_code_returns_null_when_missing(): void
    {
        Assert::assertNull($this->serviceManager->getTemplate('test_template'));
    }

    /** @test */
    public function it_can_get_templates_by_category_returns_empty_collection(): void
    {
        $result = $this->serviceManager->getTemplatesByCategory('test_category');

        Assert::assertCount(0, $result);
    }

    private function recipient(): Model
    {
        return new class() extends Model
        {
            /** @var list<string> */
            protected $guarded = [];

            public $timestamps = false;
        };
    }
}
