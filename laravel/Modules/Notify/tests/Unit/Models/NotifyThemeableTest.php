<?php

declare(strict_types=1);

namespace Modules\Notify\Tests\Unit\Models;

use Modules\Notify\Models\NotifyThemeable;
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

describe('Notify Themeable', function (): void {
    test('_can_create_notify_themeable', function (): void {
        $themeable = NotifyThemeable::create([
            'model_type' => 'App\Models\User',
            'model_id' => 123,
<<<<<<< HEAD
<<<<<<< HEAD
            'notify_theme_id' => 456]);
=======
            'notify_theme_id' => 456,
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'notify_theme_id' => 456]);
>>>>>>> a988596b (first)
        XotBasePest::assertTableHas('notify', 'notify_themeables', [
            'id' => $themeable->id,
            'model_type' => 'App\Models\User',
            'model_id' => 123,
<<<<<<< HEAD
<<<<<<< HEAD
            'notify_theme_id' => 456]);
=======
            'notify_theme_id' => 456,
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'notify_theme_id' => 456]);
>>>>>>> a988596b (first)

        Assert::assertInstanceOf(NotifyThemeable::class, $themeable);
    });

    test('_can_create_with_created_by_and_updated_by', function (): void {
        $themeable = NotifyThemeable::create([
            'model_type' => 'App\Models\Company',
            'model_id' => 789,
            'notify_theme_id' => 101,
            'created_by' => 'user_123',
<<<<<<< HEAD
<<<<<<< HEAD
            'updated_by' => 'user_123']);
=======
            'updated_by' => 'user_123',
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'updated_by' => 'user_123']);
>>>>>>> a988596b (first)
        XotBasePest::assertTableHas('notify', 'notify_themeables', [
            'id' => $themeable->id,
            'model_type' => 'App\Models\Company',
            'model_id' => 789,
            'notify_theme_id' => 101,
            'created_by' => 'user_123',
<<<<<<< HEAD
<<<<<<< HEAD
            'updated_by' => 'user_123']);
=======
            'updated_by' => 'user_123',
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'updated_by' => 'user_123']);
>>>>>>> a988596b (first)

        Assert::assertEquals('user_123', $themeable->created_by);
        Assert::assertEquals('user_123', $themeable->updated_by);
    });

    test('_can_update_notify_themeable', function (): void {
        $themeable = NotifyThemeable::create([
            'model_type' => 'App\Models\User',
            'model_id' => 123,
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
            'notify_theme_id' => 456]);

        $themeable->update([
            'notify_theme_id' => 789,
            'updated_by' => 'user_456']);
        XotBasePest::assertTableHas('notify', 'notify_themeables', [
            'id' => $themeable->id,
            'notify_theme_id' => 789,
            'updated_by' => 'user_456']);
<<<<<<< HEAD
=======
            'notify_theme_id' => 456,
        ]);

        $themeable->update([
            'notify_theme_id' => 789,
            'updated_by' => 'user_456',
        ]);
        XotBasePest::assertTableHas('notify', 'notify_themeables', [
            'id' => $themeable->id,
            'notify_theme_id' => 789,
            'updated_by' => 'user_456',
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)

        Assert::assertEquals(789, XotBasePest::assertFreshModel($themeable, NotifyThemeable::class)->notify_theme_id);
        Assert::assertEquals('user_456', XotBasePest::assertFreshModel($themeable, NotifyThemeable::class)->updated_by);
    });

    test('_can_find_by_model_type_and_id', function (): void {
        $themeable = NotifyThemeable::create([
            'model_type' => 'App\Models\User',
            'model_id' => 123,
<<<<<<< HEAD
<<<<<<< HEAD
            'notify_theme_id' => 456]);
=======
            'notify_theme_id' => 456,
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'notify_theme_id' => 456]);
>>>>>>> a988596b (first)

        $found = NotifyThemeable::where('model_type', 'App\Models\User')->where('model_id', 123)->first();

        Assert::assertNotNull($found);
        Assert::assertEquals($themeable->id, $found->id);
        Assert::assertEquals('App\Models\User', $found->model_type);
        Assert::assertEquals(123, $found->model_id);
        Assert::assertEquals(456, $found->notify_theme_id);
    });

    test('_can_find_by_notify_theme_id', function (): void {
        NotifyThemeable::create([
            'model_type' => 'App\Models\User',
            'model_id' => 123,
<<<<<<< HEAD
<<<<<<< HEAD
            'notify_theme_id' => 456]);
=======
            'notify_theme_id' => 456,
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'notify_theme_id' => 456]);
>>>>>>> a988596b (first)

        NotifyThemeable::create([
            'model_type' => 'App\Models\Company',
            'model_id' => 789,
<<<<<<< HEAD
<<<<<<< HEAD
            'notify_theme_id' => 456]);
