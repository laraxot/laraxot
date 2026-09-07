<?php

declare(strict_types=1);

namespace Modules\Notify\Tests\Unit\Models;

use Modules\Notify\Database\Factories\ContactFactory;
use Modules\Notify\Models\Contact;
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
use Modules\Xot\Tests\XotBasePest;
use PHPUnit\Framework\Assert;

use function Pest\Laravel\withoutExceptionHandling;
<<<<<<< HEAD
use Modules\User\Models\User;

beforeEach(function (): void {
    withoutExceptionHandling();
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

beforeEach(function (): void {
    withoutExceptionHandling();
>>>>>>> a988596b (first)
});

describe('Contact', function (): void {
    test('_can_create_contact', function (): void {
<<<<<<< HEAD
<<<<<<< HEAD
=======
        /** @var TestCase $this */
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
        $contact = ContactFactory::new()->createOne([
            'model_type' => 'App\Models\User',
            'model_id' => '123',
            'contact_type' => 'email',
            'value' => 'test@example.com',
            'user_id' => '456',
            'verified_at' => now(),
            'token' => 'verification-token-123',
            'sms_sent_at' => now(),
            'sms_count' => 1,
            'mail_sent_at' => now(),
            'mail_count' => 2,
            'survey_pdf_id' => 'pdf-789',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'mobile_phone' => '+393331234567',
            'attribute_1' => 'Company',
            'attribute_2' => 'Manager',
            'attribute_3' => 'Department',
            'attribute_4' => 'Location',
            'attribute_5' => 'Notes',
            'usesleft' => '5',
            'sms_status_code' => '200',
            'sms_status_txt' => 'Delivered',
            'duplicate_count' => 0,
<<<<<<< HEAD
<<<<<<< HEAD
            'order_column' => 1]);
=======
            'order_column' => 1,
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'order_column' => 1]);
>>>>>>> a988596b (first)
        XotBasePest::assertTableHas('notify', 'contacts', [
            'id' => $contact->id,
            'model_type' => 'App\Models\User',
            'model_id' => '123',
            'contact_type' => 'email',
            'value' => 'test@example.com',
            'user_id' => '456',
            'token' => 'verification-token-123',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'mobile_phone' => '+393331234567',
            'attribute_1' => 'Company',
            'attribute_2' => 'Manager',
            'attribute_3' => 'Department',
            'attribute_4' => 'Location',
            'attribute_5' => 'Notes',
            'usesleft' => '5',
            'sms_status_code' => '200',
            'sms_status_txt' => 'Delivered',
            'duplicate_count' => 0,
<<<<<<< HEAD
<<<<<<< HEAD
            'order_column' => 1]);
=======
            'order_column' => 1,
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'order_column' => 1]);
>>>>>>> a988596b (first)

        Assert::assertInstanceOf(Contact::class, $contact);
    });

    test('_has_correct_fillable_fields', function (): void {
<<<<<<< HEAD
        $contact = new Contact;
=======
        $contact = new Contact();
>>>>>>> a988596b (first)

        $expectedFillable = [
            'model_id',
            'model_type',
            'contact_type',
            'value',
            'verified_at',
            'updated_at',
            'created_at',
            'updated_by',
            'created_by',
            'user_id',
<<<<<<< HEAD
<<<<<<< HEAD
            'token'];
=======
            'token',
        ];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'token'];
>>>>>>> a988596b (first)

        Assert::assertEquals($expectedFillable, $contact->getFillable());
    });

    test('_has_correct_casts', function (): void {
<<<<<<< HEAD
        $contact = new Contact;
=======
        $contact = new Contact();
>>>>>>> a988596b (first)

        $expectedCasts = [
            'id' => 'string',
            'uuid' => 'string',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
            'updated_by' => 'string',
            'created_by' => 'string',
            'deleted_by' => 'string',
            'model_id' => 'string',
<<<<<<< HEAD
<<<<<<< HEAD
            'user_id' => 'string'];
=======
            'user_id' => 'string',
        ];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'user_id' => 'string'];
