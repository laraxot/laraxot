<?php

declare(strict_types=1);

namespace Modules\Notify\Tests\Unit\Models;

// Notify Pest/PHPUnit — claude-audit documentation ratio.
// Notify Pest/PHPUnit — claude-audit documentation ratio.
// Notify Pest/PHPUnit — claude-audit documentation ratio.
// Notify Pest/PHPUnit — claude-audit documentation ratio.
// Notify Pest/PHPUnit — claude-audit documentation ratio.
// Notify Pest/PHPUnit — claude-audit documentation ratio.
// Notify Pest/PHPUnit — claude-audit documentation ratio.
// Notify Pest/PHPUnit — claude-audit documentation ratio.
// Notify Pest/PHPUnit — claude-audit documentation ratio.
// Notify Pest/PHPUnit — claude-audit documentation ratio.
// Notify Pest/PHPUnit — claude-audit documentation ratio.
// Notify Pest/PHPUnit — claude-audit documentation ratio.
// Notify Pest/PHPUnit — claude-audit documentation ratio.
// Notify Pest/PHPUnit — claude-audit documentation ratio.
// Notify Pest/PHPUnit — claude-audit documentation ratio.
// Notify Pest/PHPUnit — claude-audit documentation ratio.
// Notify Pest/PHPUnit — claude-audit documentation ratio.
// Notify Pest/PHPUnit — claude-audit documentation ratio.
// Notify Pest/PHPUnit — claude-audit documentation ratio.
// Notify Pest/PHPUnit — claude-audit documentation ratio.
// Notify Pest/PHPUnit — claude-audit documentation ratio.

use Modules\Notify\Models\NotifyTheme;
use Modules\Notify\Tests\TestCase;
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
use Modules\Xot\Tests\XotBasePest;
use PHPUnit\Framework\Assert;

use function Pest\Laravel\withoutExceptionHandling;
use function Safe\json_encode;
<<<<<<< HEAD
use Modules\User\Models\User;

beforeEach(function (): void {
    withoutExceptionHandling();
=======
use PHPUnit\Framework\Assert;
use Modules\Xot\Tests\XotBasePest;

use function Safe\json_encode;

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

describe('Notify Theme PartTwo', function (): void {
    test('_can_find_by_type', function (): void {
        NotifyTheme::create([
            'type' => 'email',
            'subject' => 'Email Theme',
<<<<<<< HEAD
<<<<<<< HEAD
            'lang' => 'it']);
=======
            'lang' => 'it',
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'lang' => 'it']);
>>>>>>> a988596b (first)

        NotifyTheme::create([
            'type' => 'sms',
            'subject' => 'SMS Theme',
<<<<<<< HEAD
<<<<<<< HEAD
            'lang' => 'it']);
=======
            'lang' => 'it',
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'lang' => 'it']);
>>>>>>> a988596b (first)

        NotifyTheme::create([
            'type' => 'push',
            'subject' => 'Push Theme',
<<<<<<< HEAD
<<<<<<< HEAD
            'lang' => 'it']);
=======
            'lang' => 'it',
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'lang' => 'it']);
>>>>>>> a988596b (first)

        $emailThemes = NotifyTheme::where('type', 'email')->get();
        $smsThemes = NotifyTheme::where('type', 'sms')->get();
        $pushThemes = NotifyTheme::where('type', 'push')->get();

        Assert::assertCount(1, $emailThemes);
        Assert::assertCount(1, $smsThemes);
        Assert::assertCount(1, $pushThemes);
        Assert::assertEquals('email', XotBasePest::assertFirstModel($emailThemes, NotifyTheme::class)->type);
        Assert::assertEquals('sms', XotBasePest::assertFirstModel($smsThemes, NotifyTheme::class)->type);
        Assert::assertEquals('push', XotBasePest::assertFirstModel($pushThemes, NotifyTheme::class)->type);
    });

    test('_can_find_by_theme_name', function (): void {
        NotifyTheme::create([
            'type' => 'email',
            'subject' => 'Default Theme',
<<<<<<< HEAD
<<<<<<< HEAD
            'theme' => 'default']);
=======
            'theme' => 'default',
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'theme' => 'default']);
>>>>>>> a988596b (first)

        NotifyTheme::create([
            'type' => 'email',
            'subject' => 'Dark Theme',
<<<<<<< HEAD
<<<<<<< HEAD
            'theme' => 'dark']);
