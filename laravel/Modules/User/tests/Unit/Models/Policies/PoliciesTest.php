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
use Modules\User\Models\Policies\AuthenticationLogPolicy;
use Modules\User\Models\Policies\AuthenticationPolicy;
use Modules\User\Models\Policies\DevicePolicy;
use Modules\User\Models\Policies\DeviceProfilePolicy;
use Modules\User\Models\Policies\ExtraPolicy;
use Modules\User\Models\Policies\FeaturePolicy;
use Modules\User\Models\Policies\NotificationPolicy;
use Modules\User\Models\Policies\OauthAccessTokenPolicy;
use Modules\User\Models\Policies\OauthAuthCodePolicy;
use Modules\User\Models\Policies\OauthClientPolicy;
use Modules\User\Models\Policies\OauthPersonalAccessClientPolicy;
use Modules\User\Models\Policies\OauthRefreshTokenPolicy;
use Modules\User\Models\Policies\SocialiteUserPolicy;
use Modules\User\Models\Policies\SocialProviderPolicy;
use Modules\User\Models\Policies\TeamInvitationPolicy;
use Modules\User\Models\Policies\TeamPermissionPolicy;
<<<<<<< HEAD
<<<<<<< HEAD
use Modules\User\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

test('OauthClientPolicy can be instantiated', function () {
    $policy = new OauthClientPolicy;
    Assert::assertInstanceOf(OauthClientPolicy::class, $policy);
});

test('OauthAccessTokenPolicy can be instantiated', function () {
    $policy = new OauthAccessTokenPolicy;
    Assert::assertInstanceOf(OauthAccessTokenPolicy::class, $policy);
});

test('OauthAuthCodePolicy can be instantiated', function () {
    $policy = new OauthAuthCodePolicy;
    Assert::assertInstanceOf(OauthAuthCodePolicy::class, $policy);
});

test('OauthRefreshTokenPolicy can be instantiated', function () {
    $policy = new OauthRefreshTokenPolicy;
    Assert::assertInstanceOf(OauthRefreshTokenPolicy::class, $policy);
});

test('OauthPersonalAccessClientPolicy can be instantiated', function () {
    $policy = new OauthPersonalAccessClientPolicy;
    Assert::assertInstanceOf(OauthPersonalAccessClientPolicy::class, $policy);
});

test('SocialiteUserPolicy can be instantiated', function () {
    $policy = new SocialiteUserPolicy;
    Assert::assertInstanceOf(SocialiteUserPolicy::class, $policy);
});

test('SocialProviderPolicy can be instantiated', function () {
    $policy = new SocialProviderPolicy;
    Assert::assertInstanceOf(SocialProviderPolicy::class, $policy);
});

test('AuthenticationLogPolicy can be instantiated', function () {
    $policy = new AuthenticationLogPolicy;
    Assert::assertInstanceOf(AuthenticationLogPolicy::class, $policy);
});

test('AuthenticationPolicy can be instantiated', function () {
    $policy = new AuthenticationPolicy;
    Assert::assertInstanceOf(AuthenticationPolicy::class, $policy);
});

test('DevicePolicy can be instantiated', function () {
    $policy = new DevicePolicy;
    Assert::assertInstanceOf(DevicePolicy::class, $policy);
});

test('DeviceProfilePolicy can be instantiated', function () {
    $policy = new DeviceProfilePolicy;
    Assert::assertInstanceOf(DeviceProfilePolicy::class, $policy);
});

test('TeamInvitationPolicy can be instantiated', function () {
    $policy = new TeamInvitationPolicy;
    Assert::assertInstanceOf(TeamInvitationPolicy::class, $policy);
});

test('TeamPermissionPolicy can be instantiated', function () {
    $policy = new TeamPermissionPolicy;
    Assert::assertInstanceOf(TeamPermissionPolicy::class, $policy);
});

test('FeaturePolicy can be instantiated', function () {
    $policy = new FeaturePolicy;
    Assert::assertInstanceOf(FeaturePolicy::class, $policy);
});

test('ExtraPolicy can be instantiated', function () {
    $policy = new ExtraPolicy;
    Assert::assertInstanceOf(ExtraPolicy::class, $policy);
});