>>>>>>> a988596b (first)

        Assert::assertEquals($expectedCasts, $contact->getCasts());
    });

    test('_can_store_contact_with_minimal_fields', function (): void {
        $contact = ContactFactory::new()->createOne([
            'model_type' => 'App\Models\User',
            'model_id' => '123',
            'contact_type' => 'phone',
<<<<<<< HEAD
<<<<<<< HEAD
            'value' => '+393331234567']);
=======
            'value' => '+393331234567',
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'value' => '+393331234567']);
>>>>>>> a988596b (first)
        XotBasePest::assertTableHas('notify', 'contacts', [
            'id' => $contact->id,
            'model_type' => 'App\Models\User',
            'model_id' => '123',
            'contact_type' => 'phone',
<<<<<<< HEAD
<<<<<<< HEAD
            'value' => '+393331234567']);
=======
            'value' => '+393331234567',
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'value' => '+393331234567']);
>>>>>>> a988596b (first)

        Assert::assertInstanceOf(Contact::class, $contact);
    });

    test('_can_store_contact_with_all_attributes', function (): void {
        $contact = ContactFactory::new()->createOne([
            'model_type' => 'App\Models\Company',
            'model_id' => '789',
            'contact_type' => 'email',
            'value' => 'info@company.com',
            'user_id' => '456',
            'verified_at' => now(),
            'token' => 'verification-token-456',
            'sms_sent_at' => now(),
            'sms_count' => 3,
            'mail_sent_at' => now(),
            'mail_count' => 5,
            'survey_pdf_id' => 'pdf-456',
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'email' => 'jane.smith@company.com',
            'mobile_phone' => '+393339876543',
            'attribute_1' => 'Position',
            'attribute_2' => 'Senior Manager',
            'attribute_3' => 'IT Department',
            'attribute_4' => 'Milan Office',
            'attribute_5' => 'Technical Lead',
            'attribute_6' => 'Project A',
            'attribute_7' => 'Team B',
            'attribute_8' => 'Budget 100k',
            'attribute_9' => 'Deadline Q1',
            'attribute_10' => 'Priority High',
            'attribute_11' => 'Status Active',
            'attribute_12' => 'Category Premium',
            'attribute_13' => 'Region North',
            'attribute_14' => 'Zone Central',
            'usesleft' => '10',
            'sms_status_code' => '201',
            'sms_status_txt' => 'Queued',
            'duplicate_count' => 1,
<<<<<<< HEAD
<<<<<<< HEAD
            'order_column' => 2]);
=======
            'order_column' => 2,
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'order_column' => 2]);
>>>>>>> a988596b (first)
        XotBasePest::assertTableHas('notify', 'contacts', [
            'id' => $contact->id,
            'model_type' => 'App\Models\Company',
            'model_id' => '789',
            'contact_type' => 'email',
            'value' => 'info@company.com',
            'user_id' => '456',
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'email' => 'jane.smith@company.com',
            'mobile_phone' => '+393339876543',
            'attribute_1' => 'Position',
            'attribute_2' => 'Senior Manager',
            'attribute_3' => 'IT Department',
            'attribute_4' => 'Milan Office',
            'attribute_5' => 'Technical Lead',
            'attribute_6' => 'Project A',
            'attribute_7' => 'Team B',
            'attribute_8' => 'Budget 100k',
            'attribute_9' => 'Deadline Q1',
            'attribute_10' => 'Priority High',
            'attribute_11' => 'Status Active',
            'attribute_12' => 'Category Premium',
            'attribute_13' => 'Region North',
            'attribute_14' => 'Zone Central',
            'usesleft' => '10',
            'sms_status_code' => '201',
            'sms_status_txt' => 'Queued',
            'duplicate_count' => 1,
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
            'order_column' => 2]);
    });

    test('_can_update_contact', function (): void {
<<<<<<< HEAD
=======
            'order_column' => 2,
        ]);
    });

    test('_can_update_contact', function (): void {
        /** @var TestCase $this */
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
        $contact = ContactFactory::new()->createOne([
            'model_type' => 'App\Models\User',
            'model_id' => '123',
            'contact_type' => 'email',
            'value' => 'old@example.com',
            'first_name' => 'Old Name',
            'last_name' => 'Old Surname',
            'email' => 'old.email@example.com',
<<<<<<< HEAD
<<<<<<< HEAD
            'mobile_phone' => '+393330000000']);
=======
            'mobile_phone' => '+393330000000',
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'mobile_phone' => '+393330000000']);
>>>>>>> a988596b (first)

        $contact->update([
            'value' => 'new@example.com',
            'first_name' => 'New Name',
            'last_name' => 'New Surname',
            'email' => 'new.email@example.com',
            'mobile_phone' => '+393331111111',
            'verified_at' => now(),
<<<<<<< HEAD
<<<<<<< HEAD
            'token' => 'new-token-123']);
