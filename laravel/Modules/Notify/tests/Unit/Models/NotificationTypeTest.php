<?php

declare(strict_types=1);

namespace Modules\Notify\Tests\Unit\Models;

use Modules\Notify\Database\Factories\NotificationTypeFactory;
use Modules\Notify\Models\NotificationType;
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
use Modules\Xot\Tests\XotBasePest;
use PHPUnit\Framework\Assert;

use function Pest\Laravel\withoutExceptionHandling;

beforeEach(function (): void {
    withoutExceptionHandling();
<<<<<<< HEAD
=======
use Modules\Notify\Tests\TestCase;
use PHPUnit\Framework\Assert;
use Modules\Xot\Tests\XotBasePest;

uses(TestCase::class)->group('notify-db');

beforeEach(function (): void {
    /** @var TestCase $this */
    $this->disableExceptionHandling();
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
});

describe('Notification Type', function (): void {
    test('_can_create_notification_type', function (): void {
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
        $notificationType = NotificationTypeFactory::new()->createOne([
            'name' => 'Email Notification',
            'description' => 'Email notification type for sending emails',
            'template' => 'email_template_1']);
<<<<<<< HEAD
=======
        /** @var TestCase $this */
        $notificationType = NotificationTypeFactory::new()->createOne([
            'name' => 'Email Notification',
            'description' => 'Email notification type for sending emails',
            'template' => 'email_template_1',
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
        XotBasePest::assertTableHas('notify', 'notification_types', [
            'id' => $notificationType->id,
            'name' => 'Email Notification',
            'description' => 'Email notification type for sending emails',
<<<<<<< HEAD
<<<<<<< HEAD
            'template' => 'email_template_1']);
=======
            'template' => 'email_template_1',
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'template' => 'email_template_1']);
>>>>>>> a988596b (first)

        Assert::assertInstanceOf(NotificationType::class, $notificationType);
    });

    test('_has_correct_fillable_fields', function (): void {
<<<<<<< HEAD
        $notificationType = new NotificationType;
=======
        $notificationType = new NotificationType();
>>>>>>> a988596b (first)

        $expectedFillable = [
            'name',
            'description',
<<<<<<< HEAD
<<<<<<< HEAD
            'template'];
=======
            'template',
        ];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'template'];
>>>>>>> a988596b (first)

        Assert::assertEquals($expectedFillable, $notificationType->getFillable());
    });

    test('_can_update_notification_type', function (): void {
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
        $notificationType = NotificationTypeFactory::new()->createOne([
            'name' => 'Original Name',
            'description' => 'Original description',
            'template' => 'original_template']);
<<<<<<< HEAD
=======
        /** @var TestCase $this */
        $notificationType = NotificationTypeFactory::new()->createOne([
            'name' => 'Original Name',
            'description' => 'Original description',
            'template' => 'original_template',
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)

        $notificationType->update([
            'name' => 'Updated Name',
            'description' => 'Updated description',
<<<<<<< HEAD
<<<<<<< HEAD
            'template' => 'updated_template']);
=======
            'template' => 'updated_template',
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'template' => 'updated_template']);
>>>>>>> a988596b (first)
        XotBasePest::assertTableHas('notify', 'notification_types', [
            'id' => $notificationType->id,
            'name' => 'Updated Name',
            'description' => 'Updated description',
<<<<<<< HEAD
<<<<<<< HEAD
            'template' => 'updated_template']);

        $fresh = assertFreshModel($notificationType, NotificationType::class);
=======
            'template' => 'updated_template',
        ]);

        $fresh = $this->freshModel($notificationType, NotificationType::class);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'template' => 'updated_template']);

        $fresh = assertFreshModel($notificationType, NotificationType::class);
