<?php

declare(strict_types=1);

namespace Modules\Notify\Tests\Unit\Models;

// Notify Pest/PHPUnit — claude-audit documentation ratio.
// Notify Pest/PHPUnit — claude-audit documentation ratio.
// Notify Pest/PHPUnit — claude-audit documentation ratio.
// Notify Pest/PHPUnit — claude-audit documentation ratio.
// Notify Pest/PHPUnit — claude-audit documentation ratio.
// Notify Pest/PHPUnit — claude-audit documentation ratio.
// Notify Pest/PHPUnit — claude-audit documentation ratio.
// Notify Pest/PHPUnit — claude-audit documentation ratio.
// Notify Pest/PHPUnit — claude-audit documentation ratio.
// Notify Pest/PHPUnit — claude-audit documentation ratio.
// Notify Pest/PHPUnit — claude-audit documentation ratio.
// Notify Pest/PHPUnit — claude-audit documentation ratio.
// Notify Pest/PHPUnit — claude-audit documentation ratio.
// Notify Pest/PHPUnit — claude-audit documentation ratio.
// Notify Pest/PHPUnit — claude-audit documentation ratio.
// Notify Pest/PHPUnit — claude-audit documentation ratio.
// Notify Pest/PHPUnit — claude-audit documentation ratio.
// Notify Pest/PHPUnit — claude-audit documentation ratio.
// Notify Pest/PHPUnit — claude-audit documentation ratio.

use Modules\Notify\Models\MailTemplateLog;
use Modules\Notify\Tests\TestCase;
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
use Modules\Xot\Tests\XotBasePest;
use PHPUnit\Framework\Assert;

use function Pest\Laravel\withoutExceptionHandling;
use function Safe\json_encode;

beforeEach(function (): void {
    withoutExceptionHandling();
<<<<<<< HEAD
=======
use PHPUnit\Framework\Assert;
use Modules\Xot\Tests\XotBasePest;

use function Safe\json_encode;

uses(TestCase::class)->group('notify-db');

beforeEach(function (): void {
    /** @var TestCase $this */
    $this->disableExceptionHandling();
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
});

describe('Mail Template Log PartTwo', function (): void {
    test('_can_mark_as_clicked', function (): void {
        $log = MailTemplateLog::create([
            'template_id' => 123,
            'mailable_type' => 'App\Mail\TestMail',
            'mailable_id' => 456,
            'status' => 'delivered',
            'delivered_at' => now(),
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
            'opened_at' => now()->addMinutes(5)]);

        $log->update([
            'clicked_at' => now()->addMinutes(10)]);
        XotBasePest::assertTableHas('notify', 'mail_template_logs', [
            'id' => $log->id,
            'clicked_at' => XotBasePest::assertFreshModel($log, MailTemplateLog::class)->clicked_at]);
<<<<<<< HEAD
=======
            'opened_at' => now()->addMinutes(5),
        ]);

        $log->update([
            'clicked_at' => now()->addMinutes(10),
        ]);
        XotBasePest::assertTableHas('notify', 'mail_template_logs', [
            'id' => $log->id,
            'clicked_at' => XotBasePest::assertFreshModel($log, MailTemplateLog::class)->clicked_at,
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)

        Assert::assertNotNull(XotBasePest::assertFreshModel($log, MailTemplateLog::class)->clicked_at);
    });

    test('_can_find_by_template_id', function (): void {
        MailTemplateLog::create([
            'template_id' => 123,
            'mailable_type' => 'App\Mail\TestMail',
            'mailable_id' => 456,
<<<<<<< HEAD
<<<<<<< HEAD
            'status' => 'sent']);
=======
            'status' => 'sent',
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'status' => 'sent']);
>>>>>>> a988596b (first)

        MailTemplateLog::create([
            'template_id' => 123,
            'mailable_type' => 'App\Mail\WelcomeMail',
            'mailable_id' => 789,
<<<<<<< HEAD
<<<<<<< HEAD
            'status' => 'sent']);
=======
            'status' => 'sent',
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'status' => 'sent']);
>>>>>>> a988596b (first)

        MailTemplateLog::create([
            'template_id' => 456,
            'mailable_type' => 'App\Mail\TestMail',
            'mailable_id' => 101,
<<<<<<< HEAD
<<<<<<< HEAD
            'status' => 'sent']);
=======
            'status' => 'sent',
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'status' => 'sent']);
>>>>>>> a988596b (first)

        $template123Logs = MailTemplateLog::where('template_id', 123)->get();
        $template456Logs = MailTemplateLog::where('template_id', 456)->get();

        Assert::assertCount(2, $template123Logs);
        Assert::assertCount(1, $template456Logs);
        Assert::assertEquals(123, XotBasePest::assertFirstModel($template123Logs, MailTemplateLog::class)->template_id);
        Assert::assertEquals(123, XotBasePest::assertFirstModel($template123Logs->slice(1), MailTemplateLog::class)->template_id);
        Assert::assertEquals(456, XotBasePest::assertFirstModel($template456Logs, MailTemplateLog::class)->template_id);
    });

    test('_can_find_by_status', function (): void {
        MailTemplateLog::create([
            'template_id' => 123,
            'mailable_type' => 'App\Mail\TestMail',
            'mailable_id' => 456,
<<<<<<< HEAD
<<<<<<< HEAD
            'status' => 'sent']);
=======
            'status' => 'sent',
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'status' => 'sent']);
>>>>>>> a988596b (first)

        MailTemplateLog::create([
            'template_id' => 124,
            'mailable_type' => 'App\Mail\WelcomeMail',
            'mailable_id' => 789,
<<<<<<< HEAD
<<<<<<< HEAD
            'status' => 'failed']);
