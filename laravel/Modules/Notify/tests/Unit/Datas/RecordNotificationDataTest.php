<?php

declare(strict_types=1);

namespace Modules\Notify\Tests\Unit\Datas;

use Modules\Notify\Actions\SMS\NormalizePhoneNumberAction;
use Modules\Notify\Datas\RecordNotificationData;
<<<<<<< HEAD
<<<<<<< HEAD
use Modules\User\Models\User;
use Modules\Xot\Tests\XotBasePest;
use PHPUnit\Framework\Assert;
=======
use Modules\Notify\Tests\TestCase;
use Modules\User\Models\User;
use PHPUnit\Framework\Assert;
use Modules\Xot\Tests\XotBasePest;

uses(TestCase::class)->group('no-notify-db');
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])

test('record notification data returns mail route', function (): void {
    $user = new User;
=======
use Modules\User\Models\User;
use Modules\Xot\Tests\XotBasePest;
use PHPUnit\Framework\Assert;

test('record notification data returns mail route', function (): void {
    $user = new User();
>>>>>>> a988596b (first)
    $user->setAttribute('email', 'recipient@example.test');

    $data = RecordNotificationData::from([
        'record' => $user,
<<<<<<< HEAD
<<<<<<< HEAD
        'channel' => 'mail']);
=======
        'channel' => 'mail',
    ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
        'channel' => 'mail']);
>>>>>>> a988596b (first)

    Assert::assertSame('mail', $data->getChannel());
    Assert::assertSame('recipient@example.test', $data->getRoute());
});

test('record notification data returns normalized sms route', function (): void {
<<<<<<< HEAD
    app()->instance(NormalizePhoneNumberAction::class, new class
=======
    app()->instance(NormalizePhoneNumberAction::class, new class()
>>>>>>> a988596b (first)
    {
        public function execute(string $phone): string
        {
            return '+39'.$phone;
        }
    });

<<<<<<< HEAD
    $user = new User;
=======
    $user = new User();
>>>>>>> a988596b (first)
    $user->setAttribute('phone', '3331234567');

    $data = RecordNotificationData::from([
        'record' => $user,
<<<<<<< HEAD
<<<<<<< HEAD
        'channel' => 'sms']);
=======
        'channel' => 'sms',
    ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
        'channel' => 'sms']);
>>>>>>> a988596b (first)

    Assert::assertSame('+393331234567', $data->getRoute());
});

test('record notification data throws for unsupported channel', function (): void {
<<<<<<< HEAD
    $user = new User;
=======
    $user = new User();
>>>>>>> a988596b (first)
    $user->setAttribute('email', 'recipient@example.test');

    $data = RecordNotificationData::from([
        'record' => $user,
<<<<<<< HEAD
<<<<<<< HEAD
        'channel' => 'telegram']);
=======
        'channel' => 'telegram',
    ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
        'channel' => 'telegram']);
>>>>>>> a988596b (first)

    XotBasePest::assertThrows(
        fn () => $data->getRoute(),
        \Exception::class,
    );
});