=======
            'token' => 'new-token-123',
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'token' => 'new-token-123']);
>>>>>>> a988596b (first)
        XotBasePest::assertTableHas('notify', 'contacts', [
            'id' => $contact->id,
            'value' => 'new@example.com',
            'first_name' => 'New Name',
            'last_name' => 'New Surname',
            'email' => 'new.email@example.com',
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
            'mobile_phone' => '+393331111111']);

        Assert::assertNotNull(assertFreshModel($contact, Contact::class)->verified_at);
        Assert::assertEquals('new-token-123', assertFreshModel($contact, Contact::class)->token);
<<<<<<< HEAD
=======
            'mobile_phone' => '+393331111111',
        ]);

        Assert::assertNotNull($this->freshModel($contact, Contact::class)->verified_at);
        Assert::assertEquals('new-token-123', $this->freshModel($contact, Contact::class)->token);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
    });

    test('_can_find_by_model_type_and_id', function (): void {
        $contact = ContactFactory::new()->createOne([
            'model_type' => 'App\Models\User',
            'model_id' => '123',
            'contact_type' => 'email',
<<<<<<< HEAD
<<<<<<< HEAD
            'value' => 'test@example.com']);
=======
            'value' => 'test@example.com',
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'value' => 'test@example.com']);
>>>>>>> a988596b (first)

        $foundContact = Contact::where('model_type', 'App\Models\User')->where('model_id', '123')->first();

        Assert::assertNotNull($foundContact);
        Assert::assertEquals($contact->id, $foundContact->id);
        Assert::assertEquals('App\Models\User', $foundContact->model_type);
        Assert::assertEquals('123', $foundContact->model_id);
    });

    test('_can_find_by_contact_type', function (): void {
<<<<<<< HEAD
<<<<<<< HEAD
=======
        /** @var TestCase $this */
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
        ContactFactory::new()->createOne([
            'model_type' => 'App\Models\User',
            'model_id' => '123',
            'contact_type' => 'email',
<<<<<<< HEAD
<<<<<<< HEAD
            'value' => 'email@example.com']);
=======
            'value' => 'email@example.com',
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'value' => 'email@example.com']);
>>>>>>> a988596b (first)

        ContactFactory::new()->createOne([
            'model_type' => 'App\Models\User',
            'model_id' => '456',
            'contact_type' => 'phone',
<<<<<<< HEAD
<<<<<<< HEAD
            'value' => '+393331234567']);
=======
            'value' => '+393331234567',
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'value' => '+393331234567']);
>>>>>>> a988596b (first)

        ContactFactory::new()->createOne([
            'model_type' => 'App\Models\Company',
            'model_id' => '789',
            'contact_type' => 'email',
<<<<<<< HEAD
<<<<<<< HEAD
            'value' => 'company@example.com']);