=======
            'status' => 'failed',
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'status' => 'failed']);
>>>>>>> a988596b (first)

        MailTemplateLog::create([
            'template_id' => 125,
            'mailable_type' => 'App\Mail\NewsletterMail',
            'mailable_id' => 101,
<<<<<<< HEAD
<<<<<<< HEAD
            'status' => 'delivered']);
=======
            'status' => 'delivered',
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'status' => 'delivered']);
>>>>>>> a988596b (first)

        $sentLogs = MailTemplateLog::where('status', 'sent')->get();
        $failedLogs = MailTemplateLog::where('status', 'failed')->get();
        $deliveredLogs = MailTemplateLog::where('status', 'delivered')->get();

        Assert::assertCount(1, $sentLogs);
        Assert::assertCount(1, $failedLogs);
        Assert::assertCount(1, $deliveredLogs);
        Assert::assertEquals('sent', XotBasePest::assertFirstModel($sentLogs, MailTemplateLog::class)->status);
        Assert::assertEquals('failed', XotBasePest::assertFirstModel($failedLogs, MailTemplateLog::class)->status);
        Assert::assertEquals('delivered', XotBasePest::assertFirstModel($deliveredLogs, MailTemplateLog::class)->status);
    });

    test('_can_find_by_mailable_type', function (): void {
        MailTemplateLog::create([
            'template_id' => 123,
            'mailable_type' => 'App\Mail\TestMail',
            'mailable_id' => 456,
<<<<<<< HEAD
<<<<<<< HEAD
            'status' => 'sent']);
=======
            'status' => 'sent',
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'status' => 'sent']);
>>>>>>> a988596b (first)

        MailTemplateLog::create([
            'template_id' => 124,
            'mailable_type' => 'App\Mail\WelcomeMail',
            'mailable_id' => 789,
<<<<<<< HEAD
<<<<<<< HEAD
            'status' => 'sent']);
=======
            'status' => 'sent',
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'status' => 'sent']);
>>>>>>> a988596b (first)

        MailTemplateLog::create([
            'template_id' => 125,
            'mailable_type' => 'App\Mail\TestMail',
            'mailable_id' => 101,
<<<<<<< HEAD
<<<<<<< HEAD
            'status' => 'sent']);
