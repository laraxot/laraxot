<?php

declare(strict_types=1);

<<<<<<< HEAD
<<<<<<< HEAD
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Validation\Rules\Password;
use Modules\User\Database\Factories\SocialiteUserFactory;
use Modules\User\Database\Factories\UserFactory;
=======
=======
>>>>>>> 87273113 (.)
namespace Modules\User\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Illuminate\Validation\Rules\Password;
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Validation\Rules\Password;
use Modules\User\Database\Factories\SocialiteUserFactory;
use Modules\User\Database\Factories\UserFactory;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
use Modules\User\Datas\PasswordData;
use Modules\User\Events\AddingTeam;
use Modules\User\Events\Login;
use Modules\User\Events\Registered;
use Modules\User\Events\SocialiteUserConnected;
<<<<<<< HEAD
<<<<<<< HEAD
use Modules\User\Models\SocialiteUser;
use Modules\User\Models\User;
use Modules\User\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

it('password data can be instantiated', function (): void {
    $passwordData = new PasswordData;

    Assert::assertInstanceOf(PasswordData::class, $passwordData);
    Assert::assertSame(5, $passwordData->otp_expiration_minutes);
    Assert::assertSame(6, $passwordData->otp_length);
    Assert::assertSame(60, $passwordData->expires_in);
    Assert::assertSame(8, $passwordData->min);
    Assert::assertTrue($passwordData->mixedCase);
    Assert::assertTrue($passwordData->letters);
    Assert::assertTrue($passwordData->numbers);
    Assert::assertTrue($passwordData->symbols);
    Assert::assertTrue($passwordData->uncompromised);
    Assert::assertSame(0, $passwordData->compromisedThreshold);
});

it('password data can be configured', function (): void {
    $passwordData = new PasswordData(
        otp_expiration_minutes: 30,
        otp_length: 8,
        expires_in: 60,
        min: 8,
        mixedCase: true,
        letters: true,
        numbers: true,
        symbols: true,
        uncompromised: true,
        compromisedThreshold: 5
    );

    Assert::assertSame(30, $passwordData->otp_expiration_minutes);
    Assert::assertSame(8, $passwordData->otp_length);
    Assert::assertSame(60, $passwordData->expires_in);
    Assert::assertSame(8, $passwordData->min);
    Assert::assertTrue($passwordData->mixedCase);
    Assert::assertTrue($passwordData->letters);
    Assert::assertTrue($passwordData->numbers);
    Assert::assertTrue($passwordData->symbols);
    Assert::assertTrue($passwordData->uncompromised);
    Assert::assertSame(5, $passwordData->compromisedThreshold);
});

it('password data get password rule works', function (): void {
    $passwordData = new PasswordData(
        min: 8,
        mixedCase: true,
        letters: true,
        numbers: true,
        symbols: true,
        uncompromised: true,
        compromisedThreshold: 3
    );

    $rule = $passwordData->getPasswordRule();

    Assert::assertInstanceOf(Password::class, $rule);
});

it('password data get helper text works', function (): void {
    $passwordData = new PasswordData(
        min: 8,
        mixedCase: true,
        letters: true,
        numbers: true,
        symbols: true,
        uncompromised: true
    );

    $helperText = $passwordData->getHelperText();

    Assert::assertStringContainsString('8 caratteri', $helperText);
    Assert::assertStringContainsString('maiuscola e una minuscola', $helperText);
    Assert::assertStringContainsString('lettera', $helperText);
    Assert::assertStringContainsString('numero', $helperText);
    Assert::assertStringContainsString('carattere speciale', $helperText);
    Assert::assertStringContainsString('compromessa', $helperText);
});

it('password data get form components returns array', function (): void {
    $passwordData = new PasswordData;

    // Smoke tests: methods should be callable without throwing.
    $passwordData->getPasswordFormComponent('password');
    $passwordData->setFieldName('password');
    $passwordData->getPasswordConfirmationFormComponent();
});

