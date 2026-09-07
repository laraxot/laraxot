<?php

declare(strict_types=1);

namespace Modules\Notify\Tests\Unit\Models;

use Modules\Notify\Database\Factories\MailTemplateFactory;
use Modules\Notify\Models\MailTemplate;
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

describe('Mail Template', function (): void {
    test('_can_create_mail_template', function (): void {
        $template = MailTemplateFactory::new()->createOne([
            'mailable' => 'App\Mail\WelcomeMail',
            'name' => 'Welcome Email Template',
            'subject' => 'Benvenuto {{name}}!',
            'html_template' => '<h1>Benvenuto {{name}}!</h1><p>Grazie per esserti registrato.</p>',
            'text_template' => 'Benvenuto {{name}}! Grazie per esserti registrato.',
            'sms_template' => [
                'message' => 'Benvenuto {{name}}! Grazie per esserti registrato.',
<<<<<<< HEAD
<<<<<<< HEAD
                'variables' => ['name']],
            'params' => ['name', 'email'],
            'counter' => 0]);
=======
                'variables' => ['name'],
            ],
            'params' => ['name', 'email'],
            'counter' => 0,
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                'variables' => ['name']],
            'params' => ['name', 'email'],
            'counter' => 0]);
>>>>>>> a988596b (first)
        XotBasePest::assertTableHas('notify', 'mail_templates', [
            'id' => $template->id,
            'mailable' => 'App\Mail\WelcomeMail',
            'name' => 'Welcome Email Template',
            'subject' => 'Benvenuto {{name}}!',
            'html_template' => '<h1>Benvenuto {{name}}!</h1><p>Grazie per esserti registrato.</p>',
            'text_template' => 'Benvenuto {{name}}! Grazie per esserti registrato.',
            'params' => json_encode(['name', 'email']),
<<<<<<< HEAD
<<<<<<< HEAD
            'counter' => 0]);
=======
            'counter' => 0,
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'counter' => 0]);
>>>>>>> a988596b (first)

        Assert::assertInstanceOf(MailTemplate::class, $template);
    });

    test('_has_correct_fillable_fields', function (): void {
<<<<<<< HEAD
        $template = new MailTemplate;
=======
        $template = new MailTemplate();
>>>>>>> a988596b (first)

        $expectedFillable = [
            'mailable',
            'name',
            'slug',
            'subject',
            'html_template',
            'text_template',
            'sms_template',
            'params',
<<<<<<< HEAD
<<<<<<< HEAD
            'counter'];
=======
            'counter',
        ];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'counter'];
>>>>>>> a988596b (first)

        Assert::assertEquals($expectedFillable, $template->getFillable());
    });

    test('_has_correct_casts', function (): void {
<<<<<<< HEAD
        $template = new MailTemplate;
=======
        $template = new MailTemplate();
>>>>>>> a988596b (first)

        $expectedCasts = [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
<<<<<<< HEAD
<<<<<<< HEAD
            'deleted_at' => 'datetime'];
=======
            'deleted_at' => 'datetime',
        ];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'deleted_at' => 'datetime'];
>>>>>>> a988596b (first)

        Assert::assertEquals($expectedCasts, $template->getCasts());
    });

    test('_has_translatable_fields', function (): void {
<<<<<<< HEAD
        $template = new MailTemplate;
=======
        $template = new MailTemplate();
>>>>>>> a988596b (first)

        $expectedTranslatable = [
            'subject',
            'html_template',
            'text_template',
<<<<<<< HEAD
<<<<<<< HEAD
            'sms_template'];
=======
            'sms_template',
        ];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'sms_template'];