=======
            'value' => 'company@example.com',
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'value' => 'company@example.com']);
>>>>>>> a988596b (first)

        $emailContacts = Contact::where('contact_type', 'email')->get();
        $phoneContacts = Contact::where('contact_type', 'phone')->get();

        Assert::assertCount(2, $emailContacts);
        Assert::assertCount(1, $phoneContacts);
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
        Assert::assertEquals('email', assertFirstModel($emailContacts, Contact::class)->contact_type);
        Assert::assertEquals('phone', assertFirstModel($phoneContacts, Contact::class)->contact_type);
    });

    test('_can_find_by_user_id', function (): void {
<<<<<<< HEAD
=======
        Assert::assertEquals('email', $this->firstModel($emailContacts, Contact::class)->contact_type);
        Assert::assertEquals('phone', $this->firstModel($phoneContacts, Contact::class)->contact_type);
    });

    test('_can_find_by_user_id', function (): void {
        /** @var TestCase $this */
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
        ContactFactory::new()->createOne([
            'model_type' => 'App\Models\User',
            'model_id' => '123',
            'contact_type' => 'email',
            'value' => 'user1@example.com',
<<<<<<< HEAD
<<<<<<< HEAD
            'user_id' => '456']);
=======
            'user_id' => '456',
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'user_id' => '456']);
>>>>>>> a988596b (first)

        ContactFactory::new()->createOne([
            'model_type' => 'App\Models\User',
            'model_id' => '789',
            'contact_type' => 'phone',
            'value' => '+393331234567',
<<<<<<< HEAD
<<<<<<< HEAD
            'user_id' => '456']);
=======
            'user_id' => '456',
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'user_id' => '456']);
>>>>>>> a988596b (first)

        ContactFactory::new()->createOne([
            'model_type' => 'App\Models\Company',
            'model_id' => '101',
            'contact_type' => 'email',
            'value' => 'company@example.com',
<<<<<<< HEAD
<<<<<<< HEAD
            'user_id' => '789']);
=======
            'user_id' => '789',
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'user_id' => '789']);
>>>>>>> a988596b (first)

        $user456Contacts = Contact::where('user_id', '456')->get();
        $user789Contacts = Contact::where('user_id', '789')->get();

        Assert::assertCount(2, $user456Contacts);
        Assert::assertCount(1, $user789Contacts);
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
        Assert::assertEquals('456', assertFirstModel($user456Contacts, Contact::class)->user_id);
        $secondUserContact = $user456Contacts->get(1);
        Assert::assertInstanceOf(Contact::class, $secondUserContact);
        Assert::assertEquals('456', $secondUserContact->user_id);
        Assert::assertEquals('789', assertFirstModel($user789Contacts, Contact::class)->user_id);
<<<<<<< HEAD
=======
        Assert::assertEquals('456', $this->firstModel($user456Contacts, Contact::class)->user_id);
        $secondUserContact = $user456Contacts->get(1);
        Assert::assertInstanceOf(Contact::class, $secondUserContact);
        Assert::assertEquals('456', $secondUserContact->user_id);
        Assert::assertEquals('789', $this->firstModel($user789Contacts, Contact::class)->user_id);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
    });

    test('_can_find_by_email', function (): void {
        $contact = ContactFactory::new()->createOne([
            'model_type' => 'App\Models\User',
            'model_id' => '123',
            'contact_type' => 'email',
            'value' => 'test@example.com',
<<<<<<< HEAD
<<<<<<< HEAD
            'email' => 'test@example.com']);
=======
            'email' => 'test@example.com',
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'email' => 'test@example.com']);
>>>>>>> a988596b (first)

        $foundContact = Contact::where('email', 'test@example.com')->first();

        Assert::assertNotNull($foundContact);
        Assert::assertInstanceOf(Contact::class, $foundContact);
        Assert::assertEquals($contact->id, $foundContact->id);
        Assert::assertEquals('test@example.com', $foundContact->value);
    });

    test('_can_find_by_mobile_phone', function (): void {
        $contact = ContactFactory::new()->createOne([
            'model_type' => 'App\Models\User',
            'model_id' => '123',
            'contact_type' => 'phone',
            'value' => '+393331234567',
<<<<<<< HEAD
<<<<<<< HEAD
            'mobile_phone' => '+393331234567']);
=======
            'mobile_phone' => '+393331234567',
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'mobile_phone' => '+393331234567']);
>>>>>>> a988596b (first)

        $foundContact = Contact::where('mobile_phone', '+393331234567')->first();

        Assert::assertNotNull($foundContact);
        Assert::assertInstanceOf(Contact::class, $foundContact);
        Assert::assertEquals($contact->id, $foundContact->id);
        Assert::assertEquals('+393331234567', $foundContact->value);
    });

    test('_can_find_by_name_pattern', function (): void {
<<<<<<< HEAD
<<<<<<< HEAD
=======
        /** @var TestCase $this */
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
        ContactFactory::new()->createOne([
            'model_type' => 'App\Models\User',
            'model_id' => '123',
            'contact_type' => 'email',
            'value' => 'john@example.com',
            'first_name' => 'John',
<<<<<<< HEAD
<<<<<<< HEAD
            'last_name' => 'Doe']);
=======
            'last_name' => 'Doe',
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'last_name' => 'Doe']);
>>>>>>> a988596b (first)

        ContactFactory::new()->createOne([
            'model_type' => 'App\Models\User',
            'model_id' => '456',
            'contact_type' => 'email',
            'value' => 'jane@example.com',
            'first_name' => 'Jane',
<<<<<<< HEAD
<<<<<<< HEAD
            'last_name' => 'Smith']);