it('events can be instantiated', function (): void {
    $userFactory = UserFactory::new();
    \assert($userFactory instanceof Factory);
    $owner = $userFactory->create();
    \assert($owner instanceof User);

    $socialiteFactory = SocialiteUserFactory::new();
    \assert($socialiteFactory instanceof Factory);
    $ownerKey = $owner->getKey();
    $socialiteUser = $socialiteFactory->create([
        'user_id' => (is_int($ownerKey) || is_string($ownerKey)) ? (string) $ownerKey : '',
        'provider' => 'github',
        'provider_id' => 'provider-'.uniqid(),
    ]);
    \assert($socialiteUser instanceof SocialiteUser);

    $addingTeam = new AddingTeam($owner);
    $login = new Login($socialiteUser);
    $registered = new Registered($socialiteUser);
    $socialiteUserConnected = new SocialiteUserConnected($socialiteUser);

    Assert::assertInstanceOf(AddingTeam::class, $addingTeam);
    Assert::assertInstanceOf(Login::class, $login);
    Assert::assertInstanceOf(Registered::class, $registered);
    Assert::assertInstanceOf(SocialiteUserConnected::class, $socialiteUserConnected);
});

it('events have dispatchable trait', function (): void {
    $userFactory = UserFactory::new();
    \assert($userFactory instanceof Factory);
    $owner = $userFactory->create();
    \assert($owner instanceof User);

    $socialiteFactory = SocialiteUserFactory::new();
    \assert($socialiteFactory instanceof Factory);
    $ownerKey = $owner->getKey();
    $socialiteUser = $socialiteFactory->create([
        'user_id' => (is_int($ownerKey) || is_string($ownerKey)) ? (string) $ownerKey : '',
        'provider' => 'github',
        'provider_id' => 'provider-'.uniqid(),
    ]);
    \assert($socialiteUser instanceof SocialiteUser);

    // Smoke: calling dispatch should not error.
    AddingTeam::dispatch($owner);
    Login::dispatch($socialiteUser);
});

it('password data static make method exists', function (): void {
    $passwordData = PasswordData::make();
    Assert::assertInstanceOf(PasswordData::class, $passwordData);
});

it('password data get validation messages method exists', function (): void {
    $passwordData = new PasswordData;

    $passwordData->getValidationMessages();
});

it('password data get form schema method exists', function (): void {
    PasswordData::getFormSchema();
});
=======
=======
>>>>>>> 87273113 (.)

class UserModulePhpstanFixesTest extends TestCase
{
    /** @test */
    public function password_data_can_be_instantiated(): void
    {
        $passwordData = new PasswordData;

        $this->assertInstanceOf(PasswordData::class, $passwordData);
        $this->assertEquals(15, $passwordData->otp_expiration_minutes);
        $this->assertEquals(6, $passwordData->otp_length);
        $this->assertEquals(30, $passwordData->expires_in);
        $this->assertEquals(6, $passwordData->min);
        $this->assertFalse($passwordData->mixedCase);
        $this->assertFalse($passwordData->letters);
        $this->assertFalse($passwordData->numbers);
        $this->assertFalse($passwordData->symbols);
        $this->assertFalse($passwordData->uncompromised);
        $this->assertEquals(1, $passwordData->compromisedThreshold);
    }

    /** @test */
    public function password_data_can_be_configured(): void
    {
        $passwordData = new PasswordData(
            otp_expiration_minutes: 30,
            otp_length: 8,
            expires_in: 60,
            min: 8,
            mixedCase: true,
            letters: true,
            numbers: true,
            symbols: true,
            uncompromised: true,
            compromisedThreshold: 5
        );

        $this->assertEquals(30, $passwordData->otp_expiration_minutes);
        $this->assertEquals(8, $passwordData->otp_length);
        $this->assertEquals(60, $passwordData->expires_in);
        $this->assertEquals(8, $passwordData->min);
        $this->assertTrue($passwordData->mixedCase);
        $this->assertTrue($passwordData->letters);
        $this->assertTrue($passwordData->numbers);
        $this->assertTrue($passwordData->symbols);
        $this->assertTrue($passwordData->uncompromised);
        $this->assertEquals(5, $passwordData->compromisedThreshold);
    }