>>>>>>> a988596b (first)

        Assert::assertEquals($expectedTranslatable, $template->translatable);
    });

    test('_uses_notify_connection', function (): void {
<<<<<<< HEAD
        $template = new MailTemplate;
=======
        $template = new MailTemplate();
>>>>>>> a988596b (first)

        Assert::assertEquals('notify', $template->getConnectionName());
    });

    test('_generates_slug_from_name', function (): void {
        $template = MailTemplateFactory::new()->createOne([
            'mailable' => 'App\Mail\TestMail',
            'name' => 'Test Email Template',
            'subject' => 'Test Subject',
            'html_template' => '<p>Test content</p>',
            'params' => ['test'],
<<<<<<< HEAD
<<<<<<< HEAD
            'counter' => 0]);
=======
            'counter' => 0,
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'counter' => 0]);
>>>>>>> a988596b (first)

        Assert::assertEquals('test-email-template', $template->slug);
        XotBasePest::assertTableHas('notify', 'mail_templates', [
            'id' => $template->id,
<<<<<<< HEAD
<<<<<<< HEAD
            'slug' => 'test-email-template']);
=======
            'slug' => 'test-email-template',
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'slug' => 'test-email-template']);
>>>>>>> a988596b (first)
    });

    test('_can_store_json_params', function (): void {
        $params = ['name', 'email', 'company', 'role'];

        $template = MailTemplateFactory::new()->createOne([
            'mailable' => 'App\Mail\ComplexMail',
            'name' => 'Complex Email Template',
            'subject' => 'Test Subject',
            'html_template' => '<p>Test content</p>',
            'params' => $params,
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
            'counter' => 0]);
        XotBasePest::assertTableHas('notify', 'mail_templates', [
            'id' => $template->id,
            'params' => json_encode($params)]);
<<<<<<< HEAD
=======
            'counter' => 0,
        ]);
        XotBasePest::assertTableHas('notify', 'mail_templates', [
            'id' => $template->id,
            'params' => json_encode($params),
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
        $params = XotBasePest::assertArray($template->params);
        Assert::assertCount(4, $params);
        Assert::assertContains('name', $params);
        Assert::assertContains('email', $params);
        Assert::assertContains('company', $params);
        Assert::assertContains('role', $params);
    });

    test('_can_store_json_sms_template', function (): void {
        $smsTemplate = [
            'message' => 'Benvenuto {{name}}! La tua email è {{email}}',
            'variables' => ['name', 'email'],
            'max_length' => 160,
<<<<<<< HEAD
<<<<<<< HEAD
            'encoding' => 'GSM7'];
=======
            'encoding' => 'GSM7',
        ];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'encoding' => 'GSM7'];
>>>>>>> a988596b (first)

        $template = MailTemplateFactory::new()->createOne([
            'mailable' => 'App\Mail\SmsMail',
            'name' => 'SMS Email Template',
            'subject' => 'Test Subject',
            'html_template' => '<p>Test content</p>',
            'sms_template' => $smsTemplate,
            'params' => ['test'],
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
            'counter' => 0]);
        XotBasePest::assertTableHas('notify', 'mail_templates', [
            'id' => $template->id,
            'sms_template' => json_encode($smsTemplate)]);
<<<<<<< HEAD
=======
            'counter' => 0,
        ]);
        XotBasePest::assertTableHas('notify', 'mail_templates', [
            'id' => $template->id,
            'sms_template' => json_encode($smsTemplate),
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
        $smsTemplateData = XotBasePest::assertArray($template->sms_template);
        Assert::assertEquals('Benvenuto {{name}}! La tua email è {{email}}', $smsTemplateData['message']);
        Assert::assertEquals(['name', 'email'], $smsTemplateData['variables']);
        Assert::assertEquals(160, $smsTemplateData['max_length']);
        Assert::assertEquals('GSM7', $smsTemplateData['encoding']);
    });

    test('_can_increment_counter', function (): void {
        $template = MailTemplateFactory::new()->createOne([
            'mailable' => 'App\Mail\CounterMail',
            'name' => 'Counter Email Template',
            'subject' => 'Test Subject',
            'html_template' => '<p>Test content</p>',
            'params' => ['test'],
<<<<<<< HEAD
<<<<<<< HEAD
            'counter' => 0]);
=======
            'counter' => 0,
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'counter' => 0]);
>>>>>>> a988596b (first)

        Assert::assertEquals(0, $template->counter);

        $template->increment('counter');
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
        Assert::assertEquals(1, \assertFreshModel($template, MailTemplate::class)->counter);

        $template->increment('counter', 5);
        Assert::assertEquals(6, \assertFreshModel($template, MailTemplate::class)->counter);
