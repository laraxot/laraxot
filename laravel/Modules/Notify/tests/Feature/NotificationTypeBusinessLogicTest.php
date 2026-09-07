<?php

declare(strict_types=1);

namespace Modules\Notify\Tests\Feature;

use Modules\Notify\Database\Factories\NotificationTypeFactory;
use Modules\Notify\Models\NotificationType;
use Modules\Notify\Tests\TestCase;
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
use Modules\Xot\Tests\XotBasePest;
use PHPUnit\Framework\Assert;

use function Safe\json_encode;

<<<<<<< HEAD
=======
use PHPUnit\Framework\Assert;
use Modules\Xot\Tests\XotBasePest;

use function Safe\json_encode;

uses(TestCase::class)->group('notify-db');

>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
describe('Notification Type Business Logic', function () {
    it('can create notification type with basic information', function () {
        $typeData = [
            'name' => 'Appointment Reminder',
            'slug' => 'appointment-reminder',
            'description' => 'Promemoria per appuntamenti',
            'category' => 'healthcare',
<<<<<<< HEAD
<<<<<<< HEAD
            'is_active' => true];
=======
            'is_active' => true,
        ];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'is_active' => true];
>>>>>>> a988596b (first)

        $type = NotificationTypeFactory::new()->createOne($typeData);

        Assert::assertSame('Appointment Reminder', $type->name);
        Assert::assertSame('appointment-reminder', $type->slug);
        Assert::assertSame('Promemoria per appuntamenti', $type->description);
        Assert::assertSame('healthcare', $type->category);
        Assert::assertTrue($type->is_active);

        XotBasePest::assertTableHas('notify', 'notification_types', [
            'id' => $type->id,
            'name' => 'Appointment Reminder',
            'slug' => 'appointment-reminder',
            'description' => 'Promemoria per appuntamenti',
            'category' => 'healthcare',
<<<<<<< HEAD
<<<<<<< HEAD
            'is_active' => true]);
=======
            'is_active' => true,
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'is_active' => true]);
>>>>>>> a988596b (first)
    });

    it('can manage notification type channels', function () {
        $type = NotificationTypeFactory::new()->createOne();
        $channels = [
            'email' => [
                'enabled' => true,
                'priority' => 'high',
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
                'template' => 'email.appointment-reminder'],
            'sms' => [
                'enabled' => true,
                'max_length' => 160],
            'push' => [
                'enabled' => false]];
<<<<<<< HEAD
=======
                'template' => 'email.appointment-reminder',
            ],
            'sms' => [
                'enabled' => true,
                'max_length' => 160,
            ],
            'push' => [
                'enabled' => false,
            ],
        ];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)

        $type->update(['channels' => $channels]);

        XotBasePest::assertTableHas('notify', 'notification_types', [
            'id' => $type->id,
<<<<<<< HEAD
<<<<<<< HEAD
            'channels' => json_encode($channels)]);
=======
            'channels' => json_encode($channels),
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'channels' => json_encode($channels)]);
>>>>>>> a988596b (first)

        $fresh = XotBasePest::assertFreshModel($type, NotificationType::class);
        $storedChannels = XotBasePest::assertArray($fresh->channels);
        $emailChannel = XotBasePest::assertArray($storedChannels['email'] ?? null);
        $smsChannel = XotBasePest::assertArray($storedChannels['sms'] ?? null);
        $pushChannel = XotBasePest::assertArray($storedChannels['push'] ?? null);

        Assert::assertTrue($emailChannel['enabled']);
        Assert::assertSame('high', $emailChannel['priority']);
        Assert::assertTrue($smsChannel['enabled']);
        Assert::assertSame(160, $smsChannel['max_length']);
        Assert::assertFalse($pushChannel['enabled']);
    });

    it('can manage notification type settings', function () {
        $type = NotificationTypeFactory::new()->createOne();
        $settings = [
            'retry_attempts' => 3,
            'retry_delay' => 300,
            'batch_size' => 100,
            'timezone_aware' => true,
<<<<<<< HEAD
<<<<<<< HEAD
            'encryption_required' => false];
=======
            'encryption_required' => false,
        ];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'encryption_required' => false];
>>>>>>> a988596b (first)

        $type->update(['settings' => $settings]);

        XotBasePest::assertTableHas('notify', 'notification_types', [
            'id' => $type->id,
<<<<<<< HEAD
<<<<<<< HEAD
            'settings' => json_encode($settings)]);
=======
            'settings' => json_encode($settings),
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'settings' => json_encode($settings)]);
>>>>>>> a988596b (first)

        $storedSettings = XotBasePest::assertArray(TestCase::notifyFreshTypeSettings($type));

        Assert::assertSame(3, $storedSettings['retry_attempts']);
        Assert::assertSame(300, $storedSettings['retry_delay']);
        Assert::assertSame(100, $storedSettings['batch_size']);
        Assert::assertTrue($storedSettings['timezone_aware']);
        Assert::assertFalse($storedSettings['encryption_required']);
    });

    it('can assign notification type template reference', function () {
        $type = NotificationTypeFactory::new()->createOne();
        $template = 'emails.appointment-reminder';

        $type->update(['template' => $template]);

        XotBasePest::assertTableHas('notify', 'notification_types', [
            'id' => $type->id,
<<<<<<< HEAD
<<<<<<< HEAD
            'template' => $template]);
=======
            'template' => $template,
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'template' => $template]);
>>>>>>> a988596b (first)

        Assert::assertSame($template, XotBasePest::assertFreshModel($type, NotificationType::class)->template);
    });

    it('can search notification types by category and status', function () {
        $healthcareType = NotificationTypeFactory::new()->createOne(['category' => 'healthcare', 'is_active' => true]);
        NotificationTypeFactory::new()->createOne(['category' => 'marketing', 'is_active' => false]);

        $healthcareTypes = NotificationType::query()->where('category', 'healthcare')->get();
        $activeTypes = NotificationType::query()->where('is_active', true)->get();

        Assert::assertCount(1, $healthcareTypes);
        Assert::assertTrue($healthcareTypes->contains($healthcareType));
        Assert::assertTrue($activeTypes->contains($healthcareType));
    });

    it('can duplicate notification type via replicate', function () {
        $originalType = NotificationTypeFactory::new()->createOne([
            'name' => 'Original Type',
            'slug' => 'original-type',
<<<<<<< HEAD
<<<<<<< HEAD
            'category' => 'system']);
=======
            'category' => 'system',
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'category' => 'system']);
>>>>>>> a988596b (first)

        $duplicateType = $originalType->replicate();
        $duplicateType->name = 'Duplicate Type';
        $duplicateType->slug = 'duplicate-type';
        $duplicateType->save();

        XotBasePest::assertTableHas('notify', 'notification_types', [
            'id' => $duplicateType->id,
            'name' => 'Duplicate Type',
<<<<<<< HEAD
<<<<<<< HEAD
            'slug' => 'duplicate-type']);
=======
            'slug' => 'duplicate-type',
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'slug' => 'duplicate-type']);
>>>>>>> a988596b (first)
    });
});
