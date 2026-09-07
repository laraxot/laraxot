<?php

declare(strict_types=1);

namespace Modules\Notify\Tests\Unit\Models;

use Illuminate\Database\Eloquent\Builder;
<<<<<<< HEAD
<<<<<<< HEAD
use Illuminate\Mail\Mailable;
use Modules\Notify\Models\MailTemplate;
=======
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Notify\Models\MailTemplate;
use Modules\Notify\Tests\TestCase;
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
use Illuminate\Mail\Mailable;
use Modules\Notify\Models\MailTemplate;
>>>>>>> a988596b (first)
use PHPUnit\Framework\Assert;
use Spatie\Sluggable\HasSlug;
use Spatie\Translatable\HasTranslations;

use function Safe\class_uses;

<<<<<<< HEAD
<<<<<<< HEAD
=======
uses(TestCase::class)->group('notify-db');

>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
describe('MailTemplate Business Logic', function () {
    test('mail template extends spatie mail template', function () {
        Assert::assertTrue((new \ReflectionClass(MailTemplate::class))->isSubclassOf(\Spatie\MailTemplates\Models\MailTemplate::class));
    });

    test('mail template has slug trait for url-friendly names', function () {
        $traits = class_uses(MailTemplate::class);

        Assert::assertArrayHasKey(HasSlug::class, $traits);
    });

    test('mail template has translations trait', function () {
        $traits = class_uses(MailTemplate::class);

        Assert::assertArrayHasKey(HasTranslations::class, $traits);
    });

    test('mail template is instantiable without soft deletes requirement', function () {
<<<<<<< HEAD
<<<<<<< HEAD
        $template = new MailTemplate;
=======
        $template = new MailTemplate();
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
        $template = new MailTemplate();
>>>>>>> a988596b (first)
        Assert::assertInstanceOf(MailTemplate::class, $template);
        Assert::assertIsString($template->getTable());
    });

    test('mail template can store template content', function () {
<<<<<<< HEAD
        $mailTemplate = new MailTemplate;
=======
        $mailTemplate = new MailTemplate();
>>>>>>> a988596b (first)
        $mailTemplate->name = 'Welcome Email';
        $mailTemplate->subject = 'Welcome to our platform';
        $mailTemplate->html_template = '<h1>Welcome!</h1>';

        Assert::assertSame('Welcome Email', $mailTemplate->name);
        Assert::assertSame('Welcome to our platform', $mailTemplate->subject);
        Assert::assertSame('<h1>Welcome!</h1>', $mailTemplate->html_template);
    });

    test('mail template can link to mailable class', function () {
<<<<<<< HEAD
        $mailTemplate = new MailTemplate;
=======
        $mailTemplate = new MailTemplate();
>>>>>>> a988596b (first)
        $mailTemplate->mailable = 'App\\Mail\\WelcomeMail';

        Assert::assertSame('App\\Mail\\WelcomeMail', $mailTemplate->mailable);
    });

    test('mail template has version tracking', function () {
<<<<<<< HEAD
        $mailTemplate = new MailTemplate;
=======
        $mailTemplate = new MailTemplate();
>>>>>>> a988596b (first)
        $mailTemplate->version = 2;

        Assert::assertSame(2, $mailTemplate->version);
    });

    test('mail template can store optional text template', function () {
<<<<<<< HEAD
        $mailTemplate = new MailTemplate;
=======
        $mailTemplate = new MailTemplate();
>>>>>>> a988596b (first)
        $mailTemplate->text_template = 'Welcome! This is plain text.';

        Assert::assertSame('Welcome! This is plain text.', $mailTemplate->text_template);
    });

    test('mail template can be queried by mailable', function () {
<<<<<<< HEAD
<<<<<<< HEAD
        $mailable = new class extends Mailable
=======
        $mailable = new class extends \Illuminate\Mail\Mailable
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
        $mailable = new class() extends Mailable
>>>>>>> a988596b (first)
        {
            public function build(): static
            {
                return $this;
            }

            public function getSlug(): string
            {
                return 'welcome-mail';
            }
        };
        $query = MailTemplate::forMailable($mailable);

        Assert::assertInstanceOf(Builder::class, $query);
    });

    test('mail template has creator and updater tracking', function () {
<<<<<<< HEAD
        $mailTemplate = new MailTemplate;
=======
        $mailTemplate = new MailTemplate();
>>>>>>> a988596b (first)
        $mailTemplate->created_by = 'user-1';
        $mailTemplate->updated_by = 'user-2';

        Assert::assertSame('user-1', $mailTemplate->created_by);
        Assert::assertSame('user-2', $mailTemplate->updated_by);
    });
});