<<<<<<< HEAD
=======
        Assert::assertEquals(1, XotBasePest::assertFreshModel($template, MailTemplate::class)->counter);

        $template->increment('counter', 5);
        Assert::assertEquals(6, XotBasePest::assertFreshModel($template, MailTemplate::class)->counter);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
    });

    test('_can_update_template', function (): void {
        $template = MailTemplateFactory::new()->createOne([
            'mailable' => 'App\Mail\UpdateMail',
            'name' => 'Original Name',
            'subject' => 'Original Subject',
            'html_template' => '<p>Original content</p>',
            'params' => ['original'],
<<<<<<< HEAD
<<<<<<< HEAD
            'counter' => 0]);
=======
            'counter' => 0,
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'counter' => 0]);
>>>>>>> a988596b (first)

        $template->update([
            'name' => 'Updated Name',
            'subject' => 'Updated Subject',
            'html_template' => '<p>Updated content</p>',
<<<<<<< HEAD
<<<<<<< HEAD
            'params' => ['updated']]);
=======
            'params' => ['updated'],
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'params' => ['updated']]);
>>>>>>> a988596b (first)
        XotBasePest::assertTableHas('notify', 'mail_templates', [
            'id' => $template->id,
            'name' => 'Updated Name',
            'subject' => 'Updated Subject',
            'html_template' => '<p>Updated content</p>',
<<<<<<< HEAD
<<<<<<< HEAD
            'params' => json_encode(['updated'])]);

        Assert::assertEquals('updated-name', \assertFreshModel($template, MailTemplate::class)->slug);
=======
            'params' => json_encode(['updated']),
        ]);

        Assert::assertEquals('updated-name', XotBasePest::assertFreshModel($template, MailTemplate::class)->slug);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'params' => json_encode(['updated'])]);

        Assert::assertEquals('updated-name', \assertFreshModel($template, MailTemplate::class)->slug);
>>>>>>> a988596b (first)
    });

    test('_can_find_by_mailable_and_slug', function (): void {
        $template = MailTemplateFactory::new()->createOne([
            'mailable' => 'App\Mail\FindMail',
            'name' => 'Find Test Template',
            'subject' => 'Test Subject',
            'html_template' => '<p>Test content</p>',
            'params' => ['test'],
<<<<<<< HEAD
<<<<<<< HEAD
            'counter' => 0]);
=======
            'counter' => 0,
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'counter' => 0]);
>>>>>>> a988596b (first)

        $foundTemplate = MailTemplate::where('mailable', 'App\Mail\FindMail')
            ->where('slug', 'find-test-template')
            ->first();

        Assert::assertNotNull($foundTemplate);
        Assert::assertEquals($template->id, $foundTemplate->id);
        Assert::assertEquals('App\Mail\FindMail', $foundTemplate->mailable);
        Assert::assertEquals('find-test-template', $foundTemplate->slug);
    });

    test('_can_find_by_name', function (): void {
        $template = MailTemplateFactory::new()->createOne([
            'mailable' => 'App\Mail\NameMail',
            'name' => 'Name Search Template',
            'subject' => 'Test Subject',
            'html_template' => '<p>Test content</p>',
            'params' => ['test'],
<<<<<<< HEAD
<<<<<<< HEAD
            'counter' => 0]);
=======
            'counter' => 0,
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'counter' => 0]);
>>>>>>> a988596b (first)

        $foundTemplate = MailTemplate::where('name', 'Name Search Template')->first();

        Assert::assertNotNull($foundTemplate);
        Assert::assertEquals($template->id, $foundTemplate->id);
        Assert::assertEquals('Name Search Template', $foundTemplate->name);
    });

    test('_can_find_by_subject_pattern', function (): void {
        $template = MailTemplateFactory::new()->createOne([
            'mailable' => 'App\Mail\PatternMail',
            'name' => 'Pattern Template',
            'subject' => 'Welcome to our platform',
            'html_template' => '<p>Test content</p>',
            'params' => ['test'],
<<<<<<< HEAD
<<<<<<< HEAD
            'counter' => 0]);
=======
            'counter' => 0,
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'counter' => 0]);
>>>>>>> a988596b (first)

        $foundTemplates = MailTemplate::where('subject', 'like', '%Welcome%')->get();

        Assert::assertCount(1, $foundTemplates);
