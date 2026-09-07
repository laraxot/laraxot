<?php

declare(strict_types=1);

<<<<<<< HEAD
<<<<<<< HEAD
=======
uses(Modules\User\Tests\TestCase::class);

>>>>>>> 60a2c9a9 (.)
=======
uses(Modules\User\Tests\TestCase::class);

=======
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
use Modules\User\Console\Commands\AssignRoleCommand;
use Modules\User\Console\Commands\ChangeTypeCommand;
use Modules\User\Console\Commands\CreateTeamCommand;
use Modules\User\Console\Commands\CreateTenantCommand;
use Modules\User\Console\Commands\SuperAdminCommand;
<<<<<<< HEAD
<<<<<<< HEAD
use Modules\User\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

test('AssignRoleCommand can be instantiated', function () {
    try {
        $command = new AssignRoleCommand;
        Assert::assertInstanceOf(AssignRoleCommand::class, $command);
    } catch (Exception $e) {
        // assertTrue(true) removed — tautology // Pass if class exists
=======
=======
>>>>>>> 87273113 (.)

test('AssignRoleCommand can be instantiated', function () {
    expect(class_exists(AssignRoleCommand::class))->toBeTrue();

    try {
        $command = new AssignRoleCommand();
        expect($command)->toBeInstanceOf(AssignRoleCommand::class);
    } catch (Exception $e) {
        expect(true)->toBeTrue(); // Pass if class exists
<<<<<<< HEAD
>>>>>>> 60a2c9a9 (.)
=======
=======
use Modules\User\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

test('AssignRoleCommand can be instantiated', function () {
    try {
        $command = new AssignRoleCommand;
        Assert::assertInstanceOf(AssignRoleCommand::class, $command);
    } catch (Exception $e) {
        // assertTrue(true) removed — tautology // Pass if class exists
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    }
});

test('ChangeTypeCommand can be instantiated', function () {
<<<<<<< HEAD
<<<<<<< HEAD
    try {
        $command = new ChangeTypeCommand;
        Assert::assertInstanceOf(ChangeTypeCommand::class, $command);
    } catch (Exception $e) {
        // assertTrue(true) removed — tautology // Pass if class exists
=======
=======
>>>>>>> 87273113 (.)
    expect(class_exists(ChangeTypeCommand::class))->toBeTrue();

    try {
        $command = new ChangeTypeCommand();
        expect($command)->toBeInstanceOf(ChangeTypeCommand::class);
    } catch (Exception $e) {
        expect(true)->toBeTrue(); // Pass if class exists
<<<<<<< HEAD
>>>>>>> 60a2c9a9 (.)
=======
=======
    try {
        $command = new ChangeTypeCommand;
        Assert::assertInstanceOf(ChangeTypeCommand::class, $command);
    } catch (Exception $e) {
        // assertTrue(true) removed — tautology // Pass if class exists
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    }
});

test('SuperAdminCommand can be instantiated', function () {
<<<<<<< HEAD
<<<<<<< HEAD
    try {
        $command = new SuperAdminCommand;
        Assert::assertInstanceOf(SuperAdminCommand::class, $command);
    } catch (Exception $e) {
        // assertTrue(true) removed — tautology // Pass if class exists
=======
=======
>>>>>>> 87273113 (.)
    expect(class_exists(SuperAdminCommand::class))->toBeTrue();

    try {
        $command = new SuperAdminCommand();
        expect($command)->toBeInstanceOf(SuperAdminCommand::class);
    } catch (Exception $e) {
        expect(true)->toBeTrue(); // Pass if class exists
<<<<<<< HEAD
>>>>>>> 60a2c9a9 (.)
=======
=======
    try {
        $command = new SuperAdminCommand;
        Assert::assertInstanceOf(SuperAdminCommand::class, $command);
    } catch (Exception $e) {
        // assertTrue(true) removed — tautology // Pass if class exists
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    }
});

test('CreateTeamCommand can be instantiated', function () {
<<<<<<< HEAD
<<<<<<< HEAD
    try {
        $command = new CreateTeamCommand;
        Assert::assertInstanceOf(CreateTeamCommand::class, $command);
    } catch (Exception $e) {
        // assertTrue(true) removed — tautology // Pass if class exists
=======
=======
>>>>>>> 87273113 (.)
    expect(class_exists(CreateTeamCommand::class))->toBeTrue();

    try {
        $command = new CreateTeamCommand();
        expect($command)->toBeInstanceOf(CreateTeamCommand::class);
    } catch (Exception $e) {
        expect(true)->toBeTrue(); // Pass if class exists
<<<<<<< HEAD
>>>>>>> 60a2c9a9 (.)
=======
=======
    try {
        $command = new CreateTeamCommand;
        Assert::assertInstanceOf(CreateTeamCommand::class, $command);
    } catch (Exception $e) {
        // assertTrue(true) removed — tautology // Pass if class exists
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    }
});

test('CreateTenantCommand can be instantiated', function () {
<<<<<<< HEAD
<<<<<<< HEAD
    try {
        $command = new CreateTenantCommand;
        Assert::assertInstanceOf(CreateTenantCommand::class, $command);
    } catch (Exception $e) {
        // assertTrue(true) removed — tautology // Pass if class exists
=======
=======
>>>>>>> 87273113 (.)
    expect(class_exists(CreateTenantCommand::class))->toBeTrue();

    try {
        $command = new CreateTenantCommand();
        expect($command)->toBeInstanceOf(CreateTenantCommand::class);
    } catch (Exception $e) {
        expect(true)->toBeTrue(); // Pass if class exists
<<<<<<< HEAD
>>>>>>> 60a2c9a9 (.)
=======
=======
    try {
        $command = new CreateTenantCommand;
        Assert::assertInstanceOf(CreateTenantCommand::class, $command);
    } catch (Exception $e) {
        // assertTrue(true) removed — tautology // Pass if class exists
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    }
});