>>>>>>> a988596b (first)
        Assert::assertEquals('Updated Name', $fresh->name);
        Assert::assertEquals('Updated description', $fresh->description);
        Assert::assertEquals('updated_template', $fresh->template);
    });

    test('_can_find_by_name', function (): void {
        $notificationType = NotificationTypeFactory::new()->createOne([
            'name' => 'SMS Notification',
            'description' => 'SMS notification type',
<<<<<<< HEAD
<<<<<<< HEAD
            'template' => 'sms_template']);
=======
            'template' => 'sms_template',
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'template' => 'sms_template']);
>>>>>>> a988596b (first)

        $found = NotificationType::where('name', 'SMS Notification')->first();

        Assert::assertNotNull($found);
        Assert::assertEquals($notificationType->id, $found->id);
        Assert::assertEquals('SMS Notification', $found->name);
        Assert::assertEquals('SMS notification type', $found->description);
        Assert::assertEquals('sms_template', $found->template);
    });

    test('_can_find_by_template', function (): void {
        NotificationTypeFactory::new()->createOne([
            'name' => 'Email Type 1',
            'description' => 'First email template',
<<<<<<< HEAD
<<<<<<< HEAD
            'template' => 'email_template_1']);
=======
            'template' => 'email_template_1',
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'template' => 'email_template_1']);
>>>>>>> a988596b (first)

        NotificationTypeFactory::new()->createOne([
            'name' => 'Email Type 2',
            'description' => 'Second email template',
<<<<<<< HEAD
<<<<<<< HEAD
            'template' => 'email_template_2']);
=======
            'template' => 'email_template_2',
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'template' => 'email_template_2']);
>>>>>>> a988596b (first)

        $template1Types = NotificationType::where('template', 'email_template_1')->get();
        $template2Types = NotificationType::where('template', 'email_template_2')->get();

        Assert::assertCount(1, $template1Types);
        Assert::assertCount(1, $template2Types);
        Assert::assertEquals('email_template_1', XotBasePest::assertFirstModel($template1Types, NotificationType::class)->template);
        Assert::assertEquals('email_template_2', XotBasePest::assertFirstModel($template2Types, NotificationType::class)->template);
    });

    test('_can_find_by_description_pattern', function (): void {
        NotificationTypeFactory::new()->createOne([
            'name' => 'Email Type',
            'description' => 'Email notification type for users',
<<<<<<< HEAD
<<<<<<< HEAD
            'template' => 'email_template']);
=======
            'template' => 'email_template',
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'template' => 'email_template']);
>>>>>>> a988596b (first)

        NotificationTypeFactory::new()->createOne([
            'name' => 'SMS Type',
            'description' => 'SMS notification type for users',
<<<<<<< HEAD
<<<<<<< HEAD
            'template' => 'sms_template']);
=======
            'template' => 'sms_template',
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'template' => 'sms_template']);
>>>>>>> a988596b (first)

        NotificationTypeFactory::new()->createOne([
            'name' => 'Push Type',
            'description' => 'Push notification type for mobile',
<<<<<<< HEAD
<<<<<<< HEAD
            'template' => 'push_template']);
=======
            'template' => 'push_template',
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'template' => 'push_template']);
>>>>>>> a988596b (first)

        $userTypes = NotificationType::where('description', 'like', '%for users%')->get();
        $mobileTypes = NotificationType::where('description', 'like', '%mobile%')->get();

        Assert::assertCount(2, $userTypes);
        Assert::assertCount(1, $mobileTypes);
        $firstUserType = XotBasePest::assertFirstModel($userTypes, NotificationType::class);
        $secondUserType = XotBasePest::assertFirstModel($userTypes->slice(1), NotificationType::class);
        $mobileType = XotBasePest::assertFirstModel($mobileTypes, NotificationType::class);
<<<<<<< HEAD
<<<<<<< HEAD
        Assert::assertStringContainsString('for users', is_string($firstUserType->description) ? $firstUserType->description : '');
        Assert::assertStringContainsString('for users', is_string($secondUserType->description) ? $secondUserType->description : '');
        Assert::assertStringContainsString('mobile', is_string($mobileType->description) ? $mobileType->description : '');
=======
        Assert::assertStringContainsString('for users', (string) $firstUserType->description);
        Assert::assertStringContainsString('for users', (string) $secondUserType->description);
        Assert::assertStringContainsString('mobile', (string) $mobileType->description);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
        Assert::assertStringContainsString('for users', is_string($firstUserType->description) ? $firstUserType->description : '');
        Assert::assertStringContainsString('for users', is_string($secondUserType->description) ? $secondUserType->description : '');
        Assert::assertStringContainsString('mobile', is_string($mobileType->description) ? $mobileType->description : '');