=======
            'last_name' => 'Smith',
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'last_name' => 'Smith']);
>>>>>>> a988596b (first)

        ContactFactory::new()->createOne([
            'model_type' => 'App\Models\User',
            'model_id' => '789',
            'contact_type' => 'email',
            'value' => 'bob@example.com',
            'first_name' => 'Bob',
<<<<<<< HEAD
<<<<<<< HEAD
            'last_name' => 'Johnson']);
=======
            'last_name' => 'Johnson',
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'last_name' => 'Johnson']);
>>>>>>> a988596b (first)

        $johnContacts = Contact::where('first_name', 'like', '%John%')->get();
        $doeContacts = Contact::where('last_name', 'like', '%Doe%')->get();
        $jContacts = Contact::where('first_name', 'like', 'J%')->get();

        Assert::assertCount(1, $johnContacts);
        Assert::assertCount(1, $doeContacts);
        Assert::assertCount(2, $jContacts); // John and Jane
<<<<<<< HEAD
<<<<<<< HEAD
        Assert::assertEquals('John', assertFirstModel($johnContacts, Contact::class)->first_name);
        Assert::assertEquals('Doe', assertFirstModel($doeContacts, Contact::class)->last_name);
=======
        Assert::assertEquals('John', $this->firstModel($johnContacts, Contact::class)->first_name);
        Assert::assertEquals('Doe', $this->firstModel($doeContacts, Contact::class)->last_name);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
        Assert::assertEquals('John', assertFirstModel($johnContacts, Contact::class)->first_name);
        Assert::assertEquals('Doe', assertFirstModel($doeContacts, Contact::class)->last_name);