=======
            'notify_theme_id' => 456,
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'notify_theme_id' => 456]);
>>>>>>> a988596b (first)

        NotifyThemeable::create([
            'model_type' => 'App\Models\Order',
            'model_id' => 101,
<<<<<<< HEAD
<<<<<<< HEAD
            'notify_theme_id' => 789]);
=======
            'notify_theme_id' => 789,
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'notify_theme_id' => 789]);
>>>>>>> a988596b (first)

        $theme456Themeables = NotifyThemeable::where('notify_theme_id', 456)->get();
        $theme789Themeables = NotifyThemeable::where('notify_theme_id', 789)->get();

        Assert::assertCount(2, $theme456Themeables);
        Assert::assertCount(1, $theme789Themeables);
        Assert::assertEquals(456, XotBasePest::assertFirstModel($theme456Themeables, NotifyThemeable::class)->notify_theme_id);
        Assert::assertEquals(456, XotBasePest::assertFirstModel($theme456Themeables->slice(1), NotifyThemeable::class)->notify_theme_id);
        Assert::assertEquals(789, XotBasePest::assertFirstModel($theme789Themeables, NotifyThemeable::class)->notify_theme_id);
    });

    test('_can_find_by_model_type', function (): void {
        NotifyThemeable::create([
            'model_type' => 'App\Models\User',
            'model_id' => 123,
<<<<<<< HEAD
<<<<<<< HEAD
            'notify_theme_id' => 456]);
=======
            'notify_theme_id' => 456,
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'notify_theme_id' => 456]);
>>>>>>> a988596b (first)

        NotifyThemeable::create([
            'model_type' => 'App\Models\User',
            'model_id' => 456,
<<<<<<< HEAD
<<<<<<< HEAD
            'notify_theme_id' => 789]);
=======
            'notify_theme_id' => 789,
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'notify_theme_id' => 789]);
>>>>>>> a988596b (first)

        NotifyThemeable::create([
            'model_type' => 'App\Models\Company',
            'model_id' => 789,
<<<<<<< HEAD
<<<<<<< HEAD
            'notify_theme_id' => 101]);
=======
            'notify_theme_id' => 101,
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'notify_theme_id' => 101]);
>>>>>>> a988596b (first)

        $userThemeables = NotifyThemeable::where('model_type', 'App\Models\User')->get();
        $companyThemeables = NotifyThemeable::where('model_type', 'App\Models\Company')->get();

        Assert::assertCount(2, $userThemeables);
        Assert::assertCount(1, $companyThemeables);
        Assert::assertEquals('App\Models\User', XotBasePest::assertFirstModel($userThemeables, NotifyThemeable::class)->model_type);
        Assert::assertEquals('App\Models\User', XotBasePest::assertFirstModel($userThemeables->slice(1), NotifyThemeable::class)->model_type);
        Assert::assertEquals('App\Models\Company', XotBasePest::assertFirstModel($companyThemeables, NotifyThemeable::class)->model_type);
    });

    test('_can_find_by_created_by', function (): void {
        NotifyThemeable::create([
            'model_type' => 'App\Models\User',
            'model_id' => 123,
            'notify_theme_id' => 456,
<<<<<<< HEAD
<<<<<<< HEAD
            'created_by' => 'user_123']);
=======
            'created_by' => 'user_123',
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'created_by' => 'user_123']);
>>>>>>> a988596b (first)

        NotifyThemeable::create([
            'model_type' => 'App\Models\Company',
            'model_id' => 789,
            'notify_theme_id' => 101,
<<<<<<< HEAD
<<<<<<< HEAD
            'created_by' => 'user_456']);
=======
            'created_by' => 'user_456',
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'created_by' => 'user_456']);
>>>>>>> a988596b (first)

        NotifyThemeable::create([
            'model_type' => 'App\Models\Order',
            'model_id' => 101,
            'notify_theme_id' => 789,
<<<<<<< HEAD
<<<<<<< HEAD
            'created_by' => 'user_123']);