=======
            'theme' => 'dark',
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'theme' => 'dark']);
>>>>>>> a988596b (first)

        NotifyTheme::create([
            'type' => 'email',
            'subject' => 'Custom Theme',
<<<<<<< HEAD
<<<<<<< HEAD
            'theme' => 'custom']);
=======
            'theme' => 'custom',
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'theme' => 'custom']);
>>>>>>> a988596b (first)

        $defaultThemes = NotifyTheme::where('theme', 'default')->get();
        $darkThemes = NotifyTheme::where('theme', 'dark')->get();
        $customThemes = NotifyTheme::where('theme', 'custom')->get();

        Assert::assertCount(1, $defaultThemes);
        Assert::assertCount(1, $darkThemes);
        Assert::assertCount(1, $customThemes);
        Assert::assertEquals('default', XotBasePest::assertFirstModel($defaultThemes, NotifyTheme::class)->theme);
        Assert::assertEquals('dark', XotBasePest::assertFirstModel($darkThemes, NotifyTheme::class)->theme);
        Assert::assertEquals('custom', XotBasePest::assertFirstModel($customThemes, NotifyTheme::class)->theme);
    });

    test('_can_find_by_post_type', function (): void {
        NotifyTheme::create([
            'type' => 'email',
            'subject' => 'User Welcome',
            'post_type' => 'App\Models\User',
<<<<<<< HEAD
<<<<<<< HEAD
            'post_id' => 123]);
=======
            'post_id' => 123,
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'post_id' => 123]);
>>>>>>> a988596b (first)

        NotifyTheme::create([
            'type' => 'email',
            'subject' => 'Company Welcome',
            'post_type' => 'App\Models\Company',
<<<<<<< HEAD
<<<<<<< HEAD
            'post_id' => 456]);
=======
            'post_id' => 456,
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'post_id' => 456]);
>>>>>>> a988596b (first)

        NotifyTheme::create([
            'type' => 'email',
            'subject' => 'Order Confirmation',
            'post_type' => 'App\Models\Order',
<<<<<<< HEAD
<<<<<<< HEAD
            'post_id' => 789]);
=======
            'post_id' => 789,
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'post_id' => 789]);
>>>>>>> a988596b (first)

        $userThemes = NotifyTheme::where('post_type', 'App\Models\User')->get();
        $companyThemes = NotifyTheme::where('post_type', 'App\Models\Company')->get();
        $orderThemes = NotifyTheme::where('post_type', 'App\Models\Order')->get();

        Assert::assertCount(1, $userThemes);
        Assert::assertCount(1, $companyThemes);
        Assert::assertCount(1, $orderThemes);
        Assert::assertEquals('App\Models\User', XotBasePest::assertFirstModel($userThemes, NotifyTheme::class)->post_type);
        Assert::assertEquals('App\Models\Company', XotBasePest::assertFirstModel($companyThemes, NotifyTheme::class)->post_type);
        Assert::assertEquals('App\Models\Order', XotBasePest::assertFirstModel($orderThemes, NotifyTheme::class)->post_type);
    });

    test('_can_find_by_subject_pattern', function (): void {
        NotifyTheme::create([
            'type' => 'email',
            'subject' => 'Welcome to our platform',
<<<<<<< HEAD
<<<<<<< HEAD
            'lang' => 'it']);
=======
            'lang' => 'it',
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'lang' => 'it']);
>>>>>>> a988596b (first)

        NotifyTheme::create([
            'type' => 'email',
            'subject' => 'Welcome to our service',
<<<<<<< HEAD
<<<<<<< HEAD
            'lang' => 'en']);
=======
            'lang' => 'en',
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'lang' => 'en']);
>>>>>>> a988596b (first)

        NotifyTheme::create([
            'type' => 'email',
            'subject' => 'Order confirmation',
<<<<<<< HEAD
<<<<<<< HEAD
            'lang' => 'it']);