    /** @test */
    public function password_data_get_password_rule_works(): void
    {
        $passwordData = new PasswordData(
            min: 8,
            mixedCase: true,
            letters: true,
            numbers: true,
            symbols: true,
            uncompromised: true,
            compromisedThreshold: 3
        );

        $rule = $passwordData->getPasswordRule();

        $this->assertInstanceOf(Password::class, $rule);
    }

    /** @test */
    public function password_data_get_helper_text_works(): void
    {
        $passwordData = new PasswordData(
            min: 8,
            mixedCase: true,
            letters: true,
            numbers: true,
            symbols: true,
            uncompromised: true
        );

        $helperText = $passwordData->getHelperText();

        $this->assertIsString($helperText);
        $this->assertStringContainsString('8 caratteri', $helperText);
        $this->assertStringContainsString('maiuscola e una minuscola', $helperText);
        $this->assertStringContainsString('lettera', $helperText);
        $this->assertStringContainsString('numero', $helperText);
        $this->assertStringContainsString('carattere speciale', $helperText);
        $this->assertStringContainsString('compromessa', $helperText);
    }

    /** @test */
    public function password_data_get_form_components_returns_array(): void
    {
        $passwordData = new PasswordData;

        // Test che il metodo esista e non lanci eccezioni
        $this->assertTrue(method_exists($passwordData, 'getPasswordFormComponents'));

        // Test che il metodo getPasswordFormComponent esista
        $this->assertTrue(method_exists($passwordData, 'getPasswordFormComponent'));

        // Test che il metodo getPasswordConfirmationFormComponent esista
        $this->assertTrue(method_exists($passwordData, 'getPasswordConfirmationFormComponent'));
    }

    /** @test */
    public function events_can_be_instantiated(): void
    {
        $addingTeam = new AddingTeam;
        $login = new Login;
        $registered = new Registered;
        $socialiteUserConnected = new SocialiteUserConnected;

        $this->assertInstanceOf(AddingTeam::class, $addingTeam);
        $this->assertInstanceOf(Login::class, $login);
        $this->assertInstanceOf(Registered::class, $registered);
        $this->assertInstanceOf(SocialiteUserConnected::class, $socialiteUserConnected);
    }

    /** @test */
    public function events_have_dispatchable_trait(): void
    {
        $addingTeam = new AddingTeam;
        $login = new Login;

        $this->assertTrue(method_exists($addingTeam, 'dispatch'));
        $this->assertTrue(method_exists($login, 'dispatch'));
    }

    /** @test */
    public function password_data_static_make_method_exists(): void
    {
        $this->assertTrue(method_exists(PasswordData::class, 'make'));
    }

    /** @test */
    public function password_data_get_validation_messages_method_exists(): void
    {
        $passwordData = new PasswordData;

        $this->assertTrue(method_exists($passwordData, 'getValidationMessages'));
    }

    /** @test */
    public function password_data_get_form_schema_method_exists(): void
    {
        $this->assertTrue(method_exists(PasswordData::class, 'getFormSchema'));
    }
}
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
use Modules\User\Models\SocialiteUser;
use Modules\User\Models\User;
use Modules\User\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

it('password data can be instantiated', function (): void {
    $passwordData = new PasswordData();

    Assert::assertInstanceOf(PasswordData::class, $passwordData);
    Assert::assertSame(5, $passwordData->otp_expiration_minutes);
    Assert::assertSame(6, $passwordData->otp_length);
    Assert::assertSame(60, $passwordData->expires_in);
    Assert::assertSame(8, $passwordData->min);
    Assert::assertTrue($passwordData->mixedCase);
    Assert::assertTrue($passwordData->letters);
    Assert::assertTrue($passwordData->numbers);
    Assert::assertTrue($passwordData->symbols);
    Assert::assertTrue($passwordData->uncompromised);
    Assert::assertSame(0, $passwordData->compromisedThreshold);
});

it('password data can be configured', function (): void {
    $passwordData = new PasswordData(
        otp_expiration_minutes: 30,
        otp_length: 8,
        expires_in: 60,
        min: 8,
        mixedCase: true,
        letters: true,
        numbers: true,
        symbols: true,
        uncompromised: true,
        compromisedThreshold: 5
    );

    Assert::assertSame(30, $passwordData->otp_expiration_minutes);
    Assert::assertSame(8, $passwordData->otp_length);
    Assert::assertSame(60, $passwordData->expires_in);
    Assert::assertSame(8, $passwordData->min);
    Assert::assertTrue($passwordData->mixedCase);
    Assert::assertTrue($passwordData->letters);
    Assert::assertTrue($passwordData->numbers);
    Assert::assertTrue($passwordData->symbols);
    Assert::assertTrue($passwordData->uncompromised);
    Assert::assertSame(5, $passwordData->compromisedThreshold);
});

