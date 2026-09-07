<?php

declare(strict_types=1);

namespace Modules\Notify\Tests\Unit\Models\Policies;

use Modules\Notify\Models\Policies\MailTemplatePolicy;
<<<<<<< HEAD
<<<<<<< HEAD
use Modules\User\Database\Factories\UserFactory;
use Modules\Xot\Contracts\UserContract;
use PHPUnit\Framework\Assert;
use Modules\User\Models\User;
=======
use Modules\Notify\Tests\TestCase;
use Modules\User\Database\Factories\UserFactory;
use Modules\Xot\Contracts\UserContract;
use PHPUnit\Framework\Assert;

uses(TestCase::class)->group('notify-db');
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])

test('mail template policy denies view any', function () {
    $policy = new MailTemplatePolicy;
=======
use Modules\User\Database\Factories\UserFactory;
use Modules\Xot\Contracts\UserContract;
use PHPUnit\Framework\Assert;

test('mail template policy denies view any', function () {
    $policy = new MailTemplatePolicy();
>>>>>>> a988596b (first)
    $user = UserFactory::new()->createOne();
    Assert::assertInstanceOf(UserContract::class, $user);

    Assert::assertFalse($policy->viewAny($user));
});