=======
            'created_by' => 'user_123',
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'created_by' => 'user_123']);
>>>>>>> a988596b (first)

        $user123Themeables = NotifyThemeable::where('created_by', 'user_123')->get();
        $user456Themeables = NotifyThemeable::where('created_by', 'user_456')->get();

        Assert::assertCount(2, $user123Themeables);
        Assert::assertCount(1, $user456Themeables);
        Assert::assertEquals('user_123', XotBasePest::assertFirstModel($user123Themeables, NotifyThemeable::class)->created_by);
        Assert::assertEquals('user_123', XotBasePest::assertFirstModel($user123Themeables->slice(1), NotifyThemeable::class)->created_by);
        Assert::assertEquals('user_456', XotBasePest::assertFirstModel($user456Themeables, NotifyThemeable::class)->created_by);
    });

    test('_can_find_by_updated_by', function (): void {
        NotifyThemeable::create([
            'model_type' => 'App\Models\User',
            'model_id' => 123,
            'notify_theme_id' => 456,
<<<<<<< HEAD
<<<<<<< HEAD
            'updated_by' => 'user_123']);
=======
            'updated_by' => 'user_123',
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'updated_by' => 'user_123']);
>>>>>>> a988596b (first)

        NotifyThemeable::create([
            'model_type' => 'App\Models\Company',
            'model_id' => 789,
            'notify_theme_id' => 101,
<<<<<<< HEAD
<<<<<<< HEAD
            'updated_by' => 'user_456']);
=======
            'updated_by' => 'user_456',
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'updated_by' => 'user_456']);
>>>>>>> a988596b (first)

        NotifyThemeable::create([
            'model_type' => 'App\Models\Order',
            'model_id' => 101,
            'notify_theme_id' => 789,
<<<<<<< HEAD
<<<<<<< HEAD
            'updated_by' => 'user_123']);
=======
            'updated_by' => 'user_123',
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'updated_by' => 'user_123']);
>>>>>>> a988596b (first)

        $user123Themeables = NotifyThemeable::where('updated_by', 'user_123')->get();
        $user456Themeables = NotifyThemeable::where('updated_by', 'user_456')->get();

        Assert::assertCount(2, $user123Themeables);
        Assert::assertCount(1, $user456Themeables);
        Assert::assertEquals('user_123', XotBasePest::assertFirstModel($user123Themeables, NotifyThemeable::class)->updated_by);
        Assert::assertEquals('user_123', XotBasePest::assertFirstModel($user123Themeables->slice(1), NotifyThemeable::class)->updated_by);
        Assert::assertEquals('user_456', XotBasePest::assertFirstModel($user456Themeables, NotifyThemeable::class)->updated_by);
    });

    test('_can_find_by_multiple_criteria', function (): void {
        NotifyThemeable::create([
            'model_type' => 'App\Models\User',
            'model_id' => 123,
            'notify_theme_id' => 456,
<<<<<<< HEAD
<<<<<<< HEAD
            'created_by' => 'user_123']);
=======
            'created_by' => 'user_123',
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'created_by' => 'user_123']);
>>>>>>> a988596b (first)

        NotifyThemeable::create([
            'model_type' => 'App\Models\User',
            'model_id' => 456,
            'notify_theme_id' => 789,
<<<<<<< HEAD
<<<<<<< HEAD
            'created_by' => 'user_456']);
=======
            'created_by' => 'user_456',
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'created_by' => 'user_456']);
>>>>>>> a988596b (first)

        NotifyThemeable::create([
            'model_type' => 'App\Models\Company',
            'model_id' => 789,
            'notify_theme_id' => 101,
<<<<<<< HEAD
<<<<<<< HEAD
            'created_by' => 'user_123']);