>>>>>>> a988596b (first)
    });

    test('_can_find_by_token', function (): void {
        $contact = ContactFactory::new()->createOne([
            'model_type' => 'App\Models\User',
            'model_id' => '123',
            'contact_type' => 'email',
            'value' => 'test@example.com',
<<<<<<< HEAD
<<<<<<< HEAD
            'token' => 'unique-token-123']);
=======
            'token' => 'unique-token-123',
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'token' => 'unique-token-123']);
>>>>>>> a988596b (first)

        $foundContact = Contact::where('token', 'unique-token-123')->first();

        Assert::assertNotNull($foundContact);
        Assert::assertEquals($contact->id, $foundContact->id);
        Assert::assertEquals('unique-token-123', $foundContact->token);
    });

    test('_can_find_by_verification_status', function (): void {
<<<<<<< HEAD
<<<<<<< HEAD
=======
        /** @var TestCase $this */
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
        ContactFactory::new()->createOne([
            'model_type' => 'App\Models\User',
            'model_id' => '123',
            'contact_type' => 'email',
            'value' => 'verified@example.com',
<<<<<<< HEAD
<<<<<<< HEAD
            'verified_at' => now()]);
=======
            'verified_at' => now(),
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'verified_at' => now()]);
>>>>>>> a988596b (first)

        ContactFactory::new()->createOne([
            'model_type' => 'App\Models\User',
            'model_id' => '456',
            'contact_type' => 'email',
            'value' => 'unverified@example.com',
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

        $verifiedContacts = Contact::whereNotNull('verified_at')->get();
        $unverifiedContacts = Contact::whereNull('verified_at')->get();

        Assert::assertCount(1, $verifiedContacts);
        Assert::assertCount(1, $unverifiedContacts);
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
        Assert::assertNotNull(assertFirstModel($verifiedContacts, Contact::class)->verified_at);
        Assert::assertNull(assertFirstModel($unverifiedContacts, Contact::class)->verified_at);
    });

    test('_can_find_by_sms_status', function (): void {
<<<<<<< HEAD
=======
        Assert::assertNotNull($this->firstModel($verifiedContacts, Contact::class)->verified_at);
        Assert::assertNull($this->firstModel($unverifiedContacts, Contact::class)->verified_at);
    });

    test('_can_find_by_sms_status', function (): void {
        /** @var TestCase $this */
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
        ContactFactory::new()->createOne([
            'model_type' => 'App\Models\User',
            'model_id' => '123',
            'contact_type' => 'phone',
            'value' => '+393331234567',
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

        ContactFactory::new()->createOne([
            'model_type' => 'App\Models\User',
            'model_id' => '456',
            'contact_type' => 'phone',
            'value' => '+393339876543',
            'sms_status_code' => '400',
<<<<<<< HEAD
<<<<<<< HEAD
            'sms_status_txt' => 'Failed']);
=======
            'sms_status_txt' => 'Failed',
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'sms_status_txt' => 'Failed']);
>>>>>>> a988596b (first)

        $deliveredSms = Contact::where('sms_status_code', '200')->get();
        $failedSms = Contact::where('sms_status_code', '400')->get();

        Assert::assertCount(1, $deliveredSms);
        Assert::assertCount(1, $failedSms);
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
        Assert::assertEquals('200', assertFirstModel($deliveredSms, Contact::class)->sms_status_code);
        Assert::assertEquals('400', assertFirstModel($failedSms, Contact::class)->sms_status_code);
        Assert::assertEquals('Delivered', assertFirstModel($deliveredSms, Contact::class)->sms_status_txt);
        Assert::assertEquals('Failed', assertFirstModel($failedSms, Contact::class)->sms_status_txt);
    });

    test('_can_find_by_counters', function (): void {
<<<<<<< HEAD
=======
        Assert::assertEquals('200', $this->firstModel($deliveredSms, Contact::class)->sms_status_code);
        Assert::assertEquals('400', $this->firstModel($failedSms, Contact::class)->sms_status_code);
        Assert::assertEquals('Delivered', $this->firstModel($deliveredSms, Contact::class)->sms_status_txt);
        Assert::assertEquals('Failed', $this->firstModel($failedSms, Contact::class)->sms_status_txt);
    });

    test('_can_find_by_counters', function (): void {
        /** @var TestCase $this */
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
        ContactFactory::new()->createOne([
            'model_type' => 'App\Models\User',
            'model_id' => '123',
            'contact_type' => 'email',
            'value' => 'low@example.com',
            'sms_count' => 1,
<<<<<<< HEAD
<<<<<<< HEAD
            'mail_count' => 2]);
=======
            'mail_count' => 2,
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'mail_count' => 2]);
>>>>>>> a988596b (first)

        ContactFactory::new()->createOne([
            'model_type' => 'App\Models\User',
            'model_id' => '456',
            'contact_type' => 'email',
            'value' => 'high@example.com',
            'sms_count' => 10,
<<<<<<< HEAD
<<<<<<< HEAD
            'mail_count' => 25]);
