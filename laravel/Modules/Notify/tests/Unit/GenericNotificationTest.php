<?php

declare(strict_types=1);

namespace Modules\Notify\Tests\Unit;

use Illuminate\Database\Eloquent\Model;
use Modules\Notify\Notifications\GenericNotification;
<<<<<<< HEAD
<<<<<<< HEAD
use PHPUnit\Framework\Assert;

=======
use Modules\Notify\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class)->group('no-notify-db');

>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
use PHPUnit\Framework\Assert;

>>>>>>> a988596b (first)
// Basic unit tests focusing on business logic of recipient name resolution

describe('GenericNotification getRecipientName', function (): void {
    it('prefers getFullName() when available', function (): void {
        $notification = new GenericNotification('Title', 'Message');

<<<<<<< HEAD
        $notifiable = new class
=======
        $notifiable = new class()
>>>>>>> a988596b (first)
        {
            public function getFullName(): string
            {
                return 'John Doe';
            }
        };

        $ref = new \ReflectionClass(GenericNotification::class);
        $method = $ref->getMethod('getRecipientName');
        $method->setAccessible(true);

        Assert::assertSame('John Doe', $method->invoke($notification, $notifiable));
    });

    it('uses Eloquent model full_name when present and non-empty', function (): void {
        $notification = new GenericNotification('Title', 'Message');

<<<<<<< HEAD
        $model = new class extends Model
        {
            protected $attributes = [
<<<<<<< HEAD
                'full_name' => 'Jane Roe'];
=======
                'full_name' => 'Jane Roe',
            ];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
        $model = new class() extends Model
        {
            protected $attributes = [
                'full_name' => 'Jane Roe'];
>>>>>>> a988596b (first)
        };

        $ref = new \ReflectionClass(GenericNotification::class);
        $method = $ref->getMethod('getRecipientName');
        $method->setAccessible(true);

        Assert::assertSame('Jane Roe', $method->invoke($notification, $model));
    });

    it('falls back to first_name then name then default', function (): void {
        $notification = new GenericNotification('Title', 'Message');

        // first_name present
<<<<<<< HEAD
        $model1 = new class extends Model
=======
        $model1 = new class() extends Model
>>>>>>> a988596b (first)
        {
            protected $attributes = ['first_name' => 'Alice'];
        };
        // name present
<<<<<<< HEAD
        $model2 = new class extends Model
=======
        $model2 = new class() extends Model
>>>>>>> a988596b (first)
        {
            protected $attributes = ['name' => 'Bob'];
        };
        // none present
<<<<<<< HEAD
        $model3 = new class extends Model
=======
        $model3 = new class() extends Model
>>>>>>> a988596b (first)
        {
            protected $attributes = [];
        };

        $ref = new \ReflectionClass(GenericNotification::class);
        $method = $ref->getMethod('getRecipientName');
        $method->setAccessible(true);

        Assert::assertSame('Alice', $method->invoke($notification, $model1));
        Assert::assertSame('Bob', $method->invoke($notification, $model2));
        Assert::assertSame('Utente', $method->invoke($notification, $model3));
    });
});