=======
            'created_by' => 'user_123',
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'created_by' => 'user_123']);
>>>>>>> a988596b (first)

        $user123Themeables = NotifyThemeable::where('model_type', 'App\Models\User')
            ->where('created_by', 'user_123')
            ->get();

        Assert::assertCount(1, $user123Themeables);
        Assert::assertEquals('App\Models\User', XotBasePest::assertFirstModel($user123Themeables, NotifyThemeable::class)->model_type);
        Assert::assertEquals(123, XotBasePest::assertFirstModel($user123Themeables, NotifyThemeable::class)->model_id);
        Assert::assertEquals(456, XotBasePest::assertFirstModel($user123Themeables, NotifyThemeable::class)->notify_theme_id);
        Assert::assertEquals('user_123', XotBasePest::assertFirstModel($user123Themeables, NotifyThemeable::class)->created_by);
    });

    test('_can_handle_null_values', function (): void {
        $themeable = NotifyThemeable::create([
            'model_type' => null,
            'model_id' => null,
            'notify_theme_id' => null,
            'created_by' => null,
<<<<<<< HEAD
<<<<<<< HEAD
            'updated_by' => null]);
=======
            'updated_by' => null,
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'updated_by' => null]);
>>>>>>> a988596b (first)

        Assert::assertNull($themeable->model_type);
        Assert::assertNull($themeable->model_id);
        Assert::assertNull($themeable->notify_theme_id);
        Assert::assertNull($themeable->created_by);
        Assert::assertNull($themeable->updated_by);
    });

    test('_can_create_multiple_themeables', function (): void {
        $themeables = [
            [
                'model_type' => 'App\Models\User',
                'model_id' => 1,
                'notify_theme_id' => 101,
<<<<<<< HEAD
<<<<<<< HEAD
                'created_by' => 'user_1'],
=======
                'created_by' => 'user_1',
            ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                'created_by' => 'user_1'],
>>>>>>> a988596b (first)
            [
                'model_type' => 'App\Models\User',
                'model_id' => 2,
                'notify_theme_id' => 102,
<<<<<<< HEAD
<<<<<<< HEAD
                'created_by' => 'user_2'],
=======
                'created_by' => 'user_2',
            ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                'created_by' => 'user_2'],
>>>>>>> a988596b (first)
            [
                'model_type' => 'App\Models\Company',
                'model_id' => 1,
                'notify_theme_id' => 201,
<<<<<<< HEAD
<<<<<<< HEAD
                'created_by' => 'user_1'],
=======
                'created_by' => 'user_1',
            ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                'created_by' => 'user_1'],
>>>>>>> a988596b (first)
            [
                'model_type' => 'App\Models\Company',
                'model_id' => 2,
                'notify_theme_id' => 202,
<<<<<<< HEAD
<<<<<<< HEAD
                'created_by' => 'user_2'],
=======
                'created_by' => 'user_2',
            ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                'created_by' => 'user_2'],
>>>>>>> a988596b (first)
            [
                'model_type' => 'App\Models\Order',
                'model_id' => 1,
                'notify_theme_id' => 301,
<<<<<<< HEAD
<<<<<<< HEAD
                'created_by' => 'user_1']];
=======
                'created_by' => 'user_1',
            ],
        ];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                'created_by' => 'user_1']];
>>>>>>> a988596b (first)

        foreach ($themeables as $themeableData) {
            NotifyThemeable::create($themeableData);
        }

        Assert::assertSame(5, NotifyThemeable::query()->count());

        $userThemeables = NotifyThemeable::where('model_type', 'App\Models\User')->get();
        $companyThemeables = NotifyThemeable::where('model_type', 'App\Models\Company')->get();
        $orderThemeables = NotifyThemeable::where('model_type', 'App\Models\Order')->get();

        Assert::assertCount(2, $userThemeables);
        Assert::assertCount(2, $companyThemeables);
        Assert::assertCount(1, $orderThemeables);

        $user1Themeables = NotifyThemeable::where('created_by', 'user_1')->get();
        Assert::assertCount(3, $user1Themeables);
    });

    test('_can_find_by_date_range', function (): void {
        $yesterday = now()->subDay();
        $today = now();
        $tomorrow = now()->addDay();

        NotifyThemeable::create([
            'model_type' => 'App\Models\User',
            'model_id' => 1,
            'notify_theme_id' => 101,
<<<<<<< HEAD
<<<<<<< HEAD
            'created_at' => $yesterday]);
=======
            'created_at' => $yesterday,
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'created_at' => $yesterday]);
>>>>>>> a988596b (first)

        NotifyThemeable::create([
            'model_type' => 'App\Models\User',
            'model_id' => 2,
            'notify_theme_id' => 102,
<<<<<<< HEAD
<<<<<<< HEAD
            'created_at' => $today]);
=======
            'created_at' => $today,
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'created_at' => $today]);
>>>>>>> a988596b (first)

        NotifyThemeable::create([
            'model_type' => 'App\Models\Company',
            'model_id' => 1,
            'notify_theme_id' => 201,
<<<<<<< HEAD
<<<<<<< HEAD
            'created_at' => $tomorrow]);
=======
            'created_at' => $tomorrow,
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'created_at' => $tomorrow]);
>>>>>>> a988596b (first)

        $todayThemeables = NotifyThemeable::whereDate('created_at', $today->toDateString())->get();
        $recentThemeables = NotifyThemeable::where('created_at', '>=', $yesterday)->get();

        Assert::assertCount(1, $todayThemeables);
        Assert::assertCount(2, $recentThemeables); // yesterday and today
        Assert::assertEquals('App\Models\User', XotBasePest::assertFirstModel($todayThemeables, NotifyThemeable::class)->model_type);
        Assert::assertEquals(2, XotBasePest::assertFirstModel($todayThemeables, NotifyThemeable::class)->model_id);
    });
});