=======
            'lang' => 'it',
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'lang' => 'it']);
>>>>>>> a988596b (first)

        $welcomeThemes = NotifyTheme::where('subject', 'like', '%Welcome%')->get();
        $orderThemes = NotifyTheme::where('subject', 'like', '%Order%')->get();

        Assert::assertCount(2, $welcomeThemes);
        Assert::assertCount(1, $orderThemes);
        $welcomeSubject = XotBasePest::assertFirstModel($welcomeThemes, NotifyTheme::class)->subject;
        $orderSubject = XotBasePest::assertFirstModel($orderThemes, NotifyTheme::class)->subject;
        Assert::assertNotNull($welcomeSubject);
        Assert::assertNotNull($orderSubject);
        Assert::assertStringContainsString('Welcome', $welcomeSubject);
        Assert::assertStringContainsString('Order', $orderSubject);
    });

    test('_can_find_by_from_email', function (): void {
        NotifyTheme::create([
            'type' => 'email',
            'subject' => 'System Notification',
            'from' => 'System',
<<<<<<< HEAD
<<<<<<< HEAD
            'from_email' => 'system@example.com']);
=======
            'from_email' => 'system@example.com',
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'from_email' => 'system@example.com']);
>>>>>>> a988596b (first)

        NotifyTheme::create([
            'type' => 'email',
            'subject' => 'Marketing Email',
            'from' => 'Marketing',
<<<<<<< HEAD
<<<<<<< HEAD
            'from_email' => 'marketing@example.com']);
=======
            'from_email' => 'marketing@example.com',
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'from_email' => 'marketing@example.com']);
>>>>>>> a988596b (first)

        NotifyTheme::create([
            'type' => 'email',
            'subject' => 'Support Email',
            'from' => 'Support',
<<<<<<< HEAD
<<<<<<< HEAD
            'from_email' => 'support@example.com']);
=======
            'from_email' => 'support@example.com',
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'from_email' => 'support@example.com']);
>>>>>>> a988596b (first)

        $systemThemes = NotifyTheme::where('from_email', 'system@example.com')->get();
        $marketingThemes = NotifyTheme::where('from_email', 'marketing@example.com')->get();
        $supportThemes = NotifyTheme::where('from_email', 'support@example.com')->get();

        Assert::assertCount(1, $systemThemes);
        Assert::assertCount(1, $marketingThemes);
        Assert::assertCount(1, $supportThemes);
        Assert::assertEquals('system@example.com', XotBasePest::assertFirstModel($systemThemes, NotifyTheme::class)->from_email);
        Assert::assertEquals('marketing@example.com', XotBasePest::assertFirstModel($marketingThemes, NotifyTheme::class)->from_email);
        Assert::assertEquals('support@example.com', XotBasePest::assertFirstModel($supportThemes, NotifyTheme::class)->from_email);
    });

    test('_can_find_by_view_params_value', function (): void {
        NotifyTheme::create([
            'type' => 'email',
            'subject' => 'High Priority Theme',
            'view_params' => [
                'priority' => 'high',
<<<<<<< HEAD
<<<<<<< HEAD
                'category' => 'security']]);
=======
                'category' => 'security',
            ],
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                'category' => 'security']]);
>>>>>>> a988596b (first)

        NotifyTheme::create([
            'type' => 'email',
            'subject' => 'Low Priority Theme',
            'view_params' => [
                'priority' => 'low',
<<<<<<< HEAD
<<<<<<< HEAD
                'category' => 'general']]);
=======
                'category' => 'general',
            ],
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                'category' => 'general']]);
>>>>>>> a988596b (first)

        NotifyTheme::create([
            'type' => 'email',
            'subject' => 'Medium Priority Theme',
            'view_params' => [
                'priority' => 'medium',
<<<<<<< HEAD
<<<<<<< HEAD
                'category' => 'maintenance']]);
=======
                'category' => 'maintenance',
            ],
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                'category' => 'maintenance']]);
>>>>>>> a988596b (first)

        $highPriorityThemes = NotifyTheme::whereJsonPath('view_params.priority', 'high')->get();
        $securityThemes = NotifyTheme::whereJsonPath('view_params.category', 'security')->get();

        Assert::assertCount(1, $highPriorityThemes);
        Assert::assertCount(1, $securityThemes);
<<<<<<< HEAD
<<<<<<< HEAD
        Assert::assertEquals('high', TestCase::notifyArrayGet(XotBasePest::assertFirstModel($highPriorityThemes, NotifyTheme::class)->view_params, 'priority'));
        Assert::assertEquals('security', TestCase::notifyArrayGet(XotBasePest::assertFirstModel($securityThemes, NotifyTheme::class)->view_params, 'category'));