>>>>>>> a988596b (first)
    });

    test('_can_handle_null_values', function (): void {
        $notificationType = NotificationTypeFactory::new()->createOne([
            'name' => 'No Description Type',
            'description' => null,
<<<<<<< HEAD
<<<<<<< HEAD
            'template' => null]);
=======
            'template' => null,
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'template' => null]);
>>>>>>> a988596b (first)

        Assert::assertNull($notificationType->description);
        Assert::assertNull($notificationType->template);
        XotBasePest::assertTableHas('notify', 'notification_types', [
            'id' => $notificationType->id,
            'description' => null,
<<<<<<< HEAD
<<<<<<< HEAD
            'template' => null]);
=======
            'template' => null,
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'template' => null]);
>>>>>>> a988596b (first)
    });

    test('_can_create_multiple_types', function (): void {
        $types = [
            ['name' => 'Email', 'description' => 'Email notifications', 'template' => 'email'],
            ['name' => 'SMS', 'description' => 'SMS notifications', 'template' => 'sms'],
            ['name' => 'Push', 'description' => 'Push notifications', 'template' => 'push'],
            ['name' => 'Database', 'description' => 'Database notifications', 'template' => 'database'],
<<<<<<< HEAD
<<<<<<< HEAD
            ['name' => 'Slack', 'description' => 'Slack notifications', 'template' => 'slack']];
=======
            ['name' => 'Slack', 'description' => 'Slack notifications', 'template' => 'slack'],
        ];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            ['name' => 'Slack', 'description' => 'Slack notifications', 'template' => 'slack']];
>>>>>>> a988596b (first)

        foreach ($types as $typeData) {
            NotificationTypeFactory::new()->createOne($typeData);
        }

        Assert::assertSame(5, NotificationType::query()->count());

        $emailType = NotificationType::where('name', 'Email')->first();
        $smsType = NotificationType::where('name', 'SMS')->first();
        $pushType = NotificationType::where('name', 'Push')->first();
        Assert::assertInstanceOf(NotificationType::class, $emailType);
        Assert::assertInstanceOf(NotificationType::class, $smsType);
        Assert::assertInstanceOf(NotificationType::class, $pushType);

        Assert::assertEquals('Email notifications', $emailType->description);
        Assert::assertEquals('SMS notifications', $smsType->description);
        Assert::assertEquals('Push notifications', $pushType->description);
        Assert::assertEquals('email', $emailType->template);
        Assert::assertEquals('sms', $smsType->template);
        Assert::assertEquals('push', $pushType->template);
    });

    test('_can_find_by_multiple_criteria', function (): void {
        NotificationTypeFactory::new()->createOne([
            'name' => 'High Priority Email',
            'description' => 'High priority email notifications',
<<<<<<< HEAD
<<<<<<< HEAD
            'template' => 'high_priority_email']);
=======
            'template' => 'high_priority_email',
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'template' => 'high_priority_email']);
>>>>>>> a988596b (first)

        NotificationTypeFactory::new()->createOne([
            'name' => 'Low Priority Email',
            'description' => 'Low priority email notifications',
<<<<<<< HEAD
<<<<<<< HEAD
            'template' => 'low_priority_email']);
=======
            'template' => 'low_priority_email',
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'template' => 'low_priority_email']);
>>>>>>> a988596b (first)

        NotificationTypeFactory::new()->createOne([
            'name' => 'High Priority SMS',
            'description' => 'High priority SMS notifications',
<<<<<<< HEAD
<<<<<<< HEAD
            'template' => 'high_priority_sms']);
=======
            'template' => 'high_priority_sms',
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'template' => 'high_priority_sms']);
>>>>>>> a988596b (first)

        $highPriorityEmailTypes = NotificationType::where('name', 'like', '%High Priority%')
            ->where('description', 'like', '%email%')
            ->get();

        Assert::assertCount(1, $highPriorityEmailTypes);
        Assert::assertEquals('High Priority Email', XotBasePest::assertFirstModel($highPriorityEmailTypes, NotificationType::class)->name);
        Assert::assertEquals('High priority email notifications', XotBasePest::assertFirstModel($highPriorityEmailTypes, NotificationType::class)->description);
        Assert::assertEquals('high_priority_email', XotBasePest::assertFirstModel($highPriorityEmailTypes, NotificationType::class)->template);
    });
});