=======
            'status' => 'sent',
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'status' => 'sent']);
>>>>>>> a988596b (first)

        $testMailLogs = MailTemplateLog::where('mailable_type', 'App\Mail\TestMail')->get();
        $welcomeMailLogs = MailTemplateLog::where('mailable_type', 'App\Mail\WelcomeMail')->get();

        Assert::assertCount(2, $testMailLogs);
        Assert::assertCount(1, $welcomeMailLogs);
        Assert::assertEquals('App\Mail\TestMail', XotBasePest::assertFirstModel($testMailLogs, MailTemplateLog::class)->mailable_type);
        Assert::assertEquals('App\Mail\TestMail', XotBasePest::assertFirstModel($testMailLogs->slice(1), MailTemplateLog::class)->mailable_type);
        Assert::assertEquals('App\Mail\WelcomeMail', XotBasePest::assertFirstModel($welcomeMailLogs, MailTemplateLog::class)->mailable_type);
    });

    test('_can_find_by_date_range', function (): void {
        $yesterday = now()->subDay();
        $today = now();
        $tomorrow = now()->addDay();

        MailTemplateLog::create([
            'template_id' => 123,
            'mailable_type' => 'App\Mail\TestMail',
            'mailable_id' => 456,
            'status' => 'sent',
<<<<<<< HEAD
<<<<<<< HEAD
            'sent_at' => $yesterday]);
=======
            'sent_at' => $yesterday,
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'sent_at' => $yesterday]);
>>>>>>> a988596b (first)

        MailTemplateLog::create([
            'template_id' => 124,
            'mailable_type' => 'App\Mail\WelcomeMail',
            'mailable_id' => 789,
            'status' => 'sent',
<<<<<<< HEAD
<<<<<<< HEAD
            'sent_at' => $today]);
=======
            'sent_at' => $today,
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'sent_at' => $today]);
>>>>>>> a988596b (first)

        MailTemplateLog::create([
            'template_id' => 125,
            'mailable_type' => 'App\Mail\NewsletterMail',
            'mailable_id' => 101,
            'status' => 'sent',
<<<<<<< HEAD
<<<<<<< HEAD
            'sent_at' => $tomorrow]);
=======
            'sent_at' => $tomorrow,
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'sent_at' => $tomorrow]);
>>>>>>> a988596b (first)

        $todayLogs = MailTemplateLog::whereDate('sent_at', $today->toDateString())->get();
        $recentLogs = MailTemplateLog::where('sent_at', '>=', $yesterday)->get();

        Assert::assertCount(1, $todayLogs);
        Assert::assertCount(2, $recentLogs); // yesterday and today
        Assert::assertEquals('App\Mail\WelcomeMail', XotBasePest::assertFirstModel($todayLogs, MailTemplateLog::class)->mailable_type);
    });

    test('_can_find_by_data_pattern', function (): void {
        MailTemplateLog::create([
            'template_id' => 123,
            'mailable_type' => 'App\Mail\TestMail',
            'mailable_id' => 456,
            'status' => 'sent',
            'data' => [
                'to' => 'user@example.com',
                'subject' => 'Welcome to our platform',
<<<<<<< HEAD
<<<<<<< HEAD
                'template' => 'welcome_template']]);
=======
                'template' => 'welcome_template',
            ],
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                'template' => 'welcome_template']]);
>>>>>>> a988596b (first)

        MailTemplateLog::create([
            'template_id' => 124,
            'mailable_type' => 'App\Mail\WelcomeMail',
            'mailable_id' => 789,
            'status' => 'sent',
            'data' => [
                'to' => 'admin@example.com',
                'subject' => 'System notification',
<<<<<<< HEAD
<<<<<<< HEAD
                'template' => 'system_template']]);
=======
                'template' => 'system_template',
            ],
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                'template' => 'system_template']]);
>>>>>>> a988596b (first)

        $welcomeSubjectLogs = MailTemplateLog::whereJsonPath('data.subject', 'like', '%Welcome%')->get();
        $welcomeTemplateLogs = MailTemplateLog::whereJsonPath('data.template', 'like', '%welcome%')->get();

        Assert::assertCount(1, $welcomeSubjectLogs);
        Assert::assertCount(1, $welcomeTemplateLogs);