<<<<<<< HEAD
<<<<<<< HEAD
        Assert::assertEquals('Welcome to our platform', \assertFirstModel($foundTemplates, MailTemplate::class)->subject);
=======
        Assert::assertEquals('Welcome to our platform', XotBasePest::assertFirstModel($foundTemplates, MailTemplate::class)->subject);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
        Assert::assertEquals('Welcome to our platform', \assertFirstModel($foundTemplates, MailTemplate::class)->subject);
>>>>>>> a988596b (first)
    });

    test('_can_find_by_params', function (): void {
        $template = MailTemplateFactory::new()->createOne([
            'mailable' => 'App\Mail\ParamsMail',
            'name' => 'Params Template',
            'subject' => 'Test Subject',
            'html_template' => '<p>Test content</p>',
            'params' => ['name', 'email', 'company'],
<<<<<<< HEAD
<<<<<<< HEAD
            'counter' => 0]);
=======
            'counter' => 0,
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'counter' => 0]);
>>>>>>> a988596b (first)

        $foundTemplates = MailTemplate::whereJsonContains('params', 'name')->get();

        Assert::assertCount(1, $foundTemplates);
<<<<<<< HEAD
<<<<<<< HEAD
        Assert::assertEquals($template->id, \assertFirstModel($foundTemplates, MailTemplate::class)->id);
        Assert::assertContains('name', \assertNotifyArray(\assertFirstModel($foundTemplates, MailTemplate::class)->params));
=======
        Assert::assertEquals($template->id, XotBasePest::assertFirstModel($foundTemplates, MailTemplate::class)->id);
        Assert::assertContains('name', XotBasePest::assertArray(XotBasePest::assertFirstModel($foundTemplates, MailTemplate::class)->params));
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
        Assert::assertEquals($template->id, \assertFirstModel($foundTemplates, MailTemplate::class)->id);
        Assert::assertContains('name', \assertNotifyArray(\assertFirstModel($foundTemplates, MailTemplate::class)->params));
>>>>>>> a988596b (first)
    });

    test('_can_find_by_counter_range', function (): void {
        MailTemplateFactory::new()->createOne([
            'mailable' => 'App\Mail\LowCounterMail',
            'name' => 'Low Counter Template',
            'subject' => 'Test Subject',
            'html_template' => '<p>Test content</p>',
            'params' => ['test'],
<<<<<<< HEAD
<<<<<<< HEAD
            'counter' => 5]);
=======
            'counter' => 5,
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'counter' => 5]);
>>>>>>> a988596b (first)

        MailTemplateFactory::new()->createOne([
            'mailable' => 'App\Mail\HighCounterMail',
            'name' => 'High Counter Template',
            'subject' => 'Test Subject',
            'html_template' => '<p>Test content</p>',
            'params' => ['test'],
<<<<<<< HEAD
<<<<<<< HEAD
            'counter' => 50]);
=======
            'counter' => 50,
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'counter' => 50]);
>>>>>>> a988596b (first)

        $lowCounterTemplates = MailTemplate::where('counter', '<=', 10)->get();
        $highCounterTemplates = MailTemplate::where('counter', '>=', 25)->get();

        Assert::assertCount(1, $lowCounterTemplates);
        Assert::assertCount(1, $highCounterTemplates);
<<<<<<< HEAD
<<<<<<< HEAD
        Assert::assertEquals(5, \assertFirstModel($lowCounterTemplates, MailTemplate::class)->counter);
        Assert::assertEquals(50, \assertFirstModel($highCounterTemplates, MailTemplate::class)->counter);
