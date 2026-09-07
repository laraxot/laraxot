<?php

declare(strict_types=1);

namespace Modules\Notify\Tests\Unit\Enums;

use Illuminate\Database\Eloquent\Model;
use Modules\Notify\Actions\SMS\NormalizePhoneNumberAction;
use Modules\Notify\Channels\SmsChannel;
use Modules\Notify\Channels\WhatsAppChannel;
use Modules\Notify\Enums\ChannelEnum;
<<<<<<< HEAD
<<<<<<< HEAD
=======
use Modules\Notify\Tests\TestCase;
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
use Modules\Xot\Actions\Cast\SafeEloquentCastAction;
use PHPUnit\Framework\Assert;

use function Safe\preg_replace;

<<<<<<< HEAD
<<<<<<< HEAD
=======
uses(TestCase::class)->group('no-notify-db');

>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
test('notification channel mapping is correct', function () {
    Assert::assertSame('mail', ChannelEnum::Mail->getNotificationChannel());
    Assert::assertSame(SmsChannel::class, ChannelEnum::Sms->getNotificationChannel());
    Assert::assertSame(WhatsAppChannel::class, ChannelEnum::WhatsApp->getNotificationChannel());
});

test('mail recipient is resolved only for valid email', function () {
<<<<<<< HEAD
    app()->instance(SafeEloquentCastAction::class, new class
=======
    app()->instance(SafeEloquentCastAction::class, new class()
>>>>>>> a988596b (first)
    {
        public function getStringAttribute(Model $record, string $attribute, string $default = ''): string
        {
            $value = $record->getAttribute($attribute);

            return is_string($value) ? $value : $default;
        }
    });

<<<<<<< HEAD
    $valid = new class extends Model
=======
    $valid = new class() extends Model
>>>>>>> a988596b (first)
    {
        protected $guarded = [];
    };
    $valid->setAttribute('email', 'notify@example.test');

<<<<<<< HEAD
    $invalid = new class extends Model
=======
    $invalid = new class() extends Model
>>>>>>> a988596b (first)
    {
        protected $guarded = [];
    };
    $invalid->setAttribute('email', 'not-an-email');

    Assert::assertSame('notify@example.test', ChannelEnum::Mail->getRecipient($valid));
    Assert::assertNull(ChannelEnum::Mail->getRecipient($invalid));
});

test('sms and whatsapp recipients are normalized', function () {
<<<<<<< HEAD
    app()->instance(SafeEloquentCastAction::class, new class
=======
    app()->instance(SafeEloquentCastAction::class, new class()
>>>>>>> a988596b (first)
    {
        public function getStringAttribute(Model $record, string $attribute, string $default = ''): string
        {
            $value = $record->getAttribute($attribute);

            return is_string($value) ? $value : $default;
        }
    });

<<<<<<< HEAD
    app()->instance(NormalizePhoneNumberAction::class, new class
=======
    app()->instance(NormalizePhoneNumberAction::class, new class()
>>>>>>> a988596b (first)
    {
        public function execute(string $phone): string
        {
            return '+39'.preg_replace('/\D+/', '', $phone);
        }
    });

<<<<<<< HEAD
    $record = new class extends Model
=======
    $record = new class() extends Model
>>>>>>> a988596b (first)
    {
        protected $guarded = [];
    };
    $record->setAttribute('phone', ' 333-12-34-567 ');
    $record->setAttribute('whatsapp', ' 388 99 77 66 ');

    Assert::assertSame('+393331234567', ChannelEnum::Sms->getRecipient($record));
    Assert::assertSame('+39388997766', ChannelEnum::WhatsApp->getRecipient($record));
});