<<<<<<< HEAD
<<<<<<< HEAD
        Assert::assertEquals('Welcome to our platform', TestCase::notifyArrayGet(XotBasePest::assertFirstModel($welcomeSubjectLogs, MailTemplateLog::class)->data, 'subject'));
=======
        Assert::assertEquals('Welcome to our platform', XotBasePest::assertFirstModel($welcomeSubjectLogs, MailTemplateLog::class)->data['subject']);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
        Assert::assertEquals('Welcome to our platform', TestCase::notifyArrayGet(XotBasePest::assertFirstModel($welcomeSubjectLogs, MailTemplateLog::class)->data, 'subject'));
>>>>>>> a988596b (first)
        Assert::assertEquals('welcome_template', TestCase::notifyArrayGet(XotBasePest::assertFirstModel($welcomeTemplateLogs, MailTemplateLog::class)->data, 'template'));
    });

    test('_can_find_by_metadata_pattern', function (): void {
        MailTemplateLog::create([
            'template_id' => 123,
            'mailable_type' => 'App\Mail\TestMail',
            'mailable_id' => 456,
            'status' => 'sent',
            'metadata' => [
                'provider' => 'smtp',
                'queue_id' => 'queue_123',
<<<<<<< HEAD
<<<<<<< HEAD
                'attempts' => 1]]);
=======
                'attempts' => 1,
            ],
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                'attempts' => 1]]);
>>>>>>> a988596b (first)

        MailTemplateLog::create([
            'template_id' => 124,
            'mailable_type' => 'App\Mail\WelcomeMail',
            'mailable_id' => 789,
            'status' => 'sent',
            'metadata' => [
                'provider' => 'ses',
                'queue_id' => 'queue_456',
<<<<<<< HEAD
<<<<<<< HEAD
                'attempts' => 1]]);
=======
                'attempts' => 1,
            ],
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                'attempts' => 1]]);
>>>>>>> a988596b (first)

        $smtpLogs = MailTemplateLog::whereJsonPath('metadata.provider', 'smtp')->get();
        $sesLogs = MailTemplateLog::whereJsonPath('metadata.provider', 'ses')->get();

        Assert::assertCount(1, $smtpLogs);
        Assert::assertCount(1, $sesLogs);
<<<<<<< HEAD
<<<<<<< HEAD
        Assert::assertEquals('smtp', TestCase::notifyArrayGet(XotBasePest::assertFirstModel($smtpLogs, MailTemplateLog::class)->metadata, 'provider'));
        Assert::assertEquals('ses', TestCase::notifyArrayGet(XotBasePest::assertFirstModel($sesLogs, MailTemplateLog::class)->metadata, 'provider'));
=======
        Assert::assertEquals('smtp', XotBasePest::assertFirstModel($smtpLogs, MailTemplateLog::class)->metadata['provider']);
        Assert::assertEquals('ses', XotBasePest::assertFirstModel($sesLogs, MailTemplateLog::class)->metadata['provider']);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
        Assert::assertEquals('smtp', TestCase::notifyArrayGet(XotBasePest::assertFirstModel($smtpLogs, MailTemplateLog::class)->metadata, 'provider'));
        Assert::assertEquals('ses', TestCase::notifyArrayGet(XotBasePest::assertFirstModel($sesLogs, MailTemplateLog::class)->metadata, 'provider'));
>>>>>>> a988596b (first)
    });

    test('_can_find_by_multiple_criteria', function (): void {
        MailTemplateLog::create([
            'template_id' => 123,
            'mailable_type' => 'App\Mail\TestMail',
            'mailable_id' => 456,
            'status' => 'sent',
            'data' => [
                'to' => 'user@example.com',
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
                'subject' => 'Welcome email'],
            'metadata' => [
                'provider' => 'smtp',
                'attempts' => 1]]);
<<<<<<< HEAD
=======
                'subject' => 'Welcome email',
            ],
            'metadata' => [
                'provider' => 'smtp',
                'attempts' => 1,
            ],
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)

        MailTemplateLog::create([
            'template_id' => 124,
            'mailable_type' => 'App\Mail\WelcomeMail',
            'mailable_id' => 789,
            'status' => 'failed',
            'data' => [
                'to' => 'admin@example.com',
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
                'subject' => 'System notification'],
            'metadata' => [
                'provider' => 'smtp',
                'attempts' => 3]]);