=======
        Assert::assertEquals(5, XotBasePest::assertFirstModel($lowCounterTemplates, MailTemplate::class)->counter);
        Assert::assertEquals(50, XotBasePest::assertFirstModel($highCounterTemplates, MailTemplate::class)->counter);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
        Assert::assertEquals(5, \assertFirstModel($lowCounterTemplates, MailTemplate::class)->counter);
        Assert::assertEquals(50, \assertFirstModel($highCounterTemplates, MailTemplate::class)->counter);
>>>>>>> a988596b (first)
    });

    test('_can_handle_empty_params', function (): void {
        $template = MailTemplateFactory::new()->createOne([
            'mailable' => 'App\Mail\EmptyParamsMail',
            'name' => 'Empty Params Template',
            'subject' => 'Test Subject',
            'html_template' => '<p>Test content</p>',
            'params' => [],
<<<<<<< HEAD
<<<<<<< HEAD
            'counter' => 0]);
=======
            'counter' => 0,
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'counter' => 0]);
>>>>>>> a988596b (first)
        Assert::assertEmpty($template->params);
    });

    test('_can_handle_empty_sms_template', function (): void {
        $template = MailTemplateFactory::new()->createOne([
            'mailable' => 'App\Mail\EmptySmsMail',
            'name' => 'Empty SMS Template',
            'subject' => 'Test Subject',
            'html_template' => '<p>Test content</p>',
            'sms_template' => [],
            'params' => ['test'],
<<<<<<< HEAD
<<<<<<< HEAD
            'counter' => 0]);
=======
            'counter' => 0,
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'counter' => 0]);
>>>>>>> a988596b (first)
        Assert::assertEmpty($template->sms_template);
    });

    test('_can_store_complex_sms_template', function (): void {
        $complexSmsTemplate = [
            'message' => 'Benvenuto {{name}}!',
            'variables' => ['name', 'email'],
            'max_length' => 160,
            'encoding' => 'GSM7',
            'fallback' => [
                'enabled' => true,
                'message' => 'Welcome {{name}}!',
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
                'language' => 'en'],
            'delivery_options' => [
                'priority' => 'high',
                'retry_count' => 3,
                'timeout' => 30]];
<<<<<<< HEAD
=======
                'language' => 'en',
            ],
            'delivery_options' => [
                'priority' => 'high',
                'retry_count' => 3,
                'timeout' => 30,
            ],
        ];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)

        $template = MailTemplateFactory::new()->createOne([
            'mailable' => 'App\Mail\ComplexSmsMail',
            'name' => 'Complex SMS Template',
            'subject' => 'Test Subject',
            'html_template' => '<p>Test content</p>',
            'sms_template' => $complexSmsTemplate,
            'params' => ['test'],
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
            'counter' => 0]);
        XotBasePest::assertTableHas('notify', 'mail_templates', [
            'id' => $template->id,
            'sms_template' => json_encode($complexSmsTemplate)]);
<<<<<<< HEAD
=======
            'counter' => 0,
        ]);
        XotBasePest::assertTableHas('notify', 'mail_templates', [
            'id' => $template->id,
            'sms_template' => json_encode($complexSmsTemplate),
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)

        $smsData = XotBasePest::assertArray($template->sms_template);
        Assert::assertEquals('Benvenuto {{name}}!', $smsData['message']);
        Assert::assertEquals(['name', 'email'], $smsData['variables']);
        Assert::assertEquals(160, $smsData['max_length']);
        Assert::assertTrue(TestCase::notifyArrayGet($smsData, 'fallback', 'enabled'));
        Assert::assertEquals('high', TestCase::notifyArrayGet($smsData, 'delivery_options', 'priority'));
    });

    test('_can_find_templates_by_multiple_criteria', function (): void {
        MailTemplateFactory::new()->createOne([
            'mailable' => 'App\Mail\MultiCriteriaMail',
            'name' => 'Multi Criteria Template',
            'subject' => 'Welcome to our platform',
            'html_template' => '<p>Test content</p>',
            'params' => ['name', 'email'],
<<<<<<< HEAD
<<<<<<< HEAD
            'counter' => 10]);
=======
            'counter' => 10,
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'counter' => 10]);
>>>>>>> a988596b (first)

        MailTemplateFactory::new()->createOne([
            'mailable' => 'App\Mail\AnotherMultiCriteriaMail',
            'name' => 'Another Multi Criteria Template',
            'subject' => 'Welcome to our platform',
            'html_template' => '<p>Test content</p>',
            'params' => ['name', 'email'],
<<<<<<< HEAD
<<<<<<< HEAD
            'counter' => 20]);