it('password data get password rule works', function (): void {
    $passwordData = new PasswordData(
        min: 8,
        mixedCase: true,
        letters: true,
        numbers: true,
        symbols: true,
        uncompromised: true,
        compromisedThreshold: 3
    );

    $rule = $passwordData->getPasswordRule();

    Assert::assertInstanceOf(Password::class, $rule);
});

it('password data get helper text works', function (): void {
    $passwordData = new PasswordData(
        min: 8,
        mixedCase: true,
        letters: true,
        numbers: true,
        symbols: true,
        uncompromised: true
    );

    $helperText = $passwordData->getHelperText();

    Assert::assertStringContainsString('8 caratteri', $helperText);
    Assert::assertStringContainsString('maiuscola e una minuscola', $helperText);
    Assert::assertStringContainsString('lettera', $helperText);
    Assert::assertStringContainsString('numero', $helperText);
    Assert::assertStringContainsString('carattere speciale', $helperText);
    Assert::assertStringContainsString('compromessa', $helperText);
});

it('password data get form components returns array', function (): void {
    $passwordData = new PasswordData();

    // Smoke tests: methods should be callable without throwing.
    $passwordData->getPasswordFormComponent('password');
    $passwordData->setFieldName('password');
    $passwordData->getPasswordConfirmationFormComponent();
});

it('events can be instantiated', function (): void {
    $userFactory = UserFactory::new();
    \assert($userFactory instanceof Factory);
    $owner = $userFactory->create();
    \assert($owner instanceof User);

    $socialiteFactory = SocialiteUserFactory::new();
    \assert($socialiteFactory instanceof Factory);
    $ownerKey = $owner->getKey();
    $socialiteUser = $socialiteFactory->create([
        'user_id' => (is_int($ownerKey) || is_string($ownerKey)) ? (string) $ownerKey : '',
        'provider' => 'github',
        'provider_id' => 'provider-'.uniqid(),
    ]);
    \assert($socialiteUser instanceof SocialiteUser);

    $addingTeam = new AddingTeam($owner);
    $login = new Login($socialiteUser);
    $registered = new Registered($socialiteUser);
    $socialiteUserConnected = new SocialiteUserConnected($socialiteUser);

    Assert::assertInstanceOf(AddingTeam::class, $addingTeam);
    Assert::assertInstanceOf(Login::class, $login);
    Assert::assertInstanceOf(Registered::class, $registered);
    Assert::assertInstanceOf(SocialiteUserConnected::class, $socialiteUserConnected);
});

it('events have dispatchable trait', function (): void {
    $userFactory = UserFactory::new();
    \assert($userFactory instanceof Factory);
    $owner = $userFactory->create();
    \assert($owner instanceof User);

    $socialiteFactory = SocialiteUserFactory::new();
    \assert($socialiteFactory instanceof Factory);
    $ownerKey = $owner->getKey();
    $socialiteUser = $socialiteFactory->create([
        'user_id' => (is_int($ownerKey) || is_string($ownerKey)) ? (string) $ownerKey : '',
        'provider' => 'github',
        'provider_id' => 'provider-'.uniqid(),
    ]);
    \assert($socialiteUser instanceof SocialiteUser);

    // Smoke: calling dispatch should not error.
    AddingTeam::dispatch($owner);
    Login::dispatch($socialiteUser);
});

it('password data static make method exists', function (): void {
    $passwordData = PasswordData::make();
    Assert::assertInstanceOf(PasswordData::class, $passwordData);
});

it('password data get validation messages method exists', function (): void {
    $passwordData = new PasswordData();

    $passwordData->getValidationMessages();
});

it('password data get form schema method exists', function (): void {
    PasswordData::make()->getFormSchema();
});
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