test('NotificationPolicy can be instantiated', function () {
    $policy = new NotificationPolicy;
    Assert::assertInstanceOf(NotificationPolicy::class, $policy);
=======
=======
>>>>>>> 87273113 (.)

test('OauthClientPolicy can be instantiated', function () {
    $policy = new OauthClientPolicy();
    expect($policy)->toBeInstanceOf(OauthClientPolicy::class);
});

test('OauthAccessTokenPolicy can be instantiated', function () {
    $policy = new OauthAccessTokenPolicy();
    expect($policy)->toBeInstanceOf(OauthAccessTokenPolicy::class);
});

test('OauthAuthCodePolicy can be instantiated', function () {
    $policy = new OauthAuthCodePolicy();
    expect($policy)->toBeInstanceOf(OauthAuthCodePolicy::class);
});

test('OauthRefreshTokenPolicy can be instantiated', function () {
    $policy = new OauthRefreshTokenPolicy();
    expect($policy)->toBeInstanceOf(OauthRefreshTokenPolicy::class);
});

test('OauthPersonalAccessClientPolicy can be instantiated', function () {
    $policy = new OauthPersonalAccessClientPolicy();
    expect($policy)->toBeInstanceOf(OauthPersonalAccessClientPolicy::class);
});

test('SocialiteUserPolicy can be instantiated', function () {
    $policy = new SocialiteUserPolicy();
    expect($policy)->toBeInstanceOf(SocialiteUserPolicy::class);
});

test('SocialProviderPolicy can be instantiated', function () {
    $policy = new SocialProviderPolicy();
    expect($policy)->toBeInstanceOf(SocialProviderPolicy::class);
});

test('AuthenticationLogPolicy can be instantiated', function () {
    $policy = new AuthenticationLogPolicy();
    expect($policy)->toBeInstanceOf(AuthenticationLogPolicy::class);
});

test('AuthenticationPolicy can be instantiated', function () {
    $policy = new AuthenticationPolicy();
    expect($policy)->toBeInstanceOf(AuthenticationPolicy::class);
});

test('DevicePolicy can be instantiated', function () {
    $policy = new DevicePolicy();
    expect($policy)->toBeInstanceOf(DevicePolicy::class);
});

test('DeviceProfilePolicy can be instantiated', function () {
    $policy = new DeviceProfilePolicy();
    expect($policy)->toBeInstanceOf(DeviceProfilePolicy::class);
});

test('TeamInvitationPolicy can be instantiated', function () {
    $policy = new TeamInvitationPolicy();
    expect($policy)->toBeInstanceOf(TeamInvitationPolicy::class);
});

test('TeamPermissionPolicy can be instantiated', function () {
    $policy = new TeamPermissionPolicy();
    expect($policy)->toBeInstanceOf(TeamPermissionPolicy::class);
});

test('FeaturePolicy can be instantiated', function () {
    $policy = new FeaturePolicy();
    expect($policy)->toBeInstanceOf(FeaturePolicy::class);
});

test('ExtraPolicy can be instantiated', function () {
    $policy = new ExtraPolicy();
    expect($policy)->toBeInstanceOf(ExtraPolicy::class);
});

test('NotificationPolicy can be instantiated', function () {
    $policy = new NotificationPolicy();
    expect($policy)->toBeInstanceOf(NotificationPolicy::class);
<<<<<<< HEAD
>>>>>>> 60a2c9a9 (.)
=======
=======
use Modules\User\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

test('OauthClientPolicy can be instantiated', function () {
    $policy = new OauthClientPolicy;
    Assert::assertInstanceOf(OauthClientPolicy::class, $policy);
});

test('OauthAccessTokenPolicy can be instantiated', function () {
    $policy = new OauthAccessTokenPolicy;
    Assert::assertInstanceOf(OauthAccessTokenPolicy::class, $policy);
});

test('OauthAuthCodePolicy can be instantiated', function () {
    $policy = new OauthAuthCodePolicy;
    Assert::assertInstanceOf(OauthAuthCodePolicy::class, $policy);
});

test('OauthRefreshTokenPolicy can be instantiated', function () {
    $policy = new OauthRefreshTokenPolicy;
    Assert::assertInstanceOf(OauthRefreshTokenPolicy::class, $policy);
});

test('OauthPersonalAccessClientPolicy can be instantiated', function () {
    $policy = new OauthPersonalAccessClientPolicy;
    Assert::assertInstanceOf(OauthPersonalAccessClientPolicy::class, $policy);
});

test('SocialiteUserPolicy can be instantiated', function () {
    $policy = new SocialiteUserPolicy;
    Assert::assertInstanceOf(SocialiteUserPolicy::class, $policy);
});

test('SocialProviderPolicy can be instantiated', function () {
    $policy = new SocialProviderPolicy;
    Assert::assertInstanceOf(SocialProviderPolicy::class, $policy);
});

test('AuthenticationLogPolicy can be instantiated', function () {
    $policy = new AuthenticationLogPolicy;
    Assert::assertInstanceOf(AuthenticationLogPolicy::class, $policy);
});

test('AuthenticationPolicy can be instantiated', function () {
    $policy = new AuthenticationPolicy;
    Assert::assertInstanceOf(AuthenticationPolicy::class, $policy);
});

test('DevicePolicy can be instantiated', function () {
    $policy = new DevicePolicy;
    Assert::assertInstanceOf(DevicePolicy::class, $policy);
});

test('DeviceProfilePolicy can be instantiated', function () {
    $policy = new DeviceProfilePolicy;
    Assert::assertInstanceOf(DeviceProfilePolicy::class, $policy);
});

test('TeamInvitationPolicy can be instantiated', function () {
    $policy = new TeamInvitationPolicy;
    Assert::assertInstanceOf(TeamInvitationPolicy::class, $policy);
});

test('TeamPermissionPolicy can be instantiated', function () {
    $policy = new TeamPermissionPolicy;
    Assert::assertInstanceOf(TeamPermissionPolicy::class, $policy);
});

test('FeaturePolicy can be instantiated', function () {
    $policy = new FeaturePolicy;
    Assert::assertInstanceOf(FeaturePolicy::class, $policy);
});

test('ExtraPolicy can be instantiated', function () {
    $policy = new ExtraPolicy;
    Assert::assertInstanceOf(ExtraPolicy::class, $policy);
});

test('NotificationPolicy can be instantiated', function () {
    $policy = new NotificationPolicy;
    Assert::assertInstanceOf(NotificationPolicy::class, $policy);
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
});