=======
            'counter' => 20,
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'counter' => 20]);
>>>>>>> a988596b (first)

        $foundTemplates = MailTemplate::where('subject', 'like', '%Welcome%')
            ->whereJsonContains('params', 'name')
            ->where('counter', '>=', 15)
            ->get();

        Assert::assertCount(1, $foundTemplates);
<<<<<<< HEAD
<<<<<<< HEAD
        Assert::assertEquals('Another Multi Criteria Template', \assertFirstModel($foundTemplates, MailTemplate::class)->name);
        Assert::assertEquals(20, \assertFirstModel($foundTemplates, MailTemplate::class)->counter);
=======
        Assert::assertEquals('Another Multi Criteria Template', XotBasePest::assertFirstModel($foundTemplates, MailTemplate::class)->name);
        Assert::assertEquals(20, XotBasePest::assertFirstModel($foundTemplates, MailTemplate::class)->counter);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
        Assert::assertEquals('Another Multi Criteria Template', \assertFirstModel($foundTemplates, MailTemplate::class)->name);
        Assert::assertEquals(20, \assertFirstModel($foundTemplates, MailTemplate::class)->counter);
>>>>>>> a988596b (first)
    });

    test('_can_handle_null_values', function (): void {
        $template = MailTemplateFactory::new()->createOne([
            'mailable' => 'App\Mail\NullValuesMail',
            'name' => 'Null Values Template',
            'subject' => null,
            'html_template' => '<p>Test content</p>',
            'text_template' => null,
            'sms_template' => null,
            'params' => null,
<<<<<<< HEAD
<<<<<<< HEAD
            'counter' => 0]);
=======
            'counter' => 0,
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'counter' => 0]);
>>>>>>> a988596b (first)

        Assert::assertNull($template->subject);
        Assert::assertNull($template->text_template);
        Assert::assertNull($template->sms_template);
        Assert::assertNull($template->params);
    });

    test('_can_generate_unique_slugs', function (): void {
        MailTemplateFactory::new()->createOne([
            'mailable' => 'App\Mail\UniqueSlugMail1',
            'name' => 'Test Template',
            'subject' => 'Test Subject',
            'html_template' => '<p>Test content</p>',
            'params' => ['test'],
<<<<<<< HEAD
<<<<<<< HEAD
            'counter' => 0]);
=======
            'counter' => 0,
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'counter' => 0]);
>>>>>>> a988596b (first)

        MailTemplateFactory::new()->createOne([
            'mailable' => 'App\Mail\UniqueSlugMail2',
            'name' => 'Test Template',
            'subject' => 'Test Subject',
            'html_template' => '<p>Test content</p>',
            'params' => ['test'],
<<<<<<< HEAD
<<<<<<< HEAD
            'counter' => 0]);
=======
            'counter' => 0,
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'counter' => 0]);
>>>>>>> a988596b (first)

        $templates = MailTemplate::where('name', 'Test Template')->get();

        Assert::assertCount(2, $templates);
<<<<<<< HEAD
<<<<<<< HEAD
        Assert::assertEquals('test-template', \assertFirstModel($templates, MailTemplate::class)->slug);
        Assert::assertEquals('test-template-1', \assertFirstModel($templates->slice(1), MailTemplate::class)->slug);
=======
        Assert::assertEquals('test-template', XotBasePest::assertFirstModel($templates, MailTemplate::class)->slug);
        Assert::assertEquals('test-template-1', XotBasePest::assertFirstModel($templates->slice(1), MailTemplate::class)->slug);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
        Assert::assertEquals('test-template', \assertFirstModel($templates, MailTemplate::class)->slug);
        Assert::assertEquals('test-template-1', \assertFirstModel($templates->slice(1), MailTemplate::class)->slug);
>>>>>>> a988596b (first)
    });
});
