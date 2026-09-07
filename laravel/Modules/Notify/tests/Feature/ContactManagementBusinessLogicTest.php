<?php

declare(strict_types=1);

namespace Modules\Notify\Tests\Feature;

use Modules\Notify\Database\Factories\ContactFactory;
use Modules\Notify\Models\Contact;
<<<<<<< HEAD
<<<<<<< HEAD
use Modules\Xot\Tests\XotBasePest;
use PHPUnit\Framework\Assert;
=======
use Modules\Notify\Tests\TestCase;
use PHPUnit\Framework\Assert;
use Modules\Xot\Tests\XotBasePest;

uses(TestCase::class)->group('notify-db');
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
use Modules\Xot\Tests\XotBasePest;
use PHPUnit\Framework\Assert;
>>>>>>> a988596b (first)

describe('Contact Management Business Logic', function () {
    it('can create contact with basic information', function () {
        $contactData = [
            'model_type' => 'Modules\User\Models\User',
            'model_id' => 'user-'.uniqid(),
            'contact_type' => 'email',
            'value' => 'mario.rossi@example.com',
            'first_name' => 'Mario',
<<<<<<< HEAD
<<<<<<< HEAD
            'last_name' => 'Rossi'];
=======
            'last_name' => 'Rossi',
        ];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'last_name' => 'Rossi'];
>>>>>>> a988596b (first)

        $contact = ContactFactory::new()->createOne($contactData);

        Assert::assertSame('Mario', $contact->first_name);
        Assert::assertSame('Rossi', $contact->last_name);
        Assert::assertSame('email', $contact->contact_type);
        Assert::assertSame('mario.rossi@example.com', $contact->value);

        XotBasePest::assertTableHas('notify', 'contacts', [
            'id' => $contact->id,
            'contact_type' => 'email',
            'value' => 'mario.rossi@example.com',
            'first_name' => 'Mario',
<<<<<<< HEAD
<<<<<<< HEAD
            'last_name' => 'Rossi']);
=======
            'last_name' => 'Rossi',
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'last_name' => 'Rossi']);
>>>>>>> a988596b (first)
    });

    it('can update contact verification state', function () {
        $contact = ContactFactory::new()->createOne([
            'contact_type' => 'email',
            'value' => 'verify@example.com',
<<<<<<< HEAD
<<<<<<< HEAD
            'verified_at' => null]);
=======
            'verified_at' => null,
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'verified_at' => null]);
>>>>>>> a988596b (first)

        $verifiedAt = now()->toDateTimeString();
        $contact->update(['verified_at' => $verifiedAt]);

        $fresh = XotBasePest::assertFreshModel($contact, Contact::class);

        Assert::assertSame($verifiedAt, $fresh->verified_at);

        XotBasePest::assertTableHas('notify', 'contacts', [
            'id' => $contact->id,
<<<<<<< HEAD
<<<<<<< HEAD
            'verified_at' => $verifiedAt]);
=======
            'verified_at' => $verifiedAt,
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'verified_at' => $verifiedAt]);
>>>>>>> a988596b (first)
    });

    it('can track sms and mail counters', function () {
        $contact = ContactFactory::new()->createOne([
            'contact_type' => 'mobile_phone',
            'value' => '+393331234567',
            'sms_count' => 0,
<<<<<<< HEAD
<<<<<<< HEAD
            'mail_count' => 0]);
=======
            'mail_count' => 0,
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'mail_count' => 0]);
>>>>>>> a988596b (first)

        $contact->update([
            'sms_count' => 2,
            'mail_count' => 1,
            'sms_status_code' => '200',
<<<<<<< HEAD
<<<<<<< HEAD
            'sms_status_txt' => 'Delivered']);
=======
            'sms_status_txt' => 'Delivered',
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'sms_status_txt' => 'Delivered']);
>>>>>>> a988596b (first)

        $fresh = XotBasePest::assertFreshModel($contact, Contact::class);

        Assert::assertSame(2, $fresh->sms_count);
        Assert::assertSame(1, $fresh->mail_count);
        Assert::assertSame('200', $fresh->sms_status_code);
        Assert::assertSame('Delivered', $fresh->sms_status_txt);
    });

    it('can store extended attributes', function () {
        $contact = ContactFactory::new()->createOne([
            'attribute_1' => 'Studio Dentistico Milano',
            'attribute_2' => 'Referente',
            'usesleft' => '3',
<<<<<<< HEAD
<<<<<<< HEAD
            'order_column' => 10]);
=======
            'order_column' => 10,
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'order_column' => 10]);
>>>>>>> a988596b (first)

        $fresh = XotBasePest::assertFreshModel($contact, Contact::class);

        Assert::assertSame('Studio Dentistico Milano', $fresh->attribute_1);
        Assert::assertSame('Referente', $fresh->attribute_2);
        Assert::assertSame('3', $fresh->usesleft);
        Assert::assertSame(10, $fresh->order_column);
    });
});