=======
            'mail_count' => 25,
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'mail_count' => 25]);
>>>>>>> a988596b (first)

        $lowSmsContacts = Contact::where('sms_count', '<=', 5)->get();
        $highMailContacts = Contact::where('mail_count', '>=', 20)->get();

        Assert::assertCount(1, $lowSmsContacts);
        Assert::assertCount(1, $highMailContacts);
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
        Assert::assertEquals(1, assertFirstModel($lowSmsContacts, Contact::class)->sms_count);
        Assert::assertEquals(25, assertFirstModel($highMailContacts, Contact::class)->mail_count);
    });

    test('_can_find_by_attributes', function (): void {
<<<<<<< HEAD
=======
        Assert::assertEquals(1, $this->firstModel($lowSmsContacts, Contact::class)->sms_count);
        Assert::assertEquals(25, $this->firstModel($highMailContacts, Contact::class)->mail_count);
    });

    test('_can_find_by_attributes', function (): void {
        /** @var TestCase $this */
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
        ContactFactory::new()->createOne([
            'model_type' => 'App\Models\User',
            'model_id' => '123',
            'contact_type' => 'email',
            'value' => 'manager@example.com',
            'attribute_1' => 'Position',
            'attribute_2' => 'Manager',
<<<<<<< HEAD
<<<<<<< HEAD
            'attribute_3' => 'IT Department']);
=======
            'attribute_3' => 'IT Department',
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'attribute_3' => 'IT Department']);
>>>>>>> a988596b (first)

        ContactFactory::new()->createOne([
            'model_type' => 'App\Models\User',
            'model_id' => '456',
            'contact_type' => 'email',
            'value' => 'developer@example.com',
            'attribute_1' => 'Position',
            'attribute_2' => 'Developer',
<<<<<<< HEAD
<<<<<<< HEAD
            'attribute_3' => 'IT Department']);
=======
            'attribute_3' => 'IT Department',
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'attribute_3' => 'IT Department']);
>>>>>>> a988596b (first)

        $managers = Contact::where('attribute_2', 'Manager')->get();
        $itDepartment = Contact::where('attribute_3', 'IT Department')->get();

        Assert::assertCount(1, $managers);
        Assert::assertCount(2, $itDepartment);
<<<<<<< HEAD
<<<<<<< HEAD
        Assert::assertEquals('Manager', assertFirstModel($managers, Contact::class)->attribute_2);
        Assert::assertEquals('IT Department', assertFirstModel($itDepartment, Contact::class)->attribute_3);
=======
        Assert::assertEquals('Manager', $this->firstModel($managers, Contact::class)->attribute_2);
        Assert::assertEquals('IT Department', $this->firstModel($itDepartment, Contact::class)->attribute_3);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
        Assert::assertEquals('Manager', assertFirstModel($managers, Contact::class)->attribute_2);
        Assert::assertEquals('IT Department', assertFirstModel($itDepartment, Contact::class)->attribute_3);
>>>>>>> a988596b (first)
        $secondItContact = $itDepartment->get(1);
        Assert::assertInstanceOf(Contact::class, $secondItContact);
        Assert::assertEquals('IT Department', $secondItContact->attribute_3);
    });

    test('_can_find_by_multiple_criteria', function (): void {
<<<<<<< HEAD
<<<<<<< HEAD
=======
        /** @var TestCase $this */
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
        ContactFactory::new()->createOne([
            'model_type' => 'App\Models\User',
            'model_id' => '123',
            'contact_type' => 'email',
            'value' => 'verified@example.com',
            'verified_at' => now(),
            'sms_count' => 5,
<<<<<<< HEAD
<<<<<<< HEAD
            'attribute_1' => 'Manager']);
=======
            'attribute_1' => 'Manager',
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'attribute_1' => 'Manager']);
>>>>>>> a988596b (first)

        ContactFactory::new()->createOne([
            'model_type' => 'App\Models\User',
            'model_id' => '456',
            'contact_type' => 'email',
            'value' => 'unverified@example.com',
            'verified_at' => null,
            'sms_count' => 2,
<<<<<<< HEAD
<<<<<<< HEAD
            'attribute_1' => 'Developer']);
