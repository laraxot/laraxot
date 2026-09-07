<?php

declare(strict_types=1);

<<<<<<< HEAD
<<<<<<< HEAD
use Modules\User\Datas\PasswordData;
use Modules\User\Tests\TestCase;
use PHPUnit\Framework\Assert;
use Spatie\LaravelData\Data;

use function Safe\file_get_contents;

uses(TestCase::class);

function samplePasswordData(): PasswordData
{
    return new PasswordData(
=======
=======
>>>>>>> 87273113 (.)
use Tests\TestCase;
use Spatie\LaravelData\Data;
use Illuminate\Validation\Rules\Password;
use Modules\User\Datas\PasswordData;

uses(TestCase::class);

beforeEach(function (): void {
    $this->passwordData = new PasswordData(
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
use Modules\User\Datas\PasswordData;
use Modules\User\Tests\TestCase;
use PHPUnit\Framework\Assert;
use Spatie\LaravelData\Data;

use function Safe\file_get_contents;

uses(TestCase::class);

function samplePasswordData(): PasswordData
{
    return new PasswordData(
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
        otp_expiration_minutes: 10,
        otp_length: 8,
        expires_in: 120,
        min: 12,
        mixedCase: true,
        letters: true,
        numbers: true,
        symbols: true,
        uncompromised: true,
        compromisedThreshold: 5,
        failMessage: 'Password non valida',
    );
<<<<<<< HEAD
<<<<<<< HEAD
}

test('password data can be created with custom parameters', function (): void {
    $passwordData = samplePasswordData();

    Assert::assertInstanceOf(PasswordData::class, $passwordData);
    Assert::assertSame(10, $passwordData->otp_expiration_minutes);
    Assert::assertSame(8, $passwordData->otp_length);
    Assert::assertSame(120, $passwordData->expires_in);
    Assert::assertSame(12, $passwordData->min);
    Assert::assertTrue($passwordData->mixedCase);
    Assert::assertTrue($passwordData->letters);
    Assert::assertTrue($passwordData->numbers);
    Assert::assertTrue($passwordData->symbols);
    Assert::assertTrue($passwordData->uncompromised);
    Assert::assertSame(5, $passwordData->compromisedThreshold);
    Assert::assertSame('Password non valida', $passwordData->failMessage);
});

test('password data has default values', function (): void {
    $defaultPasswordData = new PasswordData;

    Assert::assertSame(5, $defaultPasswordData->otp_expiration_minutes);
    Assert::assertSame(6, $defaultPasswordData->otp_length);
    Assert::assertSame(60, $defaultPasswordData->expires_in);
    Assert::assertSame(8, $defaultPasswordData->min);
    Assert::assertTrue($defaultPasswordData->mixedCase);
    Assert::assertTrue($defaultPasswordData->letters);
    Assert::assertTrue($defaultPasswordData->numbers);
    Assert::assertTrue($defaultPasswordData->symbols);
    Assert::assertTrue($defaultPasswordData->uncompromised);
    Assert::assertSame(0, $defaultPasswordData->compromisedThreshold);
    Assert::assertNull($defaultPasswordData->failMessage);
});

test('password data extends spatie data class', function (): void {
    Assert::assertInstanceOf(Data::class, samplePasswordData());
=======
=======
>>>>>>> 87273113 (.)
});

test('password data can be created with custom parameters', function (): void {
    expect($this->passwordData)->toBeInstanceOf(PasswordData::class);
    expect($this->passwordData->otp_expiration_minutes)->toBe(10);
    expect($this->passwordData->otp_length)->toBe(8);
    expect($this->passwordData->expires_in)->toBe(120);
    expect($this->passwordData->min)->toBe(12);
    expect($this->passwordData->mixedCase)->toBeTrue();
    expect($this->passwordData->letters)->toBeTrue();
    expect($this->passwordData->numbers)->toBeTrue();
    expect($this->passwordData->symbols)->toBeTrue();
    expect($this->passwordData->uncompromised)->toBeTrue();
    expect($this->passwordData->compromisedThreshold)->toBe(5);
    expect($this->passwordData->failMessage)->toBe('Password non valida');
});

test('password data has default values', function (): void {
    $defaultPasswordData = new PasswordData();

    expect($defaultPasswordData->otp_expiration_minutes)->toBe(5);
    expect($defaultPasswordData->otp_length)->toBe(6);
    expect($defaultPasswordData->expires_in)->toBe(60);
    expect($defaultPasswordData->min)->toBe(8);
    expect($defaultPasswordData->mixedCase)->toBeTrue();
    expect($defaultPasswordData->letters)->toBeTrue();
    expect($defaultPasswordData->numbers)->toBeTrue();
    expect($defaultPasswordData->symbols)->toBeTrue();
    expect($defaultPasswordData->uncompromised)->toBeTrue();
    expect($defaultPasswordData->compromisedThreshold)->toBe(0);
    expect($defaultPasswordData->failMessage)->toBeNull();
});

test('password data extends spatie data class', function (): void {
    expect($this->passwordData)->toBeInstanceOf(Data::class);
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
}

test('password data can be created with custom parameters', function (): void {
    $passwordData = samplePasswordData();

    Assert::assertInstanceOf(PasswordData::class, $passwordData);
    Assert::assertSame(10, $passwordData->otp_expiration_minutes);
    Assert::assertSame(8, $passwordData->otp_length);
    Assert::assertSame(120, $passwordData->expires_in);
    Assert::assertSame(12, $passwordData->min);
    Assert::assertTrue($passwordData->mixedCase);
    Assert::assertTrue($passwordData->letters);
    Assert::assertTrue($passwordData->numbers);
    Assert::assertTrue($passwordData->symbols);
    Assert::assertTrue($passwordData->uncompromised);
    Assert::assertSame(5, $passwordData->compromisedThreshold);
    Assert::assertSame('Password non valida', $passwordData->failMessage);
});

test('password data has default values', function (): void {
    $defaultPasswordData = new PasswordData;

    Assert::assertSame(5, $defaultPasswordData->otp_expiration_minutes);
    Assert::assertSame(6, $defaultPasswordData->otp_length);
    Assert::assertSame(60, $defaultPasswordData->expires_in);
    Assert::assertSame(8, $defaultPasswordData->min);
    Assert::assertTrue($defaultPasswordData->mixedCase);
    Assert::assertTrue($defaultPasswordData->letters);
    Assert::assertTrue($defaultPasswordData->numbers);
    Assert::assertTrue($defaultPasswordData->symbols);
    Assert::assertTrue($defaultPasswordData->uncompromised);
    Assert::assertSame(0, $defaultPasswordData->compromisedThreshold);
    Assert::assertNull($defaultPasswordData->failMessage);
});

test('password data extends spatie data class', function (): void {
    Assert::assertInstanceOf(Data::class, samplePasswordData());
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
});

test('password data has correct properties', function (): void {
    $reflection = new ReflectionClass(PasswordData::class);
<<<<<<< HEAD
<<<<<<< HEAD
    $propertyNames = array_map(
        static fn (ReflectionProperty $prop): string => $prop->getName(),
        $reflection->getProperties(),
    );

    foreach ([
        'otp_expiration_minutes',
        'otp_length',
        'expires_in',
        'min',
        'mixedCase',
        'letters',
        'numbers',
        'symbols',
        'uncompromised',
        'compromisedThreshold',
        'failMessage',
    ] as $expected) {
        Assert::assertContains($expected, $propertyNames);
    }
=======
=======
>>>>>>> 87273113 (.)
    $properties = $reflection->getProperties();

    $propertyNames = array_map(fn($prop) => $prop->getName(), $properties);

    expect($propertyNames)->toContain('otp_expiration_minutes');
    expect($propertyNames)->toContain('otp_length');
    expect($propertyNames)->toContain('expires_in');
    expect($propertyNames)->toContain('min');
    expect($propertyNames)->toContain('mixedCase');
    expect($propertyNames)->toContain('letters');
    expect($propertyNames)->toContain('numbers');
    expect($propertyNames)->toContain('symbols');
    expect($propertyNames)->toContain('uncompromised');
    expect($propertyNames)->toContain('compromisedThreshold');
    expect($propertyNames)->toContain('failMessage');
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
    $propertyNames = array_map(
        static fn (ReflectionProperty $prop): string => $prop->getName(),
        $reflection->getProperties(),
    );

    foreach ([
        'otp_expiration_minutes',
        'otp_length',
        'expires_in',
        'min',
        'mixedCase',
        'letters',
        'numbers',
        'symbols',
        'uncompromised',
        'compromisedThreshold',
        'failMessage',
    ] as $expected) {
        Assert::assertContains($expected, $propertyNames);
    }
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
});

test('password data has correct types', function (): void {
    $reflection = new ReflectionClass(PasswordData::class);

<<<<<<< HEAD
<<<<<<< HEAD
    $typeExpectations = [
        'otp_expiration_minutes' => 'int',
        'otp_length' => 'int',
        'expires_in' => 'int',
        'min' => 'int',
        'mixedCase' => 'bool',
        'letters' => 'bool',
        'numbers' => 'bool',
        'symbols' => 'bool',
        'uncompromised' => 'bool',
        'compromisedThreshold' => 'int',
        'failMessage' => 'string',
    ];

    foreach ($typeExpectations as $propertyName => $expectedType) {
        $property = $reflection->getProperty($propertyName);
        $type = $property->getType();
        Assert::assertInstanceOf(ReflectionNamedType::class, $type);
        Assert::assertSame($expectedType, $type->getName());
    }

    $failMessageType = $reflection->getProperty('failMessage')->getType();
    Assert::assertInstanceOf(ReflectionNamedType::class, $failMessageType);
    Assert::assertTrue($failMessageType->allowsNull());
=======
=======
>>>>>>> 87273113 (.)
    $otpExpirationProperty = $reflection->getProperty('otp_expiration_minutes');
    $otpLengthProperty = $reflection->getProperty('otp_length');
    $expiresInProperty = $reflection->getProperty('expires_in');
    $minProperty = $reflection->getProperty('min');
    $mixedCaseProperty = $reflection->getProperty('mixedCase');
    $lettersProperty = $reflection->getProperty('letters');
    $numbersProperty = $reflection->getProperty('numbers');
    $symbolsProperty = $reflection->getProperty('symbols');
    $uncompromisedProperty = $reflection->getProperty('uncompromised');
    $compromisedThresholdProperty = $reflection->getProperty('compromisedThreshold');
    $failMessageProperty = $reflection->getProperty('failMessage');

    expect($otpExpirationProperty->getType()->getName())->toBe('int');
    expect($otpLengthProperty->getType()->getName())->toBe('int');
    expect($expiresInProperty->getType()->getName())->toBe('int');
    expect($minProperty->getType()->getName())->toBe('int');
    expect($mixedCaseProperty->getType()->getName())->toBe('bool');
    expect($lettersProperty->getType()->getName())->toBe('bool');
    expect($numbersProperty->getType()->getName())->toBe('bool');
    expect($symbolsProperty->getType()->getName())->toBe('bool');
    expect($uncompromisedProperty->getType()->getName())->toBe('bool');
    expect($compromisedThresholdProperty->getType()->getName())->toBe('int');
    expect($failMessageProperty->getType()->getName())->toBe('string');
    expect($failMessageProperty->getType()->allowsNull())->toBeTrue();
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
    $typeExpectations = [
        'otp_expiration_minutes' => 'int',
        'otp_length' => 'int',
        'expires_in' => 'int',
        'min' => 'int',
        'mixedCase' => 'bool',
        'letters' => 'bool',
        'numbers' => 'bool',
        'symbols' => 'bool',
        'uncompromised' => 'bool',
        'compromisedThreshold' => 'int',
        'failMessage' => 'string',
    ];

    foreach ($typeExpectations as $propertyName => $expectedType) {
        $property = $reflection->getProperty($propertyName);
        $type = $property->getType();
        Assert::assertInstanceOf(ReflectionNamedType::class, $type);
        Assert::assertSame($expectedType, $type->getName());
    }

    $failMessageType = $reflection->getProperty('failMessage')->getType();
    Assert::assertInstanceOf(ReflectionNamedType::class, $failMessageType);
    Assert::assertTrue($failMessageType->allowsNull());
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
});

test('password data has correct constructor parameters', function (): void {
    $reflection = new ReflectionClass(PasswordData::class);
    $constructor = $reflection->getConstructor();

<<<<<<< HEAD
<<<<<<< HEAD
    Assert::assertNotNull($constructor);

    $parameters = $constructor->getParameters();
    Assert::assertCount(12, $parameters);

    Assert::assertSame('otp_expiration_minutes', $parameters[0]->getName());
    $otpExpirationType = $parameters[0]->getType();
    Assert::assertInstanceOf(ReflectionNamedType::class, $otpExpirationType);
    Assert::assertSame('int', $otpExpirationType->getName());
    Assert::assertTrue($parameters[0]->isOptional());
    Assert::assertSame(5, $parameters[0]->getDefaultValue());

    Assert::assertSame('otp_length', $parameters[1]->getName());
    $otpLengthType = $parameters[1]->getType();
    Assert::assertInstanceOf(ReflectionNamedType::class, $otpLengthType);
    Assert::assertSame('int', $otpLengthType->getName());
    Assert::assertTrue($parameters[1]->isOptional());
    Assert::assertSame(6, $parameters[1]->getDefaultValue());
});

test('password data has correct namespace', function (): void {
    Assert::assertStringContainsString('Modules\User\Datas', PasswordData::class);
=======
=======
>>>>>>> 87273113 (.)
    expect($constructor)->not->toBeNull();

    $parameters = $constructor->getParameters();
    expect($parameters)->toHaveCount(12);

    // Check first few parameters
    expect($parameters[0]->getName())->toBe('otp_expiration_minutes');
    expect($parameters[0]->getType()->getName())->toBe('int');
    expect($parameters[0]->isOptional())->toBeTrue();
    expect($parameters[0]->getDefaultValue())->toBe(5);

    expect($parameters[1]->getName())->toBe('otp_length');
    expect($parameters[1]->getType()->getName())->toBe('int');
    expect($parameters[1]->isOptional())->toBeTrue();
    expect($parameters[1]->getDefaultValue())->toBe(6);
});

test('password data has correct namespace', function (): void {
    expect(PasswordData::class)->toContain('Modules\User\Datas');
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
    Assert::assertNotNull($constructor);

    $parameters = $constructor->getParameters();
    Assert::assertCount(12, $parameters);

    Assert::assertSame('otp_expiration_minutes', $parameters[0]->getName());
    $otpExpirationType = $parameters[0]->getType();
    Assert::assertInstanceOf(ReflectionNamedType::class, $otpExpirationType);
    Assert::assertSame('int', $otpExpirationType->getName());
    Assert::assertTrue($parameters[0]->isOptional());
    Assert::assertSame(5, $parameters[0]->getDefaultValue());

    Assert::assertSame('otp_length', $parameters[1]->getName());
    $otpLengthType = $parameters[1]->getType();
    Assert::assertInstanceOf(ReflectionNamedType::class, $otpLengthType);
    Assert::assertSame('int', $otpLengthType->getName());
    Assert::assertTrue($parameters[1]->isOptional());
    Assert::assertSame(6, $parameters[1]->getDefaultValue());
});

test('password data has correct namespace', function (): void {
    Assert::assertStringContainsString('Modules\User\Datas', PasswordData::class);
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
});

test('password data has correct strict types declaration', function (): void {
    $reflection = new ReflectionClass(PasswordData::class);
    $filename = $reflection->getFileName();
<<<<<<< HEAD
<<<<<<< HEAD
    Assert::assertIsString($filename);

    $content = file_get_contents($filename);
    Assert::assertStringContainsString('declare(strict_types=1)', $content);
=======
=======
>>>>>>> 87273113 (.)

    if ($filename) {
        $content = file_get_contents($filename);
        expect($content)->toContain('declare(strict_types=1);');
    }
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
    Assert::assertIsString($filename);

    $content = file_get_contents($filename);
    Assert::assertStringContainsString('declare(strict_types=1)', $content);
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
});