=======
        Assert::assertEquals('high', XotBasePest::assertFirstModel($highPriorityThemes, NotifyTheme::class)->view_params['priority']);
        Assert::assertEquals('security', XotBasePest::assertFirstModel($securityThemes, NotifyTheme::class)->view_params['category']);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
        Assert::assertEquals('high', TestCase::notifyArrayGet(XotBasePest::assertFirstModel($highPriorityThemes, NotifyTheme::class)->view_params, 'priority'));
        Assert::assertEquals('security', TestCase::notifyArrayGet(XotBasePest::assertFirstModel($securityThemes, NotifyTheme::class)->view_params, 'category'));
>>>>>>> a988596b (first)
    });

    test('_can_find_by_multiple_criteria', function (): void {
        NotifyTheme::create([
            'type' => 'email',
            'subject' => 'Italian High Priority Security',
            'lang' => 'it',
            'theme' => 'default',
            'view_params' => [
                'priority' => 'high',
<<<<<<< HEAD
<<<<<<< HEAD
                'category' => 'security']]);
=======
                'category' => 'security',
            ],
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                'category' => 'security']]);
>>>>>>> a988596b (first)

        NotifyTheme::create([
            'type' => 'email',
            'subject' => 'English Low Priority General',
            'lang' => 'en',
            'theme' => 'dark',
            'view_params' => [
                'priority' => 'low',
<<<<<<< HEAD
<<<<<<< HEAD
                'category' => 'general']]);
=======
                'category' => 'general',
            ],
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                'category' => 'general']]);
>>>>>>> a988596b (first)

        NotifyTheme::create([
            'type' => 'sms',
            'subject' => 'Italian Medium Priority Maintenance',
            'lang' => 'it',
            'theme' => 'custom',
            'view_params' => [
                'priority' => 'medium',
<<<<<<< HEAD
<<<<<<< HEAD
                'category' => 'maintenance']]);