=======
            'attribute_1' => 'Developer',
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'attribute_1' => 'Developer']);
>>>>>>> a988596b (first)

        $verifiedManagers = Contact::whereNotNull('verified_at')
            ->where('attribute_1', 'Manager')
            ->where('sms_count', '>=', 3)
            ->get();

        Assert::assertCount(1, $verifiedManagers);
<<<<<<< HEAD
<<<<<<< HEAD
        $verifiedManager = assertFirstModel($verifiedManagers, Contact::class);
=======
        $verifiedManager = $this->firstModel($verifiedManagers, Contact::class);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
        $verifiedManager = assertFirstModel($verifiedManagers, Contact::class);
>>>>>>> a988596b (first)
        Assert::assertEquals('verified@example.com', $verifiedManager->value);
        Assert::assertEquals('Manager', $verifiedManager->attribute_1);
        Assert::assertEquals(5, $verifiedManager->sms_count);
    });

    test('_can_handle_null_values', function (): void {
        $contact = ContactFactory::new()->createOne([
            'model_type' => 'App\Models\User',
            'model_id' => '123',
            'contact_type' => 'email',
            'value' => 'test@example.com',
            'first_name' => null,
            'last_name' => null,
            'email' => null,
            'mobile_phone' => null,
            'verified_at' => null,
<<<<<<< HEAD
<<<<<<< HEAD
            'token' => null]);
=======
            'token' => null,
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'token' => null]);
>>>>>>> a988596b (first)

        Assert::assertNull($contact->first_name);
        Assert::assertNull($contact->last_name);
        Assert::assertNull($contact->verified_at);
        Assert::assertNull($contact->token);
    });

    test('_can_order_by_order_column', function (): void {
<<<<<<< HEAD
<<<<<<< HEAD
=======
        /** @var TestCase $this */
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
        ContactFactory::new()->createOne([
            'model_type' => 'App\Models\User',
            'model_id' => '123',
            'contact_type' => 'email',
            'value' => 'third@example.com',
<<<<<<< HEAD
<<<<<<< HEAD
            'order_column' => 3]);
=======
            'order_column' => 3,
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'order_column' => 3]);
>>>>>>> a988596b (first)

        ContactFactory::new()->createOne([
            'model_type' => 'App\Models\User',
            'model_id' => '456',
            'contact_type' => 'email',
            'value' => 'first@example.com',
<<<<<<< HEAD
<<<<<<< HEAD
            'order_column' => 1]);
=======
            'order_column' => 1,
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'order_column' => 1]);
>>>>>>> a988596b (first)

        ContactFactory::new()->createOne([
            'model_type' => 'App\Models\User',
            'model_id' => '789',
            'contact_type' => 'email',
            'value' => 'second@example.com',
<<<<<<< HEAD
<<<<<<< HEAD
            'order_column' => 2]);
=======
            'order_column' => 2,
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'order_column' => 2]);
>>>>>>> a988596b (first)

        $orderedContacts = Contact::orderBy('order_column')->get();

        Assert::assertCount(3, $orderedContacts);
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
        Assert::assertEquals('first@example.com', assertFirstModel($orderedContacts, Contact::class)->value);
        Assert::assertEquals('second@example.com', $orderedContacts->get(1)?->value);
        Assert::assertEquals('third@example.com', $orderedContacts->get(2)?->value);
        Assert::assertEquals(1, assertFirstModel($orderedContacts, Contact::class)->order_column);
<<<<<<< HEAD
=======
        Assert::assertEquals('first@example.com', $this->firstModel($orderedContacts, Contact::class)->value);
        Assert::assertEquals('second@example.com', $orderedContacts->get(1)?->value);
        Assert::assertEquals('third@example.com', $orderedContacts->get(2)?->value);
        Assert::assertEquals(1, $this->firstModel($orderedContacts, Contact::class)->order_column);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
        Assert::assertEquals(2, $orderedContacts->get(1)?->order_column);
        Assert::assertEquals(3, $orderedContacts->get(2)?->order_column);
    });
});