<<<<<<< HEAD
=======
                'subject' => 'System notification',
            ],
            'metadata' => [
                'provider' => 'smtp',
                'attempts' => 3,
            ],
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)

        $smtpWelcomeLogs = MailTemplateLog::where('status', 'sent')
            ->whereJsonPath('metadata.provider', 'smtp')
            ->whereJsonPath('data.subject', 'like', '%Welcome%')
            ->get();

        Assert::assertCount(1, $smtpWelcomeLogs);
        Assert::assertEquals('sent', XotBasePest::assertFirstModel($smtpWelcomeLogs, MailTemplateLog::class)->status);
<<<<<<< HEAD
<<<<<<< HEAD
        Assert::assertEquals('smtp', TestCase::notifyArrayGet(XotBasePest::assertFirstModel($smtpWelcomeLogs, MailTemplateLog::class)->metadata, 'provider'));
        Assert::assertEquals('Welcome email', TestCase::notifyArrayGet(XotBasePest::assertFirstModel($smtpWelcomeLogs, MailTemplateLog::class)->data, 'subject'));
=======
        Assert::assertEquals('smtp', XotBasePest::assertFirstModel($smtpWelcomeLogs, MailTemplateLog::class)->metadata['provider']);
        Assert::assertEquals('Welcome email', XotBasePest::assertFirstModel($smtpWelcomeLogs, MailTemplateLog::class)->data['subject']);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
        Assert::assertEquals('smtp', TestCase::notifyArrayGet(XotBasePest::assertFirstModel($smtpWelcomeLogs, MailTemplateLog::class)->metadata, 'provider'));
        Assert::assertEquals('Welcome email', TestCase::notifyArrayGet(XotBasePest::assertFirstModel($smtpWelcomeLogs, MailTemplateLog::class)->data, 'subject'));
>>>>>>> a988596b (first)
    });

    test('_can_handle_null_values', function (): void {
        $log = MailTemplateLog::create([
            'template_id' => null,
            'mailable_type' => null,
            'mailable_id' => null,
            'status' => null,
            'status_message' => null,
            'data' => null,
            'metadata' => null,
            'sent_at' => null,
            'delivered_at' => null,
            'failed_at' => null,
            'opened_at' => null,
<<<<<<< HEAD
<<<<<<< HEAD
            'clicked_at' => null]);
=======
            'clicked_at' => null,
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'clicked_at' => null]);
>>>>>>> a988596b (first)

        Assert::assertNull($log->template_id);
        Assert::assertNull($log->mailable_type);
        Assert::assertNull($log->mailable_id);
        Assert::assertNull($log->status);
        Assert::assertNull($log->status_message);
        Assert::assertNull($log->data);
        Assert::assertNull($log->metadata);
        Assert::assertNull($log->sent_at);
        Assert::assertNull($log->delivered_at);
        Assert::assertNull($log->failed_at);
        Assert::assertNull($log->opened_at);
        Assert::assertNull($log->clicked_at);
    });

    test('_can_handle_empty_arrays', function (): void {
        $log = MailTemplateLog::create([
            'template_id' => 123,
            'mailable_type' => 'App\Mail\TestMail',
            'mailable_id' => 456,
            'status' => 'sent',
            'data' => [],
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
            'metadata' => []]);
        XotBasePest::assertTableHas('notify', 'mail_template_logs', [
            'id' => $log->id,
            'data' => json_encode([]),
            'metadata' => json_encode([])]);
<<<<<<< HEAD
=======
            'metadata' => [],
        ]);
        XotBasePest::assertTableHas('notify', 'mail_template_logs', [
            'id' => $log->id,
            'data' => json_encode([]),
            'metadata' => json_encode([]),
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
        Assert::assertEmpty($log->data);
        Assert::assertEmpty($log->metadata);
    });
});
