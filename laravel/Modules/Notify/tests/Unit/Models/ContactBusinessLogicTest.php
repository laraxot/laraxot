<?php

declare(strict_types=1);

namespace Modules\Notify\Tests\Unit\Models;

<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
use Illuminate\Support\Carbon;
use Modules\Notify\Models\BaseModel;
use Modules\Notify\Models\Contact;
use PHPUnit\Framework\Assert;
<<<<<<< HEAD
use Modules\User\Models\User;
=======
use Modules\Notify\Models\BaseModel;
use Modules\Notify\Models\Contact;
use Modules\Notify\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class)->group('no-notify-db');
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)

describe('Contact Business Logic', function () {
    test('contact extends base model', function () {
        $reflection = new \ReflectionClass(Contact::class);
        $parent = $reflection->getParentClass();

        Assert::assertInstanceOf(\ReflectionClass::class, $parent);
        Assert::assertSame(BaseModel::class, $parent->getName());
    });

    test('contact can store polymorphic model relationships', function () {
<<<<<<< HEAD
        $contact = new Contact;
=======
        $contact = new Contact();
>>>>>>> a988596b (first)
        $contact->model_type = 'App\\Models\\User';
        $contact->model_id = '1';

        Assert::assertSame('App\\Models\\User', $contact->model_type);
        Assert::assertSame('1', $contact->model_id);
    });

    test('contact can store contact information with type', function () {
<<<<<<< HEAD
        $contact = new Contact;
=======
        $contact = new Contact();
>>>>>>> a988596b (first)
        $contact->contact_type = 'email';
        $contact->value = 'test@example.com';

        Assert::assertSame('email', $contact->contact_type);
        Assert::assertSame('test@example.com', $contact->value);
    });

    test('contact can track sms communication', function () {
<<<<<<< HEAD
        $contact = new Contact;
=======
        $contact = new Contact();
>>>>>>> a988596b (first)
        $contact->sms_count = 5;
        $contact->sms_status_code = '200';
        $contact->sms_status_txt = 'Success';

        Assert::assertSame(5, $contact->sms_count);
        Assert::assertSame('200', $contact->sms_status_code);
        Assert::assertSame('Success', $contact->sms_status_txt);
    });

    test('contact can track email communication', function () {
<<<<<<< HEAD
        $contact = new Contact;
        $contact->mail_count = 3;
<<<<<<< HEAD
=======
        $contact = new Contact();
        $contact->mail_count = 3;
>>>>>>> a988596b (first)
        $contact->mail_sent_at = Carbon::parse('2023-01-01 10:00:00');

        Assert::assertSame(3, $contact->mail_count);
        Assert::assertSame('2023-01-01 10:00:00', $contact->mail_sent_at->format('Y-m-d H:i:s'));
<<<<<<< HEAD
=======
        $contact->mail_sent_at = '2023-01-01 10:00:00';

        Assert::assertSame(3, $contact->mail_count);
        Assert::assertSame('2023-01-01 10:00:00', $contact->mail_sent_at);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
    });

    test('contact can store personal information', function () {
        $contact = new Contact;
=======
    });

    test('contact can store personal information', function () {
        $contact = new Contact();
>>>>>>> a988596b (first)
        $contact->first_name = 'Mario';
        $contact->last_name = 'Rossi';

        Assert::assertSame('Mario', $contact->first_name);
        Assert::assertSame('Rossi', $contact->last_name);
    });

    test('contact has verification tracking', function () {
<<<<<<< HEAD
        $contact = new Contact;
=======
        $contact = new Contact();
>>>>>>> a988596b (first)
        $contact->token = 'abc123';
        $contact->verified_at = '2023-01-01 12:00:00';

        Assert::assertSame('abc123', $contact->token);
        Assert::assertSame('2023-01-01 12:00:00', $contact->verified_at);
    });

    test('contact has flexible attribute storage', function () {
<<<<<<< HEAD
        $contact = new Contact;
=======
        $contact = new Contact();
>>>>>>> a988596b (first)
        $contact->attribute_1 = 'value1';
        $contact->attribute_2 = 'value2';

        Assert::assertSame('value1', $contact->attribute_1);
        Assert::assertSame('value2', $contact->attribute_2);
    });

    test('contact can track duplicate count', function () {
<<<<<<< HEAD
        $contact = new Contact;
=======
        $contact = new Contact();
>>>>>>> a988596b (first)
        $contact->duplicate_count = 2;

        Assert::assertSame(2, $contact->duplicate_count);
    });

    test('contact has order column for sorting', function () {
<<<<<<< HEAD
        $contact = new Contact;
=======
        $contact = new Contact();
>>>>>>> a988596b (first)
        $contact->order_column = 1;

        Assert::assertSame(1, $contact->order_column);
    });
});
