<?php

declare(strict_types=1);

namespace Modules\Notify\Tests\Unit\Filament\Actions;

use Closure;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Model;
use Modules\Notify\Actions\SendRecordsNotificationAction;
use Modules\Notify\Filament\Actions\SendRecordsNotificationBulkAction;
use Modules\Notify\Filament\Forms\Components\ChannelCheckboxList;
use Modules\Notify\Filament\Forms\Components\MailTemplateSelect;
use Modules\Notify\Tests\Fixtures\SendRecordsNotificationBulkActionSpy;
<<<<<<< HEAD
<<<<<<< HEAD
use Modules\Xot\Tests\XotBasePest;
use PHPUnit\Framework\Assert;
=======
use Modules\Notify\Tests\TestCase;
use PHPUnit\Framework\Assert;
use Modules\Xot\Tests\XotBasePest;

uses(TestCase::class)->group('no-notify-db');
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
use Modules\Xot\Tests\XotBasePest;
use PHPUnit\Framework\Assert;
>>>>>>> a988596b (first)

/**
 * @param  array<string, mixed>  $attributes
 */
function makeDummyNotifyBulkModel(array $attributes = []): Model
{
    return new class($attributes) extends Model
    {
        protected $guarded = [];

        /**
         * @param  array<string, mixed>  $attributes
         */
        public function __construct(array $attributes = [])
        {
            parent::__construct($attributes);
        }
    };
}

test('send records notification bulk action exposes expected schema components', function (): void {
    $action = SendRecordsNotificationBulkAction::make();
    $reflection = new \ReflectionClass(SendRecordsNotificationBulkAction::class);
    $prop = $reflection->getProperty('schema');
    $prop->setAccessible(true);
    $schemaResolver = $prop->getValue($action);

    if ($schemaResolver instanceof Closure) {
        $schema = $schemaResolver();
    } elseif (is_array($schemaResolver)) {
        $schema = $schemaResolver;
    } else {
        $schema = [];
    }

    $schema = XotBasePest::assertArray($schema);
    Assert::assertArrayHasKey('mail_template_slug', $schema);
    Assert::assertArrayHasKey('channels', $schema);
    Assert::assertInstanceOf(MailTemplateSelect::class, $schema['mail_template_slug']);
    Assert::assertInstanceOf(ChannelCheckboxList::class, $schema['channels']);
    Assert::assertSame('mail_template_slug', $schema['mail_template_slug']->getName());
    Assert::assertSame('channels', $schema['channels']->getName());
});

test('send records notification bulk action delegates to send records action', function (): void {
<<<<<<< HEAD
    $spy = new SendRecordsNotificationBulkActionSpy;
=======
    $spy = new SendRecordsNotificationBulkActionSpy();
>>>>>>> a988596b (first)
    app()->instance(SendRecordsNotificationAction::class, $spy);

    $action = SendRecordsNotificationBulkAction::make();
    $reflection = new \ReflectionClass(SendRecordsNotificationBulkAction::class);
    $prop = $reflection->getProperty('action');
    $prop->setAccessible(true);
    $callback = $prop->getValue($action);
    Assert::assertInstanceOf(Closure::class, $callback);

    $records = new EloquentCollection([
        makeDummyNotifyBulkModel(['id' => 1]),
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
        makeDummyNotifyBulkModel(['id' => 2])]);

    $callback($records, [
        'mail_template_slug' => 'template-a',
        'channels' => ['mail', 'sms']]);
<<<<<<< HEAD
=======
        makeDummyNotifyBulkModel(['id' => 2]),
    ]);

    $callback($records, [
        'mail_template_slug' => 'template-a',
        'channels' => ['mail', 'sms'],
    ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)

    Assert::assertNotNull($spy->received);
    Assert::assertSame(2, $spy->received['count']);
    Assert::assertSame('template-a', $spy->received['slug']);
    Assert::assertSame(['mail', 'sms'], $spy->received['channels']);
});