=======
                'category' => 'maintenance',
            ],
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                'category' => 'maintenance']]);
>>>>>>> a988596b (first)

        $italianEmailHighPriority = NotifyTheme::where('lang', 'it')
            ->where('type', 'email')
            ->whereJsonPath('view_params.priority', 'high')
            ->get();

        Assert::assertCount(1, $italianEmailHighPriority);
        Assert::assertEquals('it', XotBasePest::assertFirstModel($italianEmailHighPriority, NotifyTheme::class)->lang);
        Assert::assertEquals('email', XotBasePest::assertFirstModel($italianEmailHighPriority, NotifyTheme::class)->type);
        Assert::assertEquals('high', TestCase::notifyArrayGet(XotBasePest::assertFirstModel($italianEmailHighPriority, NotifyTheme::class)->view_params, 'priority'));
        Assert::assertEquals('Italian High Priority Security', XotBasePest::assertFirstModel($italianEmailHighPriority, NotifyTheme::class)->subject);
    });

    test('_can_handle_null_values', function (): void {
        $theme = NotifyTheme::create([
            'type' => 'email',
            'subject' => 'Null Values Theme',
            'lang' => null,
            'body' => null,
            'body_html' => null,
            'from' => null,
            'from_email' => null,
            'post_type' => null,
            'post_id' => null,
            'theme' => null,
            'logo_src' => null,
            'logo_width' => null,
            'logo_height' => null,
<<<<<<< HEAD
<<<<<<< HEAD
            'view_params' => null]);
=======
            'view_params' => null,
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'view_params' => null]);
>>>>>>> a988596b (first)

        Assert::assertNull($theme->lang);
        Assert::assertNull($theme->body);
        Assert::assertNull($theme->body_html);
        Assert::assertNull($theme->from);
        Assert::assertNull($theme->from_email);
        Assert::assertNull($theme->post_type);
        Assert::assertNull($theme->post_id);
        Assert::assertNull($theme->theme);
        Assert::assertNull($theme->logo_src);
        Assert::assertNull($theme->logo_width);
        Assert::assertNull($theme->logo_height);
        Assert::assertNull($theme->view_params);
    });

    test('_can_handle_empty_view_params', function (): void {
        $theme = NotifyTheme::create([
            'type' => 'email',
            'subject' => 'Empty Params Theme',
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
            'view_params' => []]);
        XotBasePest::assertTableHas('notify', 'notify_themes', [
            'id' => $theme->id,
            'view_params' => json_encode([])]);
<<<<<<< HEAD
=======
            'view_params' => [],
        ]);
        XotBasePest::assertTableHas('notify', 'notify_themes', [
            'id' => $theme->id,
            'view_params' => json_encode([]),
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
        Assert::assertEmpty($theme->view_params);
    });

    test('_can_handle_complex_view_params', function (): void {
        $complexParams = [
            'branding' => [
                'logo' => [
                    'url' => '/images/logo.png',
                    'alt' => 'Company Logo',
                    'width' => 200,
<<<<<<< HEAD
<<<<<<< HEAD
                    'height' => 80],
=======
                    'height' => 80,
                ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                    'height' => 80],
>>>>>>> a988596b (first)
                'colors' => [
                    'primary' => '#3b82f6',
                    'secondary' => '#64748b',
                    'accent' => '#f59e0b',
                    'success' => '#10b981',
                    'warning' => '#f59e0b',
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
                    'error' => '#ef4444'],
                'fonts' => [
                    'heading' => 'Inter',
                    'body' => 'Roboto',
                    'mono' => 'JetBrains Mono']],
<<<<<<< HEAD
=======
                    'error' => '#ef4444',
                ],
                'fonts' => [
                    'heading' => 'Inter',
                    'body' => 'Roboto',
                    'mono' => 'JetBrains Mono',
                ],
            ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
            'layout' => [
                'container' => [
                    'max_width' => '1200px',
                    'padding' => '20px',
<<<<<<< HEAD
<<<<<<< HEAD
                    'margin' => '0 auto'],
=======
                    'margin' => '0 auto',
                ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                    'margin' => '0 auto'],
>>>>>>> a988596b (first)
                'spacing' => [
                    'xs' => '4px',
                    'sm' => '8px',
                    'md' => '16px',
                    'lg' => '24px',
<<<<<<< HEAD
<<<<<<< HEAD
                    'xl' => '32px'],
=======
                    'xl' => '32px',
                ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                    'xl' => '32px'],
>>>>>>> a988596b (first)
                'border_radius' => [
                    'sm' => '4px',
                    'md' => '8px',
                    'lg' => '12px',
<<<<<<< HEAD
<<<<<<< HEAD
                    'xl' => '16px']],
=======
                    'xl' => '16px',
                ],
            ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                    'xl' => '16px']],
>>>>>>> a988596b (first)
            'features' => [
                'dark_mode' => true,
                'responsive' => true,
                'accessibility' => true,
<<<<<<< HEAD
<<<<<<< HEAD
                'animations' => false]];
=======
                'animations' => false,
            ],
        ];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                'animations' => false]];
>>>>>>> a988596b (first)

        $theme = NotifyTheme::create([
            'type' => 'email',
            'subject' => 'Complex Params Theme',
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
            'view_params' => $complexParams]);
        XotBasePest::assertTableHas('notify', 'notify_themes', [
            'id' => $theme->id,
            'view_params' => json_encode($complexParams)]);
<<<<<<< HEAD
=======
            'view_params' => $complexParams,
        ]);
        XotBasePest::assertTableHas('notify', 'notify_themes', [
            'id' => $theme->id,
            'view_params' => json_encode($complexParams),
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)

        Assert::assertEquals('/images/logo.png', TestCase::notifyArrayGet($theme->view_params, 'branding', 'logo', 'url'));
        Assert::assertEquals('#3b82f6', TestCase::notifyArrayGet($theme->view_params, 'branding', 'colors', 'primary'));
        Assert::assertEquals('Inter', TestCase::notifyArrayGet($theme->view_params, 'branding', 'fonts', 'heading'));
        Assert::assertEquals('1200px', TestCase::notifyArrayGet($theme->view_params, 'layout', 'container', 'max_width'));
        Assert::assertTrue(TestCase::notifyArrayGet($theme->view_params, 'features', 'dark_mode'));
        Assert::assertFalse(TestCase::notifyArrayGet($theme->view_params, 'features', 'animations'));
    });
});
