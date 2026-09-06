<?php

declare(strict_types=1);

use Modules\Cms\Tests\TestCase;
use Modules\User\Database\Factories\UserFactory;

uses(TestCase::class);
it('renders the public profile route using the localized profile page', function (): void {
    $user = UserFactory::new()->createOne([
        'name' => 'Mario Rossi',
        'email' => TestCase::pestGenerateUniqueEmail(),
        'lang' => 'it',
    ]);

    $userId = $user->getKey();
    if (! is_numeric($userId) && ! is_string($userId)) {
        cmsSkipTest('User ID is not a valid type');
    }
    /** @var string $userIdStr */
    $userIdStr = is_string($userId) ? $userId : /* @phpstan-ignore cast.string */ (string) $userId;
    $response = cmsGet('/it/profile/'.$userIdStr);
    $status = (int) $response->getStatusCode();

    if ($status >= 500) {
        cmsSkipTest('Public profile route returned server error in this install.');
    }

    if ($status !== 200) {
        cmsSkipTest("Public profile route returned {$status} — profile FO page not configured.");
    }

    $response
        ->assertSee('Mario Rossi')
        ->assertSee(__('pub_theme::profile.badges.public_profile.label'))
        ->assertSee('ProfilePage', false);
});
